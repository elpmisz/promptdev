<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . "/../vendor/autoload.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Report</title>
  <link rel="stylesheet" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/vendor/fortawesome/font-awesome/css/all.min.css">
  <link rel="stylesheet" href="/vendor/pnikolov/bootstrap-daterangepicker/css/daterangepicker.min.css">
  <link rel="stylesheet" href="/vendor/fullcalendar/fullcalendar/dist/fullcalendar.min.css">
  <link rel="stylesheet" href="/vendor/select2/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="/assets/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
  <header id="header" class="header fixed-top d-flex align-items-center">
    <div class="d-flex align-items-center justify-content-between">
      <a href="/" class="logo d-flex align-items-center">
        <span class="d-none d-lg-block">Report</span>
      </a>
      <i class="fa fa-bars toggle-sidebar-btn"></i>
    </div>
  </header>