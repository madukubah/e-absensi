<?php
/**
 * Name:    Ion Auth
 * Author:  Ben Edmunds
 *           ben.edmunds@gmail.com
 *           @benedmunds
 *
 * Added Awesomeness: Phil Sturgeon
 *
 * Created:  10.01.2009
 *
 * Description:  Modified auth system based on redux_auth with extensive customization. This is basically what Redux Auth 2 should be.
 * Original Author name has been kept but that does not mean that the method has not been modified.
 *
 * Requirements: PHP5.6 or above
 *
 * @package    CodeIgniter-Ion-Auth
 * @author     Ben Edmunds
 * @link       http://github.com/benedmunds/CodeIgniter-Ion-Auth
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Ion_auth
 */
class Ion_auth
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
	/**
	 * account status ('not_activated', etc ...)
	 *
	 * @var string
	 **/
	protected $status;

	/**
	 * extra where
	 *
	 * @var array
	 **/
	public $_extra_where = [];

	/**
	 * extra set
	 *
	 * @var array
	 **/
	public $_extra_set = [];

	/**
	 * caching of users and their groups
	 *
	 * @var array
	 **/
	public $_cache_user_in_group;

	/**
	 * __construct
	 *
	 * @author Ben
	 */
	public function __construct()
	{
		$this->id		='';
		$this->identity		='';
		$this->first_name	="";
		$this->last_name	="";
		$this->phone		="";
		$this->address		="";
		$this->email		="";
		$this->group_id		= '';
		// Check compat first
		$this->check_compatibility();

		$this->config->load('ion_auth', TRUE);
		$this->load->library(['email']);
		$this->lang->load('ion_auth');
		$this->load->helper(['cookie', 'language','url']);

		$this->load->library('session');

		$this->load->model('ion_auth_model');

		$this->_cache_user_in_group =& $this->ion_auth_model->_cache_user_in_group;
	
		$email_config = $this->config->item('email_config', 'ion_auth');

		if ($this->config->item('use_ci_email', 'ion_auth') && isset($email_config) && is_array($email_config))
		{
			$this->email->initialize($email_config);
		}

		$this->ion_auth_model->trigger_events('library_constructor');
	}

	/**
	 * __call
	 *
	 * Acts as a simple way to call model methods without loads of stupid alias'
	 *
	 * @param string $method
	 * @param array  $arguments
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function __call($method, $arguments)
	{
		if (!method_exists( $this->ion_auth_model, $method) )
		{
			throw new Exception('Undefined method Ion_auth::' . $method . '() called');
		}
		if($method == 'create_user')
		{
			return call_user_func_array([$this, 'register'], $arguments);
		}
		if($method=='update_user')
		{
			return call_user_func_array([$this, 'update'], $arguments);
		}
		return call_user_func_array( [$this->ion_auth_model, $method], $arguments);
	}

	/**
	 * __get
	 *
	 * Enables the use of CI super-global without having to define an extra variable.
	 *
	 * I can't remember where I first saw this, so thank you if you are the original author. -Militis
	 *
	 * @param    string $var
	 *
	 * @return    mixed
	 */
	public function __get($var)
	{
		return get_instance()->$var;
	}

	/**
	 * Forgotten password feature
	 *
	 * @param string $identity
	 *
	 * @return array|bool
	 * @author Mathew
	 */
	public function forgotten_password($identity)
	{
		// Retrieve user information
		$user = $this->where($this->ion_auth_model->identity_column, $identity)
					 ->where('active', 1)
					 ->users()->row();

		if ($user)
		{
			// Generate code
			$code = $this->ion_auth_model->forgotten_password($identity);

			if ($code)
			{
				$data = [
					'identity' => $identity,
					'forgotten_password_code' => $code
				];

				if (!$this->config->item('use_ci_email', 'ion_auth'))
				{
					$this->set_message('forgot_password_successful');
					return $data;
				}
				else
				{
					$message = $this->load->view($this->config->item('email_templates', 'ion_auth') . $this->config->item('email_forgot_password', 'ion_auth'), $data, TRUE);
					$this->email->clear();
					$this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
					$this->email->to($user->email);
					$this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_forgotten_password_subject'));
					$this->email->message($message);

					if ($this->email->send())
					{
						$this->set_message('forgot_password_successful');
						return TRUE;
					}
				}
			}
		}

		$this->set_error('forgot_password_unsuccessful');
		return FALSE;
	}

	/**
	 * forgotten_password_check
	 *
	 * @param string $code
	 *
	 * @return object|bool
	 * @author Michael
	 */
	public function forgotten_password_check($code)
	{
		$user = $this->ion_auth_model->get_user_by_forgotten_password_code($code);

		if (!is_object($user))
		{
			$this->set_error('password_change_unsuccessful');
			return FALSE;
		}
		else
		{
			if ($this->config->item('forgot_password_expiration', 'ion_auth') > 0)
			{
				//Make sure it isn't expired
				$expiration = $this->config->item('forgot_password_expiration', 'ion_auth');
				if (time() - $user->forgotten_password_time > $expiration)
				{
					//it has expired
					$identity = $user->{$this->config->item('identity', 'ion_auth')};
					$this->ion_auth_model->clear_forgotten_password_code($identity);
					$this->set_error('password_change_unsuccessful');
					return FALSE;
				}
			}
			return $user;
		}
	}

	/**
	 * register
	 *
	 * @param string $identity
	 * @param string $password
	 * @param string $email
	 * @param array  $additional_data
	 * @param array  $group_ids
	 *
	 * @return int|array|bool The new user's ID if e-mail activation is disabled or Ion-Auth e-mail activation was
	 *                        completed; or an array of activation details if CI e-mail validation is enabled; or FALSE
	 *                        if the operation failed.
	 * @author Mathew
	 */
	public function register($identity, $password, $email, $additional_data = [], $group_ids = [])
	{
		$this->ion_auth_model->trigger_events('pre_account_creation');

		$email_activation = $this->config->item('email_activation', 'ion_auth');

		$id = $this->ion_auth_model->register($identity, $password, $email, $additional_data, $group_ids);

		if (!$email_activation)
		{
			if ($id !== FALSE)
			{
				$this->set_message('account_creation_successful');
				$this->ion_auth_model->trigger_events(['post_account_creation', 'post_account_creation_successful']);
				return $id;
			}
			else
			{
				$this->set_error('account_creation_unsuccessful');
				$this->ion_auth_model->trigger_events(['post_account_creation', 'post_account_creation_unsuccessful']);
				return FALSE;
			}
		}
		else
		{
			if (!$id)
			{
				$this->set_error('account_creation_unsuccessful');
				return FALSE;
			}

			// deactivate so the user must follow the activation flow
			$deactivate = $this->deactivate($id);

			// the deactivate method call adds a message, here we need to clear that
			$this->ion_auth_model->clear_messages();


			if (!$deactivate)
			{
				$this->set_error('deactivate_unsuccessful');
				$this->ion_auth_model->trigger_events(['post_account_creation', 'post_account_creation_unsuccessful']);
				return FALSE;
			}

			$activation_code = $this->ion_auth_model->activation_code;
			$identity        = $this->config->item('identity', 'ion_auth');
			$user            = $this->ion_auth_model->user($id)->row();

			$data = [
				'identity'   => $user->{$identity},
				'id'         => $user->id,
				'email'      => $email,
				'activation' => $activation_code,
			];
			if(!$this->config->item('use_ci_email', 'ion_auth'))
			{
				$this->ion_auth_model->trigger_events(['post_account_creation', 'post_account_creation_successful', 'activation_email_successful']);
				$this->set_message('activation_email_successful');
				return $data;
			}
			else
			{
				$message = $this->load->view($this->config->item('email_templates', 'ion_auth').$this->config->item('email_activate', 'ion_auth'), $data, true);

				$this->email->clear();
				$this->email->from($this->config->item('admin_email', 'ion_auth'), $this->config->item('site_title', 'ion_auth'));
				$this->email->to($email);
				$this->email->subject($this->config->item('site_title', 'ion_auth') . ' - ' . $this->lang->line('email_activation_subject'));
				$this->email->message($message);

				if ($this->email->send() === TRUE)
				{
					$this->ion_auth_model->trigger_events(['post_account_creation', 'post_account_creation_successful', 'activation_email_successful']);
					$this->set_message('activation_email_successful');
					return $id;
				}

			}

			$this->ion_auth_model->trigger_events(['post_account_creation', 'post_account_creation_unsuccessful', 'activation_email_unsuccessful']);
			$this->set_error('activation_email_unsuccessful');
			return FALSE;
		}
	}

	/**
	 * Logout
	 *
	 * @return true
	 * @author Mathew
	 **/
	public function logout()
	{
		$this->ion_auth_model->trigger_events('logout');

		$identity = $this->config->item('identity', 'ion_auth');

		$this->session->unset_userdata([$identity, 'id', 'user_id']);

		// delete the remember me cookies if they exist
		delete_cookie($this->config->item('remember_cookie_name', 'ion_auth'));

		// Clear all codes
		$this->ion_auth_model->clear_forgotten_password_code($identity);
		$this->ion_auth_model->clear_remember_code($identity);

		// Destroy the session
		$this->session->sess_destroy();

		// Recreate the session
		session_start();
		$this->session->sess_regenerate(TRUE);

		$this->set_message('logout_successful');
		return TRUE;
	}

	/**
	 * Auto logs-in the user if they are remembered
	 * @return bool Whether the user is logged in
	 * @author Mathew
	 **/
	public function logged_in()
	{
		$this->ion_auth_model->trigger_events('logged_in');

		$recheck = $this->ion_auth_model->recheck_session();

		// auto-login the user if they are remembered
		if (!$recheck && get_cookie($this->config->item('remember_cookie_name', 'ion_auth')))
		{
			$recheck = $this->ion_auth_model->login_remembered_user();
		}

		return $recheck;
	}

	/**
	 * @return int|null The user's ID from the session user data or NULL if not found
	 * @author jrmadsen67
	 **/
	public function get_user_id()
	{
		$user_id = $this->session->userdata('user_id');
		if (!empty($user_id))
		{
			return $user_id;
		}
		return NULL;
	}

	/**
	 * @param int|string|bool $id
	 *
	 * @return bool Whether the user is an administrator
	 * @author Ben Edmunds
	 */
	public function is_admin($id = FALSE)
	{
		$this->ion_auth_model->trigger_events('is_admin');

		$admin_group = $this->config->item('admin_group', 'ion_auth');

		return $this->ion_auth_model->in_group($admin_group, $id);
	}

	/**
	 * Check the compatibility with the server
	 *
	 * Script will die in case of error
	 */
	protected function check_compatibility()
	{
		// PHP password_* function sanity check
		if (!function_exists('password_hash') || !function_exists('password_verify'))
		{
			show_error("PHP function password_hash or password_verify not found. " .
				"Are you using CI 2 and PHP < 5.5? " .
				"Please upgrade to CI 3, or PHP >= 5.5 " .
				"or use password_compat (https://github.com/ircmaxell/password_compat).");
		}

		// Sanity check for CI2
		if (substr(CI_VERSION, 0, 1) === '2')
		{
			show_error("Ion Auth 3 requires CodeIgniter 3. Update to CI 3 or downgrade to Ion Auth 2.");
		}

		// Compatibility check for CSPRNG
		// See functions used in Ion_auth_model::_random_token()
		if (!function_exists('random_bytes') && !function_exists('mcrypt_create_iv') && !function_exists('openssl_random_pseudo_bytes'))
		{
			show_error("No CSPRNG functions to generate random enough token. " .
				"Please update to PHP 7 or use random_compat (https://github.com/paragonie/random_compat).");
		}
	}

	public function deactivate($id = NULL)
	{
		$this->trigger_events('deactivate');

		if (!isset($id))
		{
			$this->set_error('deactivate_unsuccessful');
			return FALSE;
		}
		else if ($this->logged_in() && $this->user()->row()->id == $id)
		{
			$this->set_error('deactivate_current_user_unsuccessful');
			return FALSE;
		}

		return $this->ion_auth_model->deactivate($id);
	}

	/**
	 * upload_photo
	 *
	 * @return true
	 * @author alanHetfielD
	 **/
	public function upload_photo( $file )
	{
		$success = FALSE;

		$user = $this->ion_auth->user()->row();//curr user
		$upload = $this->config->item('upload', 'ion_auth');

		if( strlen( $file ) > 200 )//gambar adalalah base64
		{
			$_data =  $file;
			list($type, $_data) = explode(';', $_data);
			list(, $_data)      = explode(',', $_data);
			$_data = base64_decode($_data);
			
			
			$file_name = $upload['file_name'].$user->id."_".time().'.jpg';
			$upload_path = $upload['upload_path'];

			if( file_put_contents($upload_path.$file_name, $_data) )
			{
				$data['image'] 			= $file_name;
				$success = TRUE;
			}
		}
		else
		{
			$config                         = $upload;
			$config['file_name'] 			=  $config['file_name'].$user->id."_".time();

			$this->load->library('upload', $config);
			if ( ! $this->upload->do_upload( $file ) )
			{
				$this->set_error( $this->upload->display_errors() );
				$this->set_error( 'upload_unsuccessful' );
				return FALSE;
			}
			else
			{
				$file_data = $this->upload->data();
				$data['image'] = $file_data['file_name'];
				$success = TRUE;
			}
		}

		if( $success )
		{
			if ( $this->ion_auth_model->update( $user->id, $data) )
			{
				$this->set_message('upload_successful');
				$this->remove_photo( $user->image );
				$this->session->set_userdata(array( 'user_image'=> $data['image'] ) ) ;
				return TRUE;
			}
		}

		
		$this->set_error( 'upload_unsuccessful' );
		return FALSE;
	}
	/**
	 * remove images
	 *param string| $file
	 *param string| $table
	 * @return true
	 * @author alanHetfielD
	 **/
	public function remove_photo( $file_name )
	{
		$upload = $this->config->item('upload', 'ion_auth');
		$config['upload_path']          = $upload['upload_path'];

		return @unlink( $config['upload_path'].$file_name );
	}
	/**
	 * update
	 *
	 * @return true
	 * @author alanHetfielD
	 **/
	public function update( $id_user, $data)
	{
		if (array_key_exists('old_password', $data))
		{
			$user = $this->user($id_user)->row();
			if( !$this->ion_auth_model->verify_password( $data["old_password"] , $user->password) )
			{
				$this->set_error('old_password_incorrect');
				$this->set_error('update_unsuccessful');
				return FALSE;
			}
		}
		return $this->ion_auth_model->update( $id_user, $data) ;
	}

	/**
	 * get_validation_config
	 *
	 * @return array
	 * @author alanHetfielD
	 **/
	public function get_validation_config()
	{
		$tables = $this->config->item('tables', 'ion_auth');
		$identity_column = $this->config->item('identity', 'ion_auth');

		$config = array(
			array(
				'field' => 'first_name',
				 'label' => 'Nama Depan',
				 'rules' =>  'trim|required',
			),
			array(
				'field' => 'last_name',
				 'label' => 'Nama Belakang',
				 'rules' =>  'trim|required',
			),
			array(
				'field' => 'email',
				 'label' => 'Email',
				 'rules' =>  'trim|required|valid_email',
			),
			array(
				'field' => 'phone',
				 'label' =>('No Telepon'),
				 'rules' =>  'trim|required',
			),
			array(
				'field' => 'address',
				 'label' => 'Alamat',
				 'rules' =>  'trim|required',
			),
			array(
				'field' => 'password',
				 'label' => "Kata Sandi",
				 'rules' =>  'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]',
			 ),
			 array(
				'field' => 'password_confirm',
				 'label' => "konfirmasi Kata Sandi",
				 'rules' =>  'trim|required',
			 ),
			 array(
				'field' => 'group_id',
				 'label' => "User Group",
				 'rules' =>  'trim|required',
			 ),
		);
		if( $this->router->fetch_method() == "edit" )
		{
			unset($config[7]);
			
		}
		unset($config[6]);
		unset($config[5]);
		
		return $config;
	}
	/**
	 * get_form_data
	 *
	 * @return array
	 * @author madukubah
	 **/
	public function get_form_data( $user_id = -1 )
	{
		if( $user_id != -1 )
		{
			$user 				= $this->ion_auth_model->user( $user_id )->row();
			$this->identity		= $user->username;
			$this->first_name	= $user->first_name;
			$this->last_name	= $user->last_name;
			$this->phone		= $user->phone;
			$this->id			= $user->user_id;
			$this->email		= $user->email;
			$this->group_id		= $user->group_id;
			$this->address		= $user->address;
		}
		// echo var_dump($user);

		$groups = $this->ion_auth_model->groups(  )->result();

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
			if( $group->id == 1 ) continue;
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
			"address" => array(
				'type' => 'text',
				'label' => "Alamat",
				'value' => $this->form_validation->set_value('address', $this->address),			  
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
				'type' => 'select',
				'label' => "User Group",
				'options' => $group_select,
				'selected' => $this->group_id,
			),
		  );
		return $_data;
	}
}
