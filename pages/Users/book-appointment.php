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
              <li class="breadcrumb-item"><a href="#"><i class="ti-calendar"></i></a></li>
              <li class="breadcrumb-item active">Book Appointment</li>
            </ol>
          </div>
        </div>
        <!-- /# row -->
        <section id="main-content">
          <div class="row d-flex justify-content-center">
            <div class="col-lg-8">
              <div class="card">
                <div class="card-body">
                  <form id="book-appointment" method="POST" enctype="multipart/form-data">
                    <input type="text" name="attyId" id="inputAttyId" value="<?= isset($_GET["attyId"]) ? $_GET["attyId"] : "" ?>" readonly hidden>
                    <div class="form-group d-flex justify-content-center">
                      <button type="button" onclick="return window.location.href = 'search-attorney.php'" class="btn btn-primary">
                        <?= isset($_GET["attyId"]) ? "Search for other Attorney" : "Search for available Attorney" ?>
                        <i class="fa fa-search"></i>
                      </button>
                    </div>
                    <?php
                    if (isset($_GET["attyId"])) :
                      $attorney = mysqli_fetch_object(
                        mysqli_query(
                          $con,
                          "SELECT id, fname, mname, lname FROM users WHERE id = '$_GET[attyId]'"
                        )
                      );
                    ?>
                      <div class="form-group row">
                        <label class="col-sm-2 col-form-label d-flex justify-content-end align-items-center">
                          Attorney:
                        </label>
                        <div class="col-lg-10">
                          <label class="col-form-label">
                            <?= ucwords("Atty. " . "$attorney->fname " . $attorney->mname[0] . ".
                                $attorney->lname") ?>
                          </label>
                        </div>
                      </div>
                    <?php endif; ?>
                    <div class="form-group">
                      <label>Request</label>
                      <textarea class="form-control" rows="5" required name="request"></textarea>
                    </div>

                    <div class="form-group">
                      <label>Schedule Date</label>
                      <input type="date" class="form-control" name="requestDate" required>
                    </div>

                    <div class="form-group">
                      <label>Schedule Time</label>
                      <input type="time" class="form-control" name="requestTime" required>
                    </div>

                    <div class="form-group d-flex justify-content-end mt-3">
                      <button type="submit" class="btn btn-success">Book</button>
                    </div>
                  </form>
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

  <script>
    $("#book-appointment").on("submit", function(e) {
      swal.showLoading();

      if ($("#inputAttyId").val() === "") {
        swal.close();
        swal.fire({
          title: 'Error!',
          text: "Please search for an Attorney",
          icon: 'error',
        })
      } else {
        $.ajax({
          url: '../../backend/nodes.php?action=bookAppointment',
          type: "POST",
          data: $(this).serialize(),
          success: function(data) {
            swal.close();
            const resp = JSON.parse(data);
            console.log(resp)
            if (resp.success) {
              swal.fire({
                title: 'Success!',
                text: 'Appointment booked and will review by the attorney.',
                icon: 'success',
              }).then(() => {
                window.location.href = "index.php#appointments"
              })
            } else {
              swal.fire({
                title: 'Error!',
                text: resp.message,
                icon: 'error',
              })
            }
          },
          error: function(data) {
            swal.fire({
              title: 'Oops...',
              text: 'Something went wrong.',
              icon: 'error',
            })
          }
        });
      }


      e.preventDefault();

    })
  </script>
</body>

</html>