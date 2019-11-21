<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends Opd_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'bkd';
	private $current_page = 'bkd/home/';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/Attendance_services');
		$this->services = new Attendance_services;
		$this->load->model(array(
			'employee_model',
			'fingerprint_model',
			'attendance_model',
		));
	}

	public function index()
	{
		// var_dump( $this->data["fingerprint"] );return;
		$fingerprint = $this->data["fingerprint"];
		$fingerprint_id = $this->data["fingerprint"]->id;
		$month = ($this->input->get('month', date("m"))) ? $this->input->get('month', date("m")) : date("m");
		$month = (int) $month;
		#######################################################
		$this->data['chart'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $fingerprint_id . "?group_by=date&month=" . $month . '&is_coming=1')));
		// echo var_dump( $this->data['chart'] ) ; return;
		$bar = $this->load->view('templates/chart/bar', $this->data['chart'], true);
		// $this->data['chart'] = $chart;

		$this->data['pie'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $fingerprint_id . "?group_by=date&month=" . $month . '&is_coming=1')));
		$pie = $this->load->view('templates/chart/pie', $this->data['pie'], true);
		// $this->data['pie'] = $pie;
		#######################################################
		$this->data['chart_out'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $fingerprint_id . "?group_by=date&month=" . $month . '&is_coming=0')));
		// echo var_dump( $this->data['chart'] ) ; return;
		$bar_out = $this->load->view('templates/chart/bar_out', $this->data['chart_out'], true);
		// $this->data['chart'] = $chart;

		$this->data['pie_out'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $fingerprint_id . "?group_by=date&month=" . $month . '&is_coming=0')));
		$pie_out = $this->load->view('templates/chart/pie_out', $this->data['pie_out'], true);
		// $this->data['pie'] = $pie;
		######################################################
		$this->data["contents"] = $bar . " " . $pie . $bar_out . " " . $pie_out;
		$form_data["form_data"] = array(
			"month" => array(
				'type' => 'select',
				'label' => "Bulan",
				'options' => Util::MONTH,
				'selected' => $month,
			),
			"group_by" => array(
				'type' => 'hidden',
				'label' => "Bulan",
				'value' => "date"
			),
		);
		$form_data["form_data"] = $this->load->view('templates/form/plain_form', $form_data, TRUE);
		$form_data = $this->load->view('templates/form/attendance', $form_data, TRUE);
		$this->data["header_button"] =  $form_data;
		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Data Absensi " . $fingerprint->name . " Bulan " . Util::MONTH[$month];
		$this->data["header"] = "Data Absensi " . $fingerprint->name . " Bulan " . Util::MONTH[$month];
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("opd/dashboard/plain_content");
	}
}
