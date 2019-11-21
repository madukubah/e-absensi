<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . "/libraries/Util.php";

class Attendance extends Opd_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'opd';
	private $current_page = 'opd/attendance/';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/Attendance_services');
		$this->load->library('services/Excel_services');
		$this->services = new Attendance_services;
		$this->excel = new Excel_services;
		$this->load->model(array(
			'fingerprint_model',
			'attendance_model',
			'employee_model',
		));
	}


	public function index()
	{
		$date = ($this->input->get('date', date("d"))) ? $this->input->get('date', date("d")) : NULL;
		$date = (int) $date;
		$month = ($this->input->get('month', date("m"))) ? $this->input->get('month', date("m")) : NULL;
		$month = (int) $month;
		$year = ($this->input->get('year', date("Y"))) ? $this->input->get('year', date("Y")) : NULL;
		$year = (int) $year;
		#############################################################
		$this->data["menu_list_id"] = "attendance_index"; //overwrite menu_list_id
		$fingerprint = $this->data["fingerprint"];
		$fingerprint_id = $this->data["fingerprint"]->id;


		$fingerprint = $this->fingerprint_model->fingerprint($fingerprint_id)->row();

		$page = ($this->uri->segment(4)) ? ($this->uri->segment(4) -  1) : 0;
		// echo $page; return;
		//pagination parameter
		$pagination['base_url'] = base_url($this->current_page) . "index/";
		$pagination['total_records'] = $this->attendance_model->record_count_fingerprint_id($fingerprint_id);
		if ($date && $month && $year) {
			$pagination['total_records'] = $this->attendance_model->record_count_filter_fingerprint_id($fingerprint_id, $year . '-' . $month . '-' . $date);
		}
		// echo var_dump( $page );return;
		$pagination['limit_per_page'] = 50;
		$pagination['start_record'] = $page * $pagination['limit_per_page'];
		$pagination['uri_segment'] = 4;
		//set pagination
		if ($pagination['total_records'] > 0) $this->data['pagination_links'] = $this->setPagination($pagination);
		#################################################################3
		$url_return = site_url($this->current_page);

		$table = $this->services->get_table_config_no_action($this->current_page, $pagination['start_record'] + 1, $fingerprint_id, $url_return);
		$table["rows"] = $this->attendance_model->attendances($pagination['start_record'], $pagination['limit_per_page'], $fingerprint_id)->result();
		if ($date && $month && $year) {
			$table["rows"] = $this->attendance_model->attendances($pagination['start_record'], $pagination['limit_per_page'], $fingerprint_id, $year . '-' . $month . '-' . $date)->result();
		}
		$table['index'] = ['Hadir', 'Sakit', 'Izin'];
		// echo var_dump( $this->attendance_model->db );return;

		$table = $this->load->view('templates/tables/plain_table_status', $table, true);
		$this->data["contents"] = $table;
		$add_menu = array(
			"name" => "Tambah Absensi",
			"modal_id" => "add_group_",
			"button_color" => "primary",
			"url" => site_url("attendance/add/"),
			"form_data" => $this->services->get_form_data($fingerprint_id, $url_return)["form_data"],
			'data' => NULL
		);

		$add_menu = $this->load->view('templates/actions/modal_form', $add_menu, true);

		$btn_chart =
			array(
				"name" => "Chart",
				"type" => "link",
				"url" => site_url($this->current_page . "chart/" . $fingerprint_id . "?group_by=date"),
				"button_color" => "success",
				"data" => NULL,
			);
		$btn_chart =  $this->load->view('templates/actions/link', $btn_chart, TRUE);
		$link_refresh =
			array(
				"name" => "Singkronkan",
				"type" => "link",
				"url" => site_url($this->current_page . "sync/" . $fingerprint_id),
				"button_color" => "primary",
				"data" => NULL,
			);
		$link_refresh =  $this->load->view('templates/actions/link', $link_refresh, TRUE);;

		$link_clear =
			array(
				"name" => "Bersihkan",
				"type" => "link",
				"url" => site_url($this->current_page . "clear/" . $fingerprint_id),
				"button_color" => "danger",
				"data" => NULL,
			);
		$link_clear =  $this->load->view('templates/actions/link', $link_clear, TRUE);;

		$export =
			array(
				"name" => "Export",
				"modal_id" => "export_",
				"button_color" => "success",
				"url" => site_url($this->current_page . "export/"),
				"form_data" => array(
					'fingerprint_id' => array(
						'type' => 'hidden',
						'label' => 'ID',
						'value' => $fingerprint_id
					),
					'month' => array(
						'type' => 'select',
						'label' => "Bulan",
						'options' => Util::MONTH,
					)
				),
				'data' => NULL
			);
		$btn_export =  $this->load->view('templates/actions/modal_form', $export, TRUE);;

		#################################################################
		for ($i = 1; $i <= 31; $i++) {
			$_date[$i] = $i;
		}
		for ($i = 0; $i <= 10; $i++) {
			$_year[2019 + $i] = 2019 + $i;
		}
		$month 	|| $month = date("m");
		$date 	|| $date = date("d");
		$btn_export =  $this->load->view('templates/actions/modal_form', $export, TRUE);;
		$form_data["form_data"] = array(
			"date" => array(
				'type' => 'select',
				'label' => "Tanggal",
				'options' => $_date,
				'selected' => $date,
			),
			"month" => array(
				'type' => 'select',
				'label' => "Bulan",
				'options' => Util::MONTH,
				'selected' => $month,
			),
			"year" => array(
				'type' => 'select',
				'label' => "Tahun",
				'options' => $_year,
				'selected' => $year,
			),
		);
		$form_data = $this->load->view('templates/form/filter_attendance', $form_data, TRUE);

		$this->data["header_button"] =  $link_refresh . " " . $link_clear . " " . $btn_export . " " . $btn_chart . " " . $add_menu . " " . $form_data;
		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Data Absensi " . $fingerprint->name;
		$this->data["header"] = "Data Absensi " . $fingerprint->name;
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("templates/contents/plain_content");
	}

	public function sync($fingerprint_id)
	{
		$result = json_decode(file_get_contents(site_url("api/attendance/sync/" . $fingerprint_id)));
		if ($result->status) {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $result->message));
		} else {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $result->message));
		}
		redirect(site_url($this->current_page));
	}

	public function clear($fingerprint_id)
	{
		$this->attendance_model->delete_by_fingerprint_id($fingerprint_id);
		redirect(site_url($this->current_page));
	}

	public function chart($fingerprint_id)
	{
		// var_dump( $fingerprint_id );die;
		$this->data["menu_list_id"] = "attendance_index"; //overwrite menu_list_id
		$month = ($this->input->get('month', date("m"))) ? $this->input->get('month', date("m")) : date("m");
		$month = (int) $month;

		$group_by = ($this->input->get('group_by', 1)) ? $this->input->get('group_by', 1) : [];
		$group_by = (empty($group_by)) ? [] : explode("|", $group_by);


		$fingerprint = $this->fingerprint_model->fingerprint($fingerprint_id)->row();


		$this->data['chart'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $fingerprint_id . "?group_by=date&month=" . $month. '&is_coming=1')));
		// echo var_dump( $this->data['chart'] ) ; return;
		$bar = $this->load->view('templates/chart/bar', $this->data['chart'], true);
		// $this->data['chart'] = $chart;

		$this->data['pie'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $fingerprint_id . "?group_by=date&month=" . $month. '&is_coming=1')));
		$pie = $this->load->view('templates/chart/pie', $this->data['pie'], true);
		$this->data["contents"] = $bar . " " . $pie;
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
		$this->render("templates/contents/plain_content");
	}


	public function export()
	{
		$month = $this->input->post('month');
		$fingerprint_id = $this->data["fingerprint"]->id;

		$fingerprint = $this->fingerprint_model->fingerprint($fingerprint_id)->row();
		$data = json_decode(file_get_contents(site_url("api/attendance/export/" . $fingerprint_id . "?month=" . $month . "&is_coming=1")));

		$data->month = Util::MONTH[$month];
		$data->name = $fingerprint->name;

		//absen pulang
		$data->get_out = json_decode(file_get_contents(site_url("api/attendance/export/" . $fingerprint_id . "?month=" . $month . "&is_coming=0")));
		$this->excel->excel_config($data);
	}
}
