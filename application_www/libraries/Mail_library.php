<?php 
/**
 * メール送信用クラス
 *
 * @author KyushuLab01
 */
class Mail_library
{
    const TYPE_OTHER        = 0;    // その他
    const TYPE_ERROR        = 9;    // おおまかにエラーメール
    const TYPE_CREATE_USER  = 10;   // ユーザ作成
    
    public static $types = [
        self::TYPE_OTHER                     => 'その他',
        self::TYPE_ERROR                     => 'エラーメール',
        self::TYPE_CREATE_USER               => 'ユーザ作成',
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
    protected $error;
    protected $print_debugger;
    
    function __construct() 
    {
        $this->CI =& get_instance();
        $this->CI->load->library('parser');
        $this->type = self::TYPE_OTHER;
        $this->title = 'テストメール';
        $this->from  = $this->CI->config->item('from_mail') ? $this->CI->config->item('from_mail') : 'y.fujiki201803@gmail.com';
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
    
    public function message($message)
    {
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
        
        $this->CI->load->library('email');
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
        $result = $this->CI->email->send(false);
        
        $this->error = $this->CI->email->print_debugger();
        $this->CI->load->model('mail_log_model');

        $data = array(
            'status'  => $result ? Mail_log_model::STATUS_SUCCESS : Mail_log_model::STATUS_FAILED,
            'type'    => $this->type,
            'to'      => $this->to,
            'cc'      => is_array($this->cc) ? json_encode($this->cc) : $this->cc,
            'bcc'     => is_array($this->bcc) ? json_encode($this->bcc) : $this->bcc,
            'subject' => $this->subject,
            'message' => serialize($this->message),
            'error'   => serialize($this->error),
            'created' => date('Y-m-d H:i:s'),
        );
        $this->CI->mail_log_model->dbinsert($data);
        return $result;
    }
}
