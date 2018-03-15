<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller 
{
    protected $valid_rules = [
        [
            'field' => 'login_id',
            'label' => 'ログインID',
            'rules' => 'required|trim|xss_clean|callback_validate_credentials',
        ],
        [
            'field' => 'password',
            'label' => 'パスワード',
            'rules' => 'required|md5|trim',
        ]
    ];
    
    public function __construct() {
        parent::__construct();
        // ログインしていたらメインページに飛ばす
        if($this->session->userdata("is_logged_in")){
            // ログイン済みならメインメニューに飛ばす
            redirect("main/");
        }
    }
    
    public function index(){
        $message = "";
        $this->load->template('login', compact('message'));
    }
    
    public function valid()
    {
        $this->load->library("form_validation");
        $this->load->helper('security');
        
        $this->form_validation->set_rules($this->valid_rules);
        $this->form_validation->set_rules("password", "パスワード", "required|md5|trim");
        if($this->form_validation->run()){
            // ログインIDとパスワードからIDを取得する
            $user_id = $this->users_model->get_id($this->input->post('login_id'), $this->input->post('password'));
            $data = [
                "user_id"     => $user_id,
                "is_logged_in" => 1
            ];
            $this->session->set_userdata($data);
            redirect("main/");
        }
        $message = validation_errors();
        $this->load->template("login", compact('message'));
    }
    
    public function validate_credentials() {
        //Email情報がPOSTされたときに呼び出されるコールバック機能
        $this->load->model("users_model");
        if($this->users_model->can_log_in()){
            //ユーザーがログインできたあとに実行する処理
            return true;
        }else{
            //ユーザーがログインできなかったときに実行する処理
            $this->form_validation->set_message("validate_credentials", "ユーザー名かパスワードが異なります。");
            return false;
        }
    }
}
