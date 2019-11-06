<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Attendance_services
{


  function __construct()
  { }

  public function __get($var)
  {
    return get_instance()->$var;
  }
  public function get_table_config_no_action($_page, $start_number = 1, $fingerprint_id, $url_return = "")
  {
    $table["header"] = array(
      'employee_name' => 'Nama Karyawan',
      'employee_pin' => 'Kode Pin',
      '_date' => 'tanggal',
      '_time' => 'Jam',
      'status' => 'Keterangan',
    );
    $table["number"] = $start_number;
    $table["action"] = array(
      array(
        "name" => 'Edit',
        "type" => "modal_form",
        "modal_id" => "edit_",
        "url" => site_url("attendance/edit/"),
        "button_color" => "primary",
        "param" => "id",
        "form_data" => array(
          'fingerprint_id' => array(
            'type' => 'hidden',
            'label' => "ID",
            'value' => $fingerprint_id
          ),
          "id" => array(
            'type' => 'hidden',
            'label' => "ID",
          ),
          "url_return" => array(
            'type' => 'hidden',
            'label' => "url_return",
            'value' => $url_return,
          ),
          "status" => array(
            'type' => 'select',
            'label' => "Keterangan",
            'options' => array(
              0 => 'hadir',
              1 => 'sakit',
              2 => 'izin',
            )
          ),
        ),
        "title" => "Group",
        "data_name" => "name",
      ),
      array(
        "name" => 'X',
        "type" => "modal_delete",
        "modal_id" => "delete_",
        "url" => site_url("attendance/delete/" . $fingerprint_id),
        "button_color" => "danger",
        "param" => "id",
        "form_data" => array(
          "id" => array(
            'type' => 'hidden',
            'label' => "id",
          ),
          "url_return" => array(
            'type' => 'hidden',
            'label' => "url_return",
            'value' => $url_return,
          ),
        ),
        "title" => "Group",
        "data_name" => "employee_name",
      ),
    );
    return $table;
  }

  public function get_table_config_blank($_page, $start_number = 1)
  {
    $table["header"] = array(
      'employee_name' => 'Nama Karyawan',
      'employee_pin' => 'Kode Pin',
      '_date' => 'tanggal',
      '_time' => 'Jam',
      'status' => 'Keterangan',
    );
    $table["number"] = $start_number;
    return $table;
  }

  public function table_config_view()
  {
    $table["header"] = array(
      'name' => 'Nama Karyawan',
      'employee_pin' => 'Kode Pin',
      '_date' => 'tanggal',
      '_time' => 'Jam',
      'faction' => 'Jenis Pegawai',
      '_image' => 'Foto Pegawai',
      'status' => 'Keterangan',
    );
    $table["number"] = 1;
    return $table;
  }
  public function get_table_config($_page, $start_number = 1)
  {
    $table["header"] = array(
      'name' => 'Nama SKPD',
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
        "url" => site_url($_page . "delete/"),
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
  public function validation_config()
  {
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
  public function get_form_data($fingerprint_id, $url_return = "")
  {
    $_data["form_data"] = array(
      "id" => array(
        'type' => 'hidden',
        'label' => "ID",
      ),
      "url_return" => array(
        'type' => 'hidden',
        'label' => "url_return",
        'value' => $url_return,
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
        'type' => 'hidden',
        'label' => "timestamp",
        "value" => time(),
      ),
      "date" => array(
        'type' => 'date',
        'label' => "Tanggal",
        "value" => date("m/d/Y"),
      ),
      "time" => array(
        'type' => 'text',
        'label' => "Jam",
        "value" => date("h:i:s"),
      ),
      "status" => array(
        'type' => 'select',
        'label' => "Keterangan",
        "options" => array(
          0 => 'hadir',
          1 => 'sakit',
          2 => 'izin',
        ),
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
  public function get_form_data_blank()
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

  public function extract_days($attendances)
  {
    $ARRA_DAYS = array();
    foreach ($attendances as $ind => $attendance) {
      $ARRA_DAYS[] = $attendance->day;
    }
    return $ARRA_DAYS;
  }

  public function extract_attendances($attendances, $employee_count)
  {
    $ARR_DAYS = array();
    $ARR_ABSENCE = array();
    $ARR_DAYS_P = array();
    $ARR_DAYS_S = array();
    $sum_attendances = 0;
    $sum_permission = 0;
    $sum_sick = 0;
    $sum_absences = 0;
    foreach ($attendances as $ind => $attendance) {
      if ($attendance->day == null)
        break;
      $sum_attendances += $attendance->total_attendance;
      // $sum_attendances += $attendance->count_attendance;
      $sum_absences    += $employee_count - $attendance->count_attendance;
      $sum_permission += $attendance->count_permission;
      $sum_sick += $attendance->count_sick;

      $ARR_DAYS[] = $attendance->total_attendance;
      $ARR_DAYS_P[] = $attendance->count_permission;
      $ARR_DAYS_S[] = $attendance->count_sick;
      // $ARR_DAYS[] = $attendance->count_attendance;
      $ARR_ABSENCE[] =  $employee_count - $attendance->count_attendance;
    }
    return (object) array(
      "attendances" => $ARR_DAYS,
      "permission" => $ARR_DAYS_P,
      "sick" => $ARR_DAYS_S,
      "absences" => $ARR_ABSENCE,
      "sum_attendances" => $sum_attendances,
      "sum_absences" => $sum_absences,
      "sum_permission" => $sum_permission,
      "sum_sick" => $sum_sick,
    );
  }
}
