<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Fingerprint extends User_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'user';
	private $current_page = 'user/fingerprint/';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/Fingerprint_services');
		$this->services = new Fingerprint_services;
		$this->load->model(array(
			'fingerprint_model',
		));
	}
	public function index()
	{
		$page = ($this->uri->segment(4)) ? ($this->uri->segment(4) -  1) : 0;
		// echo $page; return;
		//pagination parameter
		$pagination['base_url'] = base_url($this->current_page) . '/index';
		$pagination['total_records'] = $this->fingerprint_model->record_count();
		$pagination['limit_per_page'] = 100;
		$pagination['start_record'] = $page * $pagination['limit_per_page'];
		$pagination['uri_segment'] = 4;
		//set pagination
		if ($pagination['total_records'] > 0) $this->data['pagination_links'] = $this->setPagination($pagination);
		#################################################################3
		$table = $this->services->get_table_config($this->current_page);
		$table["rows"] = $this->fingerprint_model->fingerprints($pagination['start_record'], $pagination['limit_per_page'])->result();
		$table = $this->load->view('templates/tables/plain_table', $table, true);
		$this->data["contents"] = $table;
		$add_menu = array(
			"name" => "Tambah Fingerprint",
			"modal_id" => "add_group_",
			"button_color" => "primary",
			"url" => site_url($this->current_page . "add/"),
			"form_data" => $this->services->get_form_data()["form_data"],
			'data' => NULL
		);

		$add_menu = $this->load->view('templates/actions/modal_form', $add_menu, true);

		$this->data["header_button"] =  $add_menu;
		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Fingerprint";
		$this->data["header"] = "Fingerprint";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("templates/contents/plain_content");
	}


	public function add()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules($this->services->validation_config());
		if ($this->form_validation->run() === TRUE) {

			$data['name'] = $this->input->post('name');
			$data['acronym'] = $this->input->post('acronym');
			$name = str_replace(" ", "_",  strtolower($data['acronym']));
			$email = $name . '@gmail.com';
			$identity = $email;
			$additional_data = array(
				'first_name' => 'admin',
				'last_name' => $data['name'],
				'email' => $email,
				'phone' => '-',
				'address' => '-',
			);
			$password = $name;
			$user_id =  $this->ion_auth->register($identity, $password, $email, $additional_data, [4]);
			if (!$user_id) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->ion_auth->errors()));
				redirect(site_url($this->current_page));
			}

			$data['user_id'] = $user_id;
			$data['ip_address'] = $this->input->post('ip_address');
			$data['port'] = $this->input->post('port');
			$data['key_finger'] = $this->input->post('key_finger');
			$data['opd_category_id'] = $this->input->post('opd_category_id');
			$data['range_pin'] = $this->input->post('range_pin');

			if ($this->fingerprint_model->create($data)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->fingerprint_model->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->fingerprint_model->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->fingerprint_model->errors() ? $this->fingerprint_model->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->fingerprint_model->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page));
	}

	public function edit()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		// echo var_dump( $data );return;
		$this->form_validation->set_rules($this->services->validation_config());
		if ($this->form_validation->run() === TRUE) {
			$data['name'] = $this->input->post('name');
			$data['ip_address'] = $this->input->post('ip_address');
			$data['port'] = $this->input->post('port');
			$data['key_finger'] = $this->input->post('key_finger');
			$data['opd_category_id'] = $this->input->post('opd_category_id');
			$data['acronym'] = $this->input->post('acronym');
			$data['range_pin'] = $this->input->post('range_pin');


			$data_param['id'] = $this->input->post('id');

			if ($this->fingerprint_model->update($data, $data_param)) {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->fingerprint_model->messages()));
			} else {
				$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->fingerprint_model->errors()));
			}
		} else {
			$this->data['message'] = (validation_errors() ? validation_errors() : ($this->fingerprint_model->errors() ? $this->fingerprint_model->errors() : $this->session->flashdata('message')));
			if (validation_errors() || $this->fingerprint_model->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));
		}

		redirect(site_url($this->current_page));
	}

	public function delete()
	{
		if (!($_POST)) redirect(site_url($this->current_page));

		$data_param['id'] 	= $this->input->post('id');
		$id_user 	= $this->input->post('user_id');
		if ($this->fingerprint_model->delete($data_param)) {
			$this->ion_auth->delete_user($id_user);
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->fingerprint_model->messages()));
		} else {
			$this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->fingerprint_model->errors()));
		}
		redirect(site_url($this->current_page));
	}
}
