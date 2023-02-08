<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Roles extends CI_Controller {


	public $header = 'admin/includes/header';
	public $leftMenu  = 'admin/includes/leftside_menu';
	public $footer = 'admin/includes/footer';
	public $list_roles='admin/roles/list_roles';
	public $add_roles='admin/roles/add_roles';
	public $edit_roles='admin/roles/edit_roles';

	public $add_employees ='admin/roles/add_employees';
	public $edit_employees ='admin/roles/edit_employees';




    function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        $this->is_logged_in();

        $this->load->model('admin/Rolesmodel','my_model');
	    $this->load->model('Common_model','common_model');

        $this->load->helper('url', 'form', 'HTML');
        $this->load->library(array('form_validation', 'session'));

        $this->session->set_userdata('user_id','superadmin');
    }

    public function is_logged_in() {
        $is_logged_in = $this->session->userdata('is_logged_in');
        if (!isset($is_logged_in) || $is_logged_in != true) {
            redirect('admin/login', 'refresh');
        }
    }



    public function index()
	{		

		//$this->data['url']='admin/roles/roles/';
		if($this->session->userdata('user_id') != 'superadmin'){
		 $data['roleResponsible'] = $this->common_model->get_responsibilities();
	     }else{
		 $data['roleResponsible'] = $this->common_model->get_default_responsibilities();
		 }
		$this->setHeaderFooter($this->list_roles,$data);
	}

    public function list_roles()
	{		

		//$this->data['url']='admin/roles/roles/';
		if($this->session->userdata('user_id') != 'superadmin'){
		 $data['roleResponsible'] = $this->common_model->get_responsibilities();
	     }else{
		 $data['roleResponsible'] = $this->common_model->get_default_responsibilities();
		 }
		$this->setHeaderFooter($this->list_roles,$data);
	}


	public function add_roles()
	{  
		if($this->session->userdata('user_id') != 'superadmin'){
		 $data['roleResponsible'] = $this->common_model->get_responsibilities();
	     }else{
		 $data['roleResponsible'] = $this->common_model->get_default_responsibilities();
		 }

		
		$data['module_names'] = $this->common_model->module_names();
		if($this->input->post('submit') != ''){
				//echo '<pre>';print_r($this->input->post());exit;
				if($this->input->post('method')!=''){
							$exit_data = array(
								'rolename' => ($this->input->post('rolename')),
							);				
							$exit_details = $this->my_model->exit_details($exit_data);

							if($exit_details == 0){
								$result = $this->my_model->add_record();
								if(!empty($result)){
									$this->session->set_flashdata('success', 'Inserted Successfully...');
								}else{
									$this->session->set_flashdata('error', 'Not Inserted....');
								}
								redirect(base_url().'admin/roles/roles');
							}else{
							$this->session->set_flashdata('error', 'Name Already Exists...');
							redirect(base_url().'admin/roles/add_roles');
							}

				}else{
					$this->session->set_flashdata('success', 'Please select any check box...');
					redirect(base_url().'admin/roles/add_roles');
				}
		}
		$this->setHeaderFooter($this->add_roles,$data);

	}

	public function edit_roles($id){
		$data['module_names'] = $this->common_model->module_names();
		$data['record'] = $this->my_model->get_single_record($id);

		

		if($this->input->post('submit') != ''){
				if($this->input->post('method')!=''){
					$result = $this->my_model->update_record($id);
					//echo '<pre>'; print_r($result);
					if(!empty($result)){
						$this->session->set_flashdata('success', 'Updated Successfully...');
						
					}else{
						$this->session->set_flashdata('error', 'Not Updated...');
					}

			}else{
					$this->session->set_flashdata('success', 'Please select any check box...');
				}
				redirect(base_url().'/admin/roles/edit_roles/'.$id);
		}
		if($this->session->userdata('user_id') != 'superadmin'){
		 $data['roleResponsible'] = $this->common_model->get_responsibilities();
	     }else{
		 $data['roleResponsible'] = $this->common_model->get_default_responsibilities();
		 }
		$this->setHeaderFooter($this->edit_roles,$data);
	}


	/*-----------  Roles --------------*/
  public function all_roles()

	{

        $records = $this->my_model->all_roles($_POST);

        $result_count=$this->my_model->all_roles($_POST,1);

        $json_data = array(

            "draw"  => intval($_POST['draw'] ),

            "iTotalRecords"  => intval($result_count ),

            "iTotalDisplayRecords"  => intval($result_count ),

            "recordsFiltered"  => intval(count($records) ),

            "data"  => $records);  

        echo json_encode($json_data);

    }

    public function change_role_status($user_id, $status)
	{
		if($this->my_model->change_status('roles',$user_id, $status) == true)
		{
		$this->session->set_flashdata('success', 'Status Updated Successfully.');
		}
		else
		{
		$this->session->set_flashdata('error', 'Error in Updating.');
		}
		redirect('admin/roles', 'refresh');
	}


  /*-----------  Employees --------------*/
  	public function all_employees()
	{

        $records = $this->my_model->all_employees($_POST);

        $result_count=$this->my_model->all_employees($_POST,1);

        $json_data = array(

            "draw"  => intval($_POST['draw'] ),

            "iTotalRecords"  => intval($result_count ),

            "iTotalDisplayRecords"  => intval($result_count ),

            "recordsFiltered"  => intval(count($records) ),

            "data"  => $records);  

        echo json_encode($json_data);

    }
	public function employees()
	{		
		if($this->session->userdata('user_id') != 'superadmin'){
		 $data['roleResponsible'] = $this->common_model->get_responsibilities();
	     }else{
		 $data['roleResponsible'] = $this->common_model->get_default_responsibilities();
		 }
		 $this->setHeaderFooter('admin/roles/list_employees',$data);
	}


	public function add_employees()
	{
		
		$data['roles']=$this->common_model->get_table('roles',array('delete_status'=>'1','status'=>'1'),array('id','rolename'));
		
		//echo '<pre>';print_r($data['roles']);exit;
		if($this->session->userdata('user_id') != 'superadmin'){
		 $data['roleResponsible'] = $this->common_model->get_responsibilities();
	     }else{
		 $data['roleResponsible'] = $this->common_model->get_default_responsibilities();
		 }
		$this->setHeaderFooter($this->add_employees,$data);

	}

	public function update_employees()
	{

		$id=$this->input->post('employee_id');
		
		$role_ids=implode(',',$this->input->post('role_ids'));

		if($id =='' ){
			$check_mobile=$this->common_model->get_table_row('employees',array('user_mobile'=>$this->input->post('user_mobile')),array('user_mobile'));
			if(!empty($check_mobile)){
				$this->session->set_flashdata('error', 'Mobile already exists.!');
				redirect('admin/roles/add_employees', 'refresh');
					
			}
			$check_email=$this->common_model->get_table_row('employees',array('user_email'=>$this->input->post('user_email')),array('user_email'));
			if(!empty($check_email)){
				$this->session->set_flashdata('error', 'Email already exists.!');
				redirect('admin/roles/add_employees', 'refresh');
					
			}
		}

		
		if($_FILES['image']['name'] != ''){
				$config['upload_path'] = './storage/employees'; 
				$config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
				$config['max_size']  = '0';
				$config['max_width']  = '0';
				$config['max_height']  = '0';
				
				$this->load->library('upload', $config);
				
				if(!$this->upload->do_upload('image'))
				{
					$data['msg'] = $this->upload->display_errors();
					$this->session->set_flashdata('error', $data['msg']);
					if($id == ''){
						redirect('admin/roles/add_employees', 'refresh');
					}else{
						redirect('admin/roles/edit_employees/'.$id, 'refresh');
					}
					//echo '<pre>';print_r($data['msg']);exit;
				}
				else
				{
					$data = $this->upload->data();
					$image = 'storage/employees/'.$data['file_name'];
					//$config_image = array();
					$config_image = array(
					  'image_library' => 'gd2',
					  'source_image' => './storage/employees'.$data['file_name'],
					  'new_image' => './storage/employees'.$data['file_name'],
					  'width' => 297,
					  'height' => 302,
					  'maintain_ratio' => FALSE,
					  'rotate_by_exif' => TRUE,
					  'strip_exif' => TRUE,
					);					
					$this->load->library('image_lib', $config_image);
					$this->image_lib->resize();
					$this->image_lib->clear();
				 }
		}else{
			$image='';
			}

//echo '<pre>';print_r($_FILES);
			if($_FILES['address_proof']['name'] != ''){
				$config['upload_path'] = './storage/address_proof'; 
				$config['allowed_types'] = 'gif|jpg|png|bmp|jpeg';
				$config['max_size']  = '0';
				$config['max_width']  = '0';
				$config['max_height']  = '0';
				
				$this->load->library('upload', $config);
				
				if(!$this->upload->do_upload('address_proof'))
				{
					$data['msg'] = $this->upload->display_errors();
					$this->session->set_flashdata('error', $data['msg']);
					if($id == ''){
						redirect('admin/roles/add_employees', 'refresh');
					}else{
						redirect('admin/roles/edit_employees/'.$id, 'refresh');
					}
					//echo '<pre>';print_r($data['msg']);exit;
				}
				else
				{
					$data = $this->upload->data();
					$address_proof = 'storage/address_proof/'.$data['file_name'];
					//$config_image = array();
					$config_image = array(
					  'image_library' => 'gd2',
					  'source_image' => './storage/address_proof'.$data['file_name'],
					  'new_image' => './storage/address_proof'.$data['file_name'],
					  'width' => 297,
					  'height' => 302,
					  'maintain_ratio' => FALSE,
					  'rotate_by_exif' => TRUE,
					  'strip_exif' => TRUE,
					);					
					$this->load->library('image_lib', $config_image);
					$this->image_lib->resize();
					$this->image_lib->clear();
				 }
		}else{
			$address_proof='';
			}

		
		$data=array(
						
						'role_ids'=>$role_ids,
						'user_type'=> 'employee',
						'department'=> $this->input->post('department'),
						'user_name'=> $this->input->post('employee_name'),
						'user_email'=> $this->input->post('employee_email'),
						'user_mobile'=> $this->input->post('employee_mobile'),						
						'user_address'=> $this->input->post('emp_address'),
						'pincode'=> $this->input->post('pincode'),
						'image'=> $image,
						'address_proof'=>$address_proof
					);
			//echo '<pre>';print_r($this->session->all_userdata());exit;
		if($id == "")
		{
			$data['user_id']=getDynamicId('employee_no','EMP');
			$data['password']= md5($this->input->post('confirm_password'));
			$data['created_by']=$this->session->userdata('user_id');
			$data['created_on'] = date('Y-m-d H:i:s');
			$this->my_model->insert_employees($data);
			$this->session->set_flashdata('success', 'Record added Successfully.');
			redirect('admin/roles/add_employees', 'refresh');				
		}else{
			$res=$this->common_model->get_table_row('employees',array('id'=>$id),array());
			//echo '<pre>';print_r($image);
			if($image == ''){
			$data['image']= $res['image'];
			}else{
				$file = './'.$res['image'];
				if(is_file($file))
				unlink($file);
			}

			if($address_proof == ''){
			$data['address_proof']= $res['address_proof'];
			}else{
				$file = './'.$res['address_proof'];
				if(is_file($file))
				unlink($file);
			}
			$data['modified_by']=$this->session->userdata('user_id');
			$data['modified_on'] = date('Y-m-d H:i:s');
			//echo '<pre>';print_r($data);exit;
			$this->my_model->update_employees($data, $id);
			$this->session->set_flashdata('success', 'Record Updated Successfully.');
			redirect('admin/roles/edit_employees/'.$id, 'refresh');
		}	
		

	}

	public function view_employee($id)
	{
		$data['record']=$this->my_model->view_employee($id);
		if($this->session->userdata('user_id') != 'superadmin'){
		 $data['roleResponsible'] = $this->common_model->get_responsibilities();
	     }else{
		 $data['roleResponsible'] = $this->common_model->get_default_responsibilities();
		 }				
		$this->setHeaderFooter('admin/roles/view_employees',$data);
	}

	public function edit_employees()
	{
		

		if($query = $this->my_model->edit_employees())
		{
		 $data['row'] = $query;
		

		 $data['roles']=$this->common_model->get_table('roles',array('delete_status'=>'1','status'=>'1'),array('id','rolename'));
		
		
		}		
		 if($this->session->userdata('user_id') != 'superadmin'){
		 $data['roleResponsible'] = $this->common_model->get_responsibilities();
	     }else{
		 $data['roleResponsible'] = $this->common_model->get_default_responsibilities();
		 }
		$this->setHeaderFooter($this->edit_employees,$data);
	}

	public function delete_employees()
	{
		if($this->my_model->delete_employees() == true)
		{
			$this->session->set_flashdata('success', 'Record has been Deleted Successfully.');
		}else{
			$this->session->set_flashdata('error', 'Error in Deleting.');
		}

		redirect('admin/roles/employees', 'refresh');
	}
	public function change_employee_status($user_id, $status)
	{
		if($this->my_model->change_status('employees',$user_id, $status) == true)
		{
		$this->session->set_flashdata('success', 'Status Updated Successfully.');
		}
		else
		{
		$this->session->set_flashdata('error', 'Error in Updating.');
		}
		redirect('admin/roles/employees/', 'refresh');
	}

		/*----------- / Employees --------------*/




	/*-----------start setting header and footer --------------*/

	public function setHeaderFooter($view, $data)
	{	

		$this->load->view($this->header, $data);
		$this->load->view($this->leftMenu, $data);
		//$data['message']=$this->load->view('admin/includes/message',$data,TRUE);
		$this->load->view($view, $data);
		$this->load->view($this->footer);
	}
  /*----------- stop setting header and footer --------------*/



}