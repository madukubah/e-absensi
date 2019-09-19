<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends User_Controller
{

	public function index()
	{
		$this->data["page_title"] = "Beranda";
		$this->render("admin/dashboard/content");
	}

	public function category($category)
	{
		//
	}
}
