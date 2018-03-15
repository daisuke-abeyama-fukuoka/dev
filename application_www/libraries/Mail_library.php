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
    protected $layout = 'mail/_layout';
    protected $error;
    protected $print_debugger;
    
    function __construct() 
    {
        $this->load->library('parser');
        $this->type = self::TYPE_OTHER;
        $this->title = $this->config->item('title');
        $this->from  = $this->config->item('from_mail') ? $this->config->item('from_mail') : 'y.fujiki201803@gmail.com';
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
