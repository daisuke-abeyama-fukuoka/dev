<?php
/**
 * メール送信履歴
 */
class Mail_log_model extends MY_Model
{
    const STATUS_SUCCESS = 1;
    const STATUS_FAILED  = 9;
    
    public static $status_list = [
        self::STATUS_SUCCESS => '成功',
        self::STATUS_FAILED  => '失敗',
    ];
    
    public static $status_label_list = [
        self::STATUS_SUCCESS => 'label-success',
        self::STATUS_FAILED  => 'label-danger',
    ];
    
    protected static $table = 'mail_log';
    protected static $is_updated_at = false;
}
