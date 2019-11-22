<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . "/libraries/Util.php";

class Attendance extends Public_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'user';
	private $current_page = 'user/attendance/';

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

	public function add()
	{
		

		if (!($_POST)) redirect(site_url($this->current_page));

		$this->form_validation->set_rules($this->services->validation_config());
		$url_return = $this->input->post('url_return');
		if ($this->form_validation->run() === TRUE) {

			$data['employee_pin'] = $this->input->post('employee_pin');
			$data['timestamp'] = $this->input->post('timestamp');
			$data['date'] = date( "Y-m-d", strtotime( $this->input->post('date') ) ) ;
			$data['time'] = $this->input->post('time');
			$data['status'] = $this->input->post('status');

			$range_comein = array(
				"start" => strtotime( $data['date'] . " 07:00:00"),
				"end" => strtotime($data['date'] . " 09:00:00")
			);
			$range_comeout = array(
				"start" => strtotime($data['date'] . " 14:30:00"),
				"end" => strtotime($data['date'] . " 20:00:00")
			);

			
			$fingerprint_id = $this->input->post('fingerprint_id');
			$employee = $this->employee_model->employee_by_pin($data['employee_pin'], $fingerprint_id)->row();
			if( $employee == NULL )
			{
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, "gagal" ));
				redirect($url_return);
				return;
			}

			$curr_datetime = $data['timestamp'];
			$_is_coming = FALSE;
			if ($range_comein["start"] <= $curr_datetime && $curr_datetime <= $range_comein["end"]) //absen masuk
			{
				$_is_coming = TRUE;
			} 

			if ( $attendance = $this->attendance_model->attendance_by_iddate($employee->id, $data['date'], $_is_coming)->row()) {
			// echo var_dump( $attendance );return;
				$data = [];
				$data['status'] = $this->input->post('status');
				$data['time'] = $this->input->post('time');
				// $data['time'] = '08:00:00';

				$data_param['id'] = $attendance->id;
				if ($this->attendance_model->update($data, $data_param)) {
					$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->attendance_model->messages()));
				} else {
					$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->attendance_model->errors()));
				}
				// redirect(site_url($this->current_page) . "fingerprint/" . $this->input->post('fingerprint_id'));
				redirect($url_return);

			}
			$data['employee_id'] = $employee->id;

			if ($this->attendance_model->create($data)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->attendance_model->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->attendance_model->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->attendance_model->errors() ? $this->attendance_model->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->attendance_model->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect($url_return);
	}

	public function edit()
	{
		if (!($_POST)) redirect(site_url($this->current_page));
		// echo var_dump( $data );return;
		$this->form_validation->set_rules('status', 'status', 'required');
		$url_return = $this->input->post('url_return');
		if ($this->form_validation->run() === TRUE) {

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

		redirect($url_return);
	}

	public function delete($fingerprint_id)
	{
		if (!($_POST)) redirect(site_url($this->current_page));
		$url_return = $this->input->post('url_return');

		$data_param['id'] 	= $this->input->post('id');
		if ($this->attendance_model->delete($data_param)) {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->attendance_model->messages()));
		} else {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->attendance_model->errors()));
		}
		redirect($url_return);
	}
}
