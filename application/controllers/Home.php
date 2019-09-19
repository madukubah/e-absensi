<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends Public_Controller
{

	function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		// TODO : tampilkan landing page bagi user yang belum daftar
		// $this->render("landing_page");
		redirect(base_url('auth/login'));
	}
}
