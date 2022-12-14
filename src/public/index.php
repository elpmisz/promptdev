<?php
require_once(__DIR__ . "/vendor/autoload.php");
$Router = new AltoRouter();

$Router->map("GET", "/", function () {
  require(__DIR__ . "/views/home/index.php");
});
$Router->map("GET", "/product", function () {
  require(__DIR__ . "/views/home/product.php");
});
$Router->map("GET", "/error", function () {
  require(__DIR__ . "/views/home/error.php");
});

$Router->map("GET", "/reportsale/[**:params]", function ($params) {
  require(__DIR__ . "/views/home/report_sale.php");
});
$Router->map("GET", "/excelsale/[**:params]", function ($params) {
  require(__DIR__ . "/views/home/excel_sale.php");
});
$Router->map("GET", "/reporttax/[**:params]", function ($params) {
  require(__DIR__ . "/views/home/report_tax.php");
});
$Router->map("GET", "/exceltax/[**:params]", function ($params) {
  require(__DIR__ . "/views/home/excel_tax.php");
});
$Router->map("GET", "/reportproduct/[**:params]", function ($params) {
  require(__DIR__ . "/views/home/report_product.php");
});
$Router->map("GET", "/excelproduct/[**:params]", function ($params) {
  require(__DIR__ . "/views/home/excel_product.php");
});
$Router->map("GET", "/reportshift/[**:params]", function ($params) {
  require(__DIR__ . "/views/home/report_shift.php");
});

$Router->map("POST", "/datasale", function () {
  require(__DIR__ . "/views/home/data_sale.php");
});
$Router->map("POST", "/dataproduct", function () {
  require(__DIR__ . "/views/home/data_product.php");
});
$Router->map("POST", "/action/[**:params]", function ($params) {
  require(__DIR__ . "/views/home/action.php");
});

$match = $Router->match();

if (is_array($match) && is_callable($match['target'])) {
  call_user_func_array($match['target'], $match['params']);
} else {
  header("HTTP/1.1 404 Not Found");
  require __DIR__ . "/views/home/error.php";
}