<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
require_once(__DIR__ . "/../../includes/connection.php");
require_once(__DIR__ . "/../../vendor/autoload.php");

$param = explode("/", $params);
$date = (!empty($param[0]) ? explode("-", urldecode($param[0])) : "");
$start = (!empty($date[0]) ? str_replace("_", "/", trim($date[0])) : "");
$end = (!empty($date[1]) ? str_replace("_", "/", trim($date[1])) : "");
$keyword = (!empty($param[1]) ? urldecode($param[1]) : "");

$sql = "SELECT FORMAT(SUM(A.subtotal),2) amount, FORMAT(SUM(A.subtotal) * 7 / 100, 2) as tax,
FORMAT(SUM(A.subtotal) + (SUM(A.subtotal) * 7 / 100),2) as total,
(CASE WHEN B.id THEN MIN(reference_no) ELSE NULL END) min,
(CASE WHEN B.id THEN MAX(reference_no) ELSE NULL END) max
FROM sma_sale_items A
LEFT JOIN sma_sales B
ON A.sale_id = B.id
WHERE A.id != '' ";

if ($date) {
  $sql .= " AND DATE(B.date) BETWEEN STR_TO_DATE('{$start}', '%d/%m/%Y') AND STR_TO_DATE('{$end}', '%d/%m/%Y') ";
}
if ($keyword) {
  $sql .= " AND A.product_name LIKE '%{$keyword}%' ";
}
$stmt = $dbcon->prepare($sql);
$stmt->execute();
$sale = $stmt->fetch();

$sql = "SELECT name,vat_no,CONCAT(address,' ',city,' ',postal_code) address,gst_no
FROM sma_pos_settings A
LEFT JOIN sma_companies B
ON A.default_biller = B.id
WHERE A.pos_id != '' ";
$stmt = $dbcon->prepare($sql);
$stmt->execute();
$user = $stmt->fetch();

ob_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>รายงานขาย</title>
  </title>
  <style>
  .text-center {
    text-align: center;
  }

  .text-right {
    text-align: right;
  }

  table {
    width: 100%;
    font-size: 70%;
    border-collapse: collapse;
  }

  th {
    border: 1px solid;
    text-align: center;
    padding: 3px;
  }

  td {
    border: 1px solid;
    padding: 3px;
  }
  </style>
</head>

<body>

  <table>
    <thead>
      <tr>
        <th>เครื่องขาย เลขที่</th>
        <th>เลขที่ใบกำกับภาษี</th>
        <th>หมายเลข REG</th>
        <th>มูลค่าสินค้า <br> ไม่คิดภาษี</th>
        <th>มูลค่าสินค้า <br> คิดภาษี</th>
        <th>มูลค่าสินค้า</th>
        <th>จำนวนเงินรวม</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>002</td>
        <td>
          ใบกำกับภาษีเริ่มต้น : <?php echo $sale['min'] ?> <br>
          ใบกำกับภาษีสิ้นสุด : <?php echo $sale['max'] ?>
        </td>
        <td>
          <?php echo $user['gst_no'] ?>
        </td>
        <td class="text-right">0.00</td>
        <td class="text-right"><?php echo $sale['amount'] ?></td>
        <td class="text-right"><?php echo $sale['tax'] ?></td>
        <td class="text-right"><?php echo $sale['total'] ?></td>
      </tr>
      <tr>
        <td colspan="3"></td>
        <td class="text-right">0.00</td>
        <td class="text-right"><?php echo $sale['amount'] ?></td>
        <td class="text-right"><?php echo $sale['tax'] ?></td>
        <td class="text-right"><?php echo $sale['total'] ?></td>
      </tr>
    </tbody>
  </table>

</body>

</html>
<?php
$html = ob_get_contents();
ob_end_clean();

$random = md5(microtime(true));
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'default_font' => 'garuda', 'margin_top' => 40]);

$header = '
<h3 style="text-align: center;">รายงาน ภาษีขาย</h3>
<table width="100%">
  <tr style="border: none;">
    <td width="50%" style="border: none;"><h3>ชื่อผู้ประกอบการ ' . $user['name'] . '</h3></td>
    <td width="50%" style="text-align: right; border: none;"><h3>เลขประจำตัวผู้เสียภาษี ' . $user['vat_no'] . '</h3></td>
  </tr>
  <tr style="border: none;">
    <td width="50%" style="border: none;"><h4>ที่อยู่ ' . $user['address'] . '</h4></td>
    <td width="50%" style="text-align: right; border: none;"><h3>ระหว่างวันที่ ' . $start . ' ถึง วันที่ ' . $end . '</h3></td>
  </tr>
</table>';

$footer = '
<table width="100%">
  <tr style="border: none;">
    <td width="50%" style="border: none;"><h3>{DATE d/m/Y}</h3></td>
    <td width="50%" style="text-align: right; border: none;"><h3>{PAGENO}/{nbpg}</h3></td>
  </tr>
</table>';

$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLFooter($footer);

$mpdf->WriteHTML($html);
$mpdf->Output("report_{$random}", "I");


// echo $html;