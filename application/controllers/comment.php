<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); //we need to call PHP's session object to access it through CI

class Comment extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        //If no session, redirect to login page or appropriate json response
        if (!$this->session->userdata('logged_in')){
            if ($this->input->is_ajax_request()){
                header('HTTP/1.1 401 Unauthorized');
                header('Content-Type: application/json; charset=UTF-8');
                die(json_encode(array('message' => 'Unauthorized', 'code' => 401)));
            } else {
                redirect('login', 'refresh');
            }
        }

        // Loading the text helper which assist in working with text
        $this->load->helper('text');
        // Loading the form helper which assist in working with forms
        $this->load->helper(array('form'));
        // Loading the date helper which helps to work with dates
        $this->load->helper('date');
    }

	public function index($itemId)
	{
        $data = array();
        $sessionData = $this->session->userdata('logged_in');

        $this->load->model('comments');

        $data['comments']  = $this->comments->getAllByItemId($itemId);
        $data['itemId']    = $itemId;
        $data['userName']  = $sessionData['username'];

	    $this->layout->main('comment/index', $data);
	}
    
    public function create(){
        // Only Xhr request is allowed
        if (!$this->input->is_ajax_request()){
            header('HTTP/1.1 402 Bad Request');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode(array('message' => 'Bad Request', 'code' => 402)));
        }

        // Validating the user's input
        $this->load->library('form_validation');

        $this->form_validation->set_rules('itemId', 'item id', 'required|numeric|xss_clean');
        $this->form_validation->set_rules('commentDescription', 'description', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', '');

        if($this->form_validation->run() == false) { // if wrong data has been sent
              die(json_encode(
                  array(
                      'has_error' => true,
                      'message' => validation_errors()
                  )));
        } else { // create and return new comment instance
            $sessionData = $this->session->userdata('logged_in');

            $this->load->model('comments');

            $commentId = $this->comments->createOrUpdate(array(
                'item_id'     => $this->input->post('itemId'),
                'description' => $this->input->post('commentDescription'),
                'userid'      => $sessionData['id']
            ));

            if ($commentId){
                $comment = $this->comments->getById($commentId);

                die(json_encode(
                    array(
                        'has_error' => false,
                        'comment_view' => $this->load->view('templates/comment_template', array('comments' => $comment), true)
                    )));
            } else {
                die(json_encode(
                    array(
                        'has_error' => true,
                        'message' => 'Sorry, it\'s failed to enter your comment, please try again'
                    )));
            }
        }
    }
}

/* End of file Comment.php */
/* Location: ./application/controllers/Comment.php */