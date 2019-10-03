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

    public function chart_get( $fingerprint_id = NULL)
    {
        $fingerprint_id = ( $fingerprint_id == -1 ) ? NULL : $fingerprint_id ;

        $this->data["menu_list_id"] = "attendance_index"; //overwrite menu_list_id
        $month = ($this->input->get('month', date("m"))) ? $this->input->get('month', date("m")) : date("m");
        $month = (int) $month;
        // $month = NULL;

        $group_by = ($this->input->get('group_by', 1)) ? $this->input->get('group_by', 1) : [];
        $group_by = (empty($group_by)) ? [] : explode("|", $group_by);

        $employee_id = ($this->input->get('employee_id', 1)) ? $this->input->get('employee_id', 1) : [];
        $employee_id = (empty($employee_id)) ? [] : explode("|", $employee_id);
        // echo var_dump( $month );return;
        $attendances = $this->attendance_model->accumulation( $fingerprint_id, $group_by, $month, $employee_id)->result();
        $employee_count = $this->employee_model->count_by_fingerprint_id( $fingerprint_id );
        // var_dump( cal_days_in_month ( CAL_GREGORIAN , date("m") , date("Y") )  );return;
        $count_days = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"));

        $days = $this->services->extract_days($attendances);
        $ATTENDANCE = $this->services->extract_attendances($attendances, $employee_count) ;
		$count_attendance = $ATTENDANCE->attendances;
		$absences = $ATTENDANCE->absences;

        $fingerprint = $this->fingerprint_model->fingerprint($fingerprint_id)->row();

        $chart["days"] = $days;
        $chart["count_attendance"] = $ATTENDANCE->attendances;
        $chart["absences"] = $ATTENDANCE->absences;
        $chart["employee_count"] = $employee_count;

        $chart["sum_attendances"] = $ATTENDANCE->sum_attendances;
        $chart["sum_absences"] = $ATTENDANCE->sum_absences;
        $this->set_response($chart, REST_Controller::HTTP_OK); // CREATED (201) being the HTTP response code
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
    
    public function sync_get($fingerprint_id = NULL)
	{
        if ($fingerprint_id === NULL)
        {
            $result = array(
                "message" =>  "url tidak valid", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_NOT_FOUND); 
            return;
        }
		#######################################################################
		$fingerprint = $this->fingerprint_model->fingerprint($fingerprint_id)->row();
        if ($fingerprint === NULL)
        {
            $result = array(
                "message" =>  "fingerprint tidak Ada", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); 
            return;
        }
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
            $result = array(
                "message" =>  "Koneksi Gagal", 
                "status" => 0,
            );
            $this->set_response( $result , REST_Controller::HTTP_OK); 
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
        ############################
        $result = array(
            "message" =>  "Sinkronisasi Selesai", 
            "status" => 1,
        );
        $this->set_response( $result , REST_Controller::HTTP_OK); 
        return;
	}
   
}
