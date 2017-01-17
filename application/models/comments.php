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
}