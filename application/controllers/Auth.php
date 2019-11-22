<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends Public_Controller
{
        function __construct()
        {
                parent::__construct();
                $this->load->library('services/Attendance_services');
                $this->services = new Attendance_services;
                $this->load->library(array('form_validation'));
                $this->load->helper('form');
                $this->config->load('ion_auth', TRUE);
                $this->load->helper(array('url', 'language'));
                $this->lang->load('auth');
                $this->load->model(array(
                        'fingerprint_model',
                        'attendance_model',
                        'employee_model',
                ));
        }

        public function login()
        {

                $this->form_validation->set_rules('identity', 'identity', 'required');
                $this->form_validation->set_rules('user_password', 'user_password', 'trim|required');
                if ($this->form_validation->run() == true) {
                        // echo $this->input->post('identity');
                        // echo $this->input->post('user_password');
                        // return;
                        if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('user_password'))) {
                                //if the login is successful
                                //redirect them back to the home page
                                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->ion_auth->messages()));


                                if ($this->ion_auth->is_admin()) redirect(site_url('/admin'));
                                if ($this->ion_auth->in_group("admin_opd")) redirect(site_url('/opd'));
                                if ($this->ion_auth->in_group("admin_bkd")) redirect(site_url('/bkd'));

                                redirect(site_url('/user'), 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
                        } else {
                                // if the login was un-successful
                                // redirect them back to the login page
                                $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->ion_auth->errors()));

                                // echo $this->ion_auth->errors();return;

                                redirect('auth/login', 'refresh'); // use redirects instead of loading views for compatibility with MY_Controller libraries
                        }
                } else {
                        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
                        if (validation_errors() || $this->ion_auth->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));

                        $month = ($this->input->get('month', date("m"))) ? $this->input->get('month', date("m")) : date("m");
                        $month = (int) $month;
                        $date = ($this->input->get('date', date("d"))) ? $this->input->get('date', date("d")) : date("d");
                        $date = (int) $date;
                        $opd = ($this->input->get('opd', FALSE)) ? $this->input->get('opd', FALSE) : -1;
                        $opd = (int) $opd;
                        $fingerprints = $this->fingerprint_model->fingerprints()->result();
                        $fingerprints_select = array();
                        $fingerprints_select[-1] = "Semua OPD";
                        foreach ($fingerprints as $fingerprint) {
                                $fingerprints_select[$fingerprint->id] = $fingerprint->name;
                        }
                        for ($i = 1; $i <= 31; $i++) {
                                $_date[$i] = $i;
                        }
                        $form_data["form_data"] = array(
                                "date" => array(
                                        'type' => 'select',
                                        'label' => "Tanggal",
                                        'options' => $_date,
                                        'selected' => $date,
                                ),
                                "month" => array(
                                        'type' => 'select',
                                        'label' => "Bulan",
                                        'options' => Util::MONTH,
                                        'selected' => $month,
                                ),
                                "opd" => array(
                                        'type' => 'select',
                                        'label' => "OPD",
                                        'options' => $fingerprints_select,
                                        'selected' => $opd,
                                ),
                        );
                        $form_data = $this->load->view('templates/form/filter_login', $form_data, TRUE);
                        $this->data["header_button"] =  $form_data;

                        $form_login["form_data"] = array(
                                "identity" => array(
                                        'type' => 'text',
                                        'label' => "Email",
                                        "value" => NULL
                                ),
                                "user_password" => array(
                                        'type' => 'password',
                                        'label' => "Password",
                                        "value" => NULL
                                ),
                        );
                        $form_login["form"] = $this->load->view('templates/form/plain_form_horizontal', $form_login, TRUE);

                        $form_login = $this->load->view('templates/form/login_horizontal', $form_login, TRUE);
                        $this->data["form_login"] =  $form_login;

                        if ($opd == -1) {
                                //attendance coming in
<<<<<<< HEAD
                                $this->data['chart'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $opd . "?group_by=date&month=" . $month  . '&is_coming=1')));
=======
                                $this->data['chart'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $opd . "?group_by=date&month=" . $month. '&is_coming=1')));
>>>>>>> 61e7934e36c490d5a4d8f7cbde33c90439882307
                                $this->data['opd'] = $opd;
                                $chart = $this->load->view('templates/chart/bar', $this->data['chart'], true);
                                $this->data['chart'] = $chart;

                                // $this->data['pie'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $opd . "?group_by=date&month=" . $month)));
<<<<<<< HEAD
                                $this->data['pie'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $opd . "?date=" . $date . "&month=" . $month  . '&is_coming=1')));
=======
                                $this->data['pie'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $opd . "?date=" . $date . "&month=" . $month. '&is_coming=1')));
>>>>>>> 61e7934e36c490d5a4d8f7cbde33c90439882307
                                $pie = $this->load->view('templates/chart/pie', $this->data['pie'], true);
                                $this->data['pie'] = $pie;

                                //attendance coming out
                                $this->data['chart_out'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $opd . "?group_by=date&month=" . $month . '&is_coming=0')));
                                $this->data['opd'] = $opd;
                                $chart_out = $this->load->view('templates/chart/bar_out', $this->data['chart_out'], true);
                                $this->data['chart_out'] = $chart_out;

<<<<<<< HEAD
                                // $this->data['pie_out'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $opd . "?group_by=date&month=" . $month . '&is_coming=0')));
                                $this->data['pie_out'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $opd . "?date=" . $date . "&month=" . $month  . '&is_coming=0')));
=======
                                $this->data['pie_out'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $opd . "?group_by=date&month=" . $month . '&is_coming=0')));
                                // $this->data['pie_out'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $opd . "?date=" . $date . "&month=" . $month . '&is_coming=0')));
>>>>>>> 61e7934e36c490d5a4d8f7cbde33c90439882307
                                $pie_out = $this->load->view('templates/chart/pie_out', $this->data['pie_out'], true);
                                $this->data['pie_out'] = $pie_out;

                                $this->data['header'] = "Grafik Kehadiran Pegawai Bulan " . Util::MONTH[$month];
                        } else {
                                //attendance coming in
                                $this->data['chart'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $opd . "?date=" . $date . "&month=" . $month . '&is_coming=1')));
                                // var_dump( $this->data['chart']  ); die;
                                $this->data['opd'] = $opd;
                                $chart = $this->load->view('templates/chart/bar_hor', $this->data['chart'], true);
                                $this->data['chart'] = $chart;

                                $this->data['pie'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $opd . "?date=" . $date . "&month=" . $month . '&is_coming=1')));
                                $pie = $this->load->view('templates/chart/pie', $this->data['pie'], true);
                                $this->data['pie'] = $pie;

                                //attendance coming out
                                $this->data['chart_out'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $opd . "?date=" . $date . "&month=" . $month . '&is_coming=0')));
                                // var_dump( $this->data['chart_out']  ); die;
                                $this->data['opd'] = $opd;
                                $chart_out = $this->load->view('templates/chart/bar_hor_out', $this->data['chart_out'], true);
                                $this->data['chart_out'] = $chart_out;

                                $this->data['pie_out'] = json_decode(file_get_contents(site_url("api/attendance/chart/" . $opd . "?date=" . $date . "&month=" . $month . '&is_coming=0')));
                                $pie_out = $this->load->view('templates/chart/pie_out', $this->data['chart_out'], true);
                                $this->data['pie_out'] = $pie_out;

                                $this->data['header'] = "Grafik Kehadiran Pegawai Bulan " . Util::MONTH[$month];
                        }
                        #######################################################################################
                        $fingerprints = $this->fingerprint_model->fingerprints()->result();

                        $ids = array();
                        foreach ($fingerprints as $fingerprint) {
                                $ids[] = $fingerprint->id;
                        }
                        $this->data["fingerprint_ids"] = $ids;

                        #######################################################################################

                        $this->render("V_login_page");
                }
        }

        public function register()
        {
                $tables = $this->config->item('tables', 'ion_auth');
                $identity_column = $this->config->item('identity', 'ion_auth');
                $this->form_validation->set_rules($this->ion_auth->get_validation_config());
                $this->form_validation->set_rules('phone', "No Telepon", 'trim|required|is_unique[' . 'users' . '.' . $identity_column . ']');
                $this->form_validation->set_rules('password', "Kata Sandi", 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', "konfirmasi Kata Sandi", 'trim|required');
                if ($this->form_validation->run() === TRUE) {
                        $group_id = $this->input->post('group_id');


                        $email = strtolower($this->input->post('email'));
                        $identity = $this->input->post('phone');
                        $password = $this->input->post('password');
                        //$this->input->post('password');
                        $group_id = array($group_id);

                        $additional_data = array(
                                'first_name' => $this->input->post('first_name'),
                                'last_name' => $this->input->post('last_name'),
                                'email' => $this->input->post('email'),
                                'phone' => $this->input->post('phone'),
                                'address' => $this->input->post('address')
                        );
                }

                if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data, $group_id)) {
                        // check to see if we are creating the user
                        // redirect them back to the admin page
                        $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::SUCCESS, $this->ion_auth->messages()));
                        redirect("auth/login", 'refresh');
                } else {
                        $this->data = $this->ion_auth->get_form_data(); //harus paling pertama
                        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
                        if ((validation_errors()) || $this->ion_auth->errors()) $this->session->set_flashdata('alert', $this->alert->set_alert(Alert::DANGER, $this->data['message']));

                        $this->render("V_register_page");
                }
        }

        public function logout()
        {
                $this->data['title'] = "Logout";

                // log the user out
                $logout = $this->ion_auth->logout();

                // redirect them to the login page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect(site_url(), 'refresh');
        }
}
