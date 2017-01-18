<?php

class Comments extends Base_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * This function is using to create new entry or update an existing one
     * @param $data
     * @return bool
     */
    public function createOrUpdate($data){
        return $this->duplicate_key_update('comments', $data);
    }

    /**
     * Retrieve a comment with the given id
     * @param $id
     * @return mixed
     */
    public function getById($id){
        $this->db->select('comments.*, users.username');
        $this->db->from('comments');
        $this->db->join('users', 'comments.userid = users.id');
        $this->db->where('comments.commentid', $id);

        return $this->db->get()->result_array();
    }

    /**
     * This function is using to retrieve all comments related to given $itemId
     * @param $itemId
     * @return mixed
     */
    public function getAllByItemId($itemId){
        $this->db->select('comments.commentid, comments.description, comments.date, users.username');
        $this->db->from('comments');
        $this->db->join('users', 'comments.userid = users.id');
        $this->db->where('item_id', $itemId);
        $this->db->order_by("comments.date", "asc");

        return $this->db->get()->result_array();
    }

    public function getNewComments ($itemId, $lastCommentId, $userId){
        $this->db->select('comments.commentid, comments.description, comments.date, users.username');
        $this->db->join('users', 'comments.userid = users.id');
        $this->db->order_by("comments.date", "asc");

        return $this->db->get_where('comments', array('item_id' => $itemId, 'comments.commentid >' => $lastCommentId, 'users.id <>' => $userId))->result_array();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function get_typing_users($data) {
        $this->db->select('group_concat(username) as users');
        $this->db->from('typing_users');
        $this->db->where('comment_id', $data['comment_id']);
        $this->db->where('username !=', $data['username']);
        $this->db->where("date >= DATE_SUB(now(), INTERVAL 8 SECOND)", NULL, FALSE);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function check_duplicate_typing_users($data) {
        $this->db->from('typing_users');
        $this->db->where($data);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function insert_typing_users($data) {
        $this->db->insert('typing_users', $data);
    }

    /**
     * @param $data
     */
    public function update_typing_users($data) {
        $this->db->where($data);
        $this->db->set('date', 'NOW()', false);
        $this->db->update('typing_users');
    }
}