<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class User extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
    }

    public function users_get()
    {
        $users = $this->ion_auth->users(  )->result();
        $this->set_response($users, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
    }

    public function login_post()
    {
        // $users = $this->ion_auth->users(  )->result();
        $identity = $this->input->post('identity');
        $password = $this->input->post('password');
        $this->ion_auth->set_message_delimiters( '', '' );
        $this->ion_auth->set_error_delimiters( '', '' );
        if(  ( $user_data =  $this->ion_auth->login_api( $identity, $password ) )  != FALSE )
        {
            $result = array(
                "message" => $this->ion_auth->messages(),
                "status" => 1,
                "user_data" => $user_data,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
        }
        else
        {
            $result = array(
                "message" => $this->ion_auth->errors(),
                "status" => 0,
                "user_data" => array(),
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code            
        }
    }

    public function register_post()
    {
        $this->load->library( array( 'form_validation' ) ); 

        $this->ion_auth->set_message_delimiters( ' ', ' ' );
        $this->ion_auth->set_error_delimiters( ' ', ' ' );
        
        $identity_column = $this->config->item('identity', 'ion_auth');
		$this->form_validation->set_rules( $this->ion_auth->get_validation_config() );
		// $this->form_validation->set_rules('phone', "No Telepon", 'trim|required|is_unique[' . 'users' . '.' . $identity_column . ']');
		// $this->form_validation->set_rules('password', "Kata Sandi", 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
		// $this->form_validation->set_rules('password_confirm', "konfirmasi Kata Sandi", 'trim|required');
		if ($this->form_validation->run() === TRUE)
		{
            $group_id = 2;
            if( $this->input->post('group_id') != NULL ) $group_id = $this->input->post('group_id');

			$email = $this->input->post('email') ;
            $identity = $email;
            $password = substr( $email, 0, strpos( $identity, "@" ) ) ;
			

			$additional_data = array(
				'first_name'    => $this->input->post('first_name'),
				'last_name'     => $this->input->post('last_name'),
				'email'         => $this->input->post('email'),
				'phone'         => $this->input->post('phone'),
			);
        }
        if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data, $group_id) )
        {			
            $result = array(
                "message" => $this->ion_auth->messages(),
                "status" => 1,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
        }
        else
        {
            $message = ( validation_errors() ? validation_errors() : ( $this->ion_auth->errors() ? $this->ion_auth->errors() : '' ) );
            $message = str_replace( '<p>', ' ', $message );
            $message = str_replace( '</p>', ' ', $message );
            $message = str_replace( '<b>', ' ', $message );
            $message = str_replace( '</b>', ' ', $message );
            $result = array(
                "message" =>  $message, 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code            
        }
    }
    public function update_post()
    {
        // $this->set_response( $this->ion_auth->user( $this->input->post('user_id') )->result()  , REST_Controller::HTTP_OK); 
        // return;
        $this->load->library( array( 'form_validation' ) ); 

        $this->ion_auth->set_message_delimiters( ' ', ' ' );
        $this->ion_auth->set_error_delimiters( ' ', ' ' );

		$this->form_validation->set_rules('first_name',  $this->lang->line('create_user_validation_fname_label'), 'trim|required');
		$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label') , 'trim|required');
		$this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim|required');
		$this->form_validation->set_rules('address', 'Alamat', 'trim|required');

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
				'address' => $this->input->post('address'),
			);
			if ( $this->input->post('password') )
			{
				$data['password'] = $this->input->post('password');
				$data['old_password'] = $this->input->post('old_password');
			}
			// check to see if we are updating the user
			if ( $this->ion_auth->update( $this->input->post('user_id') , $data) )
			{
                $result = array(
                    "message" => $this->ion_auth->messages(),
                    "status" => 1,
                );
                $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
    
                return;
			}
		}
        $message = ( validation_errors() ? validation_errors() : ( $this->ion_auth->errors() ? $this->ion_auth->errors() : '' ) );
        $message = str_replace( '<p>', ' ', $message );
        $message = str_replace( '</p>', ' ', $message );
        $message = str_replace( '<b>', ' ', $message );
        $message = str_replace( '</b>', ' ', $message );
        $result = array(
            "message" =>  $message, 
            "status" => 0,
        );
        $this->set_response( $result , REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code            
    }

}
