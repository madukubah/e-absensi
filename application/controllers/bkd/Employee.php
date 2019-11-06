<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Employee extends Bkd_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'bkd';
	private $current_page = 'bkd/employee/';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/Employee_services');
		$this->services = new Employee_services;
		$this->load->model(array(
			'employee_model',
		));
	}
	public function index()
	{
		$this->load->library('services/Fingerprint_services');
		$this->services = new Fingerprint_services;

		$page = ($this->uri->segment(4)) ? ($this->uri->segment(4) -  1) : 0;
		// echo $page; return;
		//pagination parameter
		$pagination['base_url'] = base_url($this->current_page) . '/index';
		$pagination['total_records'] = $this->fingerprint_model->record_count();
		$pagination['limit_per_page'] = 10;
		$pagination['start_record'] = $page * $pagination['limit_per_page'];
		$pagination['uri_segment'] = 4;
		//set pagination
		if ($pagination['total_records'] > 0) $this->data['pagination_links'] = $this->setPagination($pagination);
		#################################################################3
		$table = $this->services->get_table_config_no_action($this->current_page, $pagination['start_record'] +1 );
		$table["rows"] = $this->fingerprint_model->fingerprints($pagination['start_record'], $pagination['limit_per_page'])->result();
		$table = $this->load->view('templates/tables/plain_table', $table, true);
		$this->data["contents"] = $table;

		$export =
			array(
				"name" => "Export",
				"modal_id" => "export_",
				"button_color" => "success",
				"url" => site_url($this->current_page . "export/"),
				"form_data" => array(
					'month' => array(
						'type' => 'select',
						'label' => "Bulan Awal",
						'options' => Util::MONTH,
					)
				),
				'data' => NULL
			);
		// $this->data["header_button"] =  $this->load->view('templates/actions/modal_form', $export, TRUE);;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Data Pegawai";
		$this->data["header"] = "Data Pegawai";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("templates/contents/plain_content");
	}

	public function fingerprint($fingerprint_id)
	{
		$curr_fingerprint = $this->data["fingerprint"];
		$curr_fingerprint_id = $this->data["fingerprint"]->id;

		$this->data["menu_list_id"] = "employee_index"; //overwrite menu_list_id


		$page = ($this->uri->segment(4 + 1)) ? ($this->uri->segment(4 + 1) -  1) : 0;
		// echo $page; return;
		//pagination parameter
		$pagination['base_url'] = base_url($this->current_page) . '/fingerprint/' . $fingerprint_id;
		$pagination['total_records'] = $this->employee_model->count_by_fingerprint_id($fingerprint_id);
		$pagination['limit_per_page'] = 10;
		$pagination['start_record'] = $page * $pagination['limit_per_page'];
		$pagination['uri_segment'] = 4 + 1;
		//set pagination
		if ($pagination['total_records'] > 0) $this->data['pagination_links'] = $this->setPagination($pagination);
		#################################################################3
		if( $curr_fingerprint_id != $fingerprint_id )
			$table = $this->services->get_table_config_no_action($this->current_page, $pagination['start_record'] + 1);
		else
			$table = $this->services->get_table_config($this->current_page, $pagination['start_record'] +1 );

		$table["rows"] = $this->employee_model->employee_by_fingerprint_id($pagination['start_record'], $pagination['limit_per_page'], $fingerprint_id)->result();
		$table['index'] = ['Non-PNS', 'PNS'];

		$table = $this->load->view('templates/tables/plain_table_image', $table, true);
		$this->data["contents"] = $table;

		$add_menu = array(
			"name" => "Sinkronisasi Pegawai",
			"modal_id" => "add_group_",
			"button_color" => "primary",
			"url" => site_url($this->current_page . "sync_employee/"),
			"form_data" => array(
				"id" => array(
				  'type' => 'hidden',
				  'label' => "ID",
				),
				"fingerprint_id" => array(
				  'type' => 'hidden',
				  'label' => "Nama OPD",
				  'value' =>$fingerprint_id,
				),
			  ),
			'data' => NULL
		);

		$add_menu = $this->load->view('templates/actions/modal_form_confirm_sync', $add_menu, true);

		if( $curr_fingerprint_id != $fingerprint_id )$add_menu = ""; 

		$this->data[ "header_button" ] =  $add_menu;
		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Pegawai";
		$this->data["header"] = "Pegawai";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("templates/contents/plain_content");
	}

	public function add()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules($this->services->validation_config());
		if ($this->form_validation->run() === TRUE) {
			$data['fingerprint_id'] = $this->input->post('fingerprint_id');
			$data['name'] = $this->input->post('name');
			$data['position'] = $this->input->post('position');
			$data['pin'] = $this->input->post('pin');

			if ($this->employee_model->create($data)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->employee_model->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->employee_model->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->employee_model->errors() ? $this->employee_model->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->employee_model->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page));
	}

	public function edit()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules($this->services->validation_config());
		if ($this->form_validation->run() === TRUE) {
			$data['fingerprint_id'] = $this->input->post('fingerprint_id');
			$data['name'] = $this->input->post('name');
			$data['position'] = $this->input->post('position');
			$data['pin'] = $this->input->post('pin');

			$this->load->library('upload'); // Load librari upload
			$config = $this->services->get_photo_upload_config($this->input->post('id'));

			$this->upload->initialize($config);
			// echo var_dump($data); return;
			if ($_FILES['image']['name'] != "")
				if ($this->upload->do_upload("image")) {
					$data['image'] = $this->upload->data()["file_name"];
					if (!@unlink($config['upload_path'] . $this->input->post('image_old')));
				} else {
					$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->upload->display_errors()));
					redirect(site_url($this->current_page));
				}

			$data_param['id'] = $this->input->post('id');

			if ($this->employee_model->update($data, $data_param)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->employee_model->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->employee_model->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->employee_model->errors() ? $this->employee_model->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->employee_model->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page));
	}

	public function delete()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		$this->load->library('upload'); // Load librari upload
		$config = $this->services->get_photo_upload_config($this->input->post('id'));

		$data_param['id'] 	= $this->input->post('id');
		if ($this->employee_model->delete($data_param)) {
			if (!@unlink($config['upload_path'] . $this->input->post('image_old'))) return;

			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->employee_model->messages()));
		} else {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->employee_model->errors()));
		}
		redirect(site_url($this->current_page));
	}
}
