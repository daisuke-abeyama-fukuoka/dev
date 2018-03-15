<?php

class Temp_users_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
    }
    
    public function generate_key()
    {
        do {
            $key = md5(uniqid());
            $query = $this->db->where('key', $key)->get('temp_users');
        } while($query->num_rows() > 0);
        return $key;
    }
    
    public function insert($data)
    {
        $data['send'] = date('Y-m-d H:i:s');
        return (bool)$this->db->insert('temp_users', $data);
    }
    
    public function delete_from_key($key)
    {
        $this->db->where("key", $key);
        $this->db->delete("temp_users");
    }
    
    /**
     * keyを元にtemp_usersからレコードを取得
     * @param string $key
     * @return array
     */
    public function get_from_key($key)
    {
        $query = $this->db->where('key', $key)->get('temp_users');
        if(!$query) {
            return null;
        }
        $records = $query->result_array();
        if(count($records) !== 1) {
            // 同じキーが複数　→　ありえない
            return null;
        }
        return $records[0];
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    public function add_temp_users($key){
        $data = array(
            "login_id" => $this->input->post("login_id"),
            //"name"   => $this->input->post("username"),
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
                //"name"   => $row->name,
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