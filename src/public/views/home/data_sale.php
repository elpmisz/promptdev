<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
require_once(__DIR__ . "/../../includes/connection.php");
require_once(__DIR__ . "/../../vendor/autoload.php");

$user_id = (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "");

$stmt = $dbcon->prepare("SELECT COUNT(*) FROM sma_sale_items");
$stmt->execute();
$count = $stmt->fetchColumn();

$column = ["A.product_code", "A.product_name", "A.product_unit_code", "A.quantity", "A.subtotal", "B.date"];

$status = (isset($_POST['status']) ? intval($_POST['status']) : "");
$date = (isset($_POST['date']) ? ($_POST['date']) : "");
$conv = (!empty($date) ? explode("-", $date) : "");
$sale = (isset($_POST['sale']) ? ($_POST['sale']) : "");
$keyword = (isset($_POST['keyword']) ? ($_POST['keyword']) : "");

// $keyword = (isset($_POST['search']['value']) ? $_POST['search']['value'] : "");
$order = (isset($_POST['order']) ? $_POST['order'] : "");
$order_column = (isset($_POST['order']['0']['column']) ? $_POST['order']['0']['column'] : "");
$order_dir = (isset($_POST['order']['0']['dir']) ? $_POST['order']['0']['dir'] : "");
$limit_start = (isset($_POST['start']) ? $_POST['start'] : "");
$limit_length = (isset($_POST['length']) ? $_POST['length'] : "");
$draw = (isset($_POST['draw']) ? $_POST['draw'] : "");

$sql = "SELECT A.product_code,A.product_name,A.product_unit_code unit,
LPAD((B.pos), GREATEST(LENGTH(B.pos), 3), '0') pos,C.username,
FORMAT(A.quantity,0) qty,
FORMAT(A.subtotal,2) total,
DATE_FORMAT(B.date, '%d/%m/%Y - %H:%i น.') date 
FROM sma_sale_items A
LEFT JOIN sma_sales B
ON A.sale_id = B.id
LEFT JOIN sma_users C
ON B.created_by = C.id ";

if ($status === 2) {
  $sql .= " WHERE DATE(B.date) = DATE(NOW()) ";
} elseif ($status === 3) {
  $sql .= " WHERE MONTH(B.date) = MONTH(NOW()) ";
} elseif ($status === 4) {
  $sql .= " WHERE YEAR(B.date) = YEAR(NOW()) ";
} else {
  $sql .= " WHERE B.id != '' ";
}

if ($conv) {
  $sql .= " AND DATE(B.date) BETWEEN STR_TO_DATE('{$conv[0]}', '%d/%m/%Y') AND STR_TO_DATE('{$conv[1]}', '%d/%m/%Y') ";
}

if ($sale) {
  $sql .= " AND C.id = {$sale} ";
}

if ($keyword) {
  $sql .= " AND A.product_name LIKE '%{$keyword}%' ";
}

if ($order) {
  $sql .= "ORDER BY {$column[$order_column]} {$order_dir} ";
} else {
  $sql .= "ORDER BY B.date DESC ";
}

$query = "";
if (!empty($limit_length)) {
  $query .= "LIMIT {$limit_start}, {$limit_length}";
}

$stmt = $dbcon->prepare($sql);
$stmt->execute();
$filter = $stmt->rowCount();
$stmt = $dbcon->prepare($sql . $query);
$stmt->execute();
$result = $stmt->fetchAll();

$data = [];
foreach ($result as $row) {
  $data[] = [
    "0" => $row['product_code'],
    "1" => $row['product_name'],
    "2" => $row['unit'],
    "3" => $row['qty'],
    "4" => $row['total'],
    "5" => $row['pos'],
    "6" => $row['username'],
    "7" => $row['date'],
  ];
}

$output = [
  "draw"    => $draw,
  "recordsTotal"  =>  $count,
  "recordsFiltered" => $filter,
  "data"    => $data
];

echo json_encode($output);