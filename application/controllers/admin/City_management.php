<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class City_management extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        $this->is_logged_in();
        $this->load->model('admin/city_management_model','my_model');
        $this->load->model('common_model');
        
        $this->load->helper('url', 'form', 'HTML');
        $this->load->library(array('form_validation', 'session'));

        //echo '<pre>';print_r($this->session->userdata('user_id'));

        if($this->session->userdata('user_id') != 'superadmin'){
         $this->data['roleResponsible'] = $this->common_model->get_responsibilities();
         }else{
         $this->data['roleResponsible'] = $this->common_model->get_default_responsibilities();
         }

        // echo '<pre>';print_r($this->data['roleResponsible']);exit;
    }

    /* ----------- rides -------------- */

     public function rides($type) {
        
        //echo '<pre>';print_r('123');exit;
        $this->data['type']=$type;
        if($type == 'car'){  
          $named_type='Car'; 
         }else if($type == 'bike'){
            $named_type='Bike'; 
         }else{
            $named_type='Auto'; 
         }
        $this->data['named_type']=$named_type; 
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/city_management/rides', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
   
    }

     

    public function all_rides() {
        $rides = $this->my_model->all_rides($_POST);
        $result_count = $this->my_model->all_rides($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($rides)),
            "data" => $rides);
        echo json_encode($json_data);
    }

/* ----------- end rides -------------- */

/* ----------- Bookings -------------- */

    public function bookings($type) {
        
        //echo '<pre>';print_r('123');exit;
        $this->data['type']=$type;
        if($type == 'car'){  
          $named_type='Car'; 
         }else if($type == 'bike'){
            $named_type='Bike'; 
         }else{
            $named_type='Auto'; 
         }
        $this->data['named_type']=$named_type; 
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/city_management/bookings', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
   
    }


    public function get_all_bookings() {
        $instantorders = $this->my_model->all_bookings($_POST);
        $result_count = $this->my_model->all_bookings($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($instantorders)),
            "data" => $instantorders);
        echo json_encode($json_data);
    }

/* ----------- End Bookings -------------- */


public function bookings_excel_export($type) {
        //print_r($_GET);exit;
        $_GET['uri_seg']=$type;
        $rides = $this->my_model->all_bookings($_GET);
        //print_r($rides);exit;
        $this->load->library('excel'); // load Excel Library
        $object_excel = new PHPExcel(); // new object for PHPExcel
        $object_excel->setActiveSheetIndex(0); // Create new worksheet
        $table_head = array('S.No', 'Booking Id', 'User Name', 'Vehicle Id', 'From lat', 'From lng', 'From Address', 'To Lat', 'To Lng', 'To Address', 'Mode', 'Vehicle Type', 'Seats Required', 'Ride Type', 'Ride Time', 'Trip Distance', 'Amount', 'Rider', 'Ride Id', 'Accepted Date', 'Status', 'Payment Status', 'Created Date'); //Set the names of header cells
        $head = 0;
        foreach ($table_head as $value) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow($head, 1, $value);
            $head++;
        }
        $body = 2; //Add some data
        $sno = 1;
        foreach ($rides as $row) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(0, $body, $sno++);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(1, $body, $row['booking_id']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(2, $body, $row['first_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(3, $body, $row['vehicle_id']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(4, $body, $row['from_lat']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(5, $body, $row['from_lng']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(6, $body, $row['from_address']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(7, $body, $row['to_lat']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(8, $body, $row['to_lng']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(9, $body, $row['to_address']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(10, $body, $row['mode']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(11, $body, $row['vehicle_type']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(12, $body, $row['seats_required']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(13, $body, $row['ride_type']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(14, $body, $row['ride_time']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(15, $body, $row['trip_distance']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(16, $body, $row['amount']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(17, $body, $row['ufirst_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(18, $body, $row['ride_id']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(19, $body, $row['accepted_date']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(20, $body, $row['status']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(21, $body, $row['payment_status']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(22, $body, $row['created_on']);
            $body++;
        }
        $object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel5'); // Explain format of Excel data
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Bookings.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/otherorders');
    }


    /* ----------- city cancellation -------------- */

    public function city_cancellation($type) {
        
        //echo '<pre>';print_r('123');exit;
        $this->data['type']=$type;
        if($type == 'car'){  
          $named_type='Car'; 
         }else if($type == 'bike'){
            $named_type='Bike'; 
         }else{
            $named_type='Auto'; 
         }
        $this->data['named_type']=$named_type; 
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/city_management/city_cancellation', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
   
    }


    public function get_all_city_cancellation() {
        $instantorders = $this->my_model->all_city_cancellation($_POST);
        $result_count = $this->my_model->all_city_cancellation($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($instantorders)),
            "data" => $instantorders);
        echo json_encode($json_data);
    }

/* ----------- End city cancellation -------------- */

     public function is_logged_in() {
        $is_logged_in = $this->session->userdata('is_logged_in');
        if (!isset($is_logged_in) || $is_logged_in != true) {
            redirect('admin/login', 'refresh');
        }
    }


}