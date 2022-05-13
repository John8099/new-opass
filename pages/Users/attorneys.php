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
  <title>Attorneys</title>

  <!-- Styles -->
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
              <li class="breadcrumb-item"><a href="#"><i class="fa fa-black-tie"></i></a></li>
              <li class="breadcrumb-item active">Our Attorneys</li>
            </ol>
          </div>
        </div>
        <!-- /# row -->
        <section id="main-content">
          <div class="jumbotron m-3" style="padding-top: 15px;">
            <h3>
              Top rated Lawyers
            </h3>
            <div class="row d-flex justify-content-center">
              <?php
              $featuredQuery = mysqli_query(
                $con,
                "SELECT * FROM users u INNER JOIN specialization s on u.specialization_id = s.specialization_id WHERE `role`='atty' and year_exp > 9"
              );
              if (mysqli_num_rows($featuredQuery) > 0) :
                while ($featuredAtty = mysqli_fetch_object($featuredQuery)) :
              ?>
                  <div class="col-lg-5">
                    <div class="card">
                      <div class="card-body">
                        <div class="user-profile">
                          <div class="d-flex flex-column">
                            <center>
                              <div class="user-profile-name">
                                <?= ucwords("Atty. " . "$featuredAtty->fname " . $featuredAtty->mname[0] . ".
                                $featuredAtty->lname") ?>
                              </div>
                            </center>
                          </div>
                          <div class="user-work mt-3">
                            <div class="work-content">
                              <h3>Specialized in</h3>
                              <p><?= $featuredAtty->specialization_name ?></p>
                            </div>
                            <div class="work-content">
                              <h3>Age</h3>
                              <p>
                                <?php
                                date_default_timezone_set("Asia/Manila");
                                $bday = new DateTime($featuredAtty->birthday);
                                $today = new Datetime(date('y-m-d'));
                                $diff = $today->diff($bday);
                                printf(
                                  '%d',
                                  $diff->y
                                ); ?>
                              </p>
                            </div>
                          </div>
                          <div class="d-flex justify-content-end">
                            <a href="book-appointment.php?attyId=<?= $featuredAtty->id ?>" class="btn btn-success m-2">Book Appointment</a>
                            <a href="attorney-details.php?id=<?= $featuredAtty->id ?>" class="btn btn-primary m-2">More</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endwhile; ?>
              <?php else : ?>
                <h2 style="margin-top: 20px;">No top rated lawyers to display.</h2>
              <?php
              endif; ?>

            </div>
          </div>
          <div class="jumbotron m-3" style="padding-top: 15px;">
            <h3>
              Our Lawyers
            </h3>
            <div class="row d-flex justify-content-center">
              <?php
              $featuredQuery = mysqli_query(
                $con,
                "SELECT * FROM users u INNER JOIN specialization s on u.specialization_id = s.specialization_id WHERE `role`='atty'and year_exp < 9"
              );
              if (mysqli_num_rows($featuredQuery) > 0) :
                while ($featuredAtty = mysqli_fetch_object($featuredQuery)) :
              ?>
                  <div class="col-lg-5">
                    <div class="card">
                      <div class="card-body">
                        <div class="user-profile">
                          <div class="d-flex flex-column">
                            <center>
                              <div class="user-profile-name">
                                <?= ucwords("Atty. " . "$featuredAtty->fname " . $featuredAtty->mname[0] . ".
                                $featuredAtty->lname") ?>
                              </div>
                            </center>
                          </div>
                          <div class="user-work mt-3">
                            <div class="work-content">
                              <h3>Specialized in</h3>
                              <p><?= $featuredAtty->specialization_name ?></p>
                            </div>
                            <div class="work-content">
                              <h3>Age</h3>
                              <p>
                                <?php
                                date_default_timezone_set("Asia/Manila");
                                $bday = new DateTime($featuredAtty->birthday);
                                $today = new Datetime(date('y-m-d'));
                                $diff = $today->diff($bday);
                                printf(
                                  '%d',
                                  $diff->y
                                ); ?>
                              </p>
                            </div>
                          </div>
                          <div class="d-flex justify-content-end">
                            <a href="book-appointment.php?attyId=<?= $featuredAtty->id ?>" class="btn btn-success m-2">Book Appointment</a>
                            <a href="attorney-details.php?id=<?= $featuredAtty->id ?>" class="btn btn-primary m-2">More</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endwhile; ?>
              <?php else : ?>
                <h2 style="margin-top: 20px;">No top rated lawyers to display.</h2>
              <?php
              endif; ?>

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

  <script src="../../assets/js/lib/calendar-2/moment.latest.min.js"></script>


  <script src="../../assets/js/lib/circle-progress/circle-progress.min.js"></script>
  <script src="../../assets/js/lib/circle-progress/circle-progress-init.js"></script>
  <script src="../../assets/js/lib/owl-carousel/owl.carousel.min.js"></script>
  <script src="../../assets/js/lib/owl-carousel/owl.carousel-init.js"></script>
  <!-- scripit init-->
</body>

</html>