<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Service extends Public_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('services/Attendance_services');
		$this->services = new Attendance_services;
		$this->load->model(array(
			'employee_model',
			'fingerprint_model',
			'opd_category_model',
		));
	}
	public function index()
	{
		$fingerprints = $this->fingerprint_model->fingerprints(  )->result();

		// $ids = array();
		// foreach( $fingerprints as $fingerprint )
		// {
		// 		$ids []= $fingerprint;
		// }
		$this->data["fingerprints"] = $fingerprints;
		// TODO : tampilkan landing page bagi user yang belum daftar


		$form_login["form_data"] = array(
				"identity" => array(
						'type' => 'text',
						'label' => "Email",
						"value" => NULL
				),
				"user_password" => array(
						'type' => 'password',
						'label' => "Password",
						"value" => NULL
				),
		);
		$form_login["form"] = $this->load->view('templates/form/plain_form_horizontal', $form_login, TRUE);

		$form_login = $this->load->view('templates/form/login_horizontal', $form_login, TRUE);
		$this->data["form_login"] =  $form_login;
		
		$this->render("landing_page");
		// redirect(base_url('auth/login'));
	}
}
