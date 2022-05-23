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
  <title>User Dashboard</title>

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
    .panel-heading h3 {
      font-size: 20px;
      color: white;
      letter-spacing: 0.025em;
      height: 60px;
      line-height: 38px;
      padding: 10px 15px;
      border-bottom: 1px solid transparent;
      border-top-left-radius: 3px;
      border-top-right-radius: 3px;
      background: rgba(0, 0, 0, 0.05);
    }

    .panel-body {
      padding: 15px;
    }
  </style>
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
              <li class="breadcrumb-item"><a href="#"><i class="ti-home"></i></a></li>
              <li class="breadcrumb-item active">Home</li>
            </ol>
          </div>
        </div>
        <!-- /# row -->
        <section id="main-content">
          <div class="row d-flex justify-content-center">
            <div class="col-md-10 ">
              <div class="card bg-primary" style="margin-top: 0;border-top: 0; padding: 0">
                <div class="panel-heading bg-primary">
                  <h3>WELCOME CLIENT!</h3>
                </div>
                <div class="panel-body ">
                  <p class="text-light">What's your plan for <strong> Today? </strong> </p>
                  <a href="book-appointment.php" class="text-light"><i class="fa fa-calendar"></i> Book Appointment</a><br>
                  <a href="inbox.php" class="text-light"><i class="fa fa-envelope"></i> Check Inbox</a>
                </div>
              </div>
            </div>
          </div>
          <!-- Header -->

          <div class="row d-flex justify-content-center">
            <div class="col-md-10 ">
              <div class="card" style="padding-top: 15px; padding-bottom: 15px">
                <h3 class="card-title">
                  Our Attorneys
                </h3>
                <div class="row d-flex justify-content-center">
                  <?php
                  $featuredQuery = mysqli_query(
                    $con,
                    "SELECT * FROM users u INNER JOIN specialization s on u.specialization_id = s.specialization_id WHERE `role`='atty'and year_exp < 9 LIMIT 4"
                  );
                  if (mysqli_num_rows($featuredQuery) > 0) :
                    while ($featuredAtty = mysqli_fetch_object($featuredQuery)) :
                  ?>
                      <div class="col-lg-6">
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
                <div class="d-flex justify-content-center">
                  <a href="attorneys.php" class="text-primary">
                    <i class="fa fa-ellipsis-h fa-2x"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <!-- Our Attorney -->

          <div class="row d-flex justify-content-center" id="appointments">
            <div class="col-md-10 ">
              <div class="card">
                <div class="card-body p-b-0">
                  <h3 class="card-title p-b-20">
                    Your Appointments
                  </h3>
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs customtab2" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" data-toggle="tab" href="#Pending" role="tab">
                        <span class="hidden-sm-up">
                          <i class="fa fa-hourglass text-muted"></i>
                        </span>
                        <span class="hidden-xs-down">
                          Pending
                        </span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" data-toggle="tab" href="#Canceled" role="tab">
                        <span class="hidden-sm-up">
                          <i class="fa fa-times-circle text-danger"></i>
                        </span>
                        <span class="hidden-xs-down">
                          Canceled
                        </span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" data-toggle="tab" href="#Accepted" role="tab">
                        <span class="hidden-sm-up">
                          <i class="fa fa-check-circle text-success"></i>
                        </span>
                        <span class="hidden-xs-down">
                          Accepted
                        </span>
                      </a>
                    </li>
                  </ul>
                  <!-- Tab panes -->
                  <div class="tab-content">
                    <div class="tab-pane active" id="Pending" role="tabpanel">
                      <table id="tablePending" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Request</th>
                            <th class="text-right">Request date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $appointmentQuery = mysqli_query(
                            $con,
                            "SELECT * FROM appointments WHERE `user_id`=$_SESSION[id] and `status`='pending'"
                          );
                          while ($appointment = mysqli_fetch_object($appointmentQuery)) :
                            $attorney = mysqli_fetch_object(
                              mysqli_query(
                                $con,
                                "SELECT * FROM users WHERE id=$appointment->attorney_id"
                              )
                            );
                          ?>
                            <tr>
                              <td>
                                <?= ucwords("Atty. " . "$attorney->fname " . $attorney->mname[0] . ". $attorney->lname") ?>
                              </td>
                              <td>
                                <?= $appointment->request ?>
                              </td>
                              <td class="text-right">
                                <?= date_format(
                                  date_create($appointment->date_created),
                                  "M d, Y h:i A"
                                ) ?>
                              </td>
                            </tr>
                          <?php endwhile; ?>

                        </tbody>
                      </table>
                    </div>
                    <!-- Tab 1 -->

                    <div class="tab-pane" id="Canceled" role="tabpanel">
                      <table id="tableCanceled" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Cancelation Reason</th>
                            <th class="text-right">Date canceled</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $appointmentCancelationQuery = mysqli_query(
                            $con,
                            "SELECT * FROM appointments WHERE `user_id`=$_SESSION[id] and `status`='canceled'"
                          );
                          while ($appointment = mysqli_fetch_object($appointmentCancelationQuery)) :
                            $attorney = mysqli_fetch_object(
                              mysqli_query(
                                $con,
                                "SELECT * FROM users WHERE id=$appointment->attorney_id"
                              )
                            );
                          ?>
                            <tr>
                              <td>
                                <?= ucwords("Atty. " . "$attorney->fname " . $attorney->mname[0] . ". $attorney->lname") ?>
                              </td>
                              <td>
                                <?= $appointment->cancelation_reason ?>
                              </td>
                              <td class="text-right">
                                <?= date_format(
                                  date_create($appointment->date_ended),
                                  "M d, Y h:i A"
                                ) ?>
                              </td>
                            </tr>
                          <?php endwhile; ?>

                        </tbody>
                      </table>
                    </div>
                    <!-- Tab 2 -->

                    <div class="tab-pane" id="Accepted" role="tabpanel">
                      <table id="tableAccept" class="table table-striped table-bordered">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Message</th>
                            <th class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $appointmentAcceptQuery = mysqli_query(
                            $con,
                            "SELECT * FROM appointments WHERE `user_id`=$_SESSION[id] and `status`='accepted'"
                          );
                          while ($appointment = mysqli_fetch_object($appointmentAcceptQuery)) :
                            $attorney = mysqli_fetch_object(
                              mysqli_query(
                                $con,
                                "SELECT * FROM users WHERE id=$appointment->attorney_id"
                              )
                            );
                          ?>
                            <tr>
                              <td>
                                <?= ucwords("Atty. " . "$attorney->fname " . $attorney->mname[0] . ". $attorney->lname") ?>
                              </td>
                              <td>
                                Your Appointment with the attorney was Approved!
                              </td>
                              <td class="d-flex justify-content-center">
                                <button class="btn btn-primary m-1" onclick="doneAppointment('<?= $appointment->appointment_id ?>', '<?= $appointment->attorney_id ?>')">
                                  Done
                                </button>
                                <button class="btn btn-warning m-1" onclick="return window.location.href='../Conversation/messages.php?user_id=<?= $appointment->user_id ?>'">
                                  Chat
                                </button>
                              </td>
                            </tr>
                          <?php endwhile; ?>

                        </tbody>
                      </table>
                    </div>
                    <!-- Tab 3 -->
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Appointments -->

          <div class="row d-flex justify-content-center" id="appointments">
            <div class="col-md-10 ">
              <div class="card">
                <div class="card-body p-b-0">
                  <h3 class="card-title p-b-20">
                    Done Appointments
                  </h3>
                  <table id="tableDone" class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Message</th>
                        <th>Case</th>
                        <th class="text-right">Date Ended</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $appointmentDoneQuery = mysqli_query(
                        $con,
                        "SELECT * FROM appointments WHERE `user_id`=$_SESSION[id] and `status`='done'"
                      );
                      while ($appointment = mysqli_fetch_object($appointmentDoneQuery)) :
                        $attorney = mysqli_fetch_object(
                          mysqli_query(
                            $con,
                            "SELECT * FROM users WHERE id=$appointment->attorney_id"
                          )
                        );
                      ?>
                        <tr>
                          <td>
                            <?= ucwords("Atty. " . "$attorney->fname " . $attorney->mname[0] . ". $attorney->lname") ?>
                          </td>
                          <td>
                            Your Appointment with the attorney Ended!
                          </td>
                          <td>
                            <?= $appointment->ended_remark ?>
                          </td>
                          <td class="text-right">
                            <?= date_format(
                              date_create($appointment->date_created),
                              "M d, Y h:i A"
                            ) ?>
                          </td>
                        </tr>
                      <?php endwhile; ?>
                    </tbody>
                  </table>
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
  function doneAppointment(appointmentId, notifyTo, endedBy) {
    swal.fire({
      title: 'Are you sure?',
      text: "You want to continue this?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes'
    }).then((result) => {
      if (result.isConfirmed) {
        swal.fire({
          title: "Please input case.",
          input: 'text',
          inputAttributes: {
            autocapitalize: 'off'
          },
          confirmButtonText: 'Submit',
          showLoaderOnConfirm: true,
          preConfirm: (remarks) => {
            return fetch(`../../backend/nodes.php?action=doneAppointment`, {
                method: "POST",
                headers: {
                  'Content-Type': 'application/json', // sent request
                  'Accept': 'application/json' // expected data sent back
                },
                body: JSON.stringify({
                  notifyTo: notifyTo,
                  inputRemark: remarks,
                  appointment_id: appointment_id
                })
              })
              .then(response => {
                if (!response.ok) {
                  throw new Error(response.statusText)
                }
                return response.json();
              })
              .catch(error => {
                Swal.showValidationMessage(
                  `Request failed: ${error}`
                )
              })
          },
          allowOutsideClick: () => !Swal.isLoading()
        }).then((resp) => {
          if (resp.value.success) {
            swal.fire({
              title: 'Success!',
              text: resp.value.message,
              icon: 'success',
            }).then(() => {
              location.reload();
            })
          } else {
            swal.fire({
              title: 'Error!',
              text: resp.value.message,
              icon: 'error',
            })
          }
        })
      }
    })
  }

  $('#tablePending').DataTable({
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": false,
    "bAutoWidth": false,
    searching: false,
    paging: false,
    info: false
  });
  $('#tableCanceled').DataTable({
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": false,
    "bAutoWidth": false,
    searching: false,
    paging: false,
    info: false
  });
  $('#tableAccept').DataTable({
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": false,
    "bAutoWidth": false,
    searching: false,
    paging: false,
    info: false
  });
  $('#tableDone').DataTable({
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": false,
    "bAutoWidth": false,
    searching: false,
    paging: false,
    info: false
  });
</script>

</html>