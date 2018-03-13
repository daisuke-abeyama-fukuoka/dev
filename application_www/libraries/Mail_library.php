<?php 
/**
 * メール送信用クラス
 *
 * @author KyushuLab01
 */
class Mail_library 
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
    
    function __construct() 
    {
        $this->CI =& get_instance();
        $this->CI->library('parser');
        $this->type = self::TYPE_OTHER;
        $this->title = $this->CI->config->item('title');
        $this->from  = $this->CI->config->item('from_mail') ? $this->CI->config->item('from_mail') : 'no-reply@shaseen.com';
        $this->from_name = mb_encode_mimeheader($this->title, 'UTF-8', 'B');
    }
    
    public function type($type)
    {
        $this->type = $type;
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
        
        $this->CI->library('email');
        $this->CI->email->from($this->from, $this->from_name);
        $this->CI->email->to($this->to);
        if($this->cc) {
            $this->CI->email->cc($this->cc);
        }
        if($this->bcc) {
            $this->CI->email->bcc($this->bcc);
        }
        $this->CI->email->subject($this->subject);
        $this->CI->email->message($this->message);
        $this->CI->email->set_wordwrap(false);
        $result = $this->CI->email->send();
        
        $this->error = $this->CI->email->print_debugger();
        
        $this->CI->model('mail_log')->insert([
            'status'  => $result ? Mail_log_model::STATUS_SUCCESS : Mail_log_model::STATUS_FAILED,
            'type'    => $this->type,
            'to'      => $this->to,
            'cc'      => is_array($this->cc) ? json_encode($this->cc) : $this->cc,
            'bcc'     => is_array($this->bcc) ? json_encode($this->bcc) : $this->bcc,
            'subject' => $this->subject,
            'message' => serialize($this->message),
            'error'   => serialize($this->error),
        ]);
        return $result;
    }
}
