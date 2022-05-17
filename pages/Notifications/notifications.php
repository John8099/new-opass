<?php
session_start();
include_once("../../backend/conn.php");
if (!isset($_SESSION["id"])) {
  header("location: ../../index.php");
}
$user = mysqli_fetch_object(
  mysqli_query(
    $con,
    "SELECT * FROM users WHERE id = $_SESSION[id]"
  )
);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Notifications</title>

  <!-- Styles -->
  <link href="../../assets/css/lib/font-awesome.min.css" rel="stylesheet">
  <link href="../../assets/css/lib/themify-icons.css" rel="stylesheet">
  <link href="../../assets/css/lib/owl.carousel.min.css" rel="stylesheet" />
  <link href="../../assets/css/lib/owl.theme.default.min.css" rel="stylesheet" />
  <link href="../../assets/css/lib/menubar/sidebar.css" rel="stylesheet">
  <link href="../../assets/css/lib/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/css/lib/helper.css" rel="stylesheet">
  <link href="../../assets/css/style.css" rel="stylesheet">
</head>

<body>

  <?php include("../../components/side-nav.php"); ?>
  <!-- /# sidebar -->

  <?php include("../../components/top-nav.php") ?>
  <!-- /# header -->

  <div class="content-wrap">
    <div class="main">
      <div class="container-fluid">
        <div class="page-header">
          <div class="page-title">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><i class="ti-bell"></i></li>
              <li class="breadcrumb-item active">Notifications</li>
            </ol>
          </div>
        </div>
        <!-- /# row -->
        <section id="main-content">
          <div class="card">
            <div class="card-title">
              <div class="row">
                <div class="col-sm-6">
                  Notifications
                </div>
                <div class="col-sm-6">
                  <div class="d-flex justify-content-end">
                    <button class="btn btn-danger" type="button" onclick="deleteNotif()">
                      Delete All
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-body">
              <div class="row mt-4">
                <div class="col">
                  <ul id="divNotificationData"></ul>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>

  <!-- jquery vendor -->
  <script src="../../assets/js/lib/jquery.min.js"></script>
  <script src="../../assets/js/lib/jquery.nanoscroller.min.js"></script>
  <!-- nano scroller -->
  <script src="../../assets/js/lib/menubar/sidebar.js"></script>
  <script src="../../assets/js/lib/preloader/pace.min.js"></script>
  <!-- sidebar -->

  <script src="../../assets/js/lib/bootstrap.min.js"></script>
  <script src="../../assets/js/scripts.js"></script>
  <!-- bootstrap -->

  <script src="../../assets/js/lib/circle-progress/circle-progress.min.js"></script>
  <script src="../../assets/js/lib/circle-progress/circle-progress-init.js"></script>
  <script src="../../assets/js/lib/owl-carousel/owl.carousel.min.js"></script>
  <script src="../../assets/js/lib/owl-carousel/owl.carousel-init.js"></script>
  <!-- scripit init-->

  <script src="../../assets/js/lib/sweetalert/sweetalert.min.js"></script>
</body>
<script>
  $.get("../../backend/nodes.php?action=getAllNotificationData", function(data) {
    $("#divNotificationData").html(data)
  });

  function deleteNotif(notifId = "all") {
    swal.showLoading();
    $.ajax({
      url: '../../backend/nodes.php?action=deleteNotif',
      data: {
        notification_id: notifId
      },
      type: 'POST',
      success: function(data) {
        swal.close();
        const resp = JSON.parse(data)
        if (resp.success) {
          swal.fire({
            title: 'Success!',
            text: resp.message,
            icon: 'success',
          }).then(() => location.reload())
        } else {
          swal.fire({
            title: 'Error!',
            text: resp.message,
            icon: 'error',
          }).then(() => location.reload())
        }
      },
      error: function(data) {
        swal.fire({
          title: 'Oops...',
          text: 'Something went wrong.',
          icon: 'error',
        }).then(() => location.reload())
      }
    });
  }
</script>

</html>