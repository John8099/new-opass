<?php
session_start();
include_once("../../backend/conn.php");
if (!isset($_SESSION["id"])) {
  header("location: ../../index.php");
}
$user = mysqli_fetch_object(
  mysqli_query(
    $con,
    "SELECT * FROM users u INNER JOIN specialization s ON u.specialization_id = s.specialization_id WHERE id = $_SESSION[id]"
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
  <title>Appointments</title>

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
              <li class="breadcrumb-item"><a href="#"><i class="ti-calendar"></i></a></li>
              <li class="breadcrumb-item active">Appointments</li>
            </ol>
          </div>
        </div>
        <!-- /# row -->
        <section id="main-content">
          <div class="card">
            <div class="card-body">
              <table id="tableAppointments" class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Request</th>
                    <th class="col-sm-2">Appointment Date and Time</th>
                    <th class="col-sm-1">Status</th>
                    <th class="col-sm-3 text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $appointmentQuery = mysqli_query(
                    $con,
                    "SELECT * FROM appointments WHERE `attorney_id`=$_SESSION[id] and `status` != 'done'"
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
                        <?= date_format(
                          date_create("$appointment->appointment_date $appointment->appointment_time"),
                          "M d, Y h:i A"
                        ) ?>
                      </td>
                      <td class="bg-<?php
                                    if ($appointment->status == "accepted") {
                                      echo "success";
                                    } else if ($appointment->status == "canceled") {
                                      echo "danger";
                                    } else {
                                      echo "warning";
                                    }
                                    ?> text-center">
                        <?= $appointment->status ?>
                      </td>
                      <td class="d-flex justify-content-center">
                        <?php
                        if ($appointment->status == "accepted") :
                        ?>
                          <button onclick="return window.location.href='../Conversation/messages.php?user_id=<?= $appointment->user_id ?>'" type="button" class="btn btn-warning m-1">
                            Chat
                          </button>
                          <button type="button" class="btn btn-primary m-1" onclick="handleBtnClick(<?= $appointment->appointment_id ?>,'<?= $appointment->user_id ?>', 'doneAppointment')">
                            End Appointment
                          </button>
                        <?php
                        elseif ($appointment->status == "canceled") :
                        ?>
                          <button type="button" class="btn btn-danger" onclick="handleBtnClick(<?= $appointment->appointment_id ?>, '<?= $appointment->user_id ?>', 'deleteAppointment')">
                            <i class="fa fa-trash"></i>
                          </button>
                        <?php
                        else :
                        ?>
                          <button type="button" class="btn btn-primary m-1" onclick="handleBtnClick(<?= $appointment->appointment_id ?>, '<?= $appointment->user_id ?>', 'acceptAppointment')">
                            Accept
                          </button>
                          <button type="button" class="btn btn-danger m-1" onclick="handleBtnClick(<?= $appointment->appointment_id ?>, '<?= $appointment->user_id ?>', 'cancelAppointment')">
                            Cancel
                          </button>
                        <?php
                        endif;
                        ?>
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
  $('#tableAppointments').DataTable();

  function handleBtnClick(appointment_id, notifyTo, type) {
    if (type == "cancelAppointment" || type == "doneAppointment") {
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
            title: type == "cancelAppointment" ? "Please input cancelation reason." : "Please input case.",
            input: 'text',
            inputAttributes: {
              autocapitalize: 'off'
            },
            confirmButtonText: 'Submit',
            showLoaderOnConfirm: true,
            preConfirm: (remarks) => {
              return fetch(`../../backend/nodes.php?action=${type == "cancelAppointment" ? "cancelAppointment" : "doneAppointment"}`, {
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
    } else {
      swal.showLoading();
      $.ajax({
        url: `../../backend/nodes.php?action=${type}`,
        type: "POST",
        data: {
          notifyTo: notifyTo,
          appointment_id: appointment_id
        },
        success: function(data) {
          swal.close();
          const resp = JSON.parse(data);
          if (resp.success) {
            swal.fire({
              title: 'Success!',
              text: resp.message,
              icon: 'success',
            }).then(() => {
              location.reload();
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


  }
</script>

</html>