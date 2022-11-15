<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
require_once(__DIR__ . "/../../includes/connection.php");
require_once(__DIR__ . "/../../vendor/autoload.php");

$param = explode("/", $params);
$category = (!empty($param[0]) ? urldecode($param[0]) : "");
$keyword = (!empty($param[1]) ? urldecode($param[1]) : "");

$sql = "SELECT name,vat_no
FROM sma_pos_settings A
LEFT JOIN sma_companies B
ON A.default_biller = B.id
WHERE A.pos_id != '' ";
$stmt = $dbcon->prepare($sql);
$stmt->execute();
$user = $stmt->fetch();


$date = date('Y-m-d');
$fileName = "product_{$date}.xls";
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
    white-space: nowrap;
    border: 1px solid #000;
    text-align: center;
    padding: 3px;
  }

  td {
    border: 1px solid #000;
    padding: 3px;
  }
  </style>
</head>

<body>

  <table>
    <thead>
      <tr>
        <th colspan="10">
          <b>รายงาน​สินค้าคง​เห​ลือ​ ณ ปัจจุ​บัน</b>
        </th>
      </tr>
      <tr>
        <th width="20%">รหัสสินค้า</th>
        <th width="30%">สินค้า</th>
        <th width="10%">หน่วย</th>
        <th width="5%">ราคาขาย</th>
        <th width="5%">คลังใหญ่</th>
        <th width="5%">หน้าร้าน</th>
        <th width="5%">รอส่งคืน</th>
        <th width="5%">คงเหลือรวม</th>
        <th width="10%">มูลค่าคงเหลือ</th>
        <th width="10%">มูลค่าขาย</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT A.category_id id,B.name
      FROM sma_products A 
      LEFT JOIN sma_categories B
      ON A.category_id = B.id ";
      if ($category) {
        $sql .= " WHERE A.category_id = {$category} ";
      }
      $sql .= "GROUP BY A.category_id";
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
      $cats = $stmt->fetchAll();

      foreach ($cats as $cat) :
      ?>
      <tr>
        <td colspan="10">
          <u>
            <b><?php echo $cat['name'] ?></b>
          </u>
        </td>
      </tr>
      <?php
        $sql = "SELECT A.code product_code,A.name product_name,B.name unit_name,FORMAT(price,2) price,FORMAT(quantity,0) quantity,FORMAT(cost * quantity,2) cost,FORMAT((price * quantity) - ((price * quantity) * tax_rate / 100),2) sale
        FROM sma_products A
        LEFT JOIN sma_units B
        ON A.unit = B.id
        WHERE category_id = ?";
        $stmt = $dbcon->prepare($sql);
        $stmt->execute([$cat['id']]);
        $products = $stmt->fetchAll();

        foreach ($products as $product) :
        ?>
      <tr>
        <td><?php echo $product['product_code'] ?></td>
        <td><?php echo $product['product_name'] ?></td>
        <td class="text-center"><?php echo $product['unit_name'] ?></td>
        <td class="text-right"><?php echo $product['price'] ?></td>
        <td class="text-right"><?php echo "-" ?></td>
        <td class="text-right"><?php echo $product['quantity'] ?></td>
        <td class="text-right"><?php echo "-" ?></td>
        <td class="text-right"><?php echo $product['quantity'] ?></td>
        <td class="text-right"><?php echo $product['cost'] ?></td>
        <td class="text-right"><?php echo $product['sale'] ?></td>
      </tr>
      <?php endforeach ?>
      <?php
        $sql = "SELECT SUM(CASE WHEN id THEN 1 ELSE 0 END) count,
        FORMAT(SUM(cost * quantity),2) cost,
        FORMAT(SUM(price * quantity) - (SUM(price * quantity) * tax_rate / 100),2) sale
        FROM sma_products
        WHERE category_id = ?";
        $stmt = $dbcon->prepare($sql);
        $stmt->execute([$cat['id']]);
        $total = $stmt->fetch();
        ?>
      <tr>
        <td colspan="8" class="text-right">
          <b>Sub Total : <?php echo $total['count'] ?></b>
        </td>
        <td class="text-right">
          <b><?php echo $total['cost'] ?></b>
        </td>
        <td class="text-right">
          <b><?php echo $total['sale'] ?></b>
        </td>
      </tr>
      <?php endforeach ?>
      <?php
      $sql = "SELECT SUM(CASE WHEN id THEN 1 ELSE 0 END) count,
        FORMAT(SUM(cost * quantity),2) cost,
        FORMAT(SUM(price * quantity) - (SUM(price * quantity) * tax_rate / 100),2) sale
        FROM sma_products";
      $stmt = $dbcon->prepare($sql);
      $stmt->execute();
      $grand = $stmt->fetch();
      if (empty($category)) :
      ?>
      <tr>
        <td colspan="8" class="text-right">
          <b>Grand Total : <?php echo $grand['count'] ?></b>
        </td>
        <td class="text-right">
          <b><?php echo $grand['cost'] ?></b>
        </td>
        <td class="text-right">
          <b><?php echo $grand['sale'] ?></b>
        </td>
      </tr>
      <?php endif; ?>
    </tbody>
  </table>

</body>

</html>