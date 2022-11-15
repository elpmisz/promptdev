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

$sql = "SELECT LPAD((B.pos), GREATEST(LENGTH(B.pos), 3), '0') pos,
FORMAT(SUM(A.subtotal),2) amount, FORMAT(SUM(A.subtotal) * 7 / 100, 2) as tax,
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

$date = date('Y-m-d');
$fileName = "tax_{$date}.xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename={$fileName}");
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
        <td colspan="3">
          <b>ชื่อผู้ประกอบการ <?php echo $user['name'] ?></b>
        </td>
        <td colspan="4" class="text-right">
          <b>เลขประจำตัวผู้เสียภาษี <?php echo $user['vat_no'] ?></b>
        </td>
      </tr>
      <tr>
        <td colspan="3">
          <b>ที่อยู่ <?php echo $user['address'] ?></b>
        </td>
        <td colspan="4" class="text-right">
          <b>ระหว่างวันที่ <?php echo $start ?> ถึง วันที่ <?php echo $end ?></b>
        </td>
      </tr>
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
        <td><?php echo $sale['pos'] ?></td>
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
        <td class="text-right"><b>0.00</b></td>
        <td class="text-right"><b><?php echo $sale['amount'] ?></b></td>
        <td class="text-right"><b><?php echo $sale['tax'] ?></b></td>
        <td class="text-right"><b><?php echo $sale['total'] ?></b></td>
      </tr>
    </tbody>
  </table>

</body>

</html>