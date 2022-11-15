<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
require_once(__DIR__ . "/../../includes/connection.php");
require_once(__DIR__ . "/../../vendor/autoload.php");

$param = explode("/", $params);
$date = (!empty($param[0]) ? explode("-", urldecode($param[0])) : [date("d/m/Y"), date("d/m/Y")]);
$start = (!empty($date[0]) ? str_replace("_", "/", trim($date[0])) : "");
$end = (!empty($date[1]) ? str_replace("_", "/", trim($date[1])) : "");
$sale = (!empty($param[1]) ? urldecode($param[1]) : 4);

$sql = "SELECT username,date,LPAD(pos,3,'0') pos,
MIN(reference_no) bill_start, 
MAX(reference_no) bill_end, 
FORMAT(SUM(B.subtotal),2) total,
(
  SUM(B.subtotal) * 7 /100
) vat,
(
  SUM(B.subtotal) - (SUM(B.subtotal) * 7 /100)
) calc,
COUNT(DISTINCT reference_no) count
FROM sma_sales A 
LEFT JOIN sma_sale_items B 
ON A.id = B.sale_id
LEFT JOIN sma_users C 
ON A.created_by = C.id
WHERE A.id != '' ";

if ($date) {
  $sql .= " AND DATE(A.date) BETWEEN STR_TO_DATE('{$start}', '%d/%m/%Y') AND STR_TO_DATE('{$end}', '%d/%m/%Y') ";
}
if ($sale) {
  $sql .= " AND A.created_by = {$sale} ";
}
$stmt = $dbcon->prepare($sql);
$stmt->execute();
$row = $stmt->fetch();
?>
<html>

<body>
  <style>
  #wrapper {
    max-width: 480px;
    width: 100%;
    min-width: 250px;
    margin: 0 auto;
  }

  table {
    border-collapse: collapse;
  }

  td {
    width: 50%;
    padding: 5px;
  }

  .text-center {
    text-align: center;
  }
  </style>

  <table>
    <tr>
      <td colspan="2" style="border: 1px solid #000; border-width: 1px 0px 1px 0px; text-align: center;">
        <b>ใบนำส่งเงิน สิ้นกะ</b>
      </td>
    </tr>
    <tr>
      <td>พนักงานขาย</td>
      <td><?php echo $row['username'] ?></td>
    </tr>
    <tr>
      <td>หมายเลขเครื่องคิดเงิน</td>
      <td><?php echo $row['pos'] ?></td>
    </tr>
    <tr>
      <td>วันที่พิมพ์</td>
      <td><?php echo date("d/m/Y") ?></td>
    </tr>
    <tr>
      <td>เวลาที่พิมพ์</td>
      <td><?php echo date("H:i:s") ?></td>
    </tr>
    <tr>
      <td colspan="2" style="border-bottom: 1px solid #000;"></td>
    </tr>
    <tr>
      <td>เลขที่บิลเริ่มต้น</td>
      <td><?php echo $row['bill_start'] ?></td>
    </tr>
    <tr>
      <td>เลขที่บิลสิ้นสุด</td>
      <td><?php echo $row['bill_end'] ?></td>
    </tr>
    <tr>
      <td>วันที่เริ่มต้น</td>
      <td><?php echo $start ?></td>
    </tr>
    <tr>
      <td>วันที่สินสุด</td>
      <td><?php echo $end ?></td>
    </tr>
    <tr>
      <td>รวมบิล</td>
      <td><?php echo $row['count'] ?></td>
    </tr>
    <tr>
      <td style="border: 1px solid #000; border-width: 1px 0px 1px 0px;">
        <b>จำนวนเงิน</b>
      </td>
      <td style="border: 1px solid #000; border-width: 1px 0px 1px 0px;">
        <b><?php echo $row['total'] ?></b>
      </td>
    </tr>
  </table>


  <script src="/../../vendor/components/jquery/jquery.min.js"></script>
  <script>
  window.print();
  </script>
</body>

</html>