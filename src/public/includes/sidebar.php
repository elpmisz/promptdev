<?php
$page = (!empty($page) ? $page : "");
$group = (!empty($group) ? $group : "");
?>
<aside id="sidebar" class="sidebar">
  <ul class="sidebar-nav" id="sidebar-nav">
    <li class="nav-item">
      <a class="nav-link <?php echo ($page === "index" ? "" : "collapsed") ?>" href="/">
        <i class="fa fa-chart-simple"></i> <span>รายงานขาย</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?php echo ($page === "product" ? "" : "collapsed") ?>" href="/product">
        <i class="fa fa-chart-line"></i> <span>รายงานสินค้า</span>
      </a>
    </li>
  </ul>
</aside>