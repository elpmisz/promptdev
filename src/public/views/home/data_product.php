<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set("Asia/Bangkok");
require_once(__DIR__ . "/../../includes/connection.php");
require_once(__DIR__ . "/../../vendor/autoload.php");

$user_id = (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "");

$stmt = $dbcon->prepare("SELECT COUNT(*) FROM sma_products");
$stmt->execute();
$count = $stmt->fetchColumn();

$column = ["C.name", "A.code", "A.name", "B.name", "price", "quantity", "(cost * quantity)", "(price * quantity) - ((price * quantity) * tax_rate / 100)"];

$category = (isset($_POST['category']) ? ($_POST['category']) : "");
$keyword = (isset($_POST['keyword']) ? ($_POST['keyword']) : "");

// $keyword = (isset($_POST['search']['value']) ? $_POST['search']['value'] : "");
$order = (isset($_POST['order']) ? $_POST['order'] : "");
$order_column = (isset($_POST['order']['0']['column']) ? $_POST['order']['0']['column'] : "");
$order_dir = (isset($_POST['order']['0']['dir']) ? $_POST['order']['0']['dir'] : "");
$limit_start = (isset($_POST['start']) ? $_POST['start'] : "");
$limit_length = (isset($_POST['length']) ? $_POST['length'] : "");
$draw = (isset($_POST['draw']) ? $_POST['draw'] : "");

$sql = "SELECT 
C.name category_name,A.code product_code,A.name product_name,B.name unit,price,quantity,(cost * quantity) cost,((price * quantity) - ((price * quantity) * tax_rate / 100)) sale
FROM sma_products A 
LEFT JOIN sma_units B 
ON A.unit = B.id
LEFT JOIN sma_categories C 
ON A.category_id = C.id
WHERE A.id != '' ";

if ($category) {
  $sql .= " AND A.category_id = {$category} ";
}

if ($keyword) {
  $sql .= " AND (A.name LIKE '%{$keyword}%' OR B.name LIKE '%{$keyword}%' OR C.name LIKE '%{$keyword}%') ";
}

if ($order) {
  $sql .= "ORDER BY {$column[$order_column]} {$order_dir} ";
} else {
  $sql .= "ORDER BY category_id ASC ";
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
    "0" => $row['category_name'],
    "1" => $row['product_code'],
    "2" => $row['product_name'],
    "3" => $row['unit'],
    "4" => $row['price'],
    "5" => $row['quantity'],
    "6" => $row['cost'],
    "7" => $row['sale'],
  ];
}

$output = [
  "draw"    => $draw,
  "recordsTotal"  =>  $count,
  "recordsFiltered" => $filter,
  "data"    => $data
];

echo json_encode($output);