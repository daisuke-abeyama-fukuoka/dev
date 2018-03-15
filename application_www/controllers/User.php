<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {
    public function index($name){
        //ログイン後のメンバーページ
            //redirect ("main/restricted");
        var_dump($this->session->userdata("login_id"));
        
        if($this->session->userdata("login_id") == $name){
            $query = $this->db->get_where('user', array('name' => $name), $limit, $offset);
            var_dump($query);
        }
        /*
        if($this->session->userdata("is_logged_in")){
            $login_id = $this->session->userdata("login_id");
            $this->load->model("user_model");
            $data = $this->user_model->output($login_id);
            $this->load->template("members/".$login_id,$username);
        }else{
            redirect ("main/restricted");
        }
        
         */

    }
    public function com_regist_validation(){
        $this->load->library("form_validation");
        $this->load->helper('security');
        
        $this->form_validation->set_rules("company_email", "メール", "required|trim|xss_clean|callback_validate_credentials");
        $this->form_validation->set_rules("company_parson_name", "担当者様", "required");
        $this->form_validation->set_rules("company_name", "企業様", "required");

        if($this->form_validation->run()){
            
            redirect("user");
            
        }else{
            $this->load->template("user");
        }            
    } 

}
