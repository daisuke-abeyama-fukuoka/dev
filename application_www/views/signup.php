	<h1>会員登録ページ</h1>
	<?php
        //エラー表示
        echo form_open("main/signup_validation"); //フォームを開く
        echo validation_errors(); //バリデーションのエラー表示用
        ?>
        <div class="form-group">
        <label for="exampleInputID">ログインID</label>
        <?php 
        $loginiddata = ['name'=>'login_id',"type"=>"text","class"=>"form-control","id"=>"exampleInputID","placeholder"=>"LoginID"];
        echo form_input($loginiddata,$this->input->post("login_id"));
        ?>
        </div>
        <!--
        <div class="form-group">
        <label for="exampleInputName">ユーザーネーム</label>
        <?php 
        //$namedata = ['name'=>'username',"type"=>"text","class"=>"form-control","id"=>"exampleInputName","placeholder"=>"ユーザー名"];
        //echo form_input($namedata,$this->input->post("username"));
        ?>
        </div>
        -->
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
        $spassdata = ['name'=>'cpassword',"type"=>"cpassword","class"=>"form-control","id"=>"exampleInputPassword1","placeholder"=>"確認用ぱ"];
        //echo form_password($spassdata,"cpassword");
        ?>
        </div>
        <!--<button type="submit" class="btn btn-primary">Submit</button>-->
        <?php echo form_submit(array(
        'name'=>'signup_submit',
        'id' => 'submit', 
        'value' => '会員登録', 
        'class' => 'btn btn-primary'
        )); ?>
        <?php echo form_close(); ?>
<?php
/*
    echo form_open("main/signup_validation");
    echo validation_errors();

    echo "<p>Email：";
    echo form_input("email", $this->input->post("email"));
    echo "</p>";

    echo "<p>パスワード：";
    echo form_password("password");	//パスワードの入力フィールド
    echo "</p>";

    echo "<p>パスワードの確認";
    //echo form_password("cpassword");	//パスワード入力ミス防止用のフィールド
    echo "</p>";

    echo "<p>";
    echo form_submit("signup_submit", "会員登録する");	//会員登録ボタン
    echo "</p>";
*/
?>