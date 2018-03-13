<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {
    public function index(){
            $this->login();
    }
    public function login(){
            $this->load->template('login');
    }
    public function login_validation(){
        $this->load->library("form_validation");
        $this->load->helper('security');

        $this->form_validation->set_rules("email", "メール", "required|trim|xss_clean|callback_validate_credentials");
        $this->form_validation->set_rules("password", "パスワード", "required|md5|trim");

        if($this->form_validation->run()){
            $data = array(
                    "email" => $this->input->post("email"),
                    "is_logged_in" => 1
            );
            $this->session->set_userdata($data);

            redirect("main/members");
        }else{
            $this->load->template("login");
        }            
    }
    //ログイン処理
    public function validate_credentials(){		//Email情報がPOSTされたときに呼び出されるコールバック機能
        $this->load->model("model_users");
        if($this->model_users->can_log_in()){	//ユーザーがログインできたあとに実行する処理
                return true;
        }else{					//ユーザーがログインできなかったときに実行する処理
                $this->form_validation->set_message("validate_credentials", "ユーザー名かパスワードが異なります。");
                return false;
        }
    }
    //ログイン後のメンバーページ
    public function members(){
        if($this->session->userdata("is_logged_in")){
            $this->load->template("members");
        }else{
            redirect ("main/restricted");
        }
    }
    //ログインしていない状態で会員ページへいく。
    public function restricted(){
        $this->load->template("restricted");
    }
    //ログアウトページ
    public function logout(){
        $this->session->sess_destroy();
        redirect ("main/login");
    }
    public function signup(){
    $this->load->template("signup");
    }
    //会員登録バリデーション
    public function signup_validation(){
            
            $this->load->library("form_validation");
            $this->form_validation->set_rules("email", "Email", "required|trim|valid_email|is_unique[users.email]");
            $this->form_validation->set_rules("password", "パスワード", "required|trim");
            //$this->form_validation->set_rules("cpassword", "パスワードの確認", "required|trim|maches[password]");

            $this->form_validation->set_message("is_unique", "入力したメールアドレスはすでに登録されています");

            if($this->form_validation->run()){
                    $this->load->library("email");
                    $this->load->model("model_users");                    
                
                    //ランダムキーを生成する
                    $key=md5(uniqid());

                    //送信元の情報
                    $this->email->from("y.fujiki201803@gmail.com", "送信元");
                    
                    //送信先の設定
                    $email = $this->input->post("email");
                    $this->email->to($email);

                    //タイトルの設定
                    $this->email->subject("test仮の会員登録が完了しました。");

                    //メッセージの本文
                    $message = "会員登録ありがとうございます。下記のURLをクリックして会員登録をしてください。\n";

                    // 各ユーザーにランダムキーをパーマリンクに含むURLを送信する
                    //$message .= base_url('main/resister_user/').$key;
                    $message .= 'main/resister_user/'.$key;

                    $this->email->message($message);
                            
                            
                    //ユーザーに確認メールを送信できた場合、ユーザーを temp_users DBに追加する
                    if($this->model_users->add_temp_users($key)){
                        if($this->email->send(FALSE)){
                                echo "ご登録いただいたメールアドレスに本登録メールを送信しました。ご確認お願い致します。";
                                echo $this->email->print_debugger();

                            }else{
                                echo "Coulsn't send the message.";
                                echo $this->email->print_debugger();
                            }
                    }else{ echo "problem adding to database";}
                    //ユーザーを temp_users DBに追加する
                    //$this->model_users->add_temp_users($key);
            }else{
                    echo "You can't pass,,,";
                    $this->load->template("signup");
            }
    }
    public function resister_user($key){
        //add_temp_usersモデルが書かれている、model_uses.phpをロードする
        $this->load->model("model_users");

        if($this->model_users->is_valid_key($key)){	//キーが正しい場合は、以下を実行
            // echo "valid key";

            //add_usersがTrueを返したら以下を実行
            if($newemail = $this->model_users->add_user($key)){	
                    echo "success";
                    $data = array(
                        "email" => $newemail,
                        "is_logged_in" => 1
                    );

                    $this->session->set_userdata($data);
                    redirect ("main/members");
            }else{
                echo "fail to add user. please try again";
            }
        }else{
            echo "invalid key";
        }
    }
}
