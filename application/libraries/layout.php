<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Layout {

    public function __construct(){
        $this->ci = & get_instance();
    }

    /**
     * Main layout
     * @access	public
     * @param string $view
     * @param array $data
     */
    public function main($view, $data = array()) {
        $this->ci->load->view('head', $data);
        $this->ci->load->view('header', $data);
        $this->ci->load->view($view, $data);
        $this->ci->load->view('footer', $data);
    }

}