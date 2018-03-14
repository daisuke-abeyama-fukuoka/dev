<?php

class Temp_users_model extends CI_Model{
    public function add_temp_users($key){
        $data = array(
            "login_id"     => $this->input->post("login_id"),
            //"name"     => $this->input->post("username"),
            "email"    => $this->input->post("email"),
            "password" => md5($this->input->post("password")),
            "key"      => $key,
            "send"     => $this->load->timestamp()
        );
        $query=$this->db->insert("temp_users", $data);
        if($query){
            //データ取得が成功したらTrue、失敗したらFalseを返す
            return true;
        }else{
            return false;
        }
    }        
    public function is_valid_key($key){
        $this->db->where("key", $key);
        $query = $this->db->get("temp_users");
        
        if($query->num_rows() == 1){
            return true;
        }else{
            return false;
        }
    }
    public function add_user($key){
        //keyのテーブルを選択
        $this->db->where("key", $key);

        //temp_usersテーブルからすべての値を取得
        $temp_user = $this->db->get("temp_users");	

        if($temp_user){
            $row = $temp_user->row();

            //$rowで取得した値のうち、必要な情報のみを取得する
            $data = array(
                "login_id" => $row->login_id,
                //"name"     => $row->name,
                "email"    => $row->email,
                "password" => $row->password,
                "created"  => $this->load->timestamp(),
                "modified" => $this->load->timestamp()
            );
        $this->load->model("users_model");
        $did_add_user = $this->users_model->regist($data);
        }
        if($did_add_user){		//did_add_userが成功したら以下を実行
            $this->db->where("key", $key);
            $this->db->delete("temp_users");
            //return $data["email"];
            return $data["login_id"];
        }else{
            return false;
        }
    }
    public function resister_user($key){
        echo $key;
    }
}