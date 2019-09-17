<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User_services
{
  // user var
	protected $id;
	protected $identity;
	protected $first_name;
	protected $last_name;
	protected $phone;
	protected $address;
	protected $email;
  protected $group_id;
  
  function __construct()
  {
      $this->id		      ='';
      $this->identity		='';
      $this->first_name	="";
      $this->last_name	="";
      $this->phone		  ="";
      $this->address		="";
      $this->email		  ="";
      $this->group_id		= '';
  }

  public function __get($var)
  {
    return get_instance()->$var;
  }
  
  public function groups_table_config( $_page, $start_number = 1 )
  {
    $table["header"] = array(
			'username' => 'username',
			'group_name' => 'Group',
			'user_fullname' => 'Nama Lengkap',
			'phone' => 'No Telepon',
			'email' => 'Email',
		  );
		  $table["number"] = $start_number ;
		  $table[ "action" ] = array(
			array(
			  "name" => "Detail",
			  "type" => "link",
			  "url" => site_url($_page."detail/"),
			  "button_color" => "primary",
			  "param" => "id",
			),
			array(
			  "name" => "Edit",
			  "type" => "link",
			  "url" => site_url($_page."edit/"),
			  "button_color" => "primary",
			  "param" => "id",
			),
			array(
			  "name" => 'X',
			  "type" => "modal_delete",
			  "modal_id" => "delete_category_",
			  "url" => site_url( $_page."delete/"),
			  "button_color" => "danger",
			  "param" => "id",
			  "form_data" => array(
				"id" => array(
				  'type' => 'hidden',
				  'label' => "id",
				),
			  ),
			  "title" => "User",
			  "data_name" => "user_fullname",
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

  /**
	 * get_form_data
	 *
	 * @return array
	 * @author madukubah
	 **/
	public function get_form_data_readonly( $user_id = -1 )
	{
		if( $user_id != -1 )
		{
			$user 				= $this->ion_auth_model->user( $user_id )->row();
			$this->identity		=$user->username;
			$this->first_name	=$user->first_name;
			$this->last_name	=$user->last_name;
			$this->phone		=$user->phone;
			$this->id			=$user->user_id;
			$this->email		=$user->email;
			$this->group_id		=$user->group_id;
		}

		$groups =$this->ion_auth_model->groups(  )->result();

		$group_options ="";
		foreach($groups as $n => $item)
		{	
			
			$group_options .= form_radio("group_id", $item->id ,set_checkbox('group_id', $item->id), ' id="basic_checkbox_'.$n.'"');
			$group_options .= '<label for="basic_checkbox_'.$n.'"> '. $item->name .'</label><br>';
		}
		$data['groups'] = $group_options;
		$group_select = array();
		foreach( $groups as $group )
		{
			// if( $group->id == 1 ) continue;
			$group_select[ $group->id ] = $group->name;
		}

		$_data["form_data"] = array(
			"id" => array(
				'type' => 'hidden',
				'label' => "ID",
				'value' => $this->form_validation->set_value('id', $this->id),
			  ),
			"first_name" => array(
			  'type' => 'text',
			  'label' => "Nama Depan",
			  'value' => $this->form_validation->set_value('first_name', $this->first_name),
			),
			"last_name" => array(
			  'type' => 'text',
			  'label' => "Nama Belakang",
			  'value' => $this->form_validation->set_value('last_name', $this->last_name),
			  
			),
			"email" => array(
			  'type' => 'text',
			  'label' => "Email",
			  'value' => $this->form_validation->set_value('email', $this->email),			  
			),
			"phone" => array(
			  'type' => 'number',
			  'label' => "Nomor Telepon",
			  'value' => $this->form_validation->set_value('phone', $this->phone),			  
			),
			"group_id" => array(
				'type' => 'text',
				'label' => "User Group",
				'value' => $group_select[ $this->group_id ],
			),
		  );
		return $_data;
	}
}
?>
