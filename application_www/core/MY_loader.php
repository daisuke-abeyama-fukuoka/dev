<?php if ( ! defined('BASEPATH')) exit('No direct script accessallowed');
class MY_Loader extends CI_Loader {
    public function template($template_name, $vars = array(), $return = FALSE)
    {
        if($return):
        $content  = $this->view('template/header', $vars, $return);
        $content .= $this->view($template_name, $vars, $return);
        $content .= $this->view('template/footer', $vars, $return);

        return $content;
    else:
        $this->view('template/header', $vars);
        $this->view($template_name, $vars);
        $this->view('template/footer', $vars);
    endif;
    }
    public function timestamp(){
        //$this->load->helper('date');
        //$datestring = '%Y-%m-%d %H:%i:%s';
        //$time = time();
        return date('Y-m-d H:i:s');
    }
}