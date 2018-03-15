<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
$config['protocol'] = 'sendmail';
$config['mailpath'] = '/usr/sbin/sendmail';
 */
$config['protocol']      = 'mail';
$config['smtp_timeout']  = '7';
$config['charset']       = 'UTF-8';
$config['newline']       = '\r\n';
$config['mailtype']      = 'text'; 
$config['validation']    = TRUE;
$config['wordwrap']      = TRUE;
$config['from_mail']     = 'y.fujiki201803@gmail.com';
