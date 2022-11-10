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

$sql = "SELECT A.product_code,A.product_name,A.product_unit_code unit,FORMAT(A.quantity,0) qty,
FORMAT(A.subtotal,2) total,
DATE_FORMAT(B.date, '%d/%m/%Y - %H:%i น.') date 
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
$result = $stmt->fetchAll();

$sql = "SELECT FORMAT(SUM(A.subtotal),2) total
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

$sql = "SELECT name,vat_no
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
        <th>รหัสสินค้า</th>
        <th>สินค้า</th>
        <th>หน่วย</th>
        <th>จำนวน</th>
        <th>ยอดเงินรวม</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($result as $row) :;
      ?>
      <tr>
        <td><?php echo $row['product_code'] ?></td>
        <td><?php echo $row['product_name'] ?></td>
        <td class="text-center"><?php echo $row['unit'] ?></td>
        <td class="text-center"><?php echo $row['qty'] ?></td>
        <td class="text-right"><?php echo $row['total'] ?></td>
      </tr>
      <?php endforeach; ?>
      <tr>
        <td colspan="4" class="text-right">
          <h4>รวมเงินสุทธิ</h4>
        </td>
        <td class="text-right">
          <h4><?php echo $sale['total'] ?></h4>
        </td>
      </tr>
    </tbody>
  </table>

</body>

</html>
<?php
$html = ob_get_contents();
ob_end_clean();

$random = md5(microtime(true));
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'default_font' => 'garuda', 'margin_top' => 20]);

$header = '
<table width="100%">
  <tr style="border: none;">
    <td width="50%" style="border: none;"><h3>' . $user['name'] . '</h3></td>
    <td width="50%" style="text-align: right; border: none;"><h3>เลขประจำตัวผู้เสียภาษี ' . $user['vat_no'] . '</h3></td>
  </tr>
  <tr style="border: none;">
    <td width="50%" style="border: none;"><h3>รายงานสรุปการขายสินค้าของแคชเชียร์ (รวม)</h3></td>
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