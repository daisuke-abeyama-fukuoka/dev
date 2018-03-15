<h1>会員登録ページ</h1>
<?
    echo form_open("main/signup_validation");
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
    <label for="exampleInputEmail1">Email address</label>
    <? 
        $formdata = ['name'=>'email',"type"=>"text","class"=>"form-control","id"=>"exampleInputEmail1","aria-describedby"=>"emailHelp","placeholder"=>"Enter email"];
        echo form_input($formdata,$this->input->post("email"));
    ?>
</div>
<div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <?
        $passdata = ['name'=>'password',"type"=>"password","class"=>"form-control","id"=>"exampleInputPassword1","placeholder"=>"Password"];
        echo form_password($passdata,"password");
        $spassdata = ['name'=>'cpassword',"type"=>"cpassword","class"=>"form-control","id"=>"exampleInputPassword1","placeholder"=>"確認用ぱ"];
    ?>
</div>
<?= form_submit(
        array(
            'name'  => 'signup_submit',
            'id'    => 'submit', 
            'value' => '会員登録', 
            'class' => 'btn btn-primary'
)); ?>
<?= form_close(); ?>
