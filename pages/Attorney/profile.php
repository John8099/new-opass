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
} ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Profile</title>

  <!-- Styles -->
  <link href="../../assets/css/lib/font-awesome.min.css" rel="stylesheet" />
  <link href="../../assets/css/lib/themify-icons.css" rel="stylesheet" />
  <link href="../../assets/css/lib/menubar/sidebar.css" rel="stylesheet" />
  <link href="../../assets/css/lib/bootstrap.min.css" rel="stylesheet" />
  <link href="../../assets/css/lib/toastr/toastr.min.css" rel="stylesheet" />
  <link href="../../assets/css/style.css" rel="stylesheet" />
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
              <li class="breadcrumb-item">
                <a href="#"><i class="ti-user"></i></a>
              </li>
              <li class="breadcrumb-item active">Profile</li>
            </ol>
          </div>
        </div>
        <!-- /# row -->
        <section id="main-content">
          <div class="row">
            <div class="col-lg-12">
              <div class="card">
                <div class="card-body">
                  <div class="user-profile">
                    <div class="row">
                      <div class="col-lg-4">
                        <div class="user-photo m-b-30">
                          <img class="img-fluid rounded border" src="../../<?= $user->profile
                                                                              == null ? "assets/images/user-profile.jpg" :
                                                                              "profile-photo/$user->profile" ?>" style="width:200px;height: 200px;" />
                        </div>
                      </div>
                      <div class="col-lg-8">
                        <div class="user-profile-name">
                          <?= ucwords("$user->fname " . $user->mname[0] . ".
                            $user->lname") ?>
                        </div>
                        <a href="#edit-profile" data-toggle="modal" style="
                              color: #007bff;
                              float: right;
                              font-size: 15px;
                            ">
                          <i class="ti-pencil-alt"></i>
                        </a>

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
                                    <?= $user->contact ?>
                                  </span>
                                </div>
                                <div class="address-content">
                                  <span class="contact-title">
                                    Address:
                                  </span>
                                  <span class="mail-address">
                                    <?= $user->address ?>
                                  </span>
                                </div>
                                <div class="email-content">
                                  <span class="contact-title"> Email: </span>
                                  <span class="contact-email">
                                    <?= $user->email ?>
                                  </span>
                                </div>
                              </div>
                              <div class="basic-information">
                                <h4>Basic information</h4>
                                <div class="birthday-content">
                                  <span class="contact-title">Birthday:</span>
                                  <span class="birth-date">
                                    <?= date_format(
                                      date_create($user->birthday),
                                      "M d, Y"
                                    ) ?>
                                  </span>
                                </div>
                                <div class="gender-content">
                                  <span class="contact-title">Age:</span>
                                  <span class="gender">
                                    <?php
                                    date_default_timezone_set("Asia/Manila");
                                    $bday = new DateTime($user->birthday);
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
                              <a href="#update-password" data-toggle="modal" class="btn btn-primary">Update Password</a>
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

  <div class="modal fade mt-3" id="edit-profile" tabindex="-1" role="dialog" aria-labelledby="editProfileLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="editProfileLabel">
            <strong>Edit Profile </strong>
          </h4>
        </div>
        <form id="update-user" role="form" method="POST" enctype="multipart/form-data">
          <input type="text" value="<?= $user->id ?>" name="id" readonly hidden>
          <div class="modal-body">
            <div class="form-group">
              <img class="img-fluid rounded border mx-auto d-block" src="../../<?= $user->profile == null ?
                                                                                  "assets/images/user-profile.jpg" :
                                                                                  "profile-photo/$user->profile" ?>" style="width:200px;height: 200px;" id="previewImg" />
            </div>

            <div style="display: flex; justify-content: center;" id="divClear">
              <button type="button" class="btn btn-danger" onclick="clearImg()">
                Clear
              </button>
            </div>

            <div class="input-group m-3" id="divInputFile">
              <div class="custom-file">
                <input type="file" class="custom-file-input" name="profile" accept="image/*" onchange="previewFile(this)" name="profile">
                <label class="custom-file-label" id="fileTxt">Choose file</label>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group" style="margin-bottom: 10px">
                  <label class="control-label">First name</label>
                  <input class="form-control" type="text" name="fname" value="<?= $user->fname ?>" required />
                </div>

                <div class="form-group" style="margin-bottom: 10px">
                  <label class="control-label">Middle name</label>
                  <input class="form-control" type="text" name="mname" value="<?= $user->mname ?>" required />
                </div>

                <div class="form-group" style="margin-bottom: 10px">
                  <label class="control-label">Last name</label>
                  <input class="form-control" type="text" name="lname" value="<?= $user->lname ?>" required />
                </div>

                <div class="form-group" id="contactDiv">
                  <label class="control-label">
                    Contact number
                    <small style="text-transform: lowercase">(eg. 09xxxxxxxxx)</small>
                  </label>
                  <input class="form-control" type="text" name="contactNumber" value="<?= $user->contact ?>" id="contact" required />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group" style="margin-bottom: 10px">
                  <label class="control-label">Address</label>
                  <input class="form-control" type="text" name="address" value="<?= $user->address ?>" required />
                </div>

                <div class="form-group" style="margin-bottom: 10px">
                  <label class="control-label">Birthday</label>
                  <input class="form-control" type="date" name="bday" value="<?= $user->birthday ?>" required />
                </div>

                <div class="form-group" style="margin-bottom: 10px" id="unameDiv">
                  <label class="control-label">Username</label>
                  <input class="form-control" type="text" name="uname" id="userName" value="<?= $user->uname ?>" required />
                </div>

                <div class="form-group" id="emailDiv">
                  <label class="control-label">Email</label>
                  <input class="form-control" type="text" name="email" id="email" value="<?= $user->email ?>" required />
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">
              Close
            </button>
            <button type="submit" class="btn btn-warning" id="btnUpdate">
              Update
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Edit Profile modal -->

  <div class="modal fade" id="update-password" tabindex="-1" role="dialog" aria-labelledby="updatePasswordLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="updatePasswordLabel">
            <strong>Update Password </strong>
          </h4>
        </div>
        <form id="form-update-password" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="form-group" id="newPassword">
              <label class="control-label">Old Password</label>
              <input type="password" class="form-control" id="inputOldPass" required />
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="control-label">New Password</label>
                  <input class="form-control form-white" type="password" name="newPass" id="inputNewPass" />
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group" id="divRepeat">
                  <label class="control-label">Repeat New Password</label>
                  <input class="form-control form-white" type="password" id="inputRepeatNewPass" />
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">
              Close
            </button>
            <button type="submit" class="btn btn-success" disabled id="btnSave">
              Save
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Update Password modal -->

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
  <script src="../../assets/js/lib/sweetalert/sweetalert.min.js"></script>
  <script src="../../assets/js/lib/toastr/toastr.min.js"></script>
</body>

<script>
  const contact = $("#contact")
  const userName = $("#userName")
  const email = $("#email")
  const btnUpdate = $("#btnUpdate")

  const btnSave = $("#btnSave")
  const inputNewPass = $("#inputNewPass");
  const inputRepeatNewPass = $("#inputRepeatNewPass");

  const divInputFile = $("#divInputFile")
  const divClear = $("#divClear")

  divClear.hide()

  function clearImg() {
    $("input[type=file]").val("")
    $("#previewImg").attr("src", "../../<?= $user->profile == null ? "assets/images/user-profile.jpg" : "profile-photo/$user->profile" ?>");
    $("#fileTxt").html("Choose file")
    divClear.hide()
  }

  function previewFile(input) {
    var file = $("input[type=file]").get(0).files[0];

    if (file) {
      var reader = new FileReader();

      reader.onload = function() {
        $("#previewImg").attr("src", reader.result);
      }

      reader.readAsDataURL(file);
      $("#fileTxt").html(file.name)
      divClear.show()
    }
  }

  const isEmail = (email) => {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  }

  contact.on("blur", (e) => {
    const contactVal = e.target.value;
    if ((/(\+?\d{2}?\s?\d{3}\s?\d{3}\s?\d{4})|([0]\d{3}\s?\d{3}\s?\d{4})/g).test(contactVal)) {
      $.ajax({
        url: '../../backend/nodes.php?action=contactExist',
        data: {
          isMyInfo: true,
          contact: contactVal
        },
        type: 'POST',
        success: function(data) {
          const resp = JSON.parse(data);
          if (resp) {
            toastr["error"]("Contact number already exist.")
            btnUpdate.prop('disabled', true);
            $("#contactDiv").addClass("has-error")
          } else {
            btnUpdate.prop('disabled', false);
            $("#contactDiv").removeClass("has-error")
          }
        },
      });
    } else {
      if (contactVal === "") {
        btnUpdate.prop('disabled', false);
        $("#contactDiv").removeClass("has-error")
      } else {
        toastr["error"]("Error contact number format.")
        btnUpdate.prop('disabled', true);
        $("#contactDiv").addClass("has-error")
      }
    }
  })

  userName.on("blur", (e) => {
    $.ajax({
      url: '../../backend/nodes.php?action=userNameExist',
      data: {
        isMyInfo: true,
        userName: e.target.value
      },
      type: 'POST',
      success: function(data) {
        const resp = JSON.parse(data);
        if (resp) {
          toastr["error"]("User name already exist.")
          btnUpdate.prop('disabled', true);
          $("#unameDiv").addClass("has-error")
        } else {
          btnUpdate.prop('disabled', false);
          $("#unameDiv").removeClass("has-error")
        }
      },

    });
  })

  email.on("blur", (e) => {
    const emailVal = e.target.value
    if (isEmail(emailVal)) {
      $.ajax({
        url: '../../backend/nodes.php?action=emailExist',
        data: {
          isMyInfo: true,
          email: emailVal
        },
        type: 'POST',
        success: function(data) {
          const resp = JSON.parse(data);
          if (resp) {
            toastr["error"]("Email Address already exist.")
            btnUpdate.prop('disabled', true);
            $("#emailDiv").addClass("has-error")
          } else {
            btnUpdate.prop('disabled', false);
            $("#emailDiv").removeClass("has-error")
          }
        },
      });
    } else {
      if (emailVal === "") {
        btnUpdate.prop('disabled', false);
        $("#emailDiv").removeClass("has-error")
      } else {
        toastr["error"]("Error Email.")
        btnUpdate.prop('disabled', true);
        $("#emailDiv").addClass("has-error")
      }
    }
  })

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

  const resetForms = () => {
    $('#form-update-password')[0].reset();
    $('#update-user')[0].reset();
  }

  $("#inputOldPass").on("blur", (e) => {
    $.ajax({
      url: '../../backend/nodes.php?action=checkPassword',
      data: {
        user_id: <?= $user->id ?>,
        password: e.target.value
      },
      type: 'POST',
      success: function(data) {
        const resp = JSON.parse(data);
        if (resp.success) {
          btnSave.prop('disabled', false);
          $("#newPassword").removeClass("has-error")
        } else {
          toastr["error"](resp.message)
          btnSave.prop('disabled', true);
          $("#newPassword").addClass("has-error")
        }
      },
    });
  })

  inputRepeatNewPass.on("input", (e) => {
    const passwordVal = inputNewPass.val();
    if (passwordVal !== e.target.value) {
      btnSave.prop('disabled', true);
      $("#divRepeat").addClass("has-error")
    } else {
      btnSave.prop('disabled', false);
      $("#divRepeat").removeClass("has-error")
    }
  });

  $("#form-update-password").on("submit", (e) => {
    swal.showLoading();
    $.ajax({
      url: '../../backend/nodes.php?action=updatePassword',
      data: {
        user_id: <?= $user->id ?>,
        password: inputNewPass.val()
      },
      type: 'POST',
      success: function(data) {
        swal.close();
        const resp = JSON.parse(data);
        if (resp.success) {
          swal.fire({
            title: 'Success!',
            text: 'Password updated successfully!',
            icon: 'success',
          }).then(() => {
            resetForms();
            $('#update-password').modal('toggle');
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
    e.preventDefault();
  })

  $("#update-user").on("submit", function(e) {
    swal.showLoading();

    $.ajax({
      url: '../../backend/nodes.php?action=updateUserProfile',
      type: "POST",
      data: new FormData(this),
      contentType: false,
      cache: false,
      processData: false,
      success: function(data) {
        swal.close();
        const resp = JSON.parse(data);
        console.log(resp)
        if (resp.success) {
          swal.fire({
            title: 'Success!',
            text: 'Profile updated successfully!',
            icon: 'success',
          }).then(() => {
            resetForms();
            $('#edit-profile').modal('toggle');
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
    e.preventDefault();

  })
</script>

</html>