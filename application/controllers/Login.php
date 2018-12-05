<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

	public function index(){
		if(isset($_GET['code']))
		{
			$this->googleplus->getAuthenticate();
			$this->session->set_userdata('login',true);
			$this->session->set_userdata('logged_in',$this->googleplus->getUserInfo());
			redirect('login/user_profile');
		}
		
		$data['loginURL'] = $this->googleplus->loginURL();
	    $data['template_file'] = 'social_login';
		$this->load_view($data);
	}
	
	public function profile(){
		if($this->session->userdata('login') == true)
		{
			$data['profileData'] = $this->session->userdata('userProfile');
			//$profile data contains all info return from google id
			$this->load->view('profile',$data);
		}
		else
		{
			redirect('');
		}
	}
	public function logout(){
		$this->session->sess_destroy();
		$this->googleplus->revokeToken();
		redirect('');
	}
	
	public function user_profile(){
		$data['profile']=$this->session->userdata('logged_in');
		$data['template_file'] = 'user_profile';
		$this->load_view($data);	
	}
	
	public function fb(){
		//post data from srcipt contains fb return info
		$inputt= $this->input->post();
		            $sessiondata = array(
						
						'email' => $inputt['email'],
						'name' => $inputt['name'],
						'phone' => '',
						'logged_in' => TRUE
					);
					$this->session->set_userdata('logged_in',$sessiondata);
					$return = array('success'=>1);
			echo json_encode($return);
		
	}
}
