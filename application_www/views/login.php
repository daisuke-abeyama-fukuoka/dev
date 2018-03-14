	<h1>ログインページ</h1>
        <?php
        echo form_open("main/login_validation");
        echo validation_errors();
        ?>

        <div class="form-group">
        <label for="exampleInputID">ログインID</label>
        <?php 
        $loginiddata = ['name'=>'login_id',"type"=>"text","class"=>"form-control","id"=>"exampleInputID","placeholder"=>"LoginID"];
        echo form_input($loginiddata,$this->input->post("login_id"));
        ?>
        </div>


        <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <?php
        $passdata = ['name'=>'password',"type"=>"password","class"=>"form-control","id"=>"exampleInputPassword1","placeholder"=>"Password"];
        echo form_password($passdata,"password");
        ?>
        </div>
        <!--<button type="submit" class="btn btn-primary">Submit</button>-->
        <?php echo form_submit(array(
        'name'=>'login_submit',
        'id' => 'submit', 
        'value' => 'Enter', 
        'class' => 'btn btn-primary'
        )); ?>
        <?php echo form_close(); ?>

        <a href="<?php echo base_url() . "main/signup" ?>">新規会員登録する</a>
