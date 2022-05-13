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
if ($user->role != "atty") {
  header("location: ../404.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Done Appointments</title>

  <!-- Styles -->
  <link href="../../assets/css/lib/font-awesome.min.css" rel="stylesheet">
  <link href="../../assets/css/lib/themify-icons.css" rel="stylesheet">
  <link href="../../assets/css/lib/owl.carousel.min.css" rel="stylesheet" />
  <link href="../../assets/css/lib/owl.theme.default.min.css" rel="stylesheet" />
  <link href="../../assets/css/lib/menubar/sidebar.css" rel="stylesheet">
  <link href="../../assets/css/lib/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/css/lib/helper.css" rel="stylesheet">
  <link href="../../assets/css/style.css" rel="stylesheet">

  <script src="../../assets/js/lib/jquery.min.js"></script>
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
              <li class="breadcrumb-item"><a href="#"><i class="ti-check-box"></i></a></li>
              <li class="breadcrumb-item active">Done Appointments</li>
            </ol>
          </div>
        </div>
        <!-- /# row -->
        <section id="main-content">
          <div class="card">
            <div class="card-body">
              <table id="tableDone" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Request</th>
                    <th>Case</th>
                    <th class="col-sm-2">Appointment Date and Time</th>
                    <th class="col-sm-2">Appointment Ended </th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $appointmentQuery = mysqli_query(
                    $con,
                    "SELECT * FROM appointments WHERE `attorney_id`=$_SESSION[id] and `status` = 'done'"
                  );
                  while ($appointment = mysqli_fetch_object($appointmentQuery)) :
                    $user = mysqli_fetch_object(
                      mysqli_query(
                        $con,
                        "SELECT * FROM users WHERE id=$appointment->user_id"
                      )
                    );
                  ?>
                    <tr>
                      <td>
                        <?= ucwords("$user->fname " . $user->mname[0] . ". $user->lname") ?>
                      </td>
                      <td>
                        <?= $appointment->request ?>
                      </td>
                      <td>
                        <?= $appointment->ended_remark ?>
                      </td>
                      <td>
                        <?= date_format(
                          date_create("$appointment->appointment_date $appointment->appointment_time"),
                          "M d, Y h:i A"
                        ) ?>
                      </td>
                      <td>
                        <?= date_format(
                          date_create("$appointment->date_ended"),
                          "M d, Y"
                        ) ?>
                      </td>
                    </tr>
                  <?php endwhile; ?>

                </tbody>
              </table>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>

  <!-- jquery vendor -->
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

  <script src="../../assets/js/lib/data-table/jquery.dataTables.min.js"></script>
  <script src="../../assets/js/lib/data-table/dataTables.bootstrap4.min.js"></script>

  <script src="../../assets/js/lib/sweetalert/sweetalert.min.js"></script>
</body>
<script>
  $('#tableDone').DataTable();
  // const interval = setInterval(function() {
  //   $("#tableDone").load("done-appointments.php #tableDone");
  //   console.log("test")
  // }, 1000); //refresh every 2 seconds

  // $('#tableDone').on('search.dt', function() {
  //   var value = $('.dataTables_filter input').val();
  //   if (value === "") {
  //     clearInterval(interval); // stop the interval
  //   } else {
  //     interval = setInterval(function() {
  //       $("#tableDone").load("done-appointments.php #tableDone");
  //     }, 2000); //refresh every 2 seconds
  //   }
  // });
</script>

</html>