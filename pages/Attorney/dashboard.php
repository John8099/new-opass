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
  <title>Dashboard</title>

  <!-- Styles -->
  <link href="../../assets/css/lib/calendar2/pignose.calendar.min.css" rel="stylesheet">
  <link href="../../assets/css/lib/chartist/chartist.min.css" rel="stylesheet">
  <link href="../../assets/css/lib/font-awesome.min.css" rel="stylesheet">
  <link href="../../assets/css/lib/themify-icons.css" rel="stylesheet">
  <link href="../../assets/css/lib/owl.carousel.min.css" rel="stylesheet" />
  <link href="../../assets/css/lib/owl.theme.default.min.css" rel="stylesheet" />
  <link href="../../assets/css/lib/weather-icons.css" rel="stylesheet" />
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
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item active">Home</li>
            </ol>
          </div>
        </div>
        <!-- /# row -->
        <section id="main-content">
          <!-- Content here -->
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

  <script src="../../assets/js/lib/calendar-2/moment.latest.min.js"></script>
  <script src="../../assets/js/lib/calendar-2/pignose.calendar.min.js"></script>
  <script src="../../assets/js/lib/calendar-2/pignose.init.js"></script>


  <script src="../../assets/js/lib/weather/jquery.simpleWeather.min.js"></script>
  <script src="../../assets/js/lib/weather/weather-init.js"></script>
  <script src="../../assets/js/lib/circle-progress/circle-progress.min.js"></script>
  <script src="../../assets/js/lib/circle-progress/circle-progress-init.js"></script>
  <script src="../../assets/js/lib/chartist/chartist.min.js"></script>
  <script src="../../assets/js/lib/sparklinechart/jquery.sparkline.min.js"></script>
  <script src="../../assets/js/lib/sparklinechart/sparkline.init.js"></script>
  <script src="../../assets/js/lib/owl-carousel/owl.carousel.min.js"></script>
  <script src="../../assets/js/lib/owl-carousel/owl.carousel-init.js"></script>
  <!-- scripit init-->
  <script src="../../assets/js/dashboard2.js"></script>
</body>

</html>