<?php

class Users_model extends CI_Model{
    public function can_log_in(){	
        //ログイン判定
        //$this->db->where("email", $this->input->post("email"));
        //POSTされたemailデータとDB情報を照合する
        $this->db->where("login_id", $this->input->post("login_id"));
        //POSTされたパスワードデータとDB情報を照合する
        $this->db->where("password", md5($this->input->post("password")));
        $query = $this->db->get("users");

        if($query->num_rows() == 1){
            //ユーザーが存在した場合の処理
            return true;
        }else{					
            //ユーザーが存在しなかった場合の処理
            return false;
        }
    }
    public function regist($data){
        $this->db->insert("users", $data);
        return true;
    }
    public function output($key){
        
    }
}