<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $sessionData;
    function __construct()
    {         
        parent::__construct();
    }

    /* 	Parameters
    *	Templatename = Email template file name
    *	maildata = values in array
    */   
    public function load_view($data,  $layout_file = 'layout'){
	
            $this->load->view($layout_file, $data);
    }
        
    public function load_portal_view($data, $layout_file = 'portal/layout'){
        $data['sessData'] = $this->sessionData;
        $this->load->view($layout_file, $data);
    }

	public function chkAccess(){
        $adminlogin = $this->session->userdata("logged_in");
        if(!empty($adminlogin)){
            return $adminlogin;
        }
        else{
            return false;
        }
    }
	
	public function sendmail($tempaltename, $maildata, $to, $subject)
     {

        require_once(APPPATH.'/third_party/phpmailer/PHPMailerAutoload.php');
        // prepare email message /
        $emailData = file_get_contents(APPPATH.'/views/emailTemplates/head.html');
        $emailData .= file_get_contents(APPPATH.'/views/emailTemplates/'.$tempaltename);
        $emailData .= file_get_contents(APPPATH.'/views/emailTemplates/footer.html');
        $maildata['SITEURL'] = base_url();
        $maildata['MAIN_WEBSITE'] = MAIN_WEBSITE;
        $maildata['SITE_NAME'] = SITE_NAME;
        foreach($maildata as $mailKey => $mailValue)
        {
          $emailData = str_replace('%%'.$mailKey.'%%', $mailValue, $emailData);
        }
            //Create a new PHPMailer instance
            $mail = new PHPMailer;
            //Tell PHPMailer to use SMTP
            $mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            $mail->Host = SMTP_HOST;
            $mail->Port = SMTP_PORT;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->SMTPAuth = true;
            $mail->XMailer = SITE_NAME;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->setFrom(SMTP_EMAIL_FROM);
            //$mail->addReplyTo(SMTP_EMAIL_REPLY_TO, SITE_NAME);
            $mail->addAddress('rajat@rkmarketing.net');
            $mail->Subject = $subject. ' | '.SITE_NAME;
            $mail->msgHTML($emailData);
            $mail->CharSet='utf-8';

            //send the message, check for errors
            if(!$mail->send()) {
                log_message('error', $mail->ErrorInfo);
                return false;
            } else {
                return true;
            }
    }
	
	 public function init_pagination($base_url, $total_rows){
            $this->load->library('pagination');
            $config = array();
            $config['base_url'] = $base_url;
            $config['page_query_string'] = true;
            $config['total_rows'] = $total_rows;
            $config['per_page'] = TOTAL_RECORD_PER_PAGE;
            $config['full_tag_open'] = '<ul class="pagination">';
            $config['full_tag_close'] = '</ul>';
            $config['first_link'] = false;
            $config['last_link'] = false;
            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['prev_link'] = '&laquo';
            $config['prev_tag_open'] ='<li class="prev">';
            $config['prev_tag_close'] ='</li>';
            $config['next_link'] ='&raquo';
            $config['next_tag_open'] ='<li>';
            $config['next_tag_close'] ='</li>';
            $config['last_tag_open'] ='<li>';
            $config['last_tag_close'] ='</li>';
            $config['cur_tag_open'] ='<li class="active"><a href="#">';
            $config['cur_tag_close'] ='</a></li>';
            $config['num_tag_open'] ='<li>';
            $config['num_tag_close'] ='</li>';
            $this->pagination->initialize($config);
            return $config;
    }
	
	public function getQueryString($excludeParams=[]){
        $getVals = $this->input->get();
        $string = [];
        foreach ($getVals as $keys => $vals){
            if($keys != 'per_page'){
                if(!in_array($keys, $excludeParams)){
                    $string[] = $keys.'='.$vals;
                }
            }
        }
        return empty($string)?'':'?'.  implode('&', $string);
    }
	
	  public function _gallery_select_box($type=''){
		$this->db->select("type");
        $this->db->distinct();
        $this->db->where('type != "customer"');
        $list       =  $this->db->get("uploads")->result_array();
        $select_box     = "<select class='form-control' id='type_value'><option value='all'>All</option>";
        foreach($list as $li){
            $value          = ucFirst(str_replace("_"," ",$li['type']));
            if($value==''){
                $select_box     .="<option value='media'>Uncategorised </option>";
            }
            else if($type==$li['type']){
                $select_box     .="<option value='{$li['type']}' selected='selected'>{$value}</option>";
            }
            else{
                $select_box     .="<option value='{$li['type']}'>{$value}</option>";
            }
        }
        $select_box     .= "</select>";
        return $select_box;
    }

}