

      <div id="usertable">
  <table class="table">
  <thead>
    <tr>
      <th>ID</th>
      <th>Maill</th>
      <th>PASSWORD</th>
      <th>#</th>
    </tr>
  </thead>
  <tbody>      
        <?php foreach ($result as $row){ ?>
        <?php if($row->del_flag == 0){ ?>    
      <tr>
        <th scope="row"><?php echo $row->id; ?></th>
        <td><?php echo $row->email; ?></td>
        <td><?php echo $row->password; ?></td>
        <td><button type="button" <?php
        echo 'name="id"';
        echo 'value="'.$row->id.'"';
        ?> class="btn btn-danger">Delete</button></td>
        </tr>
        <?php } ?>
        <?php } ?>
  </tbody>
</table>
</div>
<script>
$(function(){
$(document).on('click', 'button[name=id]', function(){
//$("button[name=id]").live('click',function(){
var dbid = $(this).val();
//alert(dbid);
    // フォームの送信データをAJAXで取得する
    var userid = dbid; 
    // jQueryのAJAXファンクションを利用
    $.ajax({
            url: '/admin/user_del',
            type: 'post',
            data: {'userid':userid},
            // url, POSTデータ, form_dataの取得に成功したら、mgsファンクションを実行する
            success: function(data){
                console.log(data);
                $("#usertable table tbody").empty();
                for(var i in data){
                    if(data[i].del_flag != 1){
                    $("#usertable table tbody").append('<tr><th scope="row">' + data[i].id + '</th><td>' + data[i].email + '</td><td>' + data[i].password + '</td><td><button type="button" name="id" value="' + data[i].id + '" class="btn btn-danger">Delete</button></td></tr>');
                   }
                }
            }
    });
    return false;
});
});
</script>
</script>
    </div>


