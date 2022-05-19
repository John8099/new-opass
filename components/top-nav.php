<div class="header">
  <label id="test"></label>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="float-left">
          <div class="hamburger sidebar-toggle">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
          </div>
        </div>
        <div class="float-right">

          <div class="dropdown dib" id="notification">
            <div class="header-icon" data-toggle="dropdown">
              <i class="ti-bell mr-2" style="font-size: 30px;" id="notificationIcon">
                <span class="position-absolute top-0 badge bg-danger" id="notificationBadge"></span>
              </i>

              <div class="drop-down dropdown-menu dropdown-menu-right">
                <div class="dropdown-content-heading">
                  <span class="text-left">Recent Notifications</span>
                </div>
                <div class="dropdown-content-body">
                  <ul id="notificationData"></ul>
                  <ul>
                    <li class="text-center" onclick="return window.location.href='../notifications/notifications.php'">
                      <a href="#" class="more-link">See All</a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
          <!-- Notifications -->

          <div class="dropdown dib">
            <div class="header-icon" data-toggle="dropdown">
              <span class="user-avatar">
                <img class="rounded-circle border " src="../../profile-photo/<?= $user->profile == null ? 'default.png' : $user->profile ?>" style="width: 40px; height: 40px;">
                <i class="ti-angle-down f-s-10"></i>
              </span>
              <div class="drop-down dropdown-profile dropdown-menu dropdown-menu-right" style="margin: 0; padding: 0;border-radius: 10px;">
                <div class="dropdown-content-heading" style="text-align: center;background-color: #343957;border-top-left-radius: 10px;border-top-right-radius: 10px;">
                  <span style="font-size: 15px;color:white;font-weight: 700;">
                    <?php
                    $name = ucwords("$user->fname " . $user->mname[0] . ". $user->lname");
                    echo $user->role == "atty" ? "Atty. $name" : $name;
                    ?>
                  </span>
                </div>
                <div class="dropdown-content-body">
                  <ul>
                    <?php
                    $userFolder = $role == "user" ? "../Users/" : "../Attorney/";
                    $dir = $self == "notifications.php" || $folder == "Conversation" ? $userFolder : "";
                    ?>
                    <li onclick="return window.location.href = '<?= $dir ?>profile.php?id=<?= $user->id ?>'">
                      <a>
                        <i class="ti-user"></i>
                        <span>Profile</span>
                      </a>
                    </li>

                    <li onclick="return window.location.href = '../Conversation'">
                      <a>
                        <i class="ti-email"></i>
                        <span>Inbox</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>