<?php
    error_reporting(0);
    date_default_timezone_set("PRC"); // China
    $config = require './key.php';
    require "./HSMTPClient.class.php";
    @header("Content-Type:text/html;charset=utf-8");
    ?>
    <!DOCTYPE html>
	<html>
	<head>
		<style type="text/css">
            body{
            	min-width:768px;
            	margin:0 auto;
            	text-align:center;
            }
        </style>
	</head>
		<body>
    <?php
    if(!empty($_POST['msg'])){
        $hsmtp = new HSMTPClient("http://api.hafrans.com/dannmaku", "HIME");

        try{
            $hsmtp->openConnection($_POST['msg']);

        }catch (Exception $e){
            echo "遇到了一些小错误：".$e->getMessage();
        }?>
       	Send Success！<br />
        <?php
    }
?>

		<form method="post">
			<input type="text" name="msg" placeholder="请输入弹幕" />
			<input type="submit" value="发射" />
		</form>
		<hr />
		<a href="//github.com/hafrans/dannmaku-hime-server/" target="_blank">Github</a>
	</body>
</html>
