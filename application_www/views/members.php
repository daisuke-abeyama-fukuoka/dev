<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title>メンバーページ</title>
</head>
<body>

<div id="container">
	<h1>メンバーページにようこそ</h1>
        <?php print_r ($this->session->userdata()); ?>
</div>
<a href="<?php echo base_url() . "main/logout" ?>">ログアウト</a>
</body>
</html>