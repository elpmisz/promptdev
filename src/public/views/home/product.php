<?php
$page = "product";
$group = "";

include_once(__DIR__ . "/../../includes/header.php");
include_once(__DIR__ . "/../../includes/sidebar.php");
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
      <a href="javascript:void(0)" class="btn btn-primary btn-sm w-100 report_sale">
        <i class="fa fa-file-lines pe-2"></i> รายงานสินค้าคงเหลือ
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
</script>