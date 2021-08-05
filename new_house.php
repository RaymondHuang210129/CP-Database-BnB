<?php 
	session_start();
    if($_SESSION['Identity'] == "user")
    {
        $input_gray = "disabled='disabled'";
    }
    else
    {
        $input_gray = "";
    }

    $dbservername = 'dbhome.cs.nctu.edu.tw';
    $dbname='tsejui210129_cs_DB_HW1';
    $dbaccount='tsejui210129_cs';
    $dbpassword='ltes123456';

?>
<!DOCTYPE html>
<html>
    <head>
        <title>新增房屋</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link type="text/css" rel="stylesheet" href="admin.css">
    </head>

    <body>
        <div class="board">
            <h2>新增房屋</h2>
            <form action="create_house.php" method="post">
                　房屋名稱: <input type="text" name="HouseName">
                <br>
                <br>
                　房屋價格: <input type="number" name="HousePrice">
                <br>
                <br>
                <table align="center">
                    <tr>
                        <td valign="top">
                            房屋位置:
                        </td>
                        <td>
                            <div class="container3" >
                                <table>
                                    <?php
                                        try
                                        {
                                            $connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
                                            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                            $stmt=$connect->prepare("SELECT * FROM Location WHERE 1 ORDER BY location ASC");
                                            $stmt->execute();
                                            while($row = $stmt->fetch())
                                            {
                                                echo "<tr><td><input type='radio' name='Location'  value='".$row[0]."' ></input></td><td>".$row[1]."</td></tr>";
                                            }
                                        }
                                        catch(PDOException $e)
                                        {
                                            $msg = $e->getMessage();
                                            echo $msg;
                                            session_unset();
                                            session_destroy();
                                            echo <<<"EOT"
                                                <!DOCTYPE html>
                                                <html>
                                                    <script>
                                                        alert("Internel Error.");
                                                        window.location.replace("index.php");
                                                    </script>
                                                </html>
EOT;
                                        }
                                    ?>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
                <br>
                <br>
                　　　時間: <input type="date" name="HouseTime"> 
                <br>
                <br>
                　　擁有者: <input type="text" name="HouseOwner" <?php echo $input_gray; ?> value="<?php echo $_SESSION['Name']; ?>">
                <br>
                <br>
                <table align="center">
                    <tr>
                        <td>
                            房屋資訊:
                        </td>
                        <td>
                            <div class="container3">
                                <table>
                                    <?php
                                        try
                                        {
                                            $connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
                                            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                            $stmt=$connect->prepare("SELECT * FROM InfoList WHERE 1");
                                            $stmt->execute();
                                            while($row = $stmt->fetch())
                                            {
                                                echo "<tr><td><input type='checkbox' name='information[]'  value='".$row[0]."' ></input></td><td>".$row[1]."</td></tr>";
                                            }
                                        }
                                        catch(PDOException $e)
                                        {
                                            $msg = $e->getMessage();
                                            echo $msg;
                                            session_unset();
                                            session_destroy();
                                            echo <<<"EOT"
                                                <!DOCTYPE html>
                                                <html>
                                                    <script>
                                                        alert("Internel Error.");
                                                        window.location.replace("index.php");
                                                    </script>
                                                </html>
EOT;
                                        }
                                    ?>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
                <br>
                <br>
                <table align="center">
                    <tr>
                        <td>
                            <input type="submit" value="新增">
                        </td>
                        <td>
                            <input type="button" value="返回" onclick="location.href='http://people.cs.nctu.edu.tw/~tsejui210129/house_manage.php'">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>