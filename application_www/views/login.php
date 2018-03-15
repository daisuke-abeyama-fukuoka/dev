<h1>ログインページ</h1>
<?if($message):?>
<div class='alert alert-danger'><?=$message?></div>
<?endif?>
<?= form_open("login/valid")?>
<div class="form-group">
    <label for="exampleInputID">ログインID</label>
    <?= form_input('login_id', $this->input->post("login_id"), ['id' => 'login_id', 'class' => 'form-control', 'placeholder' => 'LoginID']);?>
</div>
<div class="form-group">
<label for="exampleInputPassword1">Password</label>
    <?
        $passdata = ['name'=>'password',"type"=>"password","class"=>"form-control","id"=>"exampleInputPassword1","placeholder"=>"Password"];
        echo form_password('password', null, ['class' => 'form-control', 'placeholder' => 'Password']);
    ?>
</div>
<?= form_submit('login_submit', 'Enter', ['id' => 'submit', 'class' => 'btn btn-primary']) ?>
<?= form_close(); ?>
<a href="<?= base_url() . "signup/" ?>">新規会員登録する</a>
