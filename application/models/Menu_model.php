<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_model extends MY_Model
{
    protected $table = "menus";
    protected $menu_list = array();

    function __construct() {
        parent::__construct( $this->table );
        parent::set_join_key( 'menu_id' );
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
      if( !$this->delete_foreign( $data_param, ['menu_model'] ) )
      {
        $this->set_error("gagal");//('menu_delete_unsuccessful');
        return FALSE;
      }
      //foreign
      $this->db->trans_begin();

      $this->db->delete($this->table, $data_param );
      if ($this->db->trans_status() === FALSE)
      {
        $this->db->trans_rollback();

        $this->set_error("gagal");//('menu_delete_unsuccessful');
        return FALSE;
      }

      $this->db->trans_commit();

      $this->set_message("berhasil");//('menu_delete_successful');
      return TRUE;
  }

  	/**
	 * menu
	 *
	 * @param int|array|null $id = id_menus
	 * @return static
	 * @author madukubah
	 */
	public function menu( $id = NULL  )
  {
      if (isset($id))
      {
        $this->where($this->table.'.id', $id);
      }

      $this->limit(1);
      $this->order_by($this->table.'.id', 'desc');

      $this->menus(  );

      return $this;
  }
  /**
   * menus
   *
   *
   * @return static
   * @author madukubah
   */
  public function menus( $menu_id = NULL )
  {
      if( isset( $menu_id ) )
      {
        $this->where($this->table.'.menu_id', $menu_id);
      }
      $this->order_by($this->table.'.position', 'asc');
      return $this->fetch_data();
  }

  ################################################################################
  /**
	 * tree
	 *
	 * @param int  $account_id
	 * @return tree array
	 * @author madukubah
	 */
	public function tree( $menu_id = 0 )
  {	
      $tree = $this->menus( $menu_id )->result();
      // echo json_encode( $tree );
      // echo "<br>";
      if( empty( $tree ) )
      {
        return array();
      }
      foreach( $tree as $branch )
      {
        
				$this->menu_list[] = $branch;	
        $branch->branch = $this->tree( $branch->id );
      }

      return $tree;
  }
  /**
	 * tree
	 *
	 * @param int  $account_id
	 * @return tree array
	 * @author madukubah
	 */
	public function get_menu_list( )
  {	
      return $this->menu_list;
  }
################################################################################

}
?>
