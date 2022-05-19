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
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Messages</title>

  <!-- Styles -->
  <link href="../../assets/css/lib/font-awesome.min.css" rel="stylesheet" />
  <link href="../../assets/css/lib/themify-icons.css" rel="stylesheet" />
  <link href="../../assets/css/lib/owl.carousel.min.css" rel="stylesheet" />
  <link href="../../assets/css/lib/owl.theme.default.min.css" rel="stylesheet" />
  <link href="../../assets/css/lib/menubar/sidebar.css" rel="stylesheet" />
  <link href="../../assets/css/lib/bootstrap.min.css" rel="stylesheet" />
  <link href="../../assets/css/lib/helper.css" rel="stylesheet" />
  <link href="../../assets/css/style.css" rel="stylesheet" />
  <link href="../../assets/css/lib/toastr/toastr.min.css" rel="stylesheet">
  <link href="../../assets/css/lib/toggle/bootstrap4-toggle.min.css" rel="stylesheet" />
  <link href="../../assets/css/chat-style.css" rel="stylesheet" />
  <style>
    .toggle.ios,
    .toggle-on.ios,
    .toggle-off.ios {
      border-radius: 20rem;
    }

    .toggle.ios .toggle-handle {
      border-radius: 20rem;
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
        <!-- /# row -->
        <section id="main-content">
          <div class="row d-flex justify-content-center">
            <div class="col-sm-8">
              <div class="card p-0">
                <div class="row card-title d-flex align-items-center border-bottom p-3">
                  <div class="col-sm-10">
                    <?php
                    $user_details = mysqli_fetch_object(
                      mysqli_query(
                        $con,
                        "SELECT * FROM users WHERE id=$_GET[user_id]"
                      )
                    );
                    $profile = $user_details->profile == null ? 'default.png' : $user_details->profile;
                    $profileDir = "../../profile-photo/$profile";
                    ?>
                    <a href="#" onclick="return history.back()" class="pull-left mr-4 h3">
                      <i class="fa fa-arrow-left "></i>
                    </a>
                    <img class="pull-left mr-4 avatar-img" style="width: 50px; height: 50px" src="<?= $profileDir ?>" />
                    <p class="h6">
                      <?= ucwords($user_details->fname[0] . ". " . $user_details->lname) ?>
                    </p>
                  </div>
                  <div class="col-sm-2">
                    <div class='dropdown pull-right'>
                      <button class='btn' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false' style='background-color: transparent; border-radius: 50px;'>
                        <i class='fa fa-ellipsis-v' style="font-size: 20px;"></i>
                      </button>
                      <div class='dropdown-menu p-2' aria-labelledby='dropdownMenuButton'>
                        <h6 style="text-align: center;">Realtime message</h6>
                        <div class="d-flex justify-content-center">
                          <input type="checkbox" checked data-toggle="toggle" data-on="Enabled" data-off="Disabled" data-onstyle="success" data-offstyle="danger" id="switch" data-width="100">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card-body">
                  <div class="chat-box" id="chatBox"></div>

                  <form id="send-message" role="form" method="POST" enctype="multipart/form-data">

                    <div class="collapse" id="inputFiles">
                      <div class="col-lg-12">
                        <div class="control-group" id="fields">
                          <div class="controls">
                            <div class="entry input-group upload-input-group mb-2">
                              <input class="form-control" name="files[]" type="file">
                              <button class="btn btn-upload btn-success btn-add ml-2" type="button">
                                <i class="fa fa-plus"></i>
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row typing-area">
                      <input type="text" class="incoming_id" name="incoming_id" value="<?= $_GET['user_id']; ?>" hidden>
                      <div class="col">
                        <textarea id="inputMessage" name="message" placeholder="Type a message here..." rows="5"></textarea>
                      </div>
                      <div class="col-sm-2">
                        <button class="btn btn-primary m-1" type="button" data-toggle="collapse" data-target="#inputFiles" aria-expanded="false" aria-controls="inputFiles">
                          <i class="fa fa-paperclip"></i>
                        </button>
                        <button type="submit" class="btn btn-primary m-1">
                          <i class="fa fa-paper-plane-o"></i>
                        </button>
                      </div>
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
  <script src="../../assets/js/lib/toastr/toastr.min.js"></script>
  <script src="../../assets/js/scripts.js"></script>
  <!-- bootstrap -->

  <script src="../../assets/js/lib/circle-progress/circle-progress.min.js"></script>
  <script src="../../assets/js/lib/circle-progress/circle-progress-init.js"></script>
  <script src="../../assets/js/lib/owl-carousel/owl.carousel.min.js"></script>
  <script src="../../assets/js/lib/owl-carousel/owl.carousel-init.js"></script>
  <script src="../../assets/js/lib/toggle/bootstrap4-toggle.min.js"></script>
  <!-- scripit init-->

</body>
<script>
  toastr.options = {
    "closeButton": true,
    "debug": false,
    "newestOnTop": true,
    "progressBar": false,
    "positionClass": "toast-top-center",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  }
  const messageDuration = 1000;
  let realtimeMessage = setInterval(function() {
    $.get(`../../backend/nodes.php?action=getChat&&incoming=${$(".incoming_id").val()}`, function(data) {
      $("#chatBox").html(data)
      $("#chatBox").scrollTop($("#chatBox")[0].scrollHeight);
    });
  }, messageDuration)

  $('#switch').change(function(e) {
    if (e.target.checked) {
      toastr["success"]("Realtime message enabled.")
      realtimeMessage = setInterval(function() {
        $.get(`../../backend/nodes.php?action=getChat&&incoming=${$(".incoming_id").val()}`, function(data) {
          $("#chatBox").html(data)
          $("#chatBox").scrollTop($("#chatBox")[0].scrollHeight);
        });
      }, messageDuration)
    } else {
      toastr["error"]("Realtime message disabled.")
      clearTimeout(realtimeMessage)
    }
  })

  function scrollSmoothToBottom(id) {
    var div = document.getElementById(id);
    $('#' + id).animate({
      scrollTop: div.scrollHeight - div.clientHeight
    }, 500);
  }

  $('#inputFiles').on('hidden.bs.collapse', function(e) {
    $('input[type=file]').val("")
  })

  $("#send-message").on("submit", function(e) {
    const fileValue = $('input[type=file]').val();
    const messageValue = $('#inputMessage').val();

    if (fileValue == "" && messageValue == "") {
      toastr["error"]("Please send with file or message.")
    } else {
      $.ajax({
        url: '../../backend/nodes.php?action=insertMessage',
        type: "POST",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
          const resp = JSON.parse(data);
          if (!resp.success) {
            toastr["error"](resp.message)
          }
          $('#inputFiles').collapse('hide')
          $("#send-message")[0].reset();
        }
      });
    }
    e.preventDefault();
  })


  $(document).on('click', '.btn-add', function(e) {
    e.preventDefault();

    var controlForm = $('.controls:first'),
      currentEntry = $(this).parents('.entry:first'),
      newEntry = $(currentEntry.clone()).appendTo(controlForm);

    newEntry.find('input').val('');
    controlForm.find('.entry:not(:last) .btn-add')
      .removeClass('btn-add').addClass('btn-remove')
      .removeClass('btn-success').addClass('btn-danger')
      .html('<span class="fa fa-trash"></span>');
  }).on('click', '.btn-remove', function(e) {
    $(this).parents('.entry:first').remove();

    e.preventDefault();
    return false;
  });
</script>

</html>