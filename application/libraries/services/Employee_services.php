<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Employee_services
{


  function __construct(){

  }

  public function __get($var)
  {
    return get_instance()->$var;
  }
  
  public function get_table_config( $_page, $start_number = 1 )
  {
      $table["header"] = array(
        'name' => 'Nama Karyawan',
        'position' => 'Jabatan',
        'pin' => 'Kode Pin',
      );
      $table["number"] = $start_number;
      $table[ "action" ] = array(
              array(
                "name" => 'Edit',
                "type" => "modal_form",
                "modal_id" => "edit_",
                "url" => site_url( $_page."edit/"),
                "button_color" => "primary",
                "param" => "id",
                "form_data" => array(
                    "id" => array(
                      'type' => 'hidden',
                      'label' => "ID",
                      ),
                    "name" => array(
                      'type' => 'text',
                      'label' => "Nama Lengkap",
                    ),
                    "position" => array(
                      'type' => 'text',
                      'label' => "Jabatan",
                    ),
                    "pin" => array(
                      'type' => 'text',
                      'label' => "Kode Pin",
                    ),
                ),
                "title" => "Group",
                "data_name" => "name",
              ),
              array(
                "name" => 'X',
                "type" => "modal_delete",
                "modal_id" => "delete_",
                "url" => site_url( $_page."delete/"),
                "button_color" => "danger",
                "param" => "id",
                "form_data" => array(
                  "id" => array(
                    'type' => 'hidden',
                    'label' => "id",
                  ),
                ),
                "title" => "Group",
                "data_name" => "name",
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
          'field' => 'position',
          'label' => 'position',
          'rules' =>  'trim|required',
        ),
        array(
          'field' => 'pin',
          'label' => 'pin',
          'rules' =>  'trim|required',
        ),
    );
    
    return $config;
  }

  /**
	 * get_form_data
	 *
	 * @return array
	 * @author madukubah
	 **/
	public function get_form_data(  )
	{
		$_data["form_data"] = array(
			"id" => array(
				'type' => 'hidden',
				'label' => "ID",
			  ),
			"name" => array(
			  'type' => 'text',
			  'label' => "Nama Lengkap",
			),
			"position" => array(
			  'type' => 'text',
			  'label' => "Jabatan",
			),
			"pin" => array(
			  'type' => 'text',
			  'label' => "Kode Pin",
			),
		
    );
		return $_data;
	}
}
?>
