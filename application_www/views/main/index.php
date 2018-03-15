<h1><?= htmlspecialchars($user['name']) ?>様メンバーページにようこそ</h1>
<h2>顧客登録画面</h2>
<?
    echo form_open("member/com_regist_validation");
    echo validation_errors();
?>

<div id="registtable">
    <table class="table">
        <thead>
          <tr>
            <th>企業名</th>
            <th>担当者名</th>
            <th>メールアドレス</th>
            <th>#</th>
          </tr>
        </thead>
        <tbody>      
          <td>
              <div class="form-group">
                  <?
                    $comdata = ['name'=>'company_name',"type"=>"text","class"=>"form-control","placeholder"=>"企業名"];
                    echo form_input($comdata,$this->input->post("company_name"));
                  ?>
             </div>
          </td>
          <td>
              <div class="form-group">
                  <?
                    $parsondata = ['name'=>'company_parson_name',"type"=>"text","class"=>"form-control","placeholder"=>"担当者様名"];
                    echo form_input($parsondata,$this->input->post("company_parson_name"));
                  ?>
             </div>
          </td>
          <td>
              <div class="form-group">
                 <?
                    $formdata = ['name'=>'company_email',"type"=>"text","class"=>"form-control","aria-describedby"=>"emailHelp","placeholder"=>"メールアドレスを入力してください"];
                    echo form_input($formdata,$this->input->post("com_email"));
                 ?>
             </div>
          </td>    
          <td>
              <?= form_submit(
                      array(
                        'name'  => 'login_submit',
                        'id'    => 'submit', 
                        'value' => 'Regist', 
                        'class' => 'btn btn-primary'
              )); ?>                
          </td>
        </tbody>
    </table>
</div>
<?php echo form_close(); ?>

