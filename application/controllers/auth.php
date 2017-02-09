<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('user','',TRUE);
	}

	public function index() {
		$this->load->helper(array('form'));

		$this->load->view('auth/login');
	}

	public function verifylogin() {
		//This method will have the credentials validation
		$this->load->library('form_validation');

// test changes // 


//		$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
//		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|callback_check_database');

		if($this->form_validation->run() == false) { //Field validation failed.  User redirected to login page
			$this->load->view('auth/login');
		}
		else { 	//Go to private area
			redirect('comments/1', 'refresh');
		}

	}

	public function check_database($password) {
		//Field validation succeeded.  Validate against database
		if ($this->input->post('username')){
			$username = $this->input->post('username');

			//query the database
			if( $result = $this->user->login($username, $password) ){
				foreach ($result as $row){
					$sessionArray = [
						'id' => $row->id,
						'username' => $row->username
					];

					$this->session->set_userdata('logged_in', $sessionArray);
				}
				return true;
			}
			else {
				$this->form_validation->set_message('check_database', 'Invalid username or password');
				return false;
			}
		}
		return false;
	}

	public function logout() {
		$this->session->unset_userdata('logged_in');
		session_destroy();
		redirect('login', 'refresh');
	}
}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */