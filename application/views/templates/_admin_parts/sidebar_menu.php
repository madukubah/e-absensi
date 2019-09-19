  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url()  ?>" class="brand-link">
      <img src="<?= base_url() . ICON_IMAGE ?>" alt="AdminLTE Logo" class="brand-image" style="opacity: .8">
      <span class="brand-text font-weight-light"><?php echo APP_NAME ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <!-- <img src="<?= base_url('assets/') ?>dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> -->
          <?php if ($this->session->userdata('user_image')) : ?>
            <img class="img-circle elevation-2" src="<?php echo base_url('uploads/users_photo/') . $this->session->userdata('user_image') ?>" width="48" height="48" alt="User" />
          <?php else : ?>
            <img class="img-circle elevation-2" src="<?php echo base_url('assets/') ?>img/user.png" width="48" height="48" alt="User" />
          <?php endif; ?>
        </div>
        <div class="info">
          <a href="<?= base_url('user/profile') ?>" class="d-block"><?php echo ucwords($this->session->userdata('user_profile_name')) ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <!-- <li class="nav-header">DAFTAR MENU</li> -->
          <?php
          function print_menus($datas)
          {
            foreach ($datas as $data) {
              if ((!$data->status)) continue;

              if (!empty($data->branch)) {
                ?>
                <li class="nav-item has-treeview ">
                  <a id="<?php echo $data->list_id ?>" href="#" class="nav-link ">
                    <i class="nav-icon fas fa-<?php echo $data->icon ?>"></i>
                    <!-- <i class="far fa-circle nav-icon"></i>                                   -->
                    <p>
                      <?php echo $data->name ?>
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <?php
                          print_menus($data->branch);
                          ?>
                  </ul>
                </li>
              <?php
                  } else {
                    ?>
                <li class="nav-item">
                  <a id="<?php echo $data->list_id ?>" href="<?php echo site_url($data->link) ?>" class="nav-link">
                    <i class="nav-icon fas fa-<?php echo $data->icon ?>"></i>
                    <p>
                      <?php echo $data->name ?>
                      <span id="<?php echo 'notif_' . $data->list_id ?>" class="right badge badge-danger"></span>
                    </p>
                  </a>
                </li>
          <?php
              }
            }
          }

          print_menus($_menus);
          ?>
          <!-- <li class="nav-item has-treeview menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.html" class="nav-link active">
                  <i class="fas fa-tachometer-alt nav-icon"></i>
                  <p>Dashboard v1</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index2.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v2</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index3.html" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Dashboard v3</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Widgets
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li> -->


        </ul>

      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>


  <script type="text/javascript">
    function menuActive(id) {
      id = id.trim();
      // console.log(id);
      // console.log(a = document.getElementById(id.trim()));
      a = document.getElementById(id.trim())
      // // var a =document.getElementById("menu").children[num-1].className="active";
      // var a = document.getElementById(id.trim());
      // console.log(a.parentNode.parentNode);
      a.classList.add("active");
      b = a.parentNode.parentNode.parentNode;
      b.classList.add("menu-open");
      b.children[0].classList.add("active");
      // console.log( b.children[0] );
      // document.getElementById(id).classList.add("active");

    }
  </script>