<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends User_Controller {
	private $services = null;
    private $name = null;
    private $parent_page = 'user';
	private $current_page = 'user/profile/';

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('url', 'language'));
		$this->lang->load('auth');

		$this->load->library('services/User_services');
		$this->services = new User_services;
		
	} 
	public function index()
	{
		$user_id = $this->ion_auth->get_user_id();
		$form_data = $this->services->get_form_data_readonly(  $user_id );
		$form_data = $this->load->view('templates/form/plain_form_readonly', $form_data , TRUE ) ;

		$this->data[ "user" ] =  $this->ion_auth->user()->row();
		$this->data[ "contents" ] =  $form_data;
		
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Data Akun ";
		$this->data["header"] = "Data Akun ";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render( "user/profile/content" );
	}
	// public function upload_photo()
	// {
	// 	if ( ! $this->ion_auth->upload_photo( 'user_image' ) )
	// 	{
	// 			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER,  $this->ion_auth->errors() ) );
	// 			redirect(site_url('user/profile'));
	// 	}
	// 	else
	// 	{
	// 			$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->ion_auth->messages() ) );
	// 			redirect(site_url('user/profile'));
	// 	}
	// }
	public function upload_photo()
	{
		if ( ! $this->ion_auth->upload_photo( ( "image" ) ) )
		{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER,  $this->ion_auth->errors() ) );
				redirect(site_url('user/profile'));
		}
		else
		{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->ion_auth->messages() ) );
				redirect(site_url('user/profile'));
		}
	}
	public function edit() //edut curr profile
	{
		$user_id = $this->ion_auth->get_user_id();

		$this->data[ "page_title" ] = "Edit Profile";
		$this->form_validation->set_rules('first_name',  $this->lang->line('create_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label') , 'trim|required');
		$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim|required');

		if( !empty( $this->input->post('password') )  )
		{
			$this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label') , 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
			$this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'trim|required');
			$this->form_validation->set_rules('old_password', $this->lang->line('create_user_validation_old_password_confirm_label'), 'trim|required');
		}

		if ( $this->form_validation->run() === TRUE )
		{
			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'phone' => $this->input->post('phone'),
				'email' => $this->input->post('email'),
			);
			if ( $this->input->post('password') )
			{
				$data['password'] = $this->input->post('password');
				$data['old_password'] = $this->input->post('old_password');
			}

			$user = $this->ion_auth->user()->row();//curr user
			// check to see if we are updating the user
			if ( $this->ion_auth->update( $user->id, $data) )
			{
				// redirect them back to the admin page if admin, or to the base url if non admin
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->ion_auth->messages() ) );
				if ( $this->input->post('password') )
				{
					redirect(site_url('auth/logout'));
				}
				redirect(site_url('user/profile'));
			}
			else
			{
				// redirect them back to the admin page if admin, or to the base url if non admin
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->ion_auth->errors() ) );
				redirect(site_url('user/profile'));
			}
		}
		else
		{
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
            if(  !empty( validation_errors() ) || $this->ion_auth->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );

            $alert = $this->session->flashdata('alert');
			$this->data["key"] = $this->input->get('key', FALSE);
			$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
			$this->data["current_page"] = $this->current_page;
			$this->data["block_header"] = "Edit Akun ";
			$this->data["header"] = "Edit Akun ";
			$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';

            $form_data = $this->ion_auth->get_form_data( $user_id );
			$form_password[ 'form_data' ] = array(
				"old_password" => array(
					'type' => 'password',
					'label' => "Password lama",
				),
				"password" => array(
				  'type' => 'password',
				  'label' => "Password",
				),
				"password_confirm" => array(
				  'type' => 'password',
				  'label' => "Konfirmasi Password",
				),
			);
			$form_data[ 'form_data' ] = array_merge( $form_data[ 'form_data' ] , $form_password[ 'form_data' ] );
			unset( $form_data[ 'form_data' ]["group_id"] );
			$form_data = $this->load->view('templates/form/plain_form', $form_data , TRUE ) ;

			$this->data[ "user" ] =  $this->ion_auth->user()->row();
            $this->data[ "contents" ] =  $form_data;
			
			$edit_photo = array(
				"name" => "Ganti Foto",
				"modal_id" => "edit_photo_",
				"button_color" => "primary",
				"url" => site_url( $this->current_page."upload_photo/"),
				"form_data" => array(
					"image" => array(
						'type' => 'file',
						'label' => "Foto",
						'value' => "",	
					),
				'data' => NULL
				),
			);
	
			$edit_photo= $this->load->view('templates/actions/modal_form_multipart', $edit_photo, true ); 
	
			$this->data[ "edit_photo" ] =  $edit_photo ;

            $this->render( "user/profile/content_form" );
		}
	}
}
