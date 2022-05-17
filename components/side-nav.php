<?php
$role = $user->role;
?>
<div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures" style="position: fixed; top: 0">
  <div class="nano">
    <div class="nano-content">
      <ul>
        <div class="logo">
          <a href="index.php">
            <img src="../../6.png" alt="OPASS" style="width: 180px;" />
            <!-- <span>Focus</span> -->
          </a>
        </div>
        <?php
        require_once('links.php');
        $exploded = explode("/", $_SERVER["PHP_SELF"]);
        $self = $exploded[count($exploded) - 1];
        $links = $role == "user" ? $userLinks : $attyLinks;

        $userFolder = $role == "user" ? "../Users/" : "../Attorney/";
        $dir = $self == "notifications.php" ? $userFolder : "";
        foreach ($links as $index => $data) {
          if ($data["link"] == $self) :
        ?>
            <li class="active">
              <a href="<?= $data['link'] ?>">
                <i class="<?= $data['icon'] ?>"></i>
                <?= $data['title'] ?>
              </a>
            </li>
          <?php
          else :
          ?>
            <li>
              <a href="<?= $dir . $data['link'] ?>">
                <i class="<?= $data['icon'] ?>"></i>
                <?= $data['title'] ?>
              </a>
            </li>
        <?php
          endif;
        }
        ?>
        <li>
          <a href="../../backend/logout.php?role=<?= $role ?>">
            <i class="ti-power-off"></i>
            Logout
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>