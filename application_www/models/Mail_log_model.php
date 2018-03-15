<?php
/**
 * メール送信履歴
 */
class Mail_log_model extends CI_Model
{
    
        /**
     * @param MY_Controller $CI
     * @access protected
     */
    protected $CI;
    
    /**
     * 0バイト文字の場合、config/database.phpのactive_groupを用いる
     * @static
     * @var string $db_name データベース識別名
     * @access protected
     */
    protected static $db_name = 'default';
    
    /**
     * @static
     * @params string $table テーブル名
     * @access protected
     */
    //protected static $table = null;
    /**
     * @static
     * @params string $primary_key 主キーカラム
     * @access protected
     */
    protected static $primary_key = 'id';
    /**
     * @static
     * @type boolean $is_created_at 作成日が有効か
     * @access protected
     */
    protected static $is_created_at = true;
    /**
     * @static
     * @params boolean $is_updated_at 更新日が有効か
     * @access protected
     */
    //protected static $is_updated_at = true;
    /**
     * @static
     * @params string $created_at 作成日カラム
     * @access protected
     */
    protected static $created_at = 'created';
    /**
     * @static
     * @params string $updated_at 更新日カラム
     * @access protected
     */
    protected static $updated_at = 'modified';
    /**
     * @static
     * @params string $time_format 日付データのフォーマット
     * @access protected
     */
    protected static $time_format = 'Y-m-d H:i:s';
    /**
     * @static
     * @params boolean $is_softdelete 論理削除を行うか
     * @access protected
     */
    protected static $is_softdelete = false;
    /**
     * @static
     * @params string $deleted_at 削除日カラム
     * @access protected
     */
    protected static $deleted_at = 'deleted_timestamp';

    /**
     * primary_keyを引数とした配列。
     * 一度実行した結果を貯めこんでおく
     * @var array
     */
    protected $_pool;
    
    /**
     * poolするかどうか
     * @var bool
     */
    protected $is_pool = true;
    
    /**
     * _validate_recordを行うかどうかのフラグ
     * @var bool
     */
    protected $_is_all = false;
    
    
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

    public function dbinsert($data)
    {
        var_dump($data);

        $this->db->set($data);
        $this->db->insert('mail_log');
    }    
}
