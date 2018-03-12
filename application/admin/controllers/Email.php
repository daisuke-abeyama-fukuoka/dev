<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email extends CI_Controller{
	function __construct(){
		parent::__construct();
	} 
        function index(){
                $config = array(
                        "protocol" =>"smtp",
                        "smtp_host" => "ssl://smtp.googlemail.com",
                        "smtp_port"=>465,
                        "smtp_usre"=>"y.fujiki201803@gmail.com",
                        "smtp_pass"=>"yuta0925"
                );
                $this->load->library("email", $config);
                $this->email->set_newline("rn");	//エラー回避のおまじない		
        }
}
