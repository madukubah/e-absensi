<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Menu_services
{


    function __construct(){

    }

    public function __get($var)
  	{
  		return get_instance()->$var;
    }
    
    public function groups_table_config( $_page, $start_number = 1 )
    {
        $table["header"] = array(
          'name' => 'Nama Group',
          'description' => 'Deskripsi',
        );
        $table["number"] = $start_number;
        $table[ "action" ] = array(
                array(
                  "name" => "Detail",
                  "type" => "link",
                  "url" => site_url( $_page."group/"),
                  "button_color" => "primary",
                  "param" => "id",
                ),
      );
      return $table;
    }
    public function validation_config( ){
      $config = array(
          array(
            'field' => 'name',
            'label' => 'name',
            'rules' =>  'trim|required',
          ),
          array(
            'field' => 'link',
            'label' => 'link',
            'rules' =>  'trim|required',
          ),
          array(
            'field' => 'icon',
            'label' => 'icon',
            'rules' =>  'trim|required',
          ),
          array(
            'field' => 'position',
            'label' => 'position',
            'rules' =>  'trim|required',
          ),
          array(
            'field' => 'status',
            'label' => 'status',
            'rules' =>  'trim|required',
          ),
          array(
            'field' => 'description',
            'label' => 'description',
            'rules' =>  'trim|required',
          ),
          array(
            'field' => 'menu_id',
            'label' => 'menu_id',
            'rules' =>  'trim|required',
          ),
      );
      
      return $config;
    }
}
?>
