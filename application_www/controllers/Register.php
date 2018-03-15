<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Register extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->helper('security');
        $this->load->model('users_model');
        $this->load->model("temp_users_model");
    }
    
    public function complete($key) {
        // key を元にユーザー情報を取得
        $temp_user = $this->temp_users_model->get_from_key($key);
        if(!$temp_user) {
            echo "invalid key";
            exit();
        }
        
        // temp_userの情報を元にuserを登録
        $user_id = $this->users_model->insert([
            'login_id' => $temp_user['login_id'],
            'email'    => $temp_user['email'],
            'password' => md5($temp_user['password']),
        ]);
        
        // temp_usersからレコードを削除
        $this->temp_users_model->delete_from_key($key);
        
        $data = [
            //"email" => $newemail,
            "user_id"      => $user_id,
            "is_logged_in" => 1
        ];
        $this->session->set_userdata($data);
        redirect("main/");
    }
}
