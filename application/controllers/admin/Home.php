<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Admin_Controller {
	private $services = null;
    private $name = null;
    private $parent_page = 'admin';
	private $current_page = 'admin/group/';
	public function __construct(){
		parent::__construct();
		$this->load->model(array(
			'employee_model',
			'fingerprint_model',
			'opd_category_model',
		));
	}
	public function index()
	{
		$this->data["badan"] = $this->fingerprint_model->record_count_opd_category_id(2);
		$this->data["dinas"] = $this->fingerprint_model->record_count_opd_category_id(3);
		$this->data["sekretariat"] = $this->fingerprint_model->record_count_opd_category_id(4);
		$this->data["page_title"] = "Beranda";
		
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Group";
		$this->data["header"] = "Group";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render( "admin/dashboard/content" );
	}
}
