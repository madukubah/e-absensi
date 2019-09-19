<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Attendance extends User_Controller {
	private $services = null;
    private $name = null;
    private $parent_page = 'user';
	private $current_page = 'user/attendance/';
	
	public function __construct(){
		parent::__construct();
		$this->load->library('services/Attendance_services');
		$this->services = new Attendance_services;
		$this->load->model(array(
			'fingerprint_model',
			'attendance_model',
		));

	}
	public function index()
	{
		$this->load->library('services/Fingerprint_services');
		$this->services = new Fingerprint_services;

		$page = ($this->uri->segment(4)) ? ($this->uri->segment(4) -  1 ) : 0;
		// echo $page; return;
        //pagination parameter
        $pagination['base_url'] = base_url( $this->current_page ) .'/index';
        $pagination['total_records'] = $this->fingerprint_model->record_count() ;
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4;
		//set pagination
		if ($pagination['total_records'] > 0 ) $this->data['pagination_links'] = $this->setPagination($pagination);
		#################################################################3
		$table = $this->services->get_table_config_no_action( $this->current_page );
		$table[ "rows" ] = $this->fingerprint_model->fingerprints( $pagination['start_record'], $pagination['limit_per_page'] )->result();
		$table = $this->load->view('templates/tables/plain_table', $table, true);
		$this->data[ "contents" ] = $table;


		// $this->data[ "header_button" ] =  ""$add_menu;
		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Data Absensi";
		$this->data["header"] = "Data Absensi";
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render( "templates/contents/plain_content" );
	}
	public function fingerprint( $fingerprint_id )
	{
		$this->data["menu_list_id"] = "attendance_index"; //overwrite menu_list_id

		$fingerprint = $this->fingerprint_model->fingerprint( $fingerprint_id )->row()  ;

		$page = ($this->uri->segment(4 +1)) ? ($this->uri->segment(4 +1 ) -  1 ) : 0;
		// echo $page; return;
        //pagination parameter
        $pagination['base_url'] = base_url( $this->current_page ) .'/index';
        $pagination['total_records'] = $this->attendance_model->record_count() ;
        $pagination['limit_per_page'] = 10;
        $pagination['start_record'] = $page*$pagination['limit_per_page'];
        $pagination['uri_segment'] = 4;
		//set pagination
		if ($pagination['total_records'] > 0 ) $this->data['pagination_links'] = $this->setPagination($pagination);
		#################################################################3
		$table = $this->services->get_table_config_no_action( $this->current_page, 1, $fingerprint_id );
		$table[ "rows" ] = $this->attendance_model->attendances( $pagination['start_record'], $pagination['limit_per_page'], $fingerprint_id )->result();
		// echo var_dump( $this->attendance_model->db );return;

		$table = $this->load->view('templates/tables/plain_table', $table, true);
		$this->data[ "contents" ] = $table;
		$add_menu = array(
			"name" => "Tambah Absensi",
			"modal_id" => "add_group_",
			"button_color" => "primary",
			"url" => site_url( $this->current_page."add/"),
			"form_data" =>$this->services->get_form_data( $fingerprint_id )["form_data"] ,
			'data' => NULL
		);

		$add_menu= $this->load->view('templates/actions/modal_form', $add_menu, true ); 

		$this->data[ "header_button" ] =  $add_menu;
		// return;
		#################################################################3
		$alert = $this->session->flashdata('alert');
		$this->data["key"] = $this->input->get('key', FALSE);
		$this->data["alert"] = (isset($alert)) ? $alert : NULL ;
		$this->data["current_page"] = $this->current_page;
		$this->data["block_header"] = "Data Absensi ".$fingerprint->name;
		$this->data["header"] = "Data Absensi ".$fingerprint->name;
		$this->data["sub_header"] = 'Klik Tombol Action Untuk Aksi Lebih Lanjut';
		$this->render( "templates/contents/plain_content" );
	}


	public function add(  )
	{
		if( !($_POST) ) redirect(site_url(  $this->current_page ));  

		// echo var_dump( $data );return;
		$this->form_validation->set_rules( $this->services->validation_config() );
        if ($this->form_validation->run() === TRUE )
        {
			$data['employee_pin'] = $this->input->post( 'employee_pin' );
			$data['timestamp'] = $this->input->post( 'timestamp' );
			$data['date'] = $this->input->post( 'date' );
			$data['time'] = $this->input->post( 'time' );
			$data['status'] = $this->input->post( 'status' );
			if( $this->attendance_model->create( $data ) ){
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->attendance_model->messages() ) );
			}else{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->attendance_model->errors() ) );
			}
		}
        else
        {
          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->attendance_model->errors() ? $this->attendance_model->errors() : $this->session->flashdata('message')));
          if(  validation_errors() || $this->attendance_model->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
		}
		
		redirect( site_url($this->current_page)."fingerprint/".$this->input->post( 'fingerprint_id' )  );
	}

	public function edit(  )
	{
		if( !($_POST) ) redirect(site_url(  $this->current_page ));  

		// echo var_dump( $data );return;
		$this->form_validation->set_rules( $this->services->validation_config() );
        if ($this->form_validation->run() === TRUE )
        {
			$data['timestamp'] = $this->input->post( 'timestamp' );
			$data['date'] = $this->input->post( 'date' );
			$data['time'] = $this->input->post( 'time' );
			$data['status'] = $this->input->post( 'status' );

			$data_param['id'] = $this->input->post( 'id' );

			if( $this->attendance_model->update( $data, $data_param  ) ){
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->attendance_model->messages() ) );
			}else{
				$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->attendance_model->errors() ) );
			}
		}
        else
        {
          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->attendance_model->errors() ? $this->attendance_model->errors() : $this->session->flashdata('message')));
          if(  validation_errors() || $this->attendance_model->errors() ) $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->data['message'] ) );
		}
		
		redirect( site_url($this->current_page)."/fingerprint/".$this->input->post( 'fingerprint_id' )  );		
	}

	public function delete( $fingerprint_id  ) {
		if( !($_POST) ) redirect( site_url($this->current_page) );
	  
		$data_param['id'] 	= $this->input->post('id');
		if( $this->attendance_model->delete( $data_param ) ){
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->attendance_model->messages() ) );
		}else{
		  $this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->attendance_model->errors() ) );
		}
		redirect( site_url($this->current_page)."fingerprint/". $fingerprint_id );
	}
}
