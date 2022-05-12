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
  <title>Attorney Details</title>

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
              <li class="breadcrumb-item"><a href="#"><i class="fa fa-black-tie"></i></a></li>
              <li class="breadcrumb-item active">Attorney Details</li>
            </ol>
          </div>
        </div>
        <!-- /# row -->
        <?php
        $attorney = mysqli_fetch_object(
          mysqli_query(
            $con,
            "SELECT * FROM users u INNER JOIN specialization s on u.specialization_id = s.specialization_id WHERE u.id = '$_GET[id]'"
          )
        );
        ?>
        <section id="main-content">
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <div class="user-profile">
                    <div class="row">
                      <div class="col-lg-4">
                        <div class="user-photo m-b-30 d-flex justify-content-center">
                          <img class="img-fluid rounded border" src="../../<?= $attorney->profile
                                                                              == null ? "assets/images/user-profile.jpg" :
                                                                              "profile-photo/$attorney->profile" ?>" style="width:200px;height: 200px;" />
                        </div>
                        <div class="user-work mt-3">
                          <div class="work-content">
                            <h3>Specialized in</h3>
                            <p><?= $attorney->specialization_name ?></p>
                          </div>
                          <div class="work-content">
                            <h3>Year(s) of Experience</h3>
                            <p><?= $attorney->year_exp ?></p>
                          </div>
                          <div class="work-content">
                            <h3>Description</h3>
                            <p><?= $attorney->spec_description ?></p>
                          </div>
                        </div>
                      </div>
                      <div class="col-lg-8">
                        <div class="user-profile-name">
                          <?= ucwords("Atty. $attorney->fname " . $attorney->mname[0] . ".
                            $attorney->lname") ?>
                        </div>

                        <div class="custom-tab user-profile-tab">
                          <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                              <a> About </a>
                            </li>
                          </ul>
                          <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="1">
                              <div class="contact-information">
                                <h4>Contact information</h4>
                                <div class="phone-content">
                                  <span class="contact-title"> Phone: </span>
                                  <span class="phone-number">
                                    <?= $attorney->contact ?>
                                  </span>
                                </div>
                                <div class="address-content">
                                  <span class="contact-title">
                                    Address:
                                  </span>
                                  <span class="mail-address">
                                    <?= $attorney->address ?>
                                  </span>
                                </div>
                                <div class="email-content">
                                  <span class="contact-title"> Email: </span>
                                  <span class="contact-email">
                                    <?= $attorney->email ?>
                                  </span>
                                </div>
                              </div>
                              <div class="basic-information">
                                <h4>Basic information</h4>
                                <div class="birthday-content">
                                  <span class="contact-title">Birthday:</span>
                                  <span class="birth-date">
                                    <?= date_format(
                                      date_create($attorney->birthday),
                                      "M d, Y"
                                    ) ?>
                                  </span>
                                </div>
                                <div class="gender-content">
                                  <span class="contact-title">Age:</span>
                                  <span class="gender">
                                    <?php
                                    date_default_timezone_set("Asia/Manila");
                                    $bday = new DateTime($attorney->birthday);
                                    $today = new Datetime(date('y-m-d'));
                                    $diff = $today->diff($bday);
                                    printf(
                                      '%d',
                                      $diff->y
                                    ); ?>
                                  </span>
                                </div>
                              </div>
                            </div>
                            <div class="d-flex justify-content-end">
                              <button type="button" onclick="return history.back()" class="btn btn-primary m-2">Go back</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
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

  <script src="../../assets/js/lib/calendar-2/moment.latest.min.js"></script>


  <script src="../../assets/js/lib/circle-progress/circle-progress.min.js"></script>
  <script src="../../assets/js/lib/circle-progress/circle-progress-init.js"></script>
  <script src="../../assets/js/lib/owl-carousel/owl.carousel.min.js"></script>
  <script src="../../assets/js/lib/owl-carousel/owl.carousel-init.js"></script>
  <!-- scripit init-->
</body>

</html>