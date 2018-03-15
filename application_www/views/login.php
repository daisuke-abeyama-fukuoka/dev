<h1>ログインページ</h1>
<?
    echo form_open("main/login_validation");
    echo validation_errors();
?>
<div class="form-group">
    <label for="exampleInputID">ログインID</label>
    <? 
        $loginiddata = ['name'=>'login_id',"type"=>"text","class"=>"form-control","id"=>"exampleInputID","placeholder"=>"LoginID"];
        echo form_input($loginiddata,$this->input->post("login_id"));
    ?>
</div>
<div class="form-group">
<label for="exampleInputPassword1">Password</label>
    <?
        $passdata = ['name'=>'password',"type"=>"password","class"=>"form-control","id"=>"exampleInputPassword1","placeholder"=>"Password"];
        echo form_password($passdata,"password");
    ?>
</div>
<?= form_submit(
        array(
            'name'  => 'login_submit',
            'id'    => 'submit', 
            'value' => 'Enter', 
            'class' => 'btn btn-primary'
)); ?>
<?= form_close(); ?>
<a href="<?= base_url() . "main/signup" ?>">新規会員登録する</a>
