<?php


class Google extends MY_Controller{

	 public function __construct() {
        parent::__construct();
        
        
    }
	
	public function index(){
		if(isset($_GET['code']))
		{
			$this->googleplus->getAuthenticate();
			$this->session->set_userdata('login',true);
			$this->session->set_userdata('userProfile',$this->googleplus->getUserInfo());
			redirect('google/profile');
		}
		
		$data['loginURL'] = $this->googleplus->loginURL();
	    $data['template_file'] = 'social_login';
		$this->load_view($data);
	}
	
	public function profile(){
		if($this->session->userdata('login') == true)
		{
			$data['profileData'] = $this->session->userdata('userProfile');
			echo "<pre>";print_r($data);die();
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
}//class ends here
