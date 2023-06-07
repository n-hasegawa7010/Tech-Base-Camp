<?php

// 確認画面

// 入力画面のフォームから値を受け取る
$name = htmlentities($_POST['name'], ENT_QUOTES);
$email = htmlentities($_POST['email'], ENT_QUOTES);
$comment = htmlentities($_POST['comment'], ENT_QUOTES);

?>

<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<style type="text/css">
	span {
		color: green;
	}
	</style>

	<title>確認フォーム</title>
</head>

<body>
	<p>次の内容でよろしければ、送信ボタンを押してください。</p>
	<p>
		■お名前 ： <span><?php echo $name ?></span>
	</p>
	<p>
		■メールアドレス ： <span><?php echo $email ?></span>
	</p>
	<p>
		■コメント ： <span><?php echo $comment ?></span>
	</p>
</body>

</html>
