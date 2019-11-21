<section class="content">
	<!-- <div class="container-fluid"> -->
		<div class="block-header">
			<h2><?php echo $block_header ?></h2>
		</div>
        <div class="row clearfix">
          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <div class="card">
              <div class="header">
                      <div class="row clearfix">
                          <div class="col-md-12">
                                <!-- alert  -->
                                <?php
                                    echo $alert;
                                ?>
                                <!-- alert  -->
                          </div>
                      </div>
                      <!--  -->
                      <div class="row clearfix" >
                        <div class="col-md-6">
                          <h2>
                              <?php echo strtoupper($header)?>
                              <small><?php echo $sub_header ?></small>
                          </h2>
                        </div>
                        <!-- search form -->
                        <div class="col-md-6">
                          <div class="row clearfix">
                            <div class="col-md-2">
                            </div>
                            <div class="col-md-10">
                                    <div class="pull-right">
                                            <?php echo ( isset( $header_button )  ) ? $header_button : '' ;  ?>
                                    </div>
                                <!--  -->
                            </div>

                          </div>
                        </div>
                        <!--  -->
                      </div>
                      <!--  -->
                  </div>
                  <div class="body">
                        <!-- HIRARKI -->
                        <div style="  " >
                            <div class="tree" >
                                <ol>
                                    <?php
                                        function print_tree( $datas )
                                        {
                                            foreach( $datas as $data )
                                            {       
                                                    echo  '<li>';
                                                          echo '<a href="#">'.$data->name.'</a>';
                                                        ?>
                                                        
                                                        <button class="btn btn-white btn-info btn-bold btn-xs" data-toggle="modal" data-target="#add_menu_<?php echo $data->id ?>">
                                                            + 
                                                        </button>
                                                        <button class="btn btn-white btn-info btn-bold btn-xs" data-toggle="modal" data-target="#edit_menu_<?php echo $data->id ?>">
                                                            Edit
                                                        </button>
                                                        <button class="btn btn-white btn-danger btn-bold btn-xs" data-toggle="modal" data-target="#delete_menu_<?php echo $data->id ?>">
                                                            X
                                                        </button>
                                                        <?php echo $data->description?>
                                                        <?php
                                                        echo "<ol>";
                                                            print_tree( $data->branch );
                                                        echo "</ol>";
                                                    echo  '</li>';
                                            }
                                        };
                                        print_tree( $menus_tree );
                                    ?>
                                </ol>
                            </div>
                        </div>
                        <!-- HIRARKI -->
                  </div>
              </div>
          </div>
      </div>
	<!-- </div> -->
  <?php
    foreach( $menu_list as $menu )
    {
        $model_form_add = array(
          "name" => "Tambah Child Menu",
          "modal_id" => "add_menu_",
          "button_color" => "primary",
          "url" => site_url( $current_page."add/"),
          "form_data" => array(
                "name" => array(
                'type' => 'text',
                'label' => "Nama Menu",
                'value' => '',
            ),
            "link" => array(
                'type' => 'text',
                'label' => "Link",
                'value' => $group->name."/",
            ),
            "list_id" => array(
                'type' => 'text',
                'label' => "List ID",
                'value' => "-",
            ),
            "icon" => array(
                'type' => 'text',
                'label' => "Icon",
                'value' => 'home',
            ),
            "position" => array(
                'type' => 'number',
                'label' => "Urutan Ke",
                'value' => 1,
            ),
            "status" => array(
                'type' => 'select',
                'label' => "Status",
                'options' => array(
                      1 => 'Aktif',
                      0 => 'Non Aktif',
                ),
            ),
            "description" => array(
                'type' => 'textarea',
                'label' => "Deskripsi",
                'value' => "-",				
            ),
            "menu_id" => array(
                'type' => 'hidden',
                'label' => "menu_id",
                'value' => $menu->id,
            ),
            "group_id" => array(
                'type' => 'hidden',
                'label' => "group_id",
                'value' => $group->id,
            ),
          ),
          'data' => $menu,
          'param' => "id",
        );
        $this->load->view('templates/actions/modal_form_no_button', $model_form_add ); 
        $model_form_edit = array(
          "name" => "Edit Menu",
          "modal_id" => "edit_menu_",
          "button_color" => "primary",
          "url" => site_url( $current_page."edit/"),
          "form_data" => array(
                "name" => array(
                    'type' => 'text',
                    'label' => "Nama Menu",
                  ),
                  "link" => array(
                      'type' => 'text',
                      'label' => "Link",
                  ),
                  "list_id" => array(
                      'type' => 'text',
                      'label' => "List ID",
                  ),
                  "icon" => array(
                      'type' => 'text',
                      'label' => "Icon",
                  ),
                  "position" => array(
                      'type' => 'number',
                      'label' => "Urutan Ke",
                  ),
                  "status" => array(
                      'type' => 'select',
                      'label' => "Status",
                      'options' => array(
                            1 => 'Aktif',
                            0 => 'Non Aktif',
                      ),
                  ),
                  "description" => array(
                      'type' => 'textarea',
                      'label' => "Deskripsi",
                  ),
                  "menu_id" => array(
                      'type' => 'hidden',
                      'label' => "menu_id",
                  ),
                  "id" => array(
                      'type' => 'hidden',
                      'label' => "menu_id",
                  ),
                  "group_id" => array(
                      'type' => 'hidden',
                      'label' => "group_id",
                      'value' => $group->id,
                  ),
          ),
          'data' => $menu,
          'param' => "id",
        );
        $this->load->view('templates/actions/modal_form_no_button', $model_form_edit ); 

        $menu->group_id = $group->id;
        $model_form_delete =array(
          "type" => "modal_delete",
          "modal_id" => "delete_menu_",
          "url" => site_url( $current_page."delete/"),
          "button_color" => "danger",
          "param" => "id",
          "form_data" => array(
            "id" => array(
              'type' => 'hidden',
              'label' => "id",
            ),
            "group_id" => array(
                'type' => 'hidden',
                'label' => "group_id",
                'value' => $group->id,
            ),
          ),
          'data' => $menu,
          "title" => "Rekening",
          "data_name" => "name",
        );
        $this->load->view('templates/actions/modal_delete_no_button', $model_form_delete ); 
    }
  ?>
</section>

