<?php

use app\classes\Query;

$page = "product";
$group = "";

include_once(__DIR__ . "/../../includes/header.php");
include_once(__DIR__ . "/../../includes/sidebar.php");
require_once(__DIR__ . "/../../vendor/autoload.php");

$Query = new Query();
?>

<main id="main" class="main">

  <div class="row my-3">
    <div class="col-xl-3 col-md-6 mb-2">
      <select class="form-select form-select-sm filter_category" data-placeholder="-- หมวดหมู่ --"></select>
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
      <a href="javascript:void(0)" class="btn btn-danger btn-sm w-100 report_product">
        <i class="fa fa-file-lines pe-2"></i> รายงานสินค้าคงเหลือ (PDF)
      </a>
    </div>

    <div class="col-xl-3 col-md-6 mb-2">
      <a href="javascript:void(0)" class="btn btn-success btn-sm w-100 excel_product">
        <i class="fa fa-file-lines pe-2"></i> รายงานสินค้าคงเหลือ (Excel)
      </a>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-xl-12">
      <div class="card shadow">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm data w-100">
              <thead>
                <tr>
                  <th width="10%">หมวดหมู่</th>
                  <th width="10%">รหัสสินค้า</th>
                  <th width="20%">สินค้า</th>
                  <th width="10%">หน่วย</th>
                  <th width="10%">ราคา</th>
                  <th width="10%">จำนวน</th>
                  <th width="10%">มูลค่าคงเหลือ</th>
                  <th width="10%">มูลค่าขาย</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-xl-6 my-3">
      <div class="card shadow">
        <div class="card-body">
          <h4 class="text-center">10 อันดับ ปริมาณสินค้าคงเหลือ (ต่ำ)</h4>
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
                $products = $Query->product_count();
                foreach ($products as $product) :
                ?>
                <tr>
                  <td><?php echo $product['name'] ?></td>
                  <td class="text-center"><?php echo $product['qty'] ?></td>
                  <td class="text-end"><?php echo $product['cost'] ?></td>
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
          <h4 class="text-center">จำนวนสินค้า (หมวดหมู่)</h4>
          <div class="table-responsive">
            <table class="table table-hover table-sm w-100">
              <thead>
                <tr>
                  <th width="60%">หมวดหมู่</th>
                  <th width="20%">จำนวน</th>
                  <th width="20%">มูลค่า</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $category = $Query->category_count();
                foreach ($category as $cat) :
                ?>
                <tr>
                  <td><?php echo $cat['name'] ?></td>
                  <td class="text-center"><?php echo $cat['count'] ?></td>
                  <td class="text-end"><?php echo $cat['cost'] ?></td>
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

function filter_data(category, keyword) {
  let data = $(".data").DataTable({
    serverSide: true,
    scrollX: true,
    searching: false,
    order: [],
    ajax: {
      url: "/dataproduct",
      type: "POST",
      data: {
        category: category,
        keyword: keyword
      }
    },
    columnDefs: [{
        targets: [3, 4, 5],
        className: "text-center",
      }, {
        targets: [6, 7],
        className: "text-end",
      },
      {
        targets: [4, 6, 7],
        render: $.fn.dataTable.render.number(',', '.', 2, '')
      },
      {
        targets: [5],
        render: $.fn.dataTable.render.number(',', '.', 0, '')
      }
    ],
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

$(document).on("click", ".filter_btn", function() {
  let category = $(".filter_category").val();
  let keyword = $(".filter_keyword").val();

  if (category || keyword) {
    $(".data").DataTable().destroy();
    filter_data(category, keyword);
  } else {
    $(".data").DataTable().destroy();
    filter_data();
  }
});

$(".filter_category").each(function() {
  $(this).select2({
    containerCssClass: "select2--small",
    dropdownCssClass: "select2--small",
    dropdownParent: $(this).parent(),
    width: "100%",
    allowClear: true,
    ajax: {
      url: "/action/category",
      method: 'POST',
      dataType: 'json',
      delay: 100,
      processResults: function(data) {
        return {
          results: data
        };
      },
      cache: true
    }
  });
});

$(document).on("click", ".report_product", function() {
  let category = ($(".filter_category").val() ? $(".filter_category").val() : "");
  window.open("/reportproduct/" + category + "/");
});

$(document).on("click", ".excel_product", function() {
  let category = ($(".filter_category").val() ? $(".filter_category").val() : "");
  window.open("/excelproduct/" + category + "/");
});
</script>