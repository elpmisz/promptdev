<?php if (isset($_SESSION['text']) && $_SESSION['text']) : ?>
  <div class="row justify-content-center py-3">
    <div class="col-sm-12">
      <div class="alert alert-<?php echo $_SESSION['alert'] ?>">
        <h4 class="text-center"><?php echo $_SESSION['text']; ?></h4>
      </div>
    </div>
  </div>
<?php
endif;
unset($_SESSION['alert'], $_SESSION['text']);
?>