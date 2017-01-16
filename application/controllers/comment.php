<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment extends CI_Controller {

	public function index($itemId) //
	{
		$this->load->view('comment');
	}
}

/* End of file Comment.php */
/* Location: ./application/controllers/Comment.php */