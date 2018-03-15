<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller 
{
    
    protected $user = [];
    
    public function __construct() {
        parent::__construct();
        // ログインしていなければログインページに飛ばす
        if(!$this->session->userdata("is_logged_in")){
            redirect("login/");
        }
        $this->load->model('users_model');
        $user_id = $this->session->userdata('user_id');
        $this->user = $this->users_model->get($user_id);
    }
    
    public function index()
    {
        $user = $this->user;
        $this->load->template('main/index', compact('user'));
    }
}
