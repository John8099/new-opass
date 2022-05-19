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
if ($user->role != "user") {
  header("location: ../404.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Opened Appointment</title>

  <!-- Styles -->
  <link href="../../assets/css/lib/font-awesome.min.css" rel="stylesheet">
  <link href="../../assets/css/lib/themify-icons.css" rel="stylesheet">
  <link href="../../assets/css/lib/owl.carousel.min.css" rel="stylesheet" />
  <link href="../../assets/css/lib/owl.theme.default.min.css" rel="stylesheet" />
  <link href="../../assets/css/lib/menubar/sidebar.css" rel="stylesheet">
  <link href="../../assets/css/lib/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/css/lib/helper.css" rel="stylesheet">
  <link href="../../assets/css/lib/data-table/dataTables.bootstrap.min.css" rel="stylesheet" />
  <link href="../../assets/css/lib/data-table/twitter.bootstrap.css" rel="stylesheet" />
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
              <li class="breadcrumb-item"><a href="#"><i class="fa fa-envelope-open"></i></a></li>
              <li class="breadcrumb-item active">Opened Appointment</li>
            </ol>
          </div>
        </div>
        <!-- /# row -->
        <section id="main-content">
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <table id="data-table" class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Contact</th>
                      <th>Year of Experience</th>
                      <th>Specialization</th>
                      <th class="text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $attorneyQuery = mysqli_query(
                      $con,
                      "SELECT * FROM 
                      users u 
                      INNER JOIN 
                      specialization s 
                      ON 
                      u.specialization_id = s.specialization_id 
                      WHERE u.role='atty' and u.opened_appointment = 1"
                    );
                    while ($attorney = mysqli_fetch_object($attorneyQuery)) :
                    ?>
                      <tr>
                        <td>
                          <?= ucwords("Atty. " . "$attorney->fname " . $attorney->mname[0] . ". $attorney->lname") ?>
                        </td>
                        <td>
                          <?= $attorney->contact ?>
                        </td>
                        <td>
                          <?= "$attorney->year_exp Years" ?>
                        </td>
                        <td>
                          <?= $attorney->specialization_name ?>
                        </td>
                        <td class="d-flex justify-content-center">
                          <a href="../Conversation/messages.php?user_id=<?= $attorney->id ?>" class="btn btn-warning m-1">
                            Chat
                          </a>
                          <a href="attorney-details.php?id=<?= $attorney->id ?>" class="btn btn-primary m-1">
                            More
                          </a>
                        </td>
                      </tr>
                    <?php endwhile; ?>

                  </tbody>
                </table>
              </div>
              <!-- /# card -->
            </div>
            <!-- /# column -->
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

  <script src="../../assets/js/lib/data-table/jquery.dataTables.min.js"></script>
  <script src="../../assets/js/lib/data-table/dataTables.bootstrap4.min.js"></script>
</body>
<script>
  $(document).ready(function() {
    $('#data-table').DataTable();
  });
</script>

</html>