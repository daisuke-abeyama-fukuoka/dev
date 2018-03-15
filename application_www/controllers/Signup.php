<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * ログインユーザー作成ページ
 */
class Signup extends CI_Controller 
{
    protected $valid_rules = [
        [
            'field' => 'login_id',
            'label' => 'ログインID',
            'rules' => 'is_unique[users.login_id]',
        ],
        [
            'field' => 'email',
            'label' => 'メールアドレス',
            'rules' => 'required|trim|valid_email|is_unique[users.email]',
        ],
        [
            'field' => 'password',
            'label' => 'パスワード',
            'rules' => 'required|trim',
        ]
    ];
    
    public function __construct() {
        parent::__construct();
        if($this->session->userdata("is_logged_in")){
            // ログイン済みならメインメニューに飛ばす
            redirect("main/");
        }
    }
    
    public function index(){
        $message = "";
        if($this->input->get('error')) {
            $message = "登録に失敗しました";
        }
        
        $this->load->template("signup/index", compact('message'));
    }
    
    public function valid()
    {
        $this->load->library("form_validation");
        $this->form_validation->set_rules($this->valid_rules);
        //メールアドレスが登録済みの場合
        $this->form_validation->set_message("is_unique", "入力したメールアドレスはすでに登録されています");
        $message = "";
        if(!$this->form_validation->run()){
            $message = validation_errors();
            return $this->load->template("signup/index", compact('message'));
        }
        
        // DB登録 → メール送信の順に行う
        $this->load->model("temp_users_model");

        // 必要データの確保
        $key = $this->temp_users_model->generate_key();
        $email = $this->input->post('email');
        $login_id = $this->input->post('login_id');
        
        // DBへ格納
        // temp_usersにlogin_idを格納しているが、login_idの重複チェックはusersでしか行なっていない。
        // メールアドレスだけ登録させて、その後に本登録画面でlogi_idを入力させる等を行なった方が良い
        $result = $this->temp_users_model->insert([
            'key'      => $key,
            'email'    => $email,
            'login_id' => $login_id,
            'password' => $this->input->post('password'),
        ]);

        if(!$result) {
            redirect('signup/?error=1');
        }

        // メール送信
        $this->load->library("mail_library");
        $this->mail_library->type(Mail_library::TYPE_CREATE_USER);

        //送信先の設定
        $this->mail_library->to($email);

        //タイトルの設定
        $this->mail_library->subject("仮の会員登録が完了しました。");
        //メッセージの本文
        $message  = "このたびは会員登録ありがとうございます。\n";
        $message .= "あなたのログインIDは[".$login_id ."]です。\n";
        $message .= "下記のURLをクリックして会員登録を行なってください。\n";
        $message .= base_url('register/complete/'.$key);
        $this->mail_library->message($message);

        if(!$this->mail_library->send()){
            redirect('signup/?error=2');
        }
        
        redirect('signup/complete');
    }
    
    public function complete()
    {
        $this->load->template("signup/complete");
    }
}
