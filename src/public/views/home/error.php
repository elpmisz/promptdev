<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Error</title>
  <link href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body>

  <div class="container">
    <div class="row justify-content-center my-5">
      <div class="col-12">
        <h1 class="text-center text-danger display-1">404</h1>
        <h1 class="text-center text-danger display-1">ไม่พบข้อมูล!!!</h1>
      </div>
      <div class="col-6">
        <img src="/assets/img/404.jpg" class="img-fluid" alt="Page Not Found">
      </div>
    </div>
  </div>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>
</body>

</html>
<script src="/vendor/components/jquery/jquery.min.js"></script>
<script>
  $(function() {
    setTimeout(function() {
      window.location.replace("/");
    }, 5000);
  });
</script>