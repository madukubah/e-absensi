public function delete()//ok
{
	if( !($_POST) )	redirect(site_url('admin/category'));

	$data_param['id'] 		= $this->input->post('id');
	$data['category_id'] 	= $this->input->post('category_id');
	if( $this->product_model->delete( $data_param ) )
	{
		$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::SUCCESS, $this->product_model->messages() ) );
	}
	else
	{
		$this->session->set_flashdata('alert', $this->alert->set_alert( Alert::DANGER, $this->product_model->errors() ) );
	}
	if(  $data['category_id'] == 0  ) redirect(site_url('admin/category/') );
	redirect(site_url('admin/category/index/').$data['category_id']  );	
}