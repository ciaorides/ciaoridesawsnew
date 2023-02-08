<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public $header = 'website/includes/header';
    public $footer = 'website/includes/footer';

    function __construct() {
        parent::__construct();
        $this->load->model('homemodel');
        $this->load->model('app/ws_model');
        date_default_timezone_set("Asia/Kolkata");
        $this->load->library('Ajax_pagination');
        $this->load->library('MathUtil');
        $this->load->library('PolyUtil');
        $this->load->library('SphericalUtil');
        $this->perPage = 5;
    }

    public function index() {
        $data['title'] = "CIAO Rides";
        $this->load->view('website/index', $data);
    }

    function sendsms() {
        echo 'test';
    }

    public function privacy_policy() {
        $data['title'] = "CIAO Rides";
        $this->load->view('website/privacy_policy', $data);
    }

    public function terms_conditions() {
        $data['title'] = "CIAO Rides";
        $this->load->view('website/terms_conditions', $data);
    }

    public function refund_policy() {
        $data['title'] = "CIAO Rides";
        $this->load->view('website/refund_policy', $data);
    }

    public function contact() {
        $data['title'] = "CIAO Rides";
        $data['msg'] = "";
        $code = $this->generateCode(6);

        $newdata = array('security_code' => $code);
        $this->session->set_userdata($newdata);
        $this->load->view('website/contact', $data);
    }

    public function update_contactform() {
        $data['title'] = "CIAO Rides";

        $captcha = $this->input->post('ccode');
        $recaptcha = $this->input->post('captcha');
        if ($captcha != $recaptcha) {
            $data['msg'] = "Mismatch, try again";
            $this->load->view('website/contact', $data);
        } else {

            $name = $this->input->post('name');
            $email = $this->input->post('email');
            $subject = $this->input->post('subject');
            $msg = $this->input->post('message');
            $contactno = $this->input->post('contactno');

            $message = "Name :" . $name . "<br>" . "Email: " . $email . "<br>" . "ContactNo:" . $contactno . "<br>" . "Message:" . $msg;
            $mail = SendMAIL($email, $message, $subject);

            //var_dump($mail);
            if ($mail['status'] == 'true') {
                $this->session->set_flashdata('success', 'Submitted Successfully.');
                redirect('contact', 'refresh');
                //$this->load->view('website/contact', $data);
            } else {
                $this->session->set_flashdata('success', 'Message sending failed.');
                redirect('contact', 'refresh');
            }
        }
    }

    public function generateCode($characters) {
        /* list all possible characters, similar looking characters and vowels have been removed */
        $possible = '23456789bcdfghjkmnpqrstvwxyz';
        $code = '';
        $i = 0;
        while ($i < $characters) {
            $code .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
            $i++;
        }
        return $code;
    }

    public function verify_email($id) {
        $data['title'] = "CIAO Rides";
        $salt = "CIAO_SECRET_STUFF";
        $decrypted_id_raw = base64_decode($id);
        $decrypted_id = preg_replace(sprintf('/%s/', $salt), '', $decrypted_id_raw);
        update_table('users', array('email_id_verified' => 'yes'), array('id' => $decrypted_id));
        redirect("", 'refresh');
    }

    public function setHeaderFooter($view, $data) {
        $data['title'] = "CIAO Rides";
        $this->load->view($this->header, $data);
        $this->load->view($view, $data);
        $this->load->view($this->footer);
    }

}
