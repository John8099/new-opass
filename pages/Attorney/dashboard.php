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
  <title>Dashboard</title>

  <!-- Styles -->
  <link href="../../assets/css/lib/font-awesome.min.css" rel="stylesheet">
  <link href="../../assets/css/lib/themify-icons.css" rel="stylesheet">
  <link href="../../assets/css/lib/owl.carousel.min.css" rel="stylesheet" />
  <link href="../../assets/css/lib/owl.theme.default.min.css" rel="stylesheet" />
  <link href="../../assets/css/lib/menubar/sidebar.css" rel="stylesheet">
  <link href="../../assets/css/lib/bootstrap.min.css" rel="stylesheet">
  <link href="../../assets/css/lib/helper.css" rel="stylesheet">
  <link href="../../assets/css/lib/calendar/fullcalendar.min.css" rel="stylesheet">
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
          <div class="row">

            <div class="col-sm-3">
              <div class="card p-0">
                <div class="stat-widget-three">
                  <div class="stat-icon bg-primary">
                    <i class="fa fa-xl fa-check-square-o"></i>
                  </div>
                  <div class="stat-content">
                    <div class="stat-digit">
                      <?=
                      mysqli_num_rows(
                        mysqli_query(
                          $con,
                          "SELECT `status` FROM appointments WHERE `status`='done'"
                        )
                      )
                      ?>
                    </div>
                    <div class="stat-text">Done Appointments</div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-3">
              <div class="card p-0">
                <div class="stat-widget-three">
                  <div class="stat-icon bg-success">
                    <i class="fa fa-xl fa-calendar-check-o"></i>
                  </div>
                  <div class="stat-content">
                    <div class="stat-digit">
                      <?=
                      mysqli_num_rows(
                        mysqli_query(
                          $con,
                          "SELECT `status` FROM appointments WHERE `status`='accepted'"
                        )
                      )
                      ?>
                    </div>
                    <div class="stat-text">Accepted Appointments</div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-3">
              <div class="card p-0">
                <div class="stat-widget-three">
                  <div class="stat-icon bg-warning">
                    <i class="fa fa-xl fa-tasks"></i>
                  </div>
                  <div class="stat-content">
                    <div class="stat-digit">
                      <?=
                      mysqli_num_rows(
                        mysqli_query(
                          $con,
                          "SELECT `status` FROM appointments WHERE `status`='pending'"
                        )
                      )
                      ?>
                    </div>
                    <div class="stat-text">Pending Appointments</div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-sm-3">
              <div class="card p-0">
                <div class="stat-widget-three">
                  <div class="stat-icon bg-danger">
                    <i class="fa fa-xl fa-calendar-times-o"></i>
                  </div>
                  <div class="stat-content">
                    <div class="stat-digit">
                      <?=
                      mysqli_num_rows(
                        mysqli_query(
                          $con,
                          "SELECT `status` FROM appointments WHERE `status`='canceled'"
                        )
                      )
                      ?>
                    </div>
                    <div class="stat-text">Canceled Appointments</div>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <div class="row mt-2 d-flex justify-content-center">
            <div class="col-sm-12">
              <div class="card">
                <div class="card-title">
                  <h4>My Appointments</h4>
                </div>
                <div class="card-body">
                  <div id='calendar'></div>
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-2 d-flex justify-content-center">
            <div class="col-sm-10">
              <div class="card">
                <div class="card-title">
                  <h4>To-do</h4>
                </div>
                <div class="card-body">
                  <div class="todo-list">
                    <div class="tdl-holder">
                      <div class="tdl-content">
                        <ul id="divTodo"></ul>
                      </div>
                      <input type="text" class="tdl-new form-control" placeholder="Write new item and hit 'Enter'..." />
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
  <script src="../../assets/js/lib/moment/moment.min.js"></script>
  <script src="../../assets/js/lib/calendar/fullcalendar.min.js"></script>
</body>

<script>
  $.get("../../backend/nodes.php?action=getTodoList", function(data) {
    $("#divTodo").html(data)
  });

  function checkItem(todoId, checkedStatus) {
    $.ajax({
      url: '../../backend/nodes.php?action=handleCheckUncheckTodo',
      data: {
        status: checkedStatus,
        todo_id: todoId
      },
      type: 'POST',
    });
  }

  function deleteTodo(todoId, checkedStatus) {
    $.ajax({
      url: '../../backend/nodes.php?action=deleteTodo',
      data: {
        todo_id: todoId
      },
      type: 'POST',
    });
  }

  setInterval(function() {
    $('#calendar').fullCalendar({
      themeSystem: 'bootstrap4',
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay,listMonth'
      },
      weekNumbers: true,
      eventLimit: false, // allow "more" link when too many events
      events: '../../backend/nodes.php?action=getAppointmentList'
    });
  }, 1000);
</script>

</html>