<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . "/libraries/Util.php";

class Attendance extends Bkd_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'bkd';
	private $current_page = 'bkd/attendance/';

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


	public function index(  )
	{
		$this->data["menu_list_id"] = "attendance_index"; //overwrite menu_list_id
		$fingerprint = $this->data["fingerprint"];
		$fingerprint_id = $this->data["fingerprint"]->id;
		$fingerprint = $this->fingerprint_model->fingerprint($fingerprint_id)->row();

		$page = ($this->uri->segment(4 )) ? ($this->uri->segment(4) -  1) : 0;
		// echo $page; return;
		//pagination parameter
		$pagination['base_url'] = base_url($this->current_page)."index/";
		$pagination['total_records'] = $this->attendance_model->record_count_fingerprint_id($fingerprint_id);
		// echo var_dump( $page );return;
		$pagination['limit_per_page'] = 50;
		$pagination['start_record'] = $page * $pagination['limit_per_page'];
		$pagination['uri_segment'] = 4 ;
		//set pagination
		if ($pagination['total_records'] > 0) $this->data['pagination_links'] = $this->setPagination($pagination);
		#################################################################3
		$table = $this->services->get_table_config_no_action($this->current_page, $pagination['start_record'] +1, $fingerprint_id);
		$table["rows"] = $this->attendance_model->attendances( $pagination['start_record'], $pagination['limit_per_page'], $fingerprint_id)->result();
		// echo var_dump( $this->attendance_model->db );return;

		$table = $this->load->view('templates/tables/plain_table', $table, true);
		$this->data["contents"] = $table;
		$add_menu = array(
			"name" => "Tambah Absensi",
			"modal_id" => "add_group_",
			"button_color" => "primary",
			"url" => site_url($this->current_page . "add/"),
			"form_data" => $this->services->get_form_data($fingerprint_id)["form_data"],
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
		$btn_export =  $this->load->view('templates/actions/link', $export, TRUE);;

		$this->data["header_button"] =  $link_refresh . " " . $btn_export ;
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

	protected function post_download($url, $data)
	{
		$process = curl_init();
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_HEADER => false,
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => TRUE,
			CURLOPT_POST => TRUE,
			CURLOPT_BINARYTRANSFER => TRUE
		);
		curl_setopt_array($process, $options);
		$return = curl_exec($process);
		curl_close($process);
		return $return;
	}

	public function sync($fingerprint_id)
	{
		$this->data["menu_list_id"] = "attendance_index"; //overwrite menu_list_id
		#######################################################################
		$fingerprint = $this->fingerprint_model->fingerprint($fingerprint_id)->row();

		$tanggal_awal = date("Y") . '-1-01 00:00:00';
		$tanggal_akhir = date("Y") . '-12-30 23:00:00';
		$jumlah_karyawan = 200;

		$data[] = "sdate={$tanggal_awal}";
		$data[] = "edate={$tanggal_akhir}";
		$data[] = 'period=1';

		for ($i = 1; $i <= 24; $i++) {
			$data[] = "uid=" . ($i) . ""; //."uid=16";
		}

		$result = $this->post_download("http://{$fingerprint->ip_address}/form/Download", implode('&', $data));

		if ($result == FALSE) {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, "Koneksi Gagal"));
			redirect(site_url($this->current_page));
		}
		$attendances = explode("\n", $result);

		$user_attendances = array();
		foreach ($attendances as $i => $attendance) {
			$attendance = explode("\t", $attendance);
			if ($i == count($attendances) - 1) break;

			$user_attendances[$attendance[0]][] = $attendance;
		}

		$ATTENDANCE_ARR = array();
		foreach ($user_attendances as $key => $user_attendance) {
			$employee = $this->employee_model->employee_by_pin($key)->row();
			if ($employee == NULL) {
				$data_employee = array();
				$data_employee["fingerprint_id"] = $fingerprint_id;
				$data_employee["name"] = $user_attendance[0][1];
				$data_employee["pin"] = $key;
				$data_employee["position"] = "position";
				$this->employee_model->create($data_employee);
			}

			foreach ($user_attendance as $item) {
				$data_attendance = array();
				$data_attendance["employee_pin"] = $key;
				$data_attendance["timestamp"] = strtotime($item[2]);
				$datetime = explode(" ", $item[2]);
				$data_attendance["date"] = $datetime[0];
				$data_attendance["time"] = $datetime[1];
				$attendance = $this->attendance_model->attendance_by_pindate($key, $data_attendance["date"])->row();

				if ($attendance == NULL) $ATTENDANCE_ARR[] = $data_attendance;
			}
		}
		if (!empty($ATTENDANCE_ARR)) $this->attendance_model->create_batch($ATTENDANCE_ARR);
		redirect(site_url($this->current_page) );
		return;
	}

	public function chart($fingerprint_id)
	{
		
		$this->data["menu_list_id"] = "attendance_index"; //overwrite menu_list_id
		$month = ($this->input->get('month', date("m"))) ? $this->input->get('month', date("m")) : date("m");
		$month = (int) $month;

		$group_by = ($this->input->get('group_by', 1)) ? $this->input->get('group_by', 1) : [];
		$group_by = (empty($group_by)) ? [] : explode("|", $group_by);

		$employee_id = ($this->input->get('employee_id', 1)) ? $this->input->get('employee_id', 1) : [];
		$employee_id = (empty($employee_id)) ? [] : explode("|", $employee_id);
		// echo var_dump( $month );return;
		$attendances = $this->attendance_model->accumulation($fingerprint_id, $group_by, $month, $employee_id)->result();
		$employee_count = $this->employee_model->record_count();
		// var_dump( cal_days_in_month ( CAL_GREGORIAN , date("m") , date("Y") )  );return;
		$count_days = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"));

		$days = $this->services->extract_days($attendances);
		$count_attendance = $this->services->extract_attendances($attendances, $employee_count)->attendances;
		$absences = $this->services->extract_attendances($attendances, $employee_count)->absences;


		$fingerprint = $this->fingerprint_model->fingerprint($fingerprint_id)->row();

		$chart["days"] = $days;
		$chart["count_attendance"] = $count_attendance;
		$chart["absences"] = $absences;
		// $chart = $this->load->view('templates/chart/line', $chart, true);
		$bar = $this->load->view('templates/chart/bar', $chart, true);
		$pie = $this->load->view('templates/chart/pie', $chart, true);
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

	public function add()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules($this->services->validation_config());
		if ($this->form_validation->run() === TRUE) {
			$data['employee_pin'] = $this->input->post('employee_pin');
			$data['timestamp'] = $this->input->post('timestamp');
			$data['date'] = $this->input->post('date');
			$data['time'] = $this->input->post('time');
			$data['status'] = $this->input->post('status');
			if ($this->attendance_model->create($data)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->attendance_model->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->attendance_model->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->attendance_model->errors() ? $this->attendance_model->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->attendance_model->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page) . "fingerprint/" . $this->input->post('fingerprint_id'));
	}

	public function edit()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules($this->services->validation_config());
		if ($this->form_validation->run() === TRUE) {
			$data['timestamp'] = $this->input->post('timestamp');
			$data['date'] = $this->input->post('date');
			$data['time'] = $this->input->post('time');
			$data['status'] = $this->input->post('status');

			$data_param['id'] = $this->input->post('id');

			if ($this->attendance_model->update($data, $data_param)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->attendance_model->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->attendance_model->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->attendance_model->errors() ? $this->attendance_model->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->attendance_model->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page) . "/fingerprint/" . $this->input->post('fingerprint_id'));
	}

	public function delete($fingerprint_id)
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		$data_param['id'] 	= $this->input->post('id');
		if ($this->attendance_model->delete($data_param)) {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->attendance_model->messages()));
		} else {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->attendance_model->errors()));
		}
		redirect(site_url($this->current_page) . "fingerprint/" . $fingerprint_id);
	}

	public function export($fingerprint_id = null)
	{
		$this->excel->excel_config('A');
	}
}
