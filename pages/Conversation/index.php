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
  <title>Messages</title>

  <!-- Styles -->
  <link href="../../assets/css/lib/font-awesome.min.css" rel="stylesheet">
  <link href="../../assets/css/lib/themify-icons.css" rel="stylesheet">
  <link href="../../assets/css/lib/owl.carousel.min.css" rel="stylesheet" />
  <link href="../../assets/css/lib/owl.theme.default.min.css" rel="stylesheet" />
  <link href="../../assets/css/lib/menubar/sidebar.css" rel="stylesheet">
  <link href="../../assets/css/lib/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/css/lib/helper.css" rel="stylesheet">
  <link href="../../assets/css/style.css" rel="stylesheet">
  <style>
    .active-message {
      background: #eef5f9;
    }

    .messageItem {
      cursor: pointer;
    }

    .btn-delete {
      z-index: 999;
    }
  </style>
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
              <li class="breadcrumb-item"><i class="ti-email"></i></li>
              <li class="breadcrumb-item active">Messages</li>
            </ol>
          </div>
        </div>
        <!-- /# row -->
        <section id="main-content">
          <div class="row d-flex justify-content-center">
            <div class="col-sm-8">
              <div class="card">
                <div class="card-title">
                  Conversations List
                </div>

                <div class="card-body">
                  <div class="row mt-4">
                    <div class="col">
                      <ul id="divConvoData"></ul>
                      <input type="text" id="senderType" value="<?= $user->role == 'user' ? 'user' : 'attorney' ?>" readonly hidden>
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

  <script src="../../assets/js/lib/circle-progress/circle-progress.min.js"></script>
  <script src="../../assets/js/lib/circle-progress/circle-progress-init.js"></script>
  <script src="../../assets/js/lib/owl-carousel/owl.carousel.min.js"></script>
  <script src="../../assets/js/lib/owl-carousel/owl.carousel-init.js"></script>
  <!-- scripit init-->

</body>
<script>
  // setInterval(function() {
    $.get(`../../backend/nodes.php?action=getConvo&&senderType=${$("#senderType").val()}`, function(data) {
      $("#divConvoData").html(data)
    });
  // }, 1000)

  function handleMessageItemClick(userId, senderType) {
    window.location.href = `messages.php?user_id=${userId}`
  }
</script>

</html>