<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    public function index() {
        $data['title'] = "Komodeo";
        $this->load->model('admin/loginmodel');
        $this->load->view('admin/includes/headerlogin', $data);
        $this->load->view('admin/loginview', $data);
        $this->load->view('admin/includes/footerlogin', $data);
        $this->is_logged_in();
        // $is_logged_in = $this->session->userdata('is_logged_in');
        // if(isset($is_logged_in) || $is_logged_in == true)
        // {
        // 	redirect('admin/home/index', 'refresh');
        // }
    }

    public function checklogin() {
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|callback_verifyuser');

        if ($this->form_validation->run() == false) {
            $data['title'] = "Komodeo";
            $this->load->view('admin/includes/headerlogin', $data);
            $this->load->view('admin/loginview', $data);
            $this->load->view('admin/includes/footerlogin', $data);
        } else {
            redirect('admin/home/index');
        }
    }

    public function verifyuser() {
        $username = $this->security->xss_clean($this->input->post('username'));
        $password = $this->security->xss_clean($this->input->post('password'));

        $this->load->model('admin/loginmodel');
        $row = $this->loginmodel->login($username, $password);
        //print_r($row);exit;
        if (!empty($row)) {
            $this->session->set_userdata(array(
                'admin_id' => $row->id,
                 'user_id'       => $row->user_id,
                 'user_type'       => $row->user_type,
                'username' => $username,
                'is_logged_in' => TRUE
            ));
            return true;
        } else {
            $this->form_validation->set_message('verifyuser', 'Username or Password is Incorrect. Please try again');
            return false;
        }
    }

    public function is_logged_in() {
        $is_logged_in = $this->session->userdata('is_logged_in');
        if (isset($is_logged_in) || $is_logged_in == true) {
            redirect('admin/home', 'refresh');
        }
    }

    public function logout(){
        //$this->my_model->logout_time();
        $user_data = $this->session->all_userdata();
            foreach ($user_data as $key => $value) {
                
                    $this->session->unset_userdata($key);
            
            }
            
            $this->session->sess_destroy();
            $this->session->set_flashdata( 'message', 'Successfully Logout..' );
            //$this->data['logout_message']="Successfully Logout..";
            redirect(base_url('admin'));
    }


}

?>