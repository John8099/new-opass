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

          <div class="dropdown dib">
            <div class="header-icon" data-toggle="dropdown">
              <i class="ti-bell" style="font-size: 30px;"></i>
              <div class="drop-down dropdown-menu dropdown-menu-right">
                <div class="dropdown-content-heading">
                  <span class="text-left">Recent Notifications</span>
                </div>
                <div class="dropdown-content-body">
                  <ul>
                    <li>
                      <a href="#">
                        <img class="pull-left m-r-10 avatar-img" src="../../assets/images/avatar/3.jpg" alt="" />
                        <div class="notification-content">
                          <small class="notification-timestamp pull-right">02:34
                            PM</small>
                          <div class="notification-heading">Mr. John</div>
                          <div class="notification-text">5 members joined today </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <img class="pull-left m-r-10 avatar-img" src="../../assets/images/avatar/3.jpg" alt="" />
                        <div class="notification-content">
                          <small class="notification-timestamp pull-right">02:34
                            PM</small>
                          <div class="notification-heading">Mariam</div>
                          <div class="notification-text">likes a photo of you</div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <img class="pull-left m-r-10 avatar-img" src="../../assets/images/avatar/3.jpg" alt="" />
                        <div class="notification-content">
                          <small class="notification-timestamp pull-right">02:34
                            PM</small>
                          <div class="notification-heading">Tasnim</div>
                          <div class="notification-text">Hi Teddy, Just wanted to let you
                            ...</div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <a href="#">
                        <img class="pull-left m-r-10 avatar-img" src="../../assets/images/avatar/3.jpg" alt="" />
                        <div class="notification-content">
                          <small class="notification-timestamp pull-right">02:34
                            PM</small>
                          <div class="notification-heading">Mr. John</div>
                          <div class="notification-text">Hi Teddy, Just wanted to let you
                            ...</div>
                        </div>
                      </a>
                    </li>
                    <li class="text-center">
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
                    <li onclick="return window.location.href = 'profile.php?id=<?= $user->id ?>'">
                      <a>
                        <i class="ti-user"></i>
                        <span>Profile</span>
                      </a>
                    </li>

                    <li onclick="return window.location.href = '<?= $user->role == 'user' ? 'inbox.php' : 'messages.php' ?>'">
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
