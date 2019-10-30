<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fingerprint_model extends MY_Model
{
  protected $table = "fingerprint";

  function __construct()
  {
    parent::__construct($this->table);
    parent::set_join_key('fingerprint_id');
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

  /**
   * group
   *
   * @param int|array|null $id = id_fingerprint
   * @return static
   * @author madukubah
   */
  public function fingerprint($id = NULL)
  {
    if (isset($id)) {
      $this->where($this->table . '.id', $id);
    }

    $this->limit(1);
    $this->order_by($this->table . '.id', 'desc');

    $this->fingerprints();

    return $this;
  }
  /**
   * group
   *
   * @param int|array|null $id = id_fingerprint
   * @return static
   * @author madukubah
   */
  public function fingerprint_by_user_id($user_id = NULL)
  {
    $this->where($this->table . '.user_id', $user_id);

    $this->limit(1);
    $this->order_by($this->table . '.id', 'desc');

    $this->fingerprints();

    return $this;
  }
  // /**
  //  * fingerprint
  //  *
  //  *
  //  * @return static
  //  * @author madukubah
  //  */
  // public function fingerprint(  )
  // {

  //     $this->order_by($this->table.'.id', 'asc');
  //     return $this->fetch_data();
  // }

  /**
   * fingerprint
   *
   *
   * @return static
   * @author madukubah
   */
  public function fingerprints($start = 0, $limit = NULL, $data_param = NULL)
  {
    if (isset($limit)) {
      $this->limit($limit);
    }
    $this->select($this->table . '.*');
    $this->select("opd_category.name as opd_category_name");
    $this->join(
      "opd_category",
      "opd_category.id = " . $this->table . '.opd_category_id',
      "inner"
    );

    $this->offset($start);
    if ($data_param)
      $this->where($data_param);
    $this->order_by($this->table . '.id', 'asc');
    return $this->fetch_data();
  }

  /**
   * group
   *
   * @param int|array|null $id = id_attendance
   * @return static
   * @author madukubah
   */
  public function record_count_opd_category_id($opd_category_id = NULL)
  {

    if (isset($opd_category_id)) {
      $this->where($this->table . '.opd_category_id', $opd_category_id);
    }

    return $this->record_count();
  }
}
