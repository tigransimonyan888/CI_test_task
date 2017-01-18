<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); //we need to call PHP's session object to access it through CI

class Comment extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        // Loading json response library
        $this->load->library('json_response');
        
        //If no session, redirect to login page or appropriate json response
        if (!$this->session->userdata('logged_in')){
            if ($this->input->is_ajax_request()){
                $this->json_response->json_response(null, 401);
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
        
        // Load those models which are used frequently
        $this->load->model('comments');
    }

    /**
     * @param $itemId
     */
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
        $this->json_response->check_ajax_request();

        // Validating the user's input
        $this->load->library('form_validation');

        $this->form_validation->set_rules('itemId', 'item id', 'required|numeric|xss_clean');
        $this->form_validation->set_rules('commentDescription', 'description', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('', '');

        if($this->form_validation->run() == false) { // if wrong data has been sent
            $response_data['has_error'] = true;
            $response_data['message']   = validation_errors();
        } else { // create and return new comment instance
            $sessionData = $this->session->userdata('logged_in');

            $commentId = $this->comments->createOrUpdate(array(
                'item_id'     => $this->input->post('itemId'),
                'description' => $this->input->post('commentDescription'),
                'userid'      => $sessionData['id']
            ));

            if ($commentId){
                $comment = $this->comments->getById($commentId);

                $response_data['has_error'] = false;
                $response_data['comment_view']   = $this->load->view('templates/comment_template', array('comments' => $comment), true);
            } else {
                $response_data['has_error'] = true;
                $response_data['message']   = 'Sorry, it\'s failed to enter your comment, please try again';
            }
        }
        $this->json_response->json_response($response_data);
    }

    public function check_new_comments (){
        // Only Xhr request is allowed
        $this->json_response->check_ajax_request();

        $response_data = array();

        // Validating the user's input
        $this->load->library('form_validation');

        $this->form_validation->set_rules('lastCommentId', 'last Comment Id', 'required|numeric|xss_clean');
        $this->form_validation->set_rules('itemId', 'itemId', 'required|numeric|xss_clean');
        $this->form_validation->set_error_delimiters('', '');

        if($this->form_validation->run() == false) {  // if wrong data has been sent
            $response_data['has_error'] = true;
            $response_data['message']   = validation_errors();
        } else {
            $userID = $this->session->userdata('logged_in')['id'];
            $lastCommentId = $this->input->post('lastCommentId');
            $itemId = $this->input->post('itemId');

            $comments = $this->comments->getNewComments($itemId, $lastCommentId, $userID);

            $commentsCount = count($comments);

            if ($commentsCount > 0) {
                $message_part = 'are ' . $commentsCount .' new comments';

                if ($commentsCount == 1) {
                    $message_part = 'is 1 new comment';
                }

                $response_data['has_error']     = false;
                $response_data['firstCommentId']  = $comments;
                $response_data['notification_view']  = $this->load->view('templates/comments_notification_template', array('message_part' => $message_part), true);
                $response_data['comment_view']   = $this->load->view('templates/comment_template', array('comments' => $comments), true);
            } else {
                $response_data['has_error'] = false;
                $response_data['comment_view']  = '';
            }
        }

        $this->json_response->json_response($response_data);
    }

    /**
     * get typing users list
     * @param $comment_id
     */
    public function getTypingUsers($comment_id) {
        // Only Xhr request is allowed
        $this->json_response->check_ajax_request();
        
        $sessionData = $this->session->userdata('logged_in');
        
        $data['comment_id'] = $comment_id;
        $data['username'] = $sessionData['username'];
        
        $result = $this->comments->get_typing_users($data);

        $this->json_response->json_response(array('typingUsers' => $result['users']));
    }


    /**
     *  insert or update typing users
     */
    public function setTypingUser() {
        // Only Xhr request is allowed
        $this->json_response->check_ajax_request();
        
        $sessionData = $this->session->userdata('logged_in');

        $data = array(
            'comment_id' => $this->input->post('comment_id'),
            'username' => $sessionData['username']
        );

        if($this->comments->check_duplicate_typing_users($data) < 1) {
            $this->comments->insert_typing_users($data);
        } else {
            $this->comments->update_typing_users($data);
        }

        $this->json_response->json_response(array('status' => 'success'));
    }
}

/* End of file Comment.php */
/* Location: ./application/controllers/Comment.php */