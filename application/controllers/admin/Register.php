<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Register extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        $this->is_logged_in();
        $this->load->model('admin/registermodel');
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

    /* ----------- amount_calculations -------------- user_details */

    function get_city() {
        $city = GetCityName(16.302315, 80.435720);
        echo $res;
    }

    function generate_userids() {
        $referal_code = '';
        $increment_value = 0;
        //Generate the referal CODE
//        $seect_max = $this->db->select_max('id')->get('users')->row();
//        $increment_value = $seect_max->id;
//        $increment_value++;
//        $referal_code .= 'CIAO-';
//        $referal_code .= str_pad($increment_value, 6, "0", STR_PAD_LEFT);
//        echo $referal_code;
//        echo '<pre>';
        $users = $this->db->get('users')->result();
//        print_r($users);
        foreach ($users as $u) {
            $referal_code = '';
            $referal_code .= 'CIAO-';
            $referal_code .= str_pad($u->id, 6, "0", STR_PAD_LEFT);
            $this->db->set(['userid' => $referal_code])->where('id', $u->id)->update('users');
        }
        die('success');
    }

    public function all_amount_calculations() {
        $amount_calculations = $this->registermodel->all_amount_calculations($_POST);
        $result_count = $this->registermodel->all_amount_calculations($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($amount_calculations)),
            "data" => $amount_calculations);
        echo json_encode($json_data);
    }

    public function amount_calculations_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_amount_calculations($_GET);
        //print_r($rides);exit;
        $this->load->library('excel'); // load Excel Library
        $object_excel = new PHPExcel(); // new object for PHPExcel
        $object_excel->setActiveSheetIndex(0); // Create new worksheet
        $table_head = array('S.No', 'Per Km', 'Travel Type', '1 to 25', '26 to 50', '51 to 100', '101 to 300', '301 to 500', 'Greater than 500', 'Vehicle Type', 'Service tax', 'Created Date'); //Set the names of header cells
        $head = 0;
        foreach ($table_head as $value) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow($head, 1, $value);
            $head++;
        }
        $body = 2; //Add some data
        $sno = 1;
        foreach ($rides as $row) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(0, $body, $sno++);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(1, $body, $row['per_km']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(2, $body, $row['travel_type']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(3, $body, $row['1_to_25']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(4, $body, $row['26_to_50']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(5, $body, $row['51_to_100']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(6, $body, $row['101_to_300']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(7, $body, $row['301_to_500']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(8, $body, $row['greater_than_500']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(9, $body, $row['vehicle_type']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(10, $body, $row['service_tax']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(11, $body, $row['created_on']);
            $body++;
        }
        $object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel5'); // Explain format of Excel data
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Amount Calculations.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/amount_calculations');
    }

    public function amount_calculations() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_amount_calculations', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function add_amount_calculations() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/add_amount_calculations', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function edit_amount_calculations($id) {
        $this->data['title'] = "CIAO Rides";
        $this->data['row'] = get_table_row('amount_calculations', array('id' => $id));
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/edit_amount_calculations', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function update_amount_calculations() {
        $this->data = array(
            //'travel_type' => $this->input->post('travel_type'),
            //'vehicle_type' => $this->input->post('vehicle_type'),
            'base_fare' => $this->input->post('base_fare'),
            '0to1' => $this->input->post('0to1'),
            '2to10' => $this->input->post('2to10'),
            '11to30' => $this->input->post('11to30'),
            '2to25' => $this->input->post('2to25'),
            '>25' => $this->input->post('>25'),
            'service_tax' => $this->input->post('service_tax'),
            'schedule_before_time' => $this->input->post('schedule_before_time'),
            'schedule_before_percentage' => $this->input->post('schedule_before_percentage'),
            'schedule_lessthan_time' => $this->input->post('schedule_lessthan_time'),
            'schedule_lessthan_percentage' => $this->input->post('schedule_lessthan_percentage'),
            'instant_after_time' => $this->input->post('instant_after_time'),
            'instant_after_percentage' => $this->input->post('instant_after_percentage'),
            'ciao_commission' => $this->input->post('ciao_commission'),
            'payment_gateway_commision' => $this->input->post('payment_gateway_commision'),
        );
        if ($this->input->post('amount_calculations_id') == "") {
            $this->data['created_on'] = date('Y-m-d H:i:s');
            insert_table('amount_calculations', $this->data, '', false);
            $this->session->set_flashdata('success', 'Record added Successfully.');
            redirect('admin/register/add_amount_calculations', 'refresh');
        } else {
            $this->data['modified_on'] = date('Y-m-d H:i:s');
            update_table('amount_calculations', $this->data, array('id' => $this->input->post('amount_calculations_id')));
            //echo $this->db->last_query();exit;
            $this->session->set_flashdata('success', 'Record Updated Successfully.');
            redirect('admin/register/edit_amount_calculations/' . $this->input->post('amount_calculations_id'), 'refresh');
        }
    }

    public function get_charges() {
        $this->data['title'] = "CIAO Rides";
        $vehicle_type = $this->input->post('vehicle_type');
        $result = get_table_row('amount_calculations', array('vehicle_type' => $vehicle_type));
        if (!empty($result)) {
            echo "exists";
        } else {
            echo "add";
        }
    }

    public function delete_amount_calculations($amount_calculations_id) {
        if (delete_record('amount_calculations', array('id' => $amount_calculations_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/amount_calculations', 'refresh');
    }

    /* ----------- /amount_calculations -------------- */



    /* ----------- chats -------------- */

    public function all_chats() {
        $chats = $this->registermodel->all_chats($_POST);
        $result_count = $this->registermodel->all_chats($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($chats)),
            "data" => $chats);
        echo json_encode($json_data);
    }

    public function chats() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_chats', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_chats($chats_id) {
        if (delete_record('chats', array('id' => $chats_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/chats', 'refresh');
    }

    public function get_user_chats() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->registermodel->get_user_chats($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_user_chats', $this->data);
    }

    /* ----------- /chats -------------- */


    /* ----------- rider_current_location -------------- */

    public function all_rider_current_location() {
        $rider_current_location = $this->registermodel->all_rider_current_location($_POST);
        $result_count = $this->registermodel->all_rider_current_location($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($rider_current_location)),
            "data" => $rider_current_location);
        echo json_encode($json_data);
    }

    public function rider_current_location() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_rider_current_location', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_rider_current_location($rider_current_location_id) {
        if (delete_record('rider_current_location', array('id' => $rider_current_location_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/rider_current_location', 'refresh');
    }

    public function get_user_rider_current_location() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->registermodel->get_user_rider_current_location($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_user_rider_current_location', $this->data);
    }

    /* ----------- /rider_current_location -------------- */




    /* ----------- emergency_contacts -------------- */

    public function all_emergency_contacts() {
        $emergency_contacts = $this->registermodel->all_emergency_contacts($_POST);
        $result_count = $this->registermodel->all_emergency_contacts($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($emergency_contacts)),
            "data" => $emergency_contacts);
        echo json_encode($json_data);
    }

    public function emergency_contacts_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_emergency_contacts($_GET);
        //print_r($rides);exit;
        $this->load->library('excel'); // load Excel Library
        $object_excel = new PHPExcel(); // new object for PHPExcel
        $object_excel->setActiveSheetIndex(0); // Create new worksheet
        $table_head = array('S.No', 'User Name', 'Name', 'User Mobile No', 'Emergency contact name', 'Emergency mobile No', 'Created Date'); //Set the names of header cells
        $head = 0;
        foreach ($table_head as $value) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow($head, 1, $value);
            $head++;
        }
        $body = 2; //Add some data
        $sno = 1;
        foreach ($rides as $row) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(0, $body, $sno++);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(1, $body, $row['first_name'] . ' ' . $row['last_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(2, $body, $row['first_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(3, $body, $row['umobile']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(4, $body, $row['name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(5, $body, $row['mobile']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(6, $body, $row['created_on']);
            $body++;
        }
        $object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel5'); // Explain format of Excel data
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Emergency Contacts.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/emergency_contacts');
    }

    public function emergency_contacts() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_emergency_contacts', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_emergency_contacts($emergency_contacts_id) {
        if (delete_record('emergency_contacts', array('id' => $emergency_contacts_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/emergency_contacts', 'refresh');
    }

    public function get_user_emergency_contacts() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->registermodel->get_user_emergency_contacts($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_user_emergency_contacts', $this->data);
    }

    /* ----------- /emergency_contacts -------------- */



    /* ----------- favourite_locations -------------- */

    public function all_favourite_locations() {
        $favourite_locations = $this->registermodel->all_favourite_locations($_POST);
        $result_count = $this->registermodel->all_favourite_locations($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($favourite_locations)),
            "data" => $favourite_locations);
        echo json_encode($json_data);
    }

    public function favourite_locations_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_favourite_locations($_GET);
        //print_r($rides);exit;
        $this->load->library('excel'); // load Excel Library
        $object_excel = new PHPExcel(); // new object for PHPExcel
        $object_excel->setActiveSheetIndex(0); // Create new worksheet
        $table_head = array('S.No', 'User Name', 'Contact Number', 'Type of Location', 'Address', 'Lat', 'Lng', 'Created Date'); //Set the names of header cells
        $head = 0;
        foreach ($table_head as $value) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow($head, 1, $value);
            $head++;
        }
        $body = 2; //Add some data
        $sno = 1;
        foreach ($rides as $row) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(0, $body, $sno++);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(1, $body, $row['first_name'] . ' ' . $row['last_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(2, $body, $row['mobile']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(3, $body, $row['type']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(4, $body, $row['address']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(5, $body, $row['lat']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(6, $body, $row['lng']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(7, $body, $row['created_on']);
            $body++;
        }
        $object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel5'); // Explain format of Excel data
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Favourite Locations.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/favourite_locations');
    }

    public function favourite_locations() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_favourite_locations', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_favourite_locations($favourite_locations_id) {
        if (delete_record('favourite_locations', array('id' => $favourite_locations_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/favourite_locations', 'refresh');
    }

    public function get_user_favourite_locations() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->registermodel->get_user_favourite_locations($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_user_favourite_locations', $this->data);
    }

    /* ----------- /favourite_locations -------------- */


    /* ----------- notifications -------------- */

    public function all_notifications() {
        $notifications = $this->registermodel->all_notifications($_POST);
        $result_count = $this->registermodel->all_notifications($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($notifications)),
            "data" => $notifications);
        echo json_encode($json_data);
    }

    public function notifications() {
        $this->data['title'] = "Bikes7";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_notifications', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function update_notifications() {

        $this->data = array(
            'title' => $this->input->post('title'),
            'description' => $this->input->post('description'),
        );

        $this->data['created_on'] = date('Y-m-d H:i:s');
        $this->registermodel->insert_notifications($this->data);
        $this->session->set_flashdata('success', 'Record added Successfully.');
        redirect('admin/register/notifications', 'refresh');
    }

    /* ----------- notifications -------------- */

    /* ----------- rides -------------- */

    public function all_rides() {
        $rides = $this->registermodel->all_rides($_POST);
        $result_count = $this->registermodel->all_rides($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($rides)),
            "data" => $rides);
        echo json_encode($json_data);
    }

    public function rides_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_rides($_GET);
        //print_r($rides);exit;
        $this->load->library('excel'); // load Excel Library
        $object_excel = new PHPExcel(); // new object for PHPExcel
        $object_excel->setActiveSheetIndex(0); // Create new worksheet
        $table_head = array('S.No', 'User Name', 'vehicle Reg. Number', 'vehicle type', 'Status', 'Ride mode', 'trip distance', 'amount per head', 'Created Date'); //Set the names of header cells
        $head = 0;
        foreach ($table_head as $value) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow($head, 1, $value);
            $head++;
        }
        $body = 2; //Add some data
        $sno = 1;
        foreach ($rides as $row) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(0, $body, $sno++);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(1, $body, $row['first_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(2, $body, $row['vehicle_id']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(3, $body, $row['vehicle_type']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(4, $body, $row['status']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(5, $body, $row['mode']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(6, $body, $row['trip_distance']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(7, $body, $row['amount_per_head']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(8, $body, $row['created_on']);
            $body++;
        }
        $object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel5'); // Explain format of Excel data
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Rides.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/rides');
    }

    public function rides() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_rides', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_rides($rides_id) {
        if (delete_record('rides', array('id' => $rides_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/rides', 'refresh');
    }

    public function get_user_rides() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->registermodel->get_user_rides($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_user_rides', $this->data);
    }

    public function ride_details() {
        $this->data['title'] = "CIAO Rides";
        if ($query = $this->registermodel->ride_details()) {
            $this->data['row'] = $query;
        }
//        echo '<pre>';
//        print_r($this->data['row']);
//        echo '</pre>';
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/ride_details', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    /* ----------- /rides -------------- */

    /* ----------- ongoing -------------- */

    public function all_ongoing() {
        $ongoing = $this->registermodel->all_ongoing($_POST);
        $result_count = $this->registermodel->all_ongoing($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($ongoing)),
            "data" => $ongoing);
        echo json_encode($json_data);
    }

    public function ongoing_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_ongoing($_GET);
        //print_r($rides);exit;
        $this->load->library('excel'); // load Excel Library
        $object_excel = new PHPExcel(); // new object for PHPExcel
        $object_excel->setActiveSheetIndex(0); // Create new worksheet
        $table_head = array('S.No', 'User Name', 'vehicle Reg. Number', 'vehicle type', 'Status', 'Ride mode', 'trip distance', 'amount per head', 'Created Date'); //Set the names of header cells
        $head = 0;
        foreach ($table_head as $value) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow($head, 1, $value);
            $head++;
        }
        $body = 2; //Add some data
        $sno = 1;
        foreach ($rides as $row) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(0, $body, $sno++);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(1, $body, $row['first_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(2, $body, $row['vehicle_id']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(3, $body, $row['vehicle_type']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(4, $body, $row['status']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(5, $body, $row['mode']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(6, $body, $row['trip_distance']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(7, $body, $row['amount_per_head']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(8, $body, $row['created_on']);
            $body++;
        }
        $object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel5'); // Explain format of Excel data
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Rides.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/ongoing');
    }

    public function ongoing() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_ongoing', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_ongoing($ongoing_id) {
        if (delete_record('rides', array('id' => $ongoing_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/ongoing', 'refresh');
    }

    public function get_user_ongoing() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->registermodel->get_user_ongoing($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_user_ongoing', $this->data);
    }

    public function ongoing_details() {
        $this->data['title'] = "CIAO Rides";
        if ($query = $this->registermodel->ongoing_details()) {
            $this->data['row'] = $query;
        }
        //print_r($this->data['row']);	exit;

        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/ongoing_details', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    /* ----------- /ongoing -------------- */
    /* ----------- completed -------------- */

    public function all_completed() {
        $completed = $this->registermodel->all_completed($_POST);
        $result_count = $this->registermodel->all_completed($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($completed)),
            "data" => $completed);
        echo json_encode($json_data);
    }

    public function completed_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_completed($_GET);
        //print_r($rides);exit;
        $this->load->library('excel'); // load Excel Library
        $object_excel = new PHPExcel(); // new object for PHPExcel
        $object_excel->setActiveSheetIndex(0); // Create new worksheet
        $table_head = array('S.No', 'User Name', 'vehicle Reg. Number', 'vehicle type', 'Status', 'Ride mode', 'trip distance', 'amount per head', 'Created Date'); //Set the names of header cells
        $head = 0;
        foreach ($table_head as $value) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow($head, 1, $value);
            $head++;
        }
        $body = 2; //Add some data
        $sno = 1;
        foreach ($rides as $row) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(0, $body, $sno++);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(1, $body, $row['first_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(2, $body, $row['vehicle_id']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(3, $body, $row['vehicle_type']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(4, $body, $row['status']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(5, $body, $row['mode']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(6, $body, $row['trip_distance']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(7, $body, $row['amount_per_head']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(8, $body, $row['created_on']);
            $body++;
        }
        $object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel5'); // Explain format of Excel data
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Rides.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/completed');
    }

    public function completed() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_completed', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_completed($completed_id) {
        if (delete_record('rides', array('id' => $completed_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/completed', 'refresh');
    }

    public function get_user_completed() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->registermodel->get_user_completed($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_user_completed', $this->data);
    }

    public function completed_details() {
        $this->data['title'] = "CIAO Rides";
        if ($query = $this->registermodel->completed_details()) {
            $this->data['row'] = $query;
        }
        //print_r($this->data['row']);	exit;

        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/completed_details', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    /* ----------- /completed -------------- */
    /* ----------- shceduled -------------- */

    public function all_shceduled() {
        $shceduled = $this->registermodel->all_shceduled($_POST);
        $result_count = $this->registermodel->all_shceduled($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($shceduled)),
            "data" => $shceduled);
        echo json_encode($json_data);
    }

    public function scheduled_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_shceduled($_GET);
        //print_r($rides);exit;
        $this->load->library('excel'); // load Excel Library
        $object_excel = new PHPExcel(); // new object for PHPExcel
        $object_excel->setActiveSheetIndex(0); // Create new worksheet
        $table_head = array('S.No', 'User Name', 'vehicle Reg. Number', 'vehicle type', 'Status', 'Ride mode', 'trip distance', 'amount per head', 'Ride booking date', 'Ride starting date'); //Set the names of header cells
        $head = 0;
        foreach ($table_head as $value) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow($head, 1, $value);
            $head++;
        }
        $body = 2; //Add some data
        $sno = 1;
        foreach ($rides as $row) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(0, $body, $sno++);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(1, $body, $row['first_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(2, $body, $row['vehicle_id']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(3, $body, $row['vehicle_type']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(4, $body, $row['status']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(5, $body, $row['mode']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(6, $body, $row['trip_distance']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(7, $body, $row['amount_per_head']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(8, $body, $row['created_on']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(9, $body, $row['ride_time']);
            $body++;
        }
        $object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel5'); // Explain format of Excel data
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Rides.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/shceduled');
    }

    public function shceduled() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_shceduled', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_shceduled($shceduled_id) {
        if (delete_record('rides', array('id' => $shceduled_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/shceduled', 'refresh');
    }

    public function get_user_shceduled() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->registermodel->get_user_shceduled($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_user_shceduled', $this->data);
    }

    public function shceduled_details() {
        $this->data['title'] = "CIAO Rides";
        if ($query = $this->registermodel->shceduled_details()) {
            $this->data['row'] = $query;
        }
        //print_r($this->data['row']);    exit;

        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/shceduled_details', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    /* ----------- /shceduled -------------- */
    /* ----------- ride_lat_lngs -------------- */

    public function all_ride_lat_lngs() {
        $ride_lat_lngs = $this->registermodel->all_ride_lat_lngs($_POST);
        $result_count = $this->registermodel->all_ride_lat_lngs($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($ride_lat_lngs)),
            "data" => $ride_lat_lngs);
        echo json_encode($json_data);
    }

    public function ride_lat_lngs() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_ride_lat_lngs', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_ride_lat_lngs($ride_lat_lngs_id) {
        if (delete_record('ride_lat_lngs', array('id' => $ride_lat_lngs_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/ride_lat_lngs', 'refresh');
    }

    public function get_user_ride_lat_lngs() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->registermodel->get_user_ride_lat_lngs($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_user_ride_lat_lngs', $this->data);
    }

    /* ----------- /ride_lat_lngs -------------- */




    /* ----------- user_bank_details -------------- */

    public function all_user_bank_details() {
        $user_bank_details = $this->registermodel->all_user_bank_details($_POST);
        $result_count = $this->registermodel->all_user_bank_details($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($user_bank_details)),
            "data" => $user_bank_details);
        echo json_encode($json_data);
    }

    public function user_bank_details_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_user_bank_details($_GET);
        //print_r($rides);exit;
        $this->load->library('excel'); // load Excel Library
        $object_excel = new PHPExcel(); // new object for PHPExcel
        $object_excel->setActiveSheetIndex(0); // Create new worksheet
        $table_head = array('S.No', 'User Name', 'Mobile', 'Country Name', 'Bank Name', 'Account Holder Name', 'Account Number', 'Ifsc Code', 'Created Date'); //Set the names of header cells
        $head = 0;
        foreach ($table_head as $value) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow($head, 1, $value);
            $head++;
        }
        $body = 2; //Add some data
        $sno = 1;
        foreach ($rides as $row) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(0, $body, $sno++);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(1, $body, $row['first_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(2, $body, $row['mobile']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(3, $body, $row['country_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(4, $body, $row['bank_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(5, $body, $row['account_holder_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(6, $body, $row['account_number']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(7, $body, $row['ifsc_code']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(8, $body, $row['created_on']);
            $body++;
        }
        $object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel5'); // Explain format of Excel data
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="user bank details.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/user_bank_details');
    }

    public function user_bank_details() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_user_bank_details', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_user_bank_details($user_bank_details_id) {
        if (delete_record('user_bank_details', array('id' => $user_bank_details_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/user_bank_details', 'refresh');
    }

    public function get_user_user_bank_details() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->registermodel->get_user_user_bank_details($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_user_user_bank_details', $this->data);
    }

    /* ----------- /user_bank_details -------------- */





    /* ----------- user_feedback -------------- */

    public function all_user_feedback() {
        $user_feedback = $this->registermodel->all_user_feedback($_POST);
        $result_count = $this->registermodel->all_user_feedback($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($user_feedback)),
            "data" => $user_feedback);
        echo json_encode($json_data);
    }

    public function user_feedback_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_user_feedback($_GET);
        //print_r($rides);exit;
        $this->load->library('excel'); // load Excel Library
        $object_excel = new PHPExcel(); // new object for PHPExcel
        $object_excel->setActiveSheetIndex(0); // Create new worksheet
        $table_head = array('S.No', 'User Name', 'Comments', 'Created Date'); //Set the names of header cells
        $head = 0;
        foreach ($table_head as $value) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow($head, 1, $value);
            $head++;
        }
        $body = 2; //Add some data
        $sno = 1;
        foreach ($rides as $row) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(0, $body, $sno++);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(1, $body, $row['first_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(2, $body, $row['description']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(3, $body, $row['created_on']);
            $body++;
        }
        $object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel5'); // Explain format of Excel data
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="User feed backs.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/user_feedback');
    }

    public function user_feedback() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_user_feedback', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_user_feedback($user_feedback_id) {
        if (delete_record('user_feedback', array('id' => $user_feedback_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/user_feedback', 'refresh');
    }

    public function get_user_user_feedback() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->registermodel->get_user_user_feedback($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_user_user_feedback', $this->data);
    }

    /* ----------- /user_feedback -------------- */




    /* ----------- user_ratings -------------- */

    public function all_user_ratings() {
        $user_ratings = $this->registermodel->all_user_ratings($_POST);
        $result_count = $this->registermodel->all_user_ratings($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($user_ratings)),
            "data" => $user_ratings);
        echo json_encode($json_data);
    }

    public function user_ratings_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_user_ratings($_GET);
        //print_r($rides);exit;
        $this->load->library('excel'); // load Excel Library
        $object_excel = new PHPExcel(); // new object for PHPExcel
        $object_excel->setActiveSheetIndex(0); // Create new worksheet
        $table_head = array('S.No', 'User Name', 'Order Id', 'Booking Id', 'Booking Date', 'Ratings', 'Reviews', 'Created Date'); //Set the names of header cells
        $head = 0;
        foreach ($table_head as $value) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow($head, 1, $value);
            $head++;
        }
        $body = 2; //Add some data
        $sno = 1;
        foreach ($rides as $row) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(0, $body, $sno++);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(1, $body, $row['first_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(2, $body, $row['order_id']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(3, $body, $row['booking_id']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(4, $body, $row['ocreated_on']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(5, $body, $row['ratings']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(6, $body, $row['reviews']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(7, $body, $row['created_on']);
            $body++;
        }
        $object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel5'); // Explain format of Excel data
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="User ratings.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/rides');
    }

    public function user_ratings() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_user_ratings', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_user_ratings($user_ratings_id) {
        if (delete_record('user_ratings', array('id' => $user_ratings_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/user_ratings', 'refresh');
    }

    public function get_user_user_ratings() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->registermodel->get_user_user_ratings($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_user_user_ratings', $this->data);
    }

    /* ----------- /user_ratings -------------- */

    /* ----------- user_vehicles -------------- */

    public function all_user_vehicles() {
        $user_vehicles = $this->registermodel->all_user_vehicles($_POST);
        $result_count = $this->registermodel->all_user_vehicles($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($user_vehicles)),
            "data" => $user_vehicles);
        echo json_encode($json_data);
    }

    public function user_vehicles_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_user_vehicles($_GET);
        //print_r($rides);exit;
        $this->load->library('excel'); // load Excel Library
        $object_excel = new PHPExcel(); // new object for PHPExcel
        $object_excel->setActiveSheetIndex(0); // Create new worksheet
        $table_head = array('S.No', 'User Name', 'Mobile', 'Number plate', 'Vehicle Brand', 'Vehicle Model', 'Vehicle Type', 'Color', 'Reg.year of vehicle', 'Vehicle picture', 'Created Date'); //Set the names of header cells
        $head = 0;
        foreach ($table_head as $value) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow($head, 1, $value);
            $head++;
        }
        $body = 2; //Add some data
        $sno = 1;
        foreach ($rides as $row) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(0, $body, $sno++);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(1, $body, $row['first_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(2, $body, $row['mobile']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(3, $body, $row['number_plate']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(4, $body, $row['make_id']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(5, $body, $row['model_id']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(6, $body, $row['vehicle_type']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(7, $body, $row['color']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(8, $body, $row['year']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(9, $body, base_url() . $row['vehicle_picture']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(10, $body, $row['created_on']);
            $body++;
        }
        $object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel5'); // Explain format of Excel data
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="User vehicles.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/user_vehicles');
    }

    public function user_vehicles() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_user_vehicles', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_user_vehicles($user_vehicles_id) {
        if (delete_record('user_vehicles', array('id' => $user_vehicles_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/user_vehicles', 'refresh');
    }

    public function get_user_user_vehicles() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->registermodel->get_user_user_vehicles($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_user_user_vehicles', $this->data);
    }

    /* ----------- /user_vehicles -------------- */

    /* ----------- vehicle_makes -------------- */

    public function all_vehicle_makes() {
        $vehicle_makes = $this->registermodel->all_vehicle_makes($_POST);
        $result_count = $this->registermodel->all_vehicle_makes($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($vehicle_makes)),
            "data" => $vehicle_makes);
        echo json_encode($json_data);
    }

    public function vehicle_makes_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_vehicle_makes($_GET);
        //print_r($rides);exit;
        $this->load->library('excel'); // load Excel Library
        $object_excel = new PHPExcel(); // new object for PHPExcel
        $object_excel->setActiveSheetIndex(0); // Create new worksheet
        $table_head = array('S.No', 'Title', 'Created Date', 'Modified Date'); //Set the names of header cells
        $head = 0;
        foreach ($table_head as $value) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow($head, 1, $value);
            $head++;
        }
        $body = 2; //Add some data
        $sno = 1;
        foreach ($rides as $row) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(0, $body, $sno++);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(1, $body, $row['title']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(2, $body, $row['created_on']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(3, $body, $row['modified_on']);
            $body++;
        }
        $object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel5'); // Explain format of Excel data
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Vehicle Brands.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/rides');
    }

    public function vehicle_makes() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_vehicle_makes', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function add_vehicle_makes() {
        $this->data['title'] = "CIAO Rides";

        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/add_vehicle_makes', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function update_vehicle_makes() {

        $this->data = array(
            'title' => $this->input->post('title'),
            'vehicle_type' => $this->input->post('vehicle_type'),
        );

        if ($this->input->post('category_id') == "") {
            $this->data['created_on'] = date('Y-m-d H:i:s');
            $this->registermodel->insert_vehicle_makes($this->data);
            $this->session->set_flashdata('success', 'Record added Successfully.');
            redirect('admin/register/add_vehicle_makes', 'refresh');
        } else {
            // var_dump($this->data);exit;
            $this->data['modified_on'] = date('Y-m-d H:i:s');
            $this->registermodel->update_vehicle_makes($this->data, $this->input->post('category_id'));
            $this->session->set_flashdata('success', 'Record Updated Successfully.');
            /* 	redirect('admin/register/edit_vehicle_makes/'.$this->input->post('category_id'), 'refresh'); */

            redirect('admin/register/vehicle_makes', 'refresh');
        }
    }

    public function edit_vehicle_makes($vehicle_makes_id) {
        $this->data['title'] = "CIAO Rides";
        if ($query = $this->registermodel->edit_vehicle_makes()) {
            $this->data['row'] = $query;
        }
        //var_dump($this->data['row']);
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/edit_vehicle_makes', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_vehicle_makes($vehicle_makes_id) {
        if (delete_record('vehicle_makes', array('id' => $vehicle_makes_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/vehicle_makes', 'refresh');
    }

    public function get_user_vehicle_makes() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->registermodel->get_user_vehicle_makes($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_user_vehicle_makes', $this->data);
    }

    /* ----------- /vehicle_makes -------------- */


    /* ----------- vehicle_models -------------- */

    public function all_vehicle_models() {
        $vehicle_models = $this->registermodel->all_vehicle_models($_POST);
        $result_count = $this->registermodel->all_vehicle_models($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($vehicle_models)),
            "data" => $vehicle_models);
        echo json_encode($json_data);
    }

    public function vehicle_models_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_vehicle_models($_GET);
        //print_r($rides);exit;
        $this->load->library('excel'); // load Excel Library
        $object_excel = new PHPExcel(); // new object for PHPExcel
        $object_excel->setActiveSheetIndex(0); // Create new worksheet
        $table_head = array('S.No', 'Vehicle Brand', 'Vehicle Model', 'Created Date', 'Modified Date'); //Set the names of header cells
        $head = 0;
        foreach ($table_head as $value) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow($head, 1, $value);
            $head++;
        }
        $body = 2; //Add some data
        $sno = 1;
        foreach ($rides as $row) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(0, $body, $sno++);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(1, $body, $row['vehicle_title']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(2, $body, $row['title']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(3, $body, $row['created_on']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(4, $body, $row['modified_on']);
            $body++;
        }
        $object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel5'); // Explain format of Excel data
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Vehicle Models.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/rides');
    }

    public function vehicle_models() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_vehicle_models', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function add_vehicle_models() {
        $this->data['title'] = "CIAO Rides";
        $this->data['categories'] = $this->registermodel->vehicle_makes();
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/add_vehicle_models', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function update_vehicle_models() {

        if ($this->input->post('sub_category_id') == "") {
            $title = $this->input->post('title');
            $make_id = $this->input->post('make_id');

            $this->data = array(
                'title' => $title,
                'make_id' => $make_id,
                'created_on' => date('Y-m-d H:i:s')
            );


            $this->registermodel->insert_vehicle_models($this->data);
            $this->session->set_flashdata('success', 'Record added Successfully.');
            redirect('admin/register/vehicle_models', 'refresh');
        } else {
            $this->data = array(
                'make_id' => $this->input->post('make_id'),
                'title' => $this->input->post('title'),
            );
            $this->data['modified_on'] = date('Y-m-d H:i:s');
            $this->registermodel->update_vehicle_models($this->data, $this->input->post('sub_category_id'));
            $this->session->set_flashdata('success', 'Record Updated Successfully.');
            redirect('admin/register/edit_vehicle_models/' . $this->input->post('sub_category_id'), 'refresh');
        }
    }

    public function edit_vehicle_models($vehicle_models_id) {
        $this->data['title'] = "CIAO Rides";
        if ($query = $this->registermodel->edit_vehicle_models()) {
            $this->data['row'] = $query;
        }
        $this->data['categories'] = $this->registermodel->vehicle_makes();
        $this->data['sub_categories'] = $this->registermodel->vehicle_models();


        //	var_dump($this->data['row']);
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/edit_vehicle_models', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_vehicle_models($vehicle_models_id) {
        if (delete_record('vehicle_models', array('id' => $vehicle_models_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/vehicle_models', 'refresh');
    }

    public function get_user_vehicle_models() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->registermodel->get_user_vehicle_models($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_user_vehicle_models', $this->data);
    }

    /* ----------- /vehicle_models -------------- */

    /* ----------- otherorders -------------- */

    public function all_otherorders() {
        $otherorders = $this->registermodel->all_otherorders($_POST);
        $result_count = $this->registermodel->all_otherorders($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($otherorders)),
            "data" => $otherorders);
        echo json_encode($json_data);
    }

    public function otherorders_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_otherorders($_GET);
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

    public function otherorders() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_otherorders', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_otherorders($otherorders_id) {
        if (delete_record('orders', array('id' => $otherorders_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/otherorders', 'refresh');
    }

    public function get_user_otherorders() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->registermodel->get_user_otherorders($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_user_otherorders', $this->data);
    }

    public function order_details() {
        $this->data['title'] = "CIAO Rides";
        if ($query = $this->registermodel->order_details()) {
            $this->data['row'] = $query;
        }
        //print_r($this->data['row']);	exit;

        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/order_details', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    /* ----------- /otherorders -------------- */


    /* ----------- instantorders -------------- */

    public function all_instantorders() {
        $instantorders = $this->registermodel->all_instantorders($_POST);
        $result_count = $this->registermodel->all_instantorders($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($instantorders)),
            "data" => $instantorders);
        echo json_encode($json_data);
    }

    public function instantorders_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_instantorders($_GET);
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
        redirect(base_url() . 'register/instantorders');
    }

    public function instantorders() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_instantorders', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_instantorders($instantorders_id) {
        if (delete_record('orders', array('id' => $instantorders_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/instantorders', 'refresh');
    }

    public function get_user_instantorders() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->registermodel->get_user_instantorders($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_user_instantorders', $this->data);
    }

//all_instantorders
    /* ----------- /instantorders -------------- */

    public function all_bookings() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_all_bookings', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function get_all_bookings() {
        $instantorders = $this->registermodel->all_bookings($_POST);
        $result_count = $this->registermodel->all_bookings($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($instantorders)),
            "data" => $instantorders);
        echo json_encode($json_data);
    }

    /* ----------- User Payments -------------- */

    public function all_user_payments() {
        $otherorders = $this->registermodel->all_payment_details($_POST);
        $result_count = $this->registermodel->all_payment_details($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($otherorders)),
            "data" => $otherorders);
        echo json_encode($json_data);
    }

    public function user_payments_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_payment_details($_GET);
        //print_r($rides);exit;
        $this->load->library('excel'); // load Excel Library
        $object_excel = new PHPExcel(); // new object for PHPExcel
        $object_excel->setActiveSheetIndex(0); // Create new worksheet
        $table_head = array('S.No', 'booking id', 'user name', 'User Mobile', 'User email', 'Gender', 'payment status', 'User payment mode', 'Amount paid', 'Raider Name', 'Raider Payment Mode', 'Payment Date'); //Set the names of header cells
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
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(3, $body, $row['mobile']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(4, $body, $row['email_id']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(5, $body, $row['gender']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(6, $body, $row['payment_status']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(7, $body, $row['payment_mode']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(8, $body, $row['amount']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(9, $body, $row['rname']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(10, $body, $row['rpayment_mode']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(11, $body, $row['ocreated_on']);
            $body++;
        }
        $object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel5'); // Explain format of Excel data
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="User Payments.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/user_payments');
    }

    public function user_payments() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_user_payments', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function all_user_refunds() {
        $user_refunds = $this->registermodel->all_user_refunds($_POST);
        $result_count = $this->registermodel->all_user_refunds($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($user_refunds)),
            "data" => $user_refunds);
        echo json_encode($json_data);
    }

    public function user_refunds() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_user_refunds', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function all_rider_pending_payments() {
        $rider_pending_payments = $this->registermodel->all_rider_pending_payments($_POST);
        $result_count = $this->registermodel->all_rider_pending_payments($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($rider_pending_payments)),
            "data" => $rider_pending_payments);
        echo json_encode($json_data);
    }

    public function rider_pending_payments() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_rider_pending_payments', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function all_rider_paid_payments() {
        $rider_paid_payments = $this->registermodel->all_rider_paid_payments($_POST);
        $result_count = $this->registermodel->all_rider_paid_payments($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($rider_paid_payments)),
            "data" => $rider_paid_payments);
        echo json_encode($json_data);
    }

    public function rider_paid_payments() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_rider_paid_payments', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function payment_details() {
        $this->data['title'] = "CIAO Rides";
        if ($query = $this->registermodel->payment_details()) {
            $this->data['row'] = $query;
        }
        //print_r($this->data['row']);	exit;

        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/payment_details', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_payment_details($otherorders_id) {
        if (delete_record('orders', array('id' => $otherorders_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/user_payments', 'refresh');
    }

    public function make_refund($order_id) {
        if (update_table('orders', array('refund_status' => 'paid'), array('id' => $order_id)) == true) {
            $this->session->set_flashdata('success', 'Order marked as refunded!');
        } else {
            $this->session->set_flashdata('fail', 'Error!!! Please try again.');
        }
        redirect('admin/register/user_payments', 'refresh');
    }

    public function make_rider_payment($order_id) {


        if (update_table('orders', array('rider_payment_status' => 'paid', 'payment_date' => date('Y-m-d H:i:s')), array('id' => $order_id)) == true) {
            $order_details = get_table_row('orders', array('id' => $order_id));
            $rider_details = get_table_row('users', array('id' => $order_details['rider_id']));
            $ride_name = $rider_details['first_name'] . " " . $rider_details['last_name'];
            $booking_id = $order_details['booking_id'];
            $mobile = $rider_details['mobile'];

            $message = "Dear Rider, admin has made payment for Booking ID: $booking_id. Thank You.";
            SendSMS($mobile, $message);
            $this->session->set_flashdata('success', 'Order marked as Paid to rider!');
        } else {
            $this->session->set_flashdata('fail', 'Error!!! Please try again.');
        }
        redirect('admin/register/rider_pending_payments', 'refresh');
    }

    /* ----------- cancelled_rider -------------- */

    public function all_cancelled_rider() {
        $cancelled_rider = $this->registermodel->all_cancelled_rider($_POST);
        $result_count = $this->registermodel->all_cancelled_rider($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($cancelled_rider)),
            "data" => $cancelled_rider);
        echo json_encode($json_data);
    }

    public function cancelled_rider_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_cancelled_rider($_GET);
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
        header('Content-Disposition: attachment;filename="Bookings cancelled by rider.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/cancelled_user');
    }

    public function cancelled_rider() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_cancelled_rider', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_cancelled_rider($cancelled_rider_id) {
        if (delete_record('orders', array('id' => $cancelled_rider_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/cancelled_rider', 'refresh');
    }

    public function get_cancelled_rider_user() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->registermodel->get_cancelled_rider_user($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_cancelled_rider_user', $this->data);
    }

    public function cancelled_rider_details() {
        $this->data['title'] = "CIAO Rides";
        if ($query = $this->registermodel->cancelled_rider_details()) {
            $this->data['row'] = $query;
        }
        //print_r($this->data['row']);    exit;

        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/cancelled_rider_details', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    /* ----------- /cancelled_rider -------------- */
    /* ----------- cancelled_user -------------- */

    public function all_cancelled_user() {
        $cancelled_user = $this->registermodel->all_cancelled_user($_POST);
        $result_count = $this->registermodel->all_cancelled_user($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($cancelled_user)),
            "data" => $cancelled_user);
        echo json_encode($json_data);
    }

    public function cancelled_user_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_cancelled_user($_GET);
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
        header('Content-Disposition: attachment;filename="Bookings cancelled by user.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/cancelled_user');
    }

    public function cancelled_user() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_cancelled_user', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_cancelled_user($cancelled_user_id) {
        if (delete_record('orders', array('id' => $cancelled_user_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/cancelled_user', 'refresh');
    }

    public function get_cancelled_user_user() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->registermodel->get_cancelled_user_user($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_cancelled_user_user', $this->data);
    }

    public function cancelled_user_details() {
        $this->data['title'] = "CIAO Rides";
        if ($query = $this->registermodel->cancelled_user_details()) {
            $this->data['row'] = $query;
        }
        //print_r($this->data['row']);	exit;

        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/cancelled_user_details', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    /* ----------- /cancelled_user -------------- */
    /* ----------- otherusers -------------- */

    function all_users() {
        #pagination code start
        $config['base_url'] = base_url() . 'admin/register/all_users';
        $config['total_rows'] = $this->registermodel->photo_gal_count();
        $config['per_page'] = 20;
        $config['page_query_string'] = true;

        $config['full_tag_open'] = "<ul class='pagination'>";
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';



        $config['prev_link'] = '<span aria-hidden="true">&laquo;</span>';
        $config['prev_tag_open'] = '<li class="">';
        $config['prev_tag_close'] = '</li>';


        $config['next_link'] = '<span aria-hidden="true">&raquo;</span>';
        $config['next_tag_open'] = '<li class="">';
        $config['next_tag_close'] = '</li>';

        $start = ($this->input->get_post('per_page')) ? $this->input->get_post('per_page') : 0;
        $this->pagination->initialize($config);
        $this->data['pagination'] = $this->pagination->create_links();

        #pagination code end
        //$this->data['webprofile'] = $this->Main_model->webprofile();
        $this->data['photo'] = $this->registermodel->photo_gal($config['per_page'], $start);
        // 		$this->front_view('sample',$this->data);

        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/all_users_list', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function all_inactiveusers() {
        $otherusers = $this->registermodel->all_inactiveusers($_POST);
        $result_count = $this->registermodel->all_inactiveusers($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($otherusers)),
            "data" => $otherusers);
        echo json_encode($json_data);
    }

    public function inactiveusers_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_inactiveusers($_GET);
        //print_r($rides);exit;
        $this->load->library('excel'); // load Excel Library
        $object_excel = new PHPExcel(); // new object for PHPExcel
        $object_excel->setActiveSheetIndex(0); // Create new worksheet
        $table_head = array('S.No', 'User Name', 'First Name', 'Last Name', 'Mobile', 'Email Id', 'Alternate Number', 'Date of Birth', 'Gender', 'Address 1', 'Address 2', 'Post Code', 'City', 'State', 'Country', 'Bio', 'Payment Mode', 'Facebook', 'Instagram', 'Twitter', 'Linkedin', 'Office Email Id', 'Mobile Verified', 'Email id verified', 'Office email id verified', 'Driver license verified', 'Government id verified', 'Pan card verified', 'Aadhar card verified', 'Created Date'); //Set the names of header cells
        $head = 0;
        foreach ($table_head as $value) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow($head, 1, $value);
            $head++;
        }
        $body = 2; //Add some data
        $sno = 1;
        foreach ($rides as $row) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(0, $body, $sno++);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(1, $body, $row['first_name'] . ' ' . $row['last_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(2, $body, $row['first_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(3, $body, $row['last_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(4, $body, $row['mobile']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(5, $body, $row['email_id']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(6, $body, $row['alternate_number']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(7, $body, $row['dob']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(8, $body, $row['gender']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(9, $body, $row['address1']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(10, $body, $row['address2']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(11, $body, $row['postcode']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(12, $body, $row['cityname']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(13, $body, $row['states_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(14, $body, $row['countryname']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(15, $body, $row['bio']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(16, $body, $row['payment_mode']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(17, $body, $row['facebook']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(18, $body, $row['instagram']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(19, $body, $row['twitter']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(20, $body, $row['linkedin']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(21, $body, $row['office_email_id']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(22, $body, $row['mobile_verified']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(23, $body, $row['email_id_verified']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(24, $body, $row['office_email_id_verified']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(25, $body, $row['driver_license_verified']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(26, $body, $row['government_id_verified']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(27, $body, $row['pan_card_verified']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(28, $body, $row['aadhar_card_verified']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(29, $body, $row['created_on']);
            $body++;
        }
        $object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel5'); // Explain format of Excel data
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Users.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/otherusers');
    }

    public function users_excel_export() {
        //print_r($_GET);exit;

        $rides = $this->registermodel->all_otherusers($_GET);
        if ($rides) {
            //print_r($rides);exit;
            $this->load->library('excel'); // load Excel Library
            $object_excel = new PHPExcel(); // new object for PHPExcel
            $object_excel->setActiveSheetIndex(0); // Create new worksheet
            $table_head = array('S.No', 'User Name', 'First Name', 'Last Name', 'Mobile', 'Email Id', 'Alternate Number', 'Date of Birth', 'Gender', 'Address 1', 'Address 2', 'Post Code', 'City', 'State', 'Country', 'Bio', 'Payment Mode', 'Facebook', 'Instagram', 'Twitter', 'Linkedin', 'Office Email Id', 'Mobile Verified', 'Email id verified', 'Office email id verified', 'Driver license verified', 'Government id verified', 'Pan card verified', 'Aadhar card verified', 'Created Date'); //Set the names of header cells
            $head = 0;
            foreach ($table_head as $value) {
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow($head, 1, $value);
                $head++;
            }
            $body = 2; //Add some data
            $sno = 1;
            foreach ($rides as $row) {
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(0, $body, $sno++);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(1, $body, $row['first_name'] . ' ' . $row['last_name']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(2, $body, $row['first_name']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(3, $body, $row['last_name']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(4, $body, $row['mobile']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(5, $body, $row['email_id']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(6, $body, $row['alternate_number']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(7, $body, $row['dob']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(8, $body, $row['gender']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(9, $body, $row['address1']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(10, $body, $row['address2']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(11, $body, $row['postcode']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(12, $body, $row['cityname']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(13, $body, $row['states_name']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(14, $body, $row['countryname']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(15, $body, $row['bio']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(16, $body, $row['payment_mode']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(17, $body, $row['facebook']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(18, $body, $row['instagram']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(19, $body, $row['twitter']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(20, $body, $row['linkedin']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(21, $body, $row['office_email_id']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(22, $body, $row['mobile_verified']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(23, $body, $row['email_id_verified']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(24, $body, $row['office_email_id_verified']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(25, $body, $row['driver_license_verified']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(26, $body, $row['government_id_verified']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(27, $body, $row['pan_card_verified']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(28, $body, $row['aadhar_card_verified']);
                $object_excel->getActiveSheet()->setCellValueByColumnAndRow(29, $body, $row['created_on']);
                $body++;
            }
            $object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel5'); // Explain format of Excel data
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Users.xls"');
            $object_excel_writer->save('php://output'); // For automatic download to local computer
            redirect(base_url() . 'register/otherusers');
        } else {
            $this->session->set_flashdata('success', 'No Searched Data');

            redirect(base_url() . 'admin/register/otherusers', 'refresh');
        }
    }

    public function inactiveusers() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_inactiveusers', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function all_otherusers() {
        $otherusers = $this->registermodel->all_otherusers($_POST);
        $result_count = $this->registermodel->all_otherusers($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($otherusers)),
            "data" => $otherusers);
        echo json_encode($json_data);
    }

    public function otherusers() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_otherusers', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_otherusers($otherusers_id) {
        if (update_table('users', array('delete_status' => 0), array('id' => $otherusers_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/otherusers', 'refresh');
    }

    public function user_details($id) {
        $this->data['title'] = "CIAO Rides";
        $this->data['user_vehicles'] = '';
        if ($query = $this->registermodel->user_details()) {
            $this->data['row'] = $query;
            $this->data['user_vehicles'] = $this->db
                            ->select('u.*,v.title as v_make,vm.title as v_model')
                            ->from('user_vehicles  u')
                            ->where('u.user_id', $this->data['row']['id'])
                            ->join('vehicle_makes v', 'v.id=u.make_id', 'left')
                            ->join('vehicle_models vm', 'vm.id=u.model_id', 'left')
                            ->get()->result();
            //user_vehicles
//            die("he");
        }
        //echo '<pre>';print_r($this->data['row']);	exit;
        if ($_POST) {
            $up = array(
                'mobile_verified' => $this->input->get_post('mobile_verified'),
                'email_id_verified' => $this->input->get_post('email_id_verified'),
                'office_email_id_verified' => $this->input->get_post('office_email_id_verified'),
                'driver_license_verified' => $this->input->get_post('driver_license_verified'),
                'pan_card_verified' => $this->input->get_post('pan_card_verified'),
                'photo_verified' => $this->input->get_post('photo_verified'),
                'address_verified' => $this->input->get_post('address_verified'),
                'aadhar_card_verified' => $this->input->get_post('aadhar_card_verified')
            );
            $this->db->set($up)->where('id', $id)->update('users');
//            echo $this->db->last_query();
//            die;
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('success', 'Updated Successfully.');
                redirect('admin/register/user_details/' . $id, 'refresh');
            } else {
                $this->session->set_flashdata('failed', 'Updated Failed.');
                redirect('admin/register/user_details/' . $id, 'refresh');
            }
        }
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/user_details', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function update_verification() {

        $this->data = array(
            'mobile_verified' => $this->input->post('mobile_verified'),
            'email_id_verified' => $this->input->post('email_id_verified'),
            'office_email_id_verified' => $this->input->post('office_email_id_verified'),
            'driver_license_verified' => $this->input->post('driver_license_verified'),
            'photo_verified' => $this->input->post('photo_verified'),
            'address_verified' => $this->input->post('address_verified'),
            'pan_card_verified' => $this->input->post('pan_card_verified'),
            'aadhar_card_verified' => $this->input->post('aadhar_card_verified'),
        );

        $this->registermodel->update_verification($this->data, $this->input->post('status_id'));
        $this->session->set_flashdata('success', 'Record Updated Successfully.');
        redirect('admin/register/verification/' . $this->input->post('status_id'), 'refresh');
    }

    public function verification() {
        $this->data['title'] = "CIAO Rides";
        $this->data['id'] = $this->uri->segment(4);
        if ($query = $this->registermodel->edit_verification()) {
            $this->data['row'] = $query;
        }

        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/verification', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    /* ----------- /otherusers -------------- */


    /* ----------- Change Password -------------- */

    public function ChangePassword() {
        $this->data['title'] = "CIAO Rides";
        if ($query = $this->registermodel->AdminDetails()) {
            $this->data['row'] = $query;
        }
        //var_dump($this->data['row']);
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/change_password', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function UpdatePassword() {
        $password = $this->input->post('password');
        $this->data = array(
            'password' => md5($password),
        );
        $this->data['modified_on'] = date('Y-m-d H:i:s');
        $this->registermodel->UpdatePassword($this->data);
        $this->session->set_flashdata('success', 'Password Updated Successfully.');
        redirect('admin/register/ChangePassword/', 'refresh');
    }

    /* ----------- /Change Password -------------- */

    public function is_logged_in() {
        $is_logged_in = $this->session->userdata('is_logged_in');
        if (!isset($is_logged_in) || $is_logged_in != true) {
            redirect('admin/login', 'refresh');
        }
    }

    public function change_user_status($user_id, $status) {
        if ($this->registermodel->change_user_status($user_id, $status) == true) {
            $this->session->set_flashdata('success', 'Status Updated Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Updating.');
        }
        redirect('admin/register/all_users', 'refresh');
    }

    /* ----------- Push Notifications -------------- */

    public function all_push_notifications() {
        $notifications = $this->registermodel->all_push_notifications($_POST);
        $result_count = $this->registermodel->all_push_notifications($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($notifications)),
            "data" => $notifications);
        echo json_encode($json_data);
    }

    public function push_notifications_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_push_notifications($_GET);
        //print_r($rides);exit;
        $this->load->library('excel'); // load Excel Library
        $object_excel = new PHPExcel(); // new object for PHPExcel
        $object_excel->setActiveSheetIndex(0); // Create new worksheet
        $table_head = array('S.No', 'Title', 'Description', 'Sent date'); //Set the names of header cells
        $head = 0;
        foreach ($table_head as $value) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow($head, 1, $value);
            $head++;
        }
        $body = 2; //Add some data
        $sno = 1;
        foreach ($rides as $row) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(0, $body, $sno++);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(1, $body, $row['title']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(2, $body, $row['description']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(3, $body, $row['created_on']);
            $body++;
        }
        $object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel5'); // Explain format of Excel data
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Push Notifications.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/push_notifications');
    }

    public function push_notifications() {
        $this->data['title'] = "Bikes7";
        $this->data['items'] = $this->registermodel->all_push_notifications($_POST);
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_push_notifications', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function push_notificationsView() {
        $this->data['title'] = "Bikes7";
        //var_dump($this->data['row']);
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/push_notifications_view', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function send_push_notification() {
        $sent_to = "user";
        $query = $this->registermodel->send_push_notification($this->input->post('description'), $this->input->post('title'), $sent_to);
        if ($query == true) {
            $this->session->set_flashdata('success', 'Notification Sent Successfully!');
        } else {
            $this->session->set_flashdata('failed', 'Notification sending failed!');
        }

        redirect('admin/register/push_notifications', 'refresh');
    }

    /* ----------- /Push Notifications -------------- */

    /* ----------- support -------------- */

    public function all_support() {
        $support = $this->registermodel->all_support($_POST);
        $result_count = $this->registermodel->all_support($_POST, 1);
        $json_data = array(
            "draw" => intval($_POST['draw']),
            "iTotalRecords" => intval($result_count),
            "iTotalDisplayRecords" => intval($result_count),
            "recordsFiltered" => intval(count($support)),
            "data" => $support);
        echo json_encode($json_data);
    }

    public function support_excel_export() {
        //print_r($_GET);exit;
        $rides = $this->registermodel->all_support($_GET);
        //print_r($rides);exit;
        $this->load->library('excel'); // load Excel Library
        $object_excel = new PHPExcel(); // new object for PHPExcel
        $object_excel->setActiveSheetIndex(0); // Create new worksheet
        $table_head = array('S.No', 'First Name', 'Last Name', 'Mobile', 'Email ID', 'Order ID', 'Message', 'Created Date'); //Set the names of header cells
        $head = 0;
        foreach ($table_head as $value) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow($head, 1, $value);
            $head++;
        }
        $body = 2; //Add some data
        $sno = 1;
        foreach ($rides as $row) {
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(0, $body, $sno++);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(1, $body, $row['first_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(2, $body, $row['last_name']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(3, $body, $row['mobile']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(4, $body, $row['email_id']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(5, $body, $row['order_id']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(6, $body, $row['message']);
            $object_excel->getActiveSheet()->setCellValueByColumnAndRow(7, $body, $row['created_on']);
            $body++;
        }
        $object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel5'); // Explain format of Excel data
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="support.xls"');
        $object_excel_writer->save('php://output'); // For automatic download to local computer
        redirect(base_url() . 'register/support');
    }

    public function support() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_support', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function add_support() {
        $this->data['title'] = "CIAO Rides";
        $this->data['categories'] = $this->registermodel->vehicle_makes();
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/add_support', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function update_support() {
        if ($this->input->post('sub_category_id') == "") {
            $title = $this->input->post('title');
            $make_id = $this->input->post('make_id');
            $this->data = array(
                'title' => $title,
                'make_id' => $make_id,
                'created_on' => date('Y-m-d H:i:s')
            );
            $this->registermodel->insert_support($this->data);
            $this->session->set_flashdata('success', 'Record added Successfully.');
            redirect('admin/register/support', 'refresh');
        } else {
            $this->data = array(
                'make_id' => $this->input->post('make_id'),
                'title' => $this->input->post('title')
            );
            $this->data['modified_on'] = date('Y-m-d H:i:s');
            $this->registermodel->update_support($this->data, $this->input->post('sub_category_id'));
            $this->session->set_flashdata('success', 'Record Updated Successfully.');
            redirect('admin/register/edit_support/' . $this->input->post('sub_category_id'), 'refresh');
        }
    }

    public function edit_support($support_id) {
        $this->data['title'] = "CIAO Rides";
        if ($query = $this->registermodel->edit_support()) {
            $this->data['row'] = $query;
        }
        $this->data['categories'] = $this->registermodel->vehicle_makes();
        $this->data['sub_categories'] = $this->registermodel->support();


        //	var_dump($this->data['row']);
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/edit_support', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function delete_support($support_id) {
        if (delete_record('support', array('id' => $support_id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/support', 'refresh');
    }

    public function push_notifications_delete($id) {
        if (delete_record('notifications', array('id' => $id)) == true) {
            $this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
        } else {
            $this->session->set_flashdata('fail', 'Error in Deleting.');
        }
        redirect('admin/register/push_notifications', 'refresh');
    }

    function invoice($order_id) {
        $details = $this->ws_model->order_details($order_id);
//        echo '<pre>';
//        print_r($details);
//        echo '</pre>';
        $this->data['details'] = $details;
        $this->data['social'] = $this->db->get('socia_media_links')->result();
        $message = $this->load->view('website/invoice-test', $this->data, false);
    }

}

?>