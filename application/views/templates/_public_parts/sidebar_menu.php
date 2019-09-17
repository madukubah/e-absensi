<?php
  $menus = array(
    array(
      'menuId' => "home",
      'menuName' => "Beranda",
      'menuPath' => site_url("user/"),
      'menuIcon' => "fa fa-file-archive-o",
      'menuChild' => array()
    ),
    array(
      'menuId' => "book",
      'menuName' => "Daftar Buku",
      'menuPath' => site_url("user/book"),
      'menuIcon' => "fa fa-book",
      'menuChild' => array()
    ),
    array(
      'menuId' => "book",
      'menuName' => "Tambah Buku",
      'menuPath' => site_url("user/book/add"),
      'menuIcon' => "fa fa-book",
      'menuChild' => array()
    ),
  );

  $user_management = array(
    'menuId' => "admin",
    'menuName' => "User Management",
    'menuPath' => site_url("admin/user_management"),
    'menuIcon' => 'fa fa-times',
    'menuChild' => array()
  );
  $category = array(
    'menuId' => "category",
    'menuName' => "Kategori",
    'menuPath' => site_url("category"),
    'menuIcon' => 'fa fa-times'
  );

?>
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
          <img class="img-circle" src="<?php echo $a =  ( empty($this->session->userdata('user_image')) ) ?  base_url(FAVICON_IMAGE)  : base_url('uploads/users_photo/').$this->session->userdata('user_image') ?>" alt="Jason's Photo" />
      </div>
      <div class="pull-left info">
        <?php echo $this->session->userdata('user_name')?>
      </div>
    </div>
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MENU</li>
      <?php
        foreach($menus as $menu):
      ?>
        <li id="<?php echo $menu['menuId'] ?>">
          <a href="<?php echo $menu['menuPath'] ?>">
          <i class="menu-icon <?php echo $menu['menuIcon'] ?>"></i>
          <span class="menu-text"> <?php echo $menu['menuName'] ?> </span>
          </a>
          <b class="arrow"></b>
        </li>
      <?php
          endforeach;
      ?>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-share"></i> <span>Multilevel</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu" style="display: none;">
          <li class="treeview menu-open">
            <a href="#"><i class="fa fa-circle-o"></i> Level One
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu" style="display: block;">
              <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
              <li class="treeview menu-open">
                <a href="#"><i class="fa fa-circle-o"></i> Level Two
                  <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                  </span>
                </a>
                <ul class="treeview-menu" style="display: block;">
                  <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                  <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                </ul>
              </li>
            </ul>
          </li>
          <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
        </ul>
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
<script type="text/javascript">
    function menuActive( id ){
        // var a =document.getElementById("menu").children[num-1].className="active";
        if( id == "" )
          var a =document.getElementById("home").className="active";
        else
          var a =document.getElementById(id).className="active";
        console.log(a);
    }
</script>




