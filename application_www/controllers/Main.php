<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {
    public function index(){
        $this->login();
    }
    public function login(){
        //ログイン後のメンバーページ
        if($this->session->userdata("is_logged_in")){
            redirect("user");
        }else{
            $this->load->template('login');
        }
    }
    public function login_validation(){
        $this->load->library("form_validation");
        $this->load->helper('security');

        //$this->form_validation->set_rules("email", "メール", "required|trim|xss_clean|callback_validate_credentials");
        $this->form_validation->set_rules("login_id", "ログインID", "required|trim|xss_clean|callback_validate_credentials");
        $this->form_validation->set_rules("password", "パスワード", "required|md5|trim");

        if($this->form_validation->run()){
            $data = array(
                "login_id"     => $this->input->post("login_id"),
                "is_logged_in" => 1
            );
            $this->session->set_userdata($data);
            redirect("user");
        }else{
            $this->load->template("login");
        }            
    } 
    public function validate_credentials(){		
        //Email情報がPOSTされたときに呼び出されるコールバック機能
        $this->load->model("users_model");

        if($this->users_model->can_log_in()){
            //ユーザーがログインできたあとに実行する処理
            return true;
        }else{					//ユーザーがログインできなかったときに実行する処理
            $this->form_validation->set_message("validate_credentials", "ユーザー名かパスワードが異なります。");
            return false;
        }
    }
    
    public function members(){
        //ログイン後のメンバーページ
        if($this->session->userdata("is_logged_in")){
            $this->load->template("user");
        }else{
            redirect ("main/restricted");
        }
    }
    public function restricted(){
        //ログインしていない状態で会員ページへいく。
        $this->load->template("restricted");
    }
    public function logout(){
        //ログアウトページ
        $this->session->sess_destroy();
        redirect ("main/login");
    }
    public function signup(){
        $this->load->template("signup");
    }
//会員登録バリデーション
    public function signup_validation(){
        $this->load->library("form_validation");
        //formのバリエーションルールを決める
        $this->form_validation->set_rules("login_id", "ログインID", "required|is_unique[users.login_id]");
        //$this->form_validation->set_rules("username", "ユーザー名", "required|is_unique[users.name]");
        $this->form_validation->set_rules("email", "Email", "required|trim|valid_email|is_unique[users.email]");
        $this->form_validation->set_rules("password", "パスワード", "required|trim");//required|
        //$this->form_validation->set_rules("cpassword", "パスワードの確認", "required|trim|maches[password]");

        //メールアドレスが登録済みの場合
        $this->form_validation->set_message("is_unique", "入力したメールアドレスはすでに登録されています");

        if($this->form_validation->run()){

            $this->load->model("temp_users_model");                    
            $this->load->library("mail_library");

            //ランダムキーを生成する
            $key=md5(uniqid());
            //type設定
            $this->mail_library->type(Mail_library::TYPE_CREATE_USER);
            //送信元の情報
            $this->mail_library->from("y.fujiki201803@gmail.com", "送信元");
            //送信先の設定
            $email = $this->input->post("email");
            $login_id = $this->input->post("login_id");
            $this->mail_library->to($email);

            //タイトルの設定
            $this->mail_library->subject("仮の会員登録が完了しました。");
            //メッセージの本文
            $username = $this->input->post("username");
            $message = $username."様\n\nこのたびは会員登録ありがとうございます。\n";
            $message .= "あなたのログインIDは[".$login_id ."]です。";
            $message .= "下記のURLをクリックして会員登録をしてください。\n";

            // 各ユーザーにランダムキーをパーマリンクに含むURLを送信する
            //$message .= base_url('main/resister_user/').$key;
            $message .= 'main/resister_user/'.$key;

            echo "<a href='".base_url('main/resister_user/').$key."' target='_blank'>本登録</a><br /><br />";
            $this->mail_library->message($message);

                //ユーザーに確認メールを送信できた場合、ユーザーを temp_users DBに追加する
            if($this->temp_users_model->add_temp_users($key)){
                if($this->mail_library->send()){
                    echo "ご登録いただいたメールアドレスに本登録メールを送信しました。ご確認お願い致します。";   
                    //echo $this->mail_library->print_debugger();
                }else{
                    echo "Coulsn't send the message.";
                    //echo $this->email->print_debugger();
                }
            }else{
                echo "problem adding to database";
            }
        }else{
            echo "You can't pass,,,";
            $this->load->template("signup");
        }
    }
public function resister_user($key){
    $this->load->model("temp_users_model");

    if($this->temp_users_model->is_valid_key($key)){
        if($login_id = $this->temp_users_model->add_user($key)){	
                echo "success";
                $data = array(
                    //"email" => $newemail,
                    "login_id"     => $login_id,
                    "is_logged_in" => 1
                );
                $this->session->set_userdata($data);
                redirect ("user");
        }else{
            echo "fail to add user. please try again";
        }
    }else{
        echo "invalid key";
    }
}
}
