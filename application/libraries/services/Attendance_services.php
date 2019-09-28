<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Attendance_services
{


  function __construct(){

  }

  public function __get($var)
  {
    return get_instance()->$var;
  }
  public function get_table_config_no_action( $_page, $start_number = 1, $fingerprint_id )
  {
      $table["header"] = array(
        'employee_name' => 'Nama Karyawan',
        'employee_pin' => 'Kode Pin',
        '_date' => 'tanggal',
        '_time' => 'Jam',
      );
      $table["number"] = $start_number;
    //   $table[ "action" ] = array(
    //         array(
    //           "name" => 'X',
    //           "type" => "modal_delete",
    //           "modal_id" => "delete_",
    //           "url" => site_url( $_page."delete/".$fingerprint_id),
    //           "button_color" => "danger",
    //           "param" => "id",
    //           "form_data" => array(
    //             "id" => array(
    //               'type' => 'hidden',
    //               'label' => "id",
    //             ),
    //           ),
    //           "title" => "Group",
    //           "data_name" => "_time",
    //         ),
    // );
    return $table;
  }
  public function get_table_config( $_page, $start_number = 1 )
  {
      $table["header"] = array(
        'name' => 'Nama SKPD',
        'ip_address' => 'Alamat IP',
        'port' => 'Port',
        'key_finger' => 'Key',
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
                        'label' => "Nama SKPD",
                      ),
                      "ip_address" => array(
                        'type' => 'text',
                        'label' => "Alamat IP",
                      ),
                      "port" => array(
                        'type' => 'text',
                        'label' => "Port",
                      ),
                      "key_finger" => array(
                        'type' => 'text',
                        'label' => "Key",
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
          'field' => 'employee_pin',
          'label' => 'employee_pin',
          'rules' =>  'trim|required',
        ),
        array(
          'field' => 'timestamp',
          'label' => 'timestamp',
          'rules' =>  'trim|required',
        ),
        array(
          'field' => 'date',
          'label' => 'date',
          'rules' =>  'trim|required',
        ),
        array(
          'field' => 'time',
          'label' => 'time',
          'rules' =>  'trim|required',
        ),
        array(
          'field' => 'status',
          'label' => 'status',
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
	public function get_form_data( $fingerprint_id )
	{
		$_data["form_data"] = array(
			"id" => array(
				'type' => 'hidden',
				'label' => "ID",
      ),
      "fingerprint_id" => array(
			  'type' => 'hidden',
        'label' => "Kode Pin",
        'value' => $fingerprint_id,
			),
      "employee_pin" => array(
			  'type' => 'text',
			  'label' => "Kode Pin",
			),
			"timestamp" => array(
			  'type' => 'text',
        'label' => "timestamp",
        "value" => time(),
			),
			"date" => array(
			  'type' => 'text',
        'label' => "Tanggal",
        "value" => date("Y-m-d"),
			),
			"time" => array(
			  'type' => 'text',
        'label' => "Jam",
        "value" => date("h:i:s"),
      ),
      "status" => array(
			  'type' => 'text',
        'label' => "Jam",
        "value" => 0,
			),
    );

		return $_data;
  }
  /**
	 * get_form_data
	 *
	 * @return array
	 * @author madukubah
	 **/
	public function get_form_data_blank(  )
	{
		$_data["form_data"] = array(
			"id" => array(
				'type' => 'hidden',
				'label' => "ID",
      ),
      "employee_pin" => array(
			  'type' => 'hidden',
			  'label' => "Kode Pin",
			),
			"timestamp" => array(
			  'type' => 'text',
        'label' => "timestamp",
			),
			"date" => array(
			  'type' => 'text',
        'label' => "Tanggal",
			),
			"time" => array(
			  'type' => 'text',
        'label' => "Jam",
      ),
      "status" => array(
			  'type' => 'text',
        'label' => "Jam",
			),
    );

		return $_data;
	}
}
?>
