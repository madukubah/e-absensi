<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
//To Solve File REST_Controller not found
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
require_once APPPATH . "/libraries/Util.php";

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
class Attendance extends REST_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'user';
	private $current_page = 'user/home/';

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

	public function chart_get($fingerprint_id = NULL)
	{
		$fingerprint_id = ($fingerprint_id == -1) ? NULL : $fingerprint_id;

		$this->data["menu_list_id"] = "attendance_index"; //overwrite menu_list_id
		if ($this->input->get('date') === null) {
			$month = ($this->input->get('month', date("m"))) ? $this->input->get('month', date("m")) : date("m");
			$month = (int) $month;
			$date = NULL;
			// $month = NULL;
		} else {
			$month = ($this->input->get('month', date("m"))) ? $this->input->get('month', date("m")) : date("m");
			$month = (int) $month;
			// $month = 9;
			$date = ($this->input->get('date', date("d"))) ? $this->input->get('date', date("d")) : date("d");
			$date = (int) $date;
			// $date = 23;
		}
		$group_by = ($this->input->get('group_by', 1)) ? $this->input->get('group_by', 1) : [];
		$group_by = (empty($group_by)) ? [] : explode("|", $group_by);

		$employee_id = ($this->input->get('employee_id', 1)) ? $this->input->get('employee_id', 1) : [];
		$employee_id = (empty($employee_id)) ? [] : explode("|", $employee_id);

		$is_coming = ($this->input->get('is_coming') != NULL) ? $this->input->get('is_coming') : TRUE;
		// echo var_dump( $month );return;
		$attendances = $this->attendance_model->accumulation($fingerprint_id, $group_by, $month, $employee_id, $date, $is_coming)->result();
		$employee_count = $this->employee_model->count_by_fingerprint_id($fingerprint_id);
		// var_dump( cal_days_in_month ( CAL_GREGORIAN , date("m") , date("Y") )  );return;
		$count_days = cal_days_in_month(CAL_GREGORIAN, $month, date("Y"));

		$days = $this->services->extract_days($attendances);
		$ATTENDANCE = $this->services->extract_attendances($attendances, $employee_count);
		$count_attendance = $ATTENDANCE->attendances;
		$absences = $ATTENDANCE->absences;

		$fingerprint = $this->fingerprint_model->fingerprint($fingerprint_id)->row();

		$chart["days"] = $days;
		$chart["count_attendance"] = $ATTENDANCE->attendances;
		$chart["absences"] = $ATTENDANCE->absences;
		$chart["permission"] = $ATTENDANCE->permission;
		$chart["sick"] = $ATTENDANCE->sick;
		$chart["employee_count"] = $employee_count;

		$chart["sum_attendances"] = $ATTENDANCE->sum_attendances;
		$chart["sum_absences"] = $ATTENDANCE->sum_absences;
		$chart["sum_permission"] = $ATTENDANCE->sum_permission;
		$chart["sum_sick"] = $ATTENDANCE->sum_sick;

		$fingerprint_id = (!$fingerprint_id) ? -1 : $fingerprint_id;

		$chart['fingerprint_id'] = $fingerprint_id;
		$chart['date'] = $date;
		$chart['month'] = $month;
		$this->set_response($chart, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
	}

	protected function post_download($url, $data)
	{
		$process = curl_init();
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_HEADER => false,
			CURLOPT_POSTFIELDS => "username=1&userpwd=123456&" . $data,
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

	public function sync_employee_get($fingerprint_id = NULL)
	{
		if ($fingerprint_id === NULL) {
			$result = array(
				"message" =>  "url tidak valid",
				"status" => 0,
			);
			$this->set_response($result, REST_Controller::HTTP_NOT_FOUND);
			return;
		}
		#######################################################################
		$fingerprint = $this->fingerprint_model->fingerprint($fingerprint_id)->row();
		if ($fingerprint === NULL) {
			$result = array(
				"message" =>  "fingerprint tidak Ada",
				"status" => 0,
			);
			$this->set_response($result, REST_Controller::HTTP_OK);
			return;
		}
		$tanggal_awal = date("Y") . '-1-01 00:00:00';
		$tanggal_akhir = date("Y") . '-12-30 23:00:00';
		$jumlah_karyawan = 200;

		$data[] = "sdate={$tanggal_awal}";
		$data[] = "edate={$tanggal_akhir}";
		$data[] = 'period=1';

		for ($i = 1; $i <= $fingerprint->range_pin; $i++) {
			$data[] = "uid=" . ($i) . ""; //."uid=16";
		}

		$result = $this->post_download("http://{$fingerprint->ip_address}/form/Download", implode('&', $data));

		if ($result == FALSE) {
			$result = array(
				"message" =>  "Koneksi Gagal",
				"status" => 0,
			);
			$this->set_response($result, REST_Controller::HTTP_OK);
			return;
		}
		if ($result == "") {
			$result = array(
				"message" =>  "Autentikasi Gagal",
				"status" => 0,
			);
			$this->set_response($result, REST_Controller::HTTP_OK);
			return;
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
			// echo json_encode( $user_attendance[0] )."<br><br>";
			$employee = $this->employee_model->employee_by_pin($key, $fingerprint_id)->row();
			if ($employee == NULL) {
				$data_employee = array();
				$data_employee["fingerprint_id"] = $fingerprint_id;
				$data_employee["name"] = $user_attendance[0][1];
				$data_employee["pin"] = $key;
				$data_employee["position"] = "position";
				$this->employee_model->create($data_employee);
			}
		}
		$this->sync_get($fingerprint_id);
		############################
		$result = array(
			"message" =>  "Sinkronisasi Selesai",
			"status" => 1,
		);
		$this->set_response($result, REST_Controller::HTTP_OK);
		return;
	}
	public function sync_all_get()
	{
		$fingerprints = $this->fingerprint_model->fingerprints()->result();
		foreach ($fingerprints as $fingerprint) {
			$this->sync_get($fingerprint->id);
		}
		$result = array(
			"message" =>  "Sinkronisasi Selesai",
			"status" => 1,
		);
		$this->set_response($result, REST_Controller::HTTP_OK);
		return;
	}
	#######################################################################

	public function sync_get($fingerprint_id = NULL)
	{
		if ($fingerprint_id === NULL) {
			$result = array(
				"message" =>  "url tidak valid",
				"status" => 0,
			);
			$this->set_response($result, REST_Controller::HTTP_NOT_FOUND);
			return;
		}
		#######################################################################
		$fingerprint = $this->fingerprint_model->fingerprint($fingerprint_id)->row();
		if ($fingerprint === NULL) {
			$result = array(
				"message" =>  "fingerprint tidak Ada",
				"status" => 0,
			);
			$this->set_response($result, REST_Controller::HTTP_OK);
			return;
		}
		$tanggal_awal = date("Y") . '-1-01 00:00:00';
		$tanggal_akhir = date("Y") . '-12-30 23:00:00';
		$jumlah_karyawan = 200;

		$data[] = "sdate={$tanggal_awal}";
		$data[] = "edate={$tanggal_akhir}";
		$data[] = 'period=1';

		$employees = $this->employee_model->employee_by_fingerprint_id(0, NULL, $fingerprint_id)->result();
		foreach ($employees as $employee) {
			$data[] = "uid=" . ($employee->pin) . ""; //."uid=16";
		}

		$result = $this->post_download("http://{$fingerprint->ip_address}/form/Download", implode('&', $data));
		// $this->set_response($result, REST_Controller::HTTP_OK);
		// 	return;
		if ($result == FALSE) {
			$result = array(
				"message" =>  "Koneksi Gagal",
				"status" => 0,
			);
			$this->set_response($result, REST_Controller::HTTP_OK);
			return;
		}
		if ($result == "") {
			$result = array(
				"message" =>  "Autentikasi Gagal",
				"status" => 0,
			);
			$this->set_response($result, REST_Controller::HTTP_OK);
			return;
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
			// echo json_encode( $user_attendance[0] )."<br><br>";
			$employee = $this->employee_model->employee_by_pin($key, $fingerprint_id)->row();
			if ($employee == NULL) {
				$data_employee = array();
				$data_employee["fingerprint_id"] = $fingerprint_id;
				$data_employee["name"] = $user_attendance[0][1];
				$data_employee["pin"] = $key;
				$data_employee["position"] = "position";
				$id = $this->employee_model->create($data_employee);
			} else {
				$id = $employee->id;
			}

			$CURR_USER_ATTENDANCE = array();
			foreach ($user_attendance as $item) {

				$data_attendance = array();
				$data_attendance["employee_pin"] 	= $key;
				$data_attendance["employee_id"] 	= $id;
				$data_attendance["timestamp"] = strtotime($item[2]);
				$datetime = explode(" ", $item[2]);

				$curr_datetime = strtotime($item[2]);

				$range_comein = array(
					"start" => strtotime($datetime[0] . " 07:00:00"),
					"end" => strtotime($datetime[0] . " 09:00:00")
				);
				$range_comeout = array(
					"start" => strtotime($datetime[0] . " 14:30:00"),
					"end" => strtotime($datetime[0] . " 20:00:00")
				);
				$data_attendance["date"] = $datetime[0];
				$data_attendance["time"] = $datetime[1];

				if ($range_comein["start"] <= $curr_datetime && $curr_datetime <= $range_comein["end"]) //absen masuk
				{
					if (isset($CURR_USER_ATTENDANCE[$datetime[0]])) continue;
					$CURR_USER_ATTENDANCE[$datetime[0]] = $datetime[0];

					$attendance = $this->attendance_model->attendance_by_iddate($id, $data_attendance["date"])->row();
					if ($attendance == NULL) $ATTENDANCE_ARR[] = $data_attendance;
				} else if ($range_comeout["start"] <= $curr_datetime && $curr_datetime <= $range_comeout["end"]) //absen keluar
				{
					if (isset($CURR_USER_ATTENDANCE[$datetime[0]])) continue;
					$CURR_USER_ATTENDANCE[$datetime[0]] = $datetime[0];

					$attendance = $this->attendance_model->attendance_by_iddate($id, $data_attendance["date"])->row();
					if ($attendance == NULL) $ATTENDANCE_ARR[] = $data_attendance;
				}
			}
		}
		// return;
		if (!empty($ATTENDANCE_ARR)) $this->attendance_model->create_batch($ATTENDANCE_ARR);
		############################
		$result = array(
			"message" =>  "Sinkronisasi Selesai",
			"status" => 1,
		);
		$this->set_response($result, REST_Controller::HTTP_OK);
		return;
	}
	###############################################################################

	public function export_get($fingerprint_id = null)
	{
		$fingerprint_id = ($fingerprint_id == -1) ? NULL : $fingerprint_id;

		$month = ($this->input->get('month', date("m"))) ? $this->input->get('month', date("m")) : date("m");
		$month = (int) $month;
		// $month = NULL;

		$group_by = ($this->input->get('group_by', 1)) ? $this->input->get('group_by', 1) : [];
		$group_by = (empty($group_by)) ? [] : explode("|", $group_by);

		$employee_id = ($this->input->get('employee_id', 1)) ? $this->input->get('employee_id', 1) : [];
		$employee_id = (empty($employee_id)) ? [] : explode("|", $employee_id);
		// echo var_dump( $month ); return;
		$employee_name = $this->employee_model->employee_by_fingerprint_id(0, null, $fingerprint_id)->result();
		$count_days = cal_days_in_month(CAL_GREGORIAN, $month, date("Y"));

		$is_coming = ($this->input->get('is_coming') != NULL) ? $this->input->get('is_coming') : TRUE;

		for ($i = 1; $i <= $count_days; $i++) {
			$attendances[$i] = $this->attendance_model->employee_attendance($fingerprint_id, $month, $i, $is_coming)->result();
		}

		$data['attendances'] = $attendances;
		$data['days'] = $count_days;
		$data['employee'] = $employee_name;
		$this->set_response($data, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
	}
}
