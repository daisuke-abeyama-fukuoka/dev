<?php 
/**
 * メール送信用クラス
 *
 * @author KyushuLab01
 */
class Mail_library extends CI_Model
{
    const TYPE_OTHER                      = 0;    // その他
    const TYPE_ERROR                      = 9;    // おおまかにエラーメール
    const TYPE_CREATE_USER                = 10;   // ユーザ作成
    const TYPE_NEW_NOTICE                 = 30;   // 新着お知らせ
    const TYPE_APPLICATION                = 40;   // 外部表示申し込み
    const TYPE_APPLICATION_TO_SUPPORT     = 41;  // 外部表示申し込み（サポート用）
    const TYPE_INQUIRY_UPDATE             = 990;  // エラー診断
    const TYPE_INQUIRY_UPDATE_TO_SUPPORT  = 991;  // エラー診断（サポート用）
    const TYPE_TASK_ERROR                 = 998;  // taskで処理失敗
    const TYPE_TASK_FATAL_ERROR           = 999;  // taskにFatal Errorが発生し強制的に終了
    const TYPE_CREATE_ACCOUNT             = 1000; // [管理画面]アカウント発行
    const TYPE_CREATE_ADMINISTRATOR       = 1100; // [管理画面]管理アカウント作成
    const TYPE_CREATE_USER_FROM_ADMIN     = 1110; // [管理画面]ユーザー作成
    const TYPE_USER_PASSWORD_RESET        = 1150; // [管理画面]ユーザーパスワードリセット
    
    public static $types = [
        self::TYPE_OTHER                     => 'その他',
        self::TYPE_ERROR                     => 'エラーメール',
        self::TYPE_CREATE_USER               => 'ユーザ作成',
        self::TYPE_NEW_NOTICE                => '新着お知らせ',
        self::TYPE_INQUIRY_UPDATE            => 'エラー診断申し込み',
        self::TYPE_INQUIRY_UPDATE_TO_SUPPORT => 'エラー診断申し込み（サポート宛）',
        self::TYPE_APPLICATION               => '外部表示申し込み',
        self::TYPE_APPLICATION_TO_SUPPORT    => '外部表示申し込み（サポート宛）',
        self::TYPE_TASK_ERROR                => '処理失敗',
        self::TYPE_TASK_FATAL_ERROR          => 'タスクに致命的なエラー',
        self::TYPE_CREATE_ACCOUNT            => '[管理画面]アカウント発行',
        self::TYPE_CREATE_ADMINISTRATOR      => '[管理画面]管理アカウント作成',
        self::TYPE_CREATE_USER_FROM_ADMIN    => '[管理画面]ユーザ作成',
        self::TYPE_USER_PASSWORD_RESET       => '[管理画面]ユーザパスワードリセット',
    ];
    
    /**
     *
     * @var MY_Controller
     */
    protected $CI;
    protected $title;
    protected $type;
    protected $from;
    protected $from_name;
    protected $to;
    protected $cc;
    protected $bcc;
    protected $subject;
    protected $message;
    protected $layout = 'mail/_layout';
    protected $error;
    protected $print_debugger;
    
    function __construct() 
    {
        //$this->CI =& get_instance();
        $this->load->library('parser');
        $this->type = self::TYPE_OTHER;
        $this->title = $this->config->item('title');
        //$this->from  = $this->CI->config->item('from_mail') ? $this->CI->config->item('from_mail') : 'y.fujiki201803@gmail.com';
        //var_dump($this->from);
        $this->from_name = mb_encode_mimeheader($this->title, 'UTF-8', 'B');
    }
    
    public function type($type)
    {
        $this->type = $type;
        return $this;
    }
    
    public function from($from)
    {
        $this->from = $from;
        return $this;
    }
    public function to($to)
    {
        $this->to = $to;
        return $this;
    }
    
    public function subject($subject)
    {
        $this->subject = "[" . $this->title . "]" . $subject;
        return $this;
    }
    
    public function layout($path)
    {
        $this->layout = $path;
    }
    
    public function message($message, $data = null)
    {
        if(!is_null($data)) {
            $message = $this->CI->_make_blade($this->layout, ['path' => $message, 'values' => $data]);
        }
        $this->message = $message;
        return $this;
    }
    
    public function cc($cc)
    {
        $this->cc = $cc;
        return $this;
    }
    
    public function bcc($bcc)
    {
        $this->bcc = $bcc;
        return $this;
    }
    /*
    public function print_debugger($print_debugger)
    {
        $this->print_debugger = $print_debugger;
        return $this;
    }    
    */
    public function send($to = null, $subject = null, $message = null)
    {
        if(!is_null($to)) {
            $this->to = $to;
        }
        if(!is_null($subject)) {
            $this->subject = $subject;
        }
        if(!is_null($message)) {
            $this->message = $message;
        }
        
        $this->load->library('email');
        $this->email->from($this->from, $this->from_name);
        $this->email->to($this->to);
        if($this->cc) {
            $this->email->cc($this->cc);
        }
        if($this->bcc) {
            $this->email->bcc($this->bcc);
        }
        $this->email->subject($this->subject);
        $this->email->message($this->message);
        $this->email->set_wordwrap(false);
        $result = $this->email->send(false);
        
        $this->error = $this->email->print_debugger();
        $this->load->model('mail_log_model');

        //$mail_log = new Mail_log_model();
        //$this->CI->mail_log->insert([]);
        $data = array(
            'status'  => $result ? Mail_log_model::STATUS_SUCCESS : Mail_log_model::STATUS_FAILED,
            'type'    => $this->type,
            'to'      => $this->to,
            'cc'      => is_array($this->cc) ? json_encode($this->cc) : $this->cc,
            'bcc'     => is_array($this->bcc) ? json_encode($this->bcc) : $this->bcc,
            'subject' => $this->subject,
            'message' => serialize($this->message),
            'error'   => serialize($this->error),
            'created' => $this->load->timestamp()
        );
        $this->mail_log_model->dbinsert($data);
        return $result;
    }
}
