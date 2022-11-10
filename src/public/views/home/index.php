<?php

use app\classes\Query;

$page = "index";
$group = "";

include_once(__DIR__ . "/../../includes/header.php");
include_once(__DIR__ . "/../../includes/sidebar.php");
require_once(__DIR__ . "/../../vendor/autoload.php");

$Query = new Query();
$card = $Query->sale_card();
?>

<main id="main" class="main">

  <div class="row">
    <div class="col-xl-3 col-md-6 mb-2">
      <div class="card text-bg-success shadow py-2 count" id="1">
        <div class="card-body">
          <h3 class="text-end"><?php echo $card['total'] ?></h3>
          <h5 class="text-end">ยอดขายทั้งหมด</h5>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-2">
      <div class="card text-bg-warning shadow py-2 count" id="2">
        <div class="card-body">
          <h3 class="text-end"><?php echo $card['today'] ?></h3>
          <h5 class="text-end">ยอดขายรายวัน</h5>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-2">
      <div class="card text-bg-primary shadow py-2 count" id="3">
        <div class="card-body">
          <h3 class="text-end"><?php echo $card['month'] ?></h3>
          <h5 class="text-end">ยอดขายรายเดือน</h5>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-2">
      <div class="card text-bg-danger shadow py-2 count" id="4">
        <div class="card-body">
          <h3 class="text-end"><?php echo $card['year'] ?></h3>
          <h5 class="text-end">ยอดขายรายปี</h5>
        </div>
      </div>
    </div>
  </div>

  <div class="row my-3">
    <div class="col-xl-3 col-md-6 mb-2">
      <input type="text" class="form-control form-control-sm filter_date" placeholder="-- วันที่ --">
    </div>

    <div class="col-xl-6 col-md-6 mb-2">
      <input type="text" class="form-control form-control-sm filter_keyword" placeholder="-- คำค้นหา --">
    </div>

    <div class="col-xl-3 col-md-6 mb-2">
      <button class="btn btn-warning btn-sm w-100 filter_btn">
        <i class="fa fa-search pe-2"></i> ค้นหา
      </button>
    </div>

    <div class="col-xl-3 col-md-6 mb-2">
      <a href="javascript:void(0)" class="btn btn-primary btn-sm w-100 report_sale">
        <i class="fa fa-file-lines pe-2"></i> รายงานขาย
      </a>
    </div>

    <div class="col-xl-3 col-md-6 mb-2">
      <a href="javascript:void(0)" class="btn btn-success btn-sm w-100 report_tax">
        <i class="fa fa-file-lines pe-2"></i> รายงานภาษี
      </a>
    </div>

    <div class="col-xl-3 col-md-6 mb-2" style="display: none;">
      <input type="text" class="form-control form-control-sm status" readonly>
    </div>
  </div>

  <div class="row my-3">
    <div class="col-xl-12">
      <div class="card shadow">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm data w-100">
              <thead>
                <tr>
                  <th width="10%">รหัสสินค้า</th>
                  <th width="20%">สินค้า</th>
                  <th width="10%">หน่วย</th>
                  <th width="10%">จำนวน</th>
                  <th width="10%">ยอดเงินรวม</th>
                  <th width="20%">วันที่ทำรายการ</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row my-3">
    <div class="col-xl-6 my-3">
      <div class="card shadow">
        <div class="card-body">
          <h4 class="text-center">10 อันดับสินค้าขายดี</h4>
          <h4 class="text-center">ประจำเดือน</h4>
          <div class="table-responsive">
            <table class="table table-hover table-sm w-100">
              <thead>
                <tr>
                  <th width="60%">สินค้า</th>
                  <th width="20%">จำนวน</th>
                  <th width="20%">ยอดเงินรวม</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sales = $Query->product_month_top();
                foreach ($sales as $sale) :
                ?>
                <tr>
                  <td><?php echo $sale['product_name'] ?></td>
                  <td class="text-center"><?php echo $sale['amount'] ?></td>
                  <td class="text-end"><?php echo $sale['total'] ?></td>
                </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-6 my-3">
      <div class="card shadow">
        <div class="card-body">
          <h4 class="text-center">10 อันดับสินค้าขายดี</h4>
          <h4 class="text-center">ประจำปี</h4>
          <div class="table-responsive">
            <table class="table table-hover table-sm w-100">
              <thead>
                <tr>
                  <th width="60%">สินค้า</th>
                  <th width="20%">จำนวน</th>
                  <th width="20%">ยอดเงินรวม</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sales = $Query->product_year_top();
                foreach ($sales as $sale) :
                ?>
                <tr>
                  <td><?php echo $sale['product_name'] ?></td>
                  <td class="text-center"><?php echo $sale['amount'] ?></td>
                  <td class="text-end"><?php echo $sale['total'] ?></td>
                </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xl-6 my-3">
      <div class="card shadow">
        <div class="card-body">
          <h4 class="text-center">10 อันดับหมวดหมู่ขายดี</h4>
          <h4 class="text-center">ประจำเดือน</h4>
          <div class="table-responsive">
            <table class="table table-hover table-sm w-100">
              <thead>
                <tr>
                  <th width="60%">หมวดหมู่</th>
                  <th width="20%">จำนวน</th>
                  <th width="20%">ยอดเงินรวม</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sales = $Query->category_month_top();
                foreach ($sales as $sale) :
                ?>
                <tr>
                  <td><?php echo $sale['name'] ?></td>
                  <td class="text-center"><?php echo $sale['amount'] ?></td>
                  <td class="text-end"><?php echo $sale['total'] ?></td>
                </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-6 my-3">
      <div class="card shadow">
        <div class="card-body">
          <h4 class="text-center">10 อันดับหมวดหมู่ขายดี</h4>
          <h4 class="text-center">ประจำปี</h4>
          <div class="table-responsive">
            <table class="table table-hover table-sm w-100">
              <thead>
                <tr>
                  <th width="60%">หมวดหมู่</th>
                  <th width="20%">จำนวน</th>
                  <th width="20%">ยอดเงินรวม</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sales = $Query->category_year_top();
                foreach ($sales as $sale) :
                ?>
                <tr>
                  <td><?php echo $sale['name'] ?></td>
                  <td class="text-center"><?php echo $sale['amount'] ?></td>
                  <td class="text-end"><?php echo $sale['total'] ?></td>
                </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</main>


<?php
include_once(__DIR__ . "/../../includes/footer.php");
?>
<script>
filter_data();

function filter_data(status, date, keyword) {
  let data = $(".data").DataTable({
    serverSide: true,
    scrollX: true,
    searching: false,
    order: [],
    ajax: {
      url: "/datasale",
      type: "POST",
      data: {
        status: status,
        date: date,
        keyword: keyword
      }
    },
    columnDefs: [{
      targets: [2, 3, 4, 5],
      className: "text-center",
    }],
    oLanguage: {
      sLengthMenu: "แสดง _MENU_ ลำดับ ต่อหน้า",
      sZeroRecords: "ไม่พบข้อมูลที่ค้นหา",
      sInfo: "แสดง _START_ ถึง _END_ ของ _TOTAL_ ลำดับ",
      sInfoEmpty: "แสดง 0 ถึง 0 ของ 0 ลำดับ",
      sInfoFiltered: "(จากทั้งหมด _MAX_ ลำดับ)",
      sSearch: "ค้นหา :",
      oPaginate: {
        sFirst: "หน้าแรก",
        sLast: "หน้าสุดท้าย",
        sNext: "ถัดไป",
        sPrevious: "ก่อนหน้า"
      }
    }
  });
};

$(document).on("click", ".count", function() {
  let status = $(this).prop("id");
  $(".status").val(status)
  if (status) {
    $(".data").DataTable().destroy();
    filter_data(status);
  } else {
    $(".data").DataTable().destroy();
    filter_data();
  }
});

$(document).on("click", ".filter_btn", function() {
  let status = 1;
  let date = $(".filter_date").val();
  let keyword = $(".filter_keyword").val();

  if (status || date || keyword) {
    $(".data").DataTable().destroy();
    filter_data(status, date, keyword);
  } else {
    $(".data").DataTable().destroy();
    filter_data();
  }
});

$(".filter_date").on("keydown", function(e) {
  e.preventDefault();
});

$(".filter_date").daterangepicker({
  autoUpdateInput: false,
  showDropdowns: true,
  startDate: moment(),
  endDate: moment().add(1, 'days'),
  locale: {
    "format": "DD/MM/YYYY",
    "applyLabel": "ยืนยัน",
    "cancelLabel": "ยกเลิก",
    "daysOfWeek": [
      "อา", "จ", "อ", "พ", "พฤ", "ศ", "ส"
    ],
    "monthNames": [
      "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน",
      "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"
    ]
  },
  "applyButtonClasses": "btn-success",
  "cancelClass": "btn-danger"
});

$(".filter_date").on("apply.daterangepicker", function(ev, picker) {
  $(this).val(picker.startDate.format("DD/MM/YYYY") + ' - ' + picker.endDate.format("DD/MM/YYYY"));
});

$(".filter_date").on("cancel.daterangepicker", function(ev, picker) {
  $(this).val("");
});

$(document).on("click", ".report_sale", function() {
  let date = $(".filter_date").val();
  date = date.split("/").join("_");
  let keyword = $(".filter_keyword").val();
  window.open("/reportsale/" + date + "/" + keyword);
});

$(document).on("click", ".report_tax", function() {
  let date = $(".filter_date").val();
  date = date.split("/").join("_");
  let keyword = $(".filter_keyword").val();
  window.open("/reporttax/" + date + "/" + keyword);
});
</script>