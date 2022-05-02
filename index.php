<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Sign In</title>

  <!-- ================= Favicon ================== -->

  <!-- Styles -->
  <link href="assets/css/lib/font-awesome.min.css" rel="stylesheet">
  <link href="assets/css/lib/themify-icons.css" rel="stylesheet">
  <link href="assets/css/lib/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/lib/helper.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>

<body class="opass-body">

  <div class="unix-login">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-5">
          <div class="login-content" style="margin:0;">
            <div class="login-logo">
              <a href="index.php">
                <img src="5.png" style="height: 150px;width: 280px;">
              </a>
            </div>
            <div class="login-form">
              <h4>Sign in</h4>
              <form id="login-form" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                  <label>Username</label>
                  <input type="text" class="form-control" placeholder="Username" name="uname" required>
                </div>
                <div class="form-group">
                  <label>Password</label>
                  <input type="password" class="form-control" placeholder="Password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary m-b-30 m-t-30">Sign in</button>
                <div class="register-link m-t-15 text-center">
                  <p>Don't have account ? <a href="pages/sign-up.php"> Sign Up Here</a></p>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
<script src="assets/js/lib/jquery.min.js"></script>
<script src="assets/js/lib/bootstrap.min.js"></script>
<script src="assets/js/lib/sweetalert/sweetalert.min.js"></script>
<script src="assets/js/lib/toastr/toastr.min.js"></script>
<script src="assets/js/scripts.js"></script>

<script>
  $("#login-form").on("submit", function(e) {
    swal.showLoading();
    $.ajax({
      url: 'backend/login.php',
      data: $(this).serialize(),
      type: 'POST',
      success: function(data) {
        swal.close();
        const resp = JSON.parse(data)
        if (resp.success) {
          window.location.href = "otp.php"
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
  });
</script>

</html>