<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); //we need to call PHP's session object to access it through CI

class Comment extends CI_Controller {

    function __construct()
    {
        parent::__construct();
    }

	public function index($itemId)
	{
        if ($this->session->userdata('logged_in')){
            //$session_data = $this->session->userdata('logged_in'); // todo: use logged in user's data

		    $this->load->view('comment/index');
        }
        else {
            //If no session, redirect to login page
            redirect('login', 'refresh');
        }
	}
}

/* End of file Comment.php */
/* Location: ./application/controllers/Comment.php */