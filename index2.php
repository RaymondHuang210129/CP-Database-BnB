<?php 
	session_start(); 
	# remove all session variables 
	session_unset(); 
	# destroy the session 
	session_destroy(); 
	$_SESSION['Authenticated']=false; 
?>
<!DOCTYPE html>
<html>
    <head>
        <title>創建帳號</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link type="text/css" rel="stylesheet" href="index.css">
    </head>

    <body>
        <div class="board">
            <h2>創建帳號</h2>
            <form action="signin.php" method="post">
                使用者名稱: <input type="text" name="YourName">
                <br>
                <br>
                　電子郵件: <input type="text" name="YourMail">
                <br>
                <br>
                　　　帳號: <input type="text" name="YourAccount"> 
                <br>
                <br>
                　　　密碼: <input type="password" name="YourPassword">
                <br>
                <br>
                　確認密碼: <input type="password" name="ConfirmPassword">
                <br>
                <br>
                <input type="submit" value="創建">
                <br>
                <br>
                <input type="button" value="返回" onclick="location.href='http://people.cs.nctu.edu.tw/~tsejui210129/index.php'">
            </form>
        </div>
    </body>
</html>