<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends Public_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library('services/Attendance_services');
		$this->services = new Attendance_services;
		$this->load->model(array(
			'fingerprint_model',
			'attendance_model',
		));
	}
	public function index()
	{
		// TODO : tampilkan landing page bagi user yang belum daftar
		// $this->render("landing_page");
		redirect(base_url('auth/login'));
	}

	public function view($status = NULL)
	{
		$fingerprint_id = ($this->input->get('fingerprint_id') == -1) ? NULL : $this->input->get('fingerprint_id');
		$fingerprint = (object) array();
		if ($fingerprint_id)
			$fingerprint = $this->fingerprint_model->fingerprint($fingerprint_id)->row();
		else
			$fingerprint->name = 'Semua OPD';

		$date = $this->input->get('date');
		$month = $this->input->get('month');
		#################################################################3

		if ($status != 3) {
			$table = $this->services->table_config_view();
			$table["rows"] = $this->attendance_model->get_attendances($fingerprint_id, $status, $month, $date)->result();
		} else {
			$table['header'] = array(
				'name' => 'Nama Karyawan',
				'pin' => 'Kode Pin',
				'faction' => 'Jenis Pegawai',
				'_image' => 'Foto Pegawai',
			);
			$table["number"] = 1;
			$table["rows"] = $this->attendance_model->get_absences($fingerprint_id, $month, $date)->result();
		}
		// var_dump( $table["rows"] ); die;
		$table['index'] = ['Hadir', 'Sakit', 'Izin'];
		$table['faction'] = ['Non PNS', 'PNS'];

		$table = $this->load->view('templates/tables/plain_table_image_attendance', $table, true);
		$this->data["contents"] = $table;
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
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		// $this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Data Absensi " . $fingerprint->name;
		$this->data["header"] = "Data Absensi " . $fingerprint->name;
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("templates/contents/plain_content");
	}
}
