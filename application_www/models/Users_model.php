<?php

class Users_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
    }
    
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
    
    public function insert($data)
    {
        $data['created'] = date('Y-m-d H:i:s');
        $data['modified'] = date('Y-m-d H:i:s');
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }
    
    public function get($id)
    {
        $query = $this->db->get_where('users', ['id' => $id]);
        if(!$query || $query->num_rows() !== 1) {
            return null;
        }
        return $query->result_array()[0];
    }
    
    public function get_id($login_id, $password)
    {
        $this->db->select('id');
        $this->db->where('login_id', $login_id);
        $this->db->where('password', $password);
        $query = $this->db->get('users');
        
        if(!$query || $query->num_rows() !== 1) {
            return null;
        }
        $records = $query->result_array();
        return (int)$records[0]['id'];
    }
    
    
    public function regist($data){
        $this->db->insert("users", $data);
        return true;
    }
    public function output($key){
        
    }
}