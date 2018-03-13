<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
	public function index()
	{
		$this->login();
	}
        //ログインページ
	public function login(){

		$this->load->template('login');

	}
        //ログインフォーム処理
        public function login_validation(){
            $this->load->library("form_validation");	//フォームバリデーションライブラリを読み込む。
            $this->load->helper('security');
            //フォームバリデーションにルールをセット
            $this->form_validation->set_rules("email", "メール", "required|trim|xss_clean|callback_validate_credentials");
            $this->form_validation->set_rules("password", "パスワード", "required|md5|trim");
            
            if($this->form_validation->run()){
                    $data = array(
                            "email" => $this->input->post("email"),
                            "is_logged_in" => 1
                    );
                    $this->session->set_userdata($data);
                    redirect("admin/members");
                }else{						//バリデーションエラーがあった場合の処理
                    $this->load->template("login");
            }            
            //echo $this->input->post("email");
            //echo $this->input->post("password");

        }
        //ログイン処理
        public function validate_credentials(){		//Email情報がPOSTされたときに呼び出されるコールバック機能
                $this->load->model("model_adminusers");

                if($this->model_adminusers->can_log_in()){	//ユーザーがログインできたあとに実行する処理
                        return true;
                }else{					//ユーザーがログインできなかったときに実行する処理
                        $this->form_validation->set_message("validate_credentials", "ユーザー名かパスワードが異なります。");
                        return false;
                }
        }
        //ログイン後のメンバーページ
        public function members(){
            
            if($this->session->userdata("is_logged_in")){	//ログインしている場合の処理
                    $this->load->model("model_adminusers");
                    $membership['result'] = $this->model_adminusers->get_membership();
                    $this->load->template("members",$membership);
            }else{						//ログインしていない場合の処理
                    redirect ("admin/restricted");
            }
        }
        public function user_del(){
            $post_data = $this->input->post('userid');
            //var_dump($post_data);
            $this->load->model("model_adminusers");
            $this->model_adminusers->del_user_update($post_data);
            $this->output->set_content_type('application/json');
            $membership = $this->model_adminusers->get_membership();
            $this->output->set_output(json_encode($membership));
        }
        //ログインしていない状態で会員ページへいく。
        public function restricted(){
            $this->load->template("restricted");
        }
        //ログアウトページ
        public function logout(){
            $this->session->sess_destroy();		//セッションデータの削除
            redirect ("admin/login");		//ログインページにリダイレクトする
        }
}
