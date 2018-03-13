
	<h1>管理ログインページ</h1>
        
        <?php
        //エラー表示
        echo form_open("admin/login_validation");	//フォームを開く
        echo validation_errors();		//バリデーションのエラー表示用
        ?>
        <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
        <?php 
        $formdata = ['name'=>'email',"type"=>"text","class"=>"form-control","id"=>"exampleInputEmail1","aria-describedby"=>"emailHelp","placeholder"=>"Enter email"];
        echo form_input($formdata,$this->input->post("email"));
        ?>
        <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
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
        
        管理者アカウントについては、システム管理者にお尋ねください。
