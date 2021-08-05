<?php
    session_start();
    ob_start();
    if(isset($_SESSION['Authenticated']) and $_SESSION['Authenticated'] == true and isset($_SESSION['Account']))
    {
        if(isset($_SESSION['Identity']) and $_SESSION['Identity'] == "admin")
        {
            header("Location: admin.php");
            ob_end_flush();
            exit();
        }
        else if(isset($_SESSION['Identity']) and $_SESSION['Identity'] == "user")
        {
            header("Location: user.php");
            ob_end_flush();
            exit();
        }
        else
        {
            session_unset();
            session_destroy(); 
            $_SESSION['Authenticated']=false; 
            ob_end_flush();   
        }
    }
    else
    {
        session_unset();
        session_destroy(); 
        $_SESSION['Authenticated']=false; 
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <title>登入頁面</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link type="text/css" rel="stylesheet" href="index.css">
    </head>

    <body>
        <div class="board">
            <h2>登入頁面</h2>
            <form action="login.php" method="post">
                帳號: <input type="text" name="YourAccount">
                <br>
                <br>
                密碼: <input type="password" name="YourPassword">
                <br>
                <br>
                <input type="submit" value="登入">
                <br>
                <br>
                <input type="button" value="創建帳號" onclick="location.href='http://people.cs.nctu.edu.tw/~tsejui210129/index2.php'">
            </form>
        </div>
    </body>
</html>