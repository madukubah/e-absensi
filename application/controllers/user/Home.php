<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends User_Controller
{
	private $services = null;
	private $name = null;
	private $parent_page = 'user';
	private $current_page = 'user/home/';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('services/Home_services');
		$this->services = new Home_services;
		$this->load->model(array(
			'employee_model',
			'fingerprint_model',
		));
	}

	public function index()
	{
		$this->data["page_title"] = "Beranda";
		$this->render("admin/dashboard/content");
	}

	public function opd_category($category_id)
	{
		$page = ($this->uri->segment(4 + 1)) ? ($this->uri->segment(4 + 1) -  1) : 0;
		// echo $page; return;
		//pagination parameter
		$pagination['base_url'] = base_url($this->current_page) . '/opd_category';
		$pagination['total_records'] = $this->employee_model->record_count();
		$pagination['limit_per_page'] = 10;
		$pagination['start_record'] = $page * $pagination['limit_per_page'];
		$pagination['uri_segment'] = 4;
		//set pagination
		if ($pagination['total_records'] > 0) $this->data['pagination_links'] = $this->setPagination($pagination);
		#################################################################

		$data_param['opd_category_id'] = $category_id;
		$table = $this->services->get_table_config('user/attendance/fingerprint/');
		$table['rows'] = $this->fingerprint_model->fingerprints($pagination['start_record'], $pagination['limit_per_page'], $data_param)->result();
		$table = $this->load->view('templates/tables/plain_table', $table, true);
		$this->data["contents"] = $table;

		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Karyawan";
		$this->data["header"] = "Karyawan";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render("templates/contents/plain_content");
	}
}
