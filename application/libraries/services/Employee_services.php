<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Employee_services
{


  function __construct()
  { }

  public function __get($var)
  {
    return get_instance()->$var;
  }

  public function get_photo_upload_config($name = "")
  {
    // $filename = $name . "_" . time();
    $filename = $name . "_" . time();
    $upload_path = 'uploads/employee/';

    $config['upload_path'] = './' . $upload_path;
    $config['image_path'] = base_url() . $upload_path;
    $config['allowed_types'] = "gif|jpg|png|jpeg";
    $config['overwrite'] = "true";
    // $config['max_size'] = "2048";
    $config['file_name'] = '' . $filename;

    return $config;
  }
  public function get_table_config_no_action($_page, $start_number = 1)
  {
    $table["header"] = array(
      'name' => 'Nama Karyawan',
      'fingerprint_name' => 'OPD',
      'position' => 'Jabatan',
      'pin' => 'Kode Pin',
      'faction' => 'Golongan',
      '_image' => 'Foto Pegawai',
    );
    $table["number"] = $start_number;
    return $table;
  }
  public function get_table_config($_page, $start_number = 1)
  {
    $table["header"] = array(
      'name' => 'Nama Karyawan',
      'fingerprint_name' => 'OPD',
      'position' => 'Jabatan',
      'pin' => 'Kode Pin',
      'faction' => 'Golongan',
      '_image' => 'Foto Pegawai',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => 'Edit',
        "type" => "modal_form_multipart",
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
          "image_old" => array(
            'type' => 'hidden',
            'label' => "Foto Pegawai",
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
  public function get_form_data()
  {
    $this->load->model(array(
      'fingerprint_model',
    ));

    $opds = $this->fingerprint_model->fingerprints()->result();
    $opd_select = array();
    foreach ($opds as $opd) {
      $opd_select[$opd->id] = $opd->name;
    }

    $_data["form_data"] = array(
      "id" => array(
        'type' => 'hidden',
        'label' => "ID",
      ),
      "fingerprint_id" => array(
        'type' => 'hidden',
        'label' => "Nama OPD",
        // 'options' => $opd_select,
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
        'type' => 'hidden',
        'label' => "Kode Pin",
      ),
      "faction" => array(
        'type' => 'select',
        'label' => "Golongan",
        'options' => array(
          0 => 'Non-PNS',
          1 => 'PNS',
        )
      ),
      "image" => array(
        'type' => 'file',
        'label' => "Foto Pegawai",
      ),
      "image_old" => array(
        'type' => 'hidden',
        'label' => "Foto Pegawai",
      ),
    );
    return $_data;
  }
}
