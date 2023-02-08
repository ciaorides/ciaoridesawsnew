<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vehicle_management extends CI_Controller {

    function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        $this->is_logged_in();
        $this->load->model('admin/vehiclemodel');
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

    
    
    /* ----------- vehicle_makes -------------- */

    public function all_vehicle_makes() {
        $vehicle_makes = $this->vehiclemodel->all_vehicle_makes($_POST);
        $result_count = $this->vehiclemodel->all_vehicle_makes($_POST, 1);
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
        $rides = $this->vehiclemodel->all_vehicle_makes($_GET);
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
        redirect(base_url() . 'vehicle_management/rides');
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
            $this->vehiclemodel->insert_vehicle_makes($this->data);
            $this->session->set_flashdata('success', 'Record added Successfully.');
            redirect('admin/vehicle_management/add_vehicle_makes', 'refresh');
        } else {
            // var_dump($this->data);exit;
            $this->data['modified_on'] = date('Y-m-d H:i:s');
            $this->vehiclemodel->update_vehicle_makes($this->data, $this->input->post('category_id'));
            $this->session->set_flashdata('success', 'Record Updated Successfully.');
            /* 	redirect('admin/vehicle_management/edit_vehicle_makes/'.$this->input->post('category_id'), 'refresh'); */

            redirect('admin/vehicle_management/vehicle_makes', 'refresh');
        }
    }

    public function edit_vehicle_makes($vehicle_makes_id) {
        $this->data['title'] = "CIAO Rides";
        if ($query = $this->vehiclemodel->edit_vehicle_makes()) {
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
        redirect('admin/vehicle_management/vehicle_makes', 'refresh');
    }

    public function get_user_vehicle_makes() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->vehiclemodel->get_user_vehicle_makes($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_user_vehicle_makes', $this->data);
    }

    /* ----------- /vehicle_makes -------------- */


    /* ----------- vehicle_models -------------- */

    public function all_vehicle_models() {
        $vehicle_models = $this->vehiclemodel->all_vehicle_models($_POST);
        $result_count = $this->vehiclemodel->all_vehicle_models($_POST, 1);
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
        $rides = $this->vehiclemodel->all_vehicle_models($_GET);
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
        redirect(base_url() . 'vehicle_management/rides');
    }

    public function vehicle_models() {
        $this->data['title'] = "CIAO Rides";
        $this->load->view('admin/includes/header', $this->data);
        $this->load->view('admin/list_vehicle_models', $this->data);
        $this->load->view('admin/includes/footer', $this->data);
    }

    public function add_vehicle_models() {
        $this->data['title'] = "CIAO Rides";
        $this->data['categories'] = $this->vehiclemodel->vehicle_makes();
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


            $this->vehiclemodel->insert_vehicle_models($this->data);
            $this->session->set_flashdata('success', 'Record added Successfully.');
            redirect('admin/vehicle_management/vehicle_models', 'refresh');
        } else {
            $this->data = array(
                'make_id' => $this->input->post('make_id'),
                'title' => $this->input->post('title'),
            );
            $this->data['modified_on'] = date('Y-m-d H:i:s');
            $this->vehiclemodel->update_vehicle_models($this->data, $this->input->post('sub_category_id'));
            $this->session->set_flashdata('success', 'Record Updated Successfully.');
            redirect('admin/vehicle_management/edit_vehicle_models/' . $this->input->post('sub_category_id'), 'refresh');
        }
    }

    public function edit_vehicle_models($vehicle_models_id) {
        $this->data['title'] = "CIAO Rides";
        if ($query = $this->vehiclemodel->edit_vehicle_models()) {
            $this->data['row'] = $query;
        }
        $this->data['categories'] = $this->vehiclemodel->vehicle_makes();
        $this->data['sub_categories'] = $this->vehiclemodel->vehicle_models();


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
        redirect('admin/vehicle_management/vehicle_models', 'refresh');
    }

    public function get_user_vehicle_models() {
        $this->data['title'] = "CIAO Rides";
        $user_id = $this->input->post('user_id');
        $this->data['details'] = $this->vehiclemodel->get_user_vehicle_models($user_id);
        //var_dump($this->data['row']);
        $this->load->view('admin/get_user_vehicle_models', $this->data);
    }

    /* ----------- /vehicle_models -------------- */

    public function is_logged_in() {
        $is_logged_in = $this->session->userdata('is_logged_in');
        if (!isset($is_logged_in) || $is_logged_in != true) {
            redirect('admin/login', 'refresh');
        }
    }
}