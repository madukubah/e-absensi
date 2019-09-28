<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Attendance_model extends MY_Model
{
  protected $table = "attendance";

  function __construct() {
      parent::__construct( $this->table );
      parent::set_join_key( 'attendance_id' );
  }

  /**
   * create
   *
   * @param array  $data
   * @return static
   * @author madukubah
   */
  public function create( $data )
  {
      // Filter the data passed
      $data = $this->_filter_data($this->table, $data);
      
      $this->db->insert($this->table, $data);
      $id = $this->db->insert_id($this->table . '_id_seq');
    
      if( isset($id) )
      {
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
  public function create_batch( $data_batch )
  {
    $this->db->trans_begin();

    $this->db->insert_batch($this->table, $data_batch);
    if ($this->db->trans_status() === FALSE)
    {
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
  public function update( $data, $data_param  )
  {
    $this->db->trans_begin();
    $data = $this->_filter_data($this->table, $data);

    $this->db->update($this->table, $data, $data_param );
    if ($this->db->trans_status() === FALSE)
    {
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
  public function delete( $data_param  )
  {
    //foreign
    //delete_foreign( $data_param. $models[]  )
    if( !$this->delete_foreign( $data_param ) )
    {
      $this->set_error("gagal");//('group_delete_unsuccessful');
      return FALSE;
    }
    //foreign
    $this->db->trans_begin();

    $this->db->delete($this->table, $data_param );
    if ($this->db->trans_status() === FALSE)
    {
      $this->db->trans_rollback();

      $this->set_error("gagal");//('group_delete_unsuccessful');
      return FALSE;
    }

    $this->db->trans_commit();

    $this->set_message("berhasil");//('group_delete_successful');
    return TRUE;
  }

    /**
   * group
   *
   * @param int|array|null $id = id_attendance
   * @return static
   * @author madukubah
   */
  public function attendance( $id = NULL  )
  {
      if (isset($id))
      {
        $this->where($this->table.'.id', $id);
      }

      $this->limit(1);
      $this->order_by($this->table.'.id', 'desc');

      $this->attendances(  );

      return $this;
  }

   /**
   * group
   *
   * @param int|array|null $id = id_attendance
   * @return static
   * @author madukubah
   */
  public function attendance_by_pindate( $pin, $date  )
  {
      $this->where($this->table.'.employee_pin', $pin);
      $this->where($this->table.'.date', $date);

      $this->limit(1);
      $this->order_by($this->table.'.id', 'desc');

      $this->attendances(  );

      return $this;
  }

  /**
   * group
   *
   * @param int|array|null $id = id_attendance
   * @return static
   * @author madukubah
   */
  public function record_count_fingerprint_id( $fingerprint_id  )
  {
      // $this->db->distinct();
      $this->db->join( 
        "employee",
        "employee.pin = ".$this->table.'.employee_pin',
        "inner"
      );
      $this->db->where( 'employee.fingerprint_id', $fingerprint_id);

      return  $this->db->count_all_results( $this->table );

  }
  /**
   * attendance
   *
   *
   * @return static
   * @author madukubah
   */
  public function accumulation( $fingerprint_id , $group_by = NULL, $month = NULL, $employee_ids = NULL, $_is_coming = TRUE )
  {
      $come_out = [ 'time BETWEEN "12:01:00" AND "18:00:00" ' , ' time BETWEEN "06:00:00" AND "12:00:00"' ];
      $_group = array(
        'date' => $this->table.".date",
        'month' => "month",
      );
      $this->db->select( [
        "*",
        "count(*) as count_attendance",
      ] );
      $this->db->from( "
          (
            SELECT employee.id as employee_id, employee.name,employee.fingerprint_id , attendance.*, day( attendance.date ) as day , month( attendance.date ) as month  from attendance
              INNER JOIN employee 
            ON employee.pin = attendance.employee_pin
          ) 
          attendance
      " );
      // $this
      $this->db->where( $come_out[ $_is_coming ] ,  NULL );
      if ( isset($month)  )
      {
        $this->db->where_in( $this->table.".month",  $month );
          
      }else{
        $this->db->where_in( $this->table.".month",  date("m") );
      }
      if ( isset( $group_by ) )
      {
        foreach( $group_by as $group )
        {
          $this->db->group_by( $_group[ $group ] );	
        }
      }

      if ( isset( $employee_ids ) )
      {
          foreach( $employee_ids as $employee_id )
          {
             $this->db->where( "employee_id", $employee_id );	 
          }
      }
      $this->db->where( "fingerprint_id", $fingerprint_id );	 
      $this->db->order_by( "date", "asc" );
      return $this->db->get( ) ;

      $query = $this->db->query(  $sql );
      return $query;
  }

  /**
   * attendance
   *
   *
   * @return static
   * @author madukubah
   */
  public function attendances( $start = 0 , $limit = NULL,  $fingerprint_id = NULL )
  {
      if (isset( $limit ))
      {
        $this->limit( $limit );
      }

      $this->select( $this->table.'.*' );
      $this->select( $this->table.'.date as _date' );
      $this->select( $this->table.'.time as _time' );
      $this->select( $this->table.'.timestamp as date' );
      $this->select( "employee.name as employee_name" );
      $this->join( 
        "employee" ,
        "employee.pin = " .$this->table.'.employee_pin' ,
        "inner"
      );
      $this->join( 
        "fingerprint" ,
        "fingerprint.id = employee.fingerprint_id",
        "inner"
      );

      if( $fingerprint_id != NULL )
      {
        $this->where( "fingerprint.id", $fingerprint_id );
      }
      $this->offset( $start );
      $this->order_by($this->table.'.date asc, '.$this->table.'.employee_pin asc, '.$this->table.'.time asc ', '');
      return $this->fetch_data();
  }

}
?>