<?php
$role = $user->role;
?>
<div class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures" style="position: fixed; top: 0">
  <div class="nano">
    <div class="nano-content">
      <ul>
        <div class="logo">
            <img src="../../6.png" alt="OPASS" style="width: 180px;" />
        </div>
        <?php
        require_once('links.php');
        $exploded = explode("/", $_SERVER["PHP_SELF"]);
        $self = $exploded[count($exploded) - 1];
        $links = $role == "user" ? $userLinks : $attyLinks;

        $folder = $exploded[count($exploded) - 2];

        $userFolder = $role == "user" ? "../Users/" : "../Attorney/";
        $dir = $self == "notifications.php" ? $userFolder : "";
        foreach ($links as $index => $data) {
          $link = $data["link"];
          if ($folder == "Conversation") {
            $link = $userFolder . $data["link"];
          }
          if ($link == $self) :
        ?>
            <li class="active">
              <a href="<?= $link ?>">
                <i class="<?= $data['icon'] ?>"></i>
                <?= $data['title'] ?>
              </a>
            </li>
          <?php
          else :
          ?>
            <li>
              <a href="<?= $dir . $link ?>">
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