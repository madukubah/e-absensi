<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Attendance_model extends MY_Model
{
  protected $table = "attendance";
  function __construct()
  {
    parent::__construct($this->table);
    parent::set_join_key('attendance_id');
  }
  /**
   * create
   *
   * @param array  $data
   * @return static
   * @author madukubah
   */
  public function create($data)
  {
    // Filter the data passed
    $data = $this->_filter_data($this->table, $data);
    $this->db->insert($this->table, $data);
    $id = $this->db->insert_id($this->table . '_id_seq');
    if (isset($id)) {
      $this->set_message("berhasil");
      return $id;
    }
    $this->set_error("gagal");
    return FALSE;
  }
  /**
   * create
   *
   * @param array  $data
   * @return static
   * @author madukubah
   */
  public function create_batch($data_batch)
  {
    $this->db->trans_begin();
    $this->db->insert_batch($this->table, $data_batch);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $this->set_error("gagal");
      return FALSE;
    }
    $this->db->trans_commit();
    $this->set_message("berhasil");
    return TRUE;
  }
  /**
   * update
   *
   * @param array  $data
   * @param array  $data_param
   * @return bool
   * @author madukubah
   */
  public function update($data, $data_param)
  {
    $this->db->trans_begin();
    $data = $this->_filter_data($this->table, $data);
    $this->db->update($this->table, $data, $data_param);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $this->set_error("gagal");
      return FALSE;
    }
    $this->db->trans_commit();
    $this->set_message("berhasil");
    return TRUE;
  }
  /**
   * delete
   *
   * @param array  $data_param
   * @return bool
   * @author madukubah
   */
  public function delete($data_param)
  {
    //foreign
    //delete_foreign( $data_param. $models[]  )
    if (!$this->delete_foreign($data_param)) {
      $this->set_error("gagal"); //('group_delete_unsuccessful');
      return FALSE;
    }
    //foreign
    $this->db->trans_begin();
    $this->db->delete($this->table, $data_param);
    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $this->set_error("gagal"); //('group_delete_unsuccessful');
      return FALSE;
    }
    $this->db->trans_commit();
    $this->set_message("berhasil"); //('group_delete_successful');
    return TRUE;
  }

  public function delete_by_fingerprint_id($fingerprint_id)
  {
    $employee_ids = $this->db->select("id as employee_id")
      ->where("fingerprint_id", $fingerprint_id)
      ->get("employee")->result();

    $ids = array();
    foreach ($employee_ids as $id) {
      $ids[] = $id->employee_id;
    }
    // var_dump( $employee_ids );
    // die;
    //foreign
    $this->db->trans_begin();
    $this->db->where_in('employee_id', $ids);
    $this->db->delete($this->table);


    if ($this->db->trans_status() === FALSE) {
      $this->db->trans_rollback();
      $this->set_error("gagal"); //('group_delete_unsuccessful');
      return FALSE;
    }
    $this->db->trans_commit();
    $this->set_message("berhasil"); //('group_delete_successful');
    return TRUE;
  }
  /**
   * group
   *
   * @param int|array|null $id = id_attendance
   * @return static
   * @author madukubah
   */
  public function attendance($id = NULL)
  {
    if (isset($id)) {
      $this->where($this->table . '.id', $id);
    }
    $this->limit(1);
    $this->order_by($this->table . '.id', 'desc');
    $this->attendances();
    return $this;
  }
  /**
   * group
   *
   * @param int|array|null $id = id_attendance
   * @return static
   * @author madukubah
   */
  public function attendance_by_iddate($id, $date , $_is_coming = TRUE)
  {
    $come_out = ['time BETWEEN "12:01:00" AND "18:00:00" ', ' time BETWEEN "06:00:00" AND "12:00:00"'];


    $this->where($this->table . '.employee_id', $id);
    $this->where($this->table . '.date', $date);
    $this->limit(1);
    $this->order_by($this->table . '.id', 'desc');
    $this->where($come_out[$_is_coming],  NULL);
    $this->attendances();
    return $this;
  }
  /**
   * group
   *
   * @param int|array|null $id = id_attendance
   * @return static
   * @author madukubah
   */
  public function record_count_fingerprint_id($fingerprint_id)
  {
    $this->join(
      "employee",
      "employee.id = " . $this->table . '.employee_id',
      "inner"
    );
    if (isset($fingerprint_id)) {
      $this->where('employee.fingerprint_id', $fingerprint_id);
    }
    return $this->record_count();
  }
  
  public function record_count_filter_fingerprint_id($fingerprint_id, $date = NULL)
  {
    $this->db->join(
      "employee",
      "employee.id = " . $this->table . '.employee_id',
      "inner"
    );
    if ($fingerprint_id) {
      $this->db->where('employee.fingerprint_id', $fingerprint_id);
    }
    if ($date) {
      $this->db->where('date', $date);
    }
    return $this->db->count_all_results($this->table);
  }
  /**
   * attendance
   *
   *
   * @return static
   * @author madukubah
   */
  public function accumulation($fingerprint_id = NULL, $group_by = NULL, $month = NULL, $employee_ids = NULL, $date = NULL, $_is_coming = TRUE)
  {
    $come_out = ['time BETWEEN "12:01:00" AND "18:00:00" ', ' time BETWEEN "06:00:00" AND "12:00:00"'];
    $_group = array(
      'date' => $this->table . ".date",
      'status' => $this->table . ".status",
      'month' => "month",
    );
    $this->db->select([
      "*",
      "count(*) as count_attendance",
      "count(CASE WHEN status = 0 THEN 1 ELSE NULL end) as total_attendance",
      "count(CASE WHEN status = 1 THEN 1 ELSE NULL end) as count_sick",
      "count(CASE WHEN status = 2 THEN 1 ELSE NULL end) as count_permission",
    ]);
    $this->db->from("
          (
            SELECT employee.name,employee.fingerprint_id , attendance.*, day( attendance.date ) as day , month( attendance.date ) as month,year( attendance.date ) as year  from attendance
              INNER JOIN employee 
            ON employee.id = attendance.employee_id
          ) 
          attendance
      ");
    // $this
    if ($_is_coming == 'FALSE') $_is_coming = 0;
    $this->db->where($come_out[$_is_coming],  NULL);
    if (isset($date)) {
      $this->db->where($this->table . ".day", $date);
    }
    if (isset($month)) {
      $this->db->where_in($this->table . ".month",  $month);
    } else {
      $this->db->where_in($this->table . ".month",  date("m"));
    }
    if (isset($group_by)) {
      foreach ($group_by as $group) {
        $this->db->group_by($_group[$group]);
      }
    }
    if (isset($employee_ids)) {
      foreach ($employee_ids as $employee_id) {
        $this->db->where("employee_id", $employee_id);
      }
    }
    if (isset($fingerprint_id)) {
      $this->db->where("fingerprint_id", $fingerprint_id);
    }
    $this->db->order_by("date", "asc");
    return $this->db->get();
    $query = $this->db->query($sql);
    return $query;
  }
  /**
   * attendance
   *
   *
   * @return static
   * @author madukubah
   */
  public function attendances($start = 0, $limit = NULL,  $fingerprint_id = NULL, $date = NULL)
  {
    if (isset($limit)) {
      $this->limit($limit);
    }
    $this->select($this->table . '.*');
    $this->select($this->table . '.date as _date');
    $this->select($this->table . '.time as _time');
    $this->select($this->table . '.timestamp as date');
    $this->select("employee.name as employee_name");
    $this->join(
      "employee",
      "employee.id = " . $this->table . '.employee_id',
      "inner"
    );
    $this->join(
      "fingerprint",
      "fingerprint.id = employee.fingerprint_id",
      "inner"
    );
    if ($fingerprint_id != NULL) {
      $this->where("fingerprint.id", $fingerprint_id);
    }
    if ($date != NULL) {
      $this->where("date", $date);
    }
    $this->offset($start);
    $this->order_by($this->table . '.date desc, ' . $this->table . '.employee_pin asc, ' . $this->table . '.time asc ', '');
    return $this->fetch_data();
  }


  #########################################
  public function employee_attendance($fingerprint_id = NULL, $month = NULL, $day = null, $year = null, $_is_coming = TRUE)
  {
    $come_out = ['time BETWEEN "12:01:00" AND "18:00:00" ', ' time BETWEEN "06:00:00" AND "12:00:00"'];
    $this->db->select([
      "*",
      $this->table . '.status'
    ]);
    $this->db->from("
          (
            SELECT  employee.name,employee.fingerprint_id , attendance.*, day( attendance.date ) as day , month( attendance.date ) as month ,year( attendance.date ) as year from attendance
              INNER JOIN employee 
            ON employee.id = attendance.employee_id
          ) 
          attendance
      ");
    // $this
    $this->db->where($come_out[$_is_coming],  NULL);
    if (isset($month)) {
      $this->db->where_in($this->table . ".month",  $month);
    } else {
      $this->db->where_in($this->table . ".month",  date("m"));
    }
    if (isset($fingerprint_id)) {
      $this->db->where("fingerprint_id", $fingerprint_id);
    }
    if (isset($day)) {
      $this->db->where("day", $day);
    }
    if ($year)
      $this->db->where("year", $year);
    $this->db->group_by('name');
    $this->db->order_by("date", "asc");
    $this->db->order_by("employee_id", "asc");
    return $this->db->get();
    $query = $this->db->query($sql);
    return $query;
  }
  public function get_attendances($fingerprint_id = NULL, $status = NULL, $month = NULL, $date = NULL, $_is_coming = TRUE)
  {
    $come_out = ['time BETWEEN "12:01:00" AND "18:00:00" ', ' time BETWEEN "06:00:00" AND "12:00:00"'];
    $this->db->select([
      "*",
      "attendance.date as _date",
      "attendance.time as _time",
    ]);
    $this->db->from("
          (
            SELECT faction as faction ,CONCAT('" . base_url() . "uploads/employee/" . "' , " . "employee.image) as _image,  employee.position, employee.pin, employee.name,employee.fingerprint_id , attendance.*, day( attendance.date ) as day , month( attendance.date ) as month, year( attendance.date ) as year  from attendance
              INNER JOIN employee 
            ON employee.id = attendance.employee_id
          ) 
          attendance
    ");
    // $this
    $this->db->where($come_out[$_is_coming],  NULL);
    if (isset($month)) {
      $this->db->where_in($this->table . ".month",  $month);
    } else {
      $this->db->where_in($this->table . ".month",  date("m"));
    }
    if ($fingerprint_id != null) {
      $this->db->where("fingerprint_id", $fingerprint_id);
    }
    if ($date != null) {
      $this->db->where("day", $date);
    }
    if ($month != null) {
      $this->db->where("month", $month);
    }
    if (is_array($status)) {
      $this->db->where_in("status", $status);
    } else
      $this->db->where("status", $status);
    $this->db->group_by('name');
    $this->db->order_by("date", "asc");
    $this->db->order_by("attendance.pin", "asc");
    return $this->db->get();
    $query = $this->db->query($sql);
    return $query;
  }
  public function get_absences($fingerprint_id = NULL, $month = NULL, $date = NULL, $_is_coming = TRUE)
  {
    $status = [0, 1, 2];
    $employees = $this->get_attendances($fingerprint_id, $status, $month, $date, $_is_coming)->result();
    $id = [];
    foreach ($employees as $key => $employee) {
      $id[] = $employee->employee_id;
    }
    // return (object) array("result" => []);
    $this->db->select('employee.*');
    $this->db->select('CONCAT(position.name, " " ,employee.position) AS main_position');
    $this->db->select('position.name AS position_name');
    $this->db->select('faction as faction');
    $this->db->select(" CONCAT( '" . base_url() . 'uploads/employee/' . "' , " . "employee.image )  as _image");
    $this->db->join(
      'position',
      'position.id = employee.position_id',
      'join'
    );
    $this->db->from('employee');
    if ($fingerprint_id != null)
      $this->db->where("fingerprint_id", $fingerprint_id);
    if (!empty($id))
      $this->db->where_not_in('employee.id', $id);

    // $this->db->order_by("pin", "asc");
    $this->db->order_by("employee.position_id", "asc");
    
    return $this->db->get();
  }
}
