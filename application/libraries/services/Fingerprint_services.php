<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Fingerprint_services
{


  function __construct()
  { }

  public function __get($var)
  {
    return get_instance()->$var;
  }
  public function get_table_config_no_action($_page, $start_number = 1)
  {
    $table["header"] = array(
      'name' => 'Nama OPD',
      'opd_category_name' => 'Kategori OPD',
      'ip_address' => 'Alamat IP',
      'port' => 'Port',
      'key_finger' => 'Key',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => "Detail",
        "type" => "link",
        "url" => site_url($_page . "fingerprint/"),
        "button_color" => "primary",
        "param" => "id",
        "data" => NULL,
      ),
    );
    return $table;
  }
  public function get_table_config($_page, $start_number = 1)
  {
    $table["header"] = array(
      'name' => 'Nama SKPD',
      'opd_category_name' => 'Kategori OPD',
      'ip_address' => 'Alamat IP',
      'port' => 'Port',
      'key_finger' => 'Key',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => 'Edit',
        "type" => "modal_form",
        "modal_id" => "edit_",
        "url" => site_url($_page . "edit/"),
        "button_color" => "primary",
        "param" => "id",
        "form_data" => $this->get_form_data()["form_data"],
        "title" => "Group",
        "data_name" => "name",
      ),
      array(
        "name" => 'X',
        "type" => "modal_delete",
        "modal_id" => "delete_",
        "url" => site_url($_page . "delete/"),
        "button_color" => "danger",
        "param" => "id",
        "form_data" => array(
          "id" => array(
            'type' => 'hidden',
            'label' => "id",
          ),
          "user_id" => array(
            'type' => 'hidden',
            'label' => "user_id",
          ),
        ),
        "title" => "Group",
        "data_name" => "name",
      ),
    );
    return $table;
  }
  public function validation_config()
  {
    $config = array(
      array(
        'field' => 'name',
        'label' => 'name',
        'rules' =>  'trim|required',
      ),
      array(
        'field' => 'ip_address',
        'label' => 'ip_address',
        'rules' =>  'trim|required',
      ),
      array(
        'field' => 'port',
        'label' => 'port',
        'rules' =>  'trim|required',
      ),
      array(
        'field' => 'key_finger',
        'label' => 'key_finger',
        'rules' =>  'trim|required',
      ),
      array(
        'field' => 'range_pin',
        'label' => 'range_pin',
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
  public function get_form_data()
  {
    $this->load->model(array(
      'opd_category_model',
    ));

    $opds = $this->opd_category_model->opd_categories()->result();
    $opd_select = array();
    foreach ($opds as $opd) {
      $opd_select[$opd->id] = $opd->name;
    }
    $_data["form_data"] = array(
      "id" => array(
        'type' => 'hidden',
        'label' => "ID",
      ),
      "opd_category_id" => array(
        'type' => 'select',
        'label' => "Kategori OPD",
        'options' => $opd_select,
      ),
      "name" => array(
        'type' => 'text',
        'label' => "Nama OPD",
      ),
      "acronym" => array(
        'type' => 'text',
        'label' => "Singkatan OPD",
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
      "range_pin" => array(
        'type' => 'number',
        'label' => "pin finger tertinggi",
      ), 
    );
    return $_data;
  }
}
