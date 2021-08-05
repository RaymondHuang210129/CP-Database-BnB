<?php
	session_start();

	$dbservername = 'dbhome.cs.nctu.edu.tw';
	$dbname='tsejui210129_cs_DB_HW1';
	$dbaccount='tsejui210129_cs';
	$dbpassword='ltes123456';

	if(isset($_POST['reserve']) and $_POST['reserve'] != "")
	{
		$_SESSION['Reserve'] = $_POST['reserve'];
		if(!(isset($_SESSION['Checkin_date']) and $_SESSION['Checkin_date'] != "" and isset($_SESSION['Checkout_date']) and $_SESSION['Checkout_date'] != ""))
		{
			$_SESSION['Checkin_date'] = "";
			$_SESSION['Checkout_date'] = "";
		}
		try
		{
			$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt=$connect->prepare("SELECT House.name FROM House WHERE House.id = ".$_POST['reserve']);
			$stmt->execute();
			$house_name = $stmt->fetch();

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
	}
	elseif(isset($_POST['confirm']) and $_POST['confirm'] != "")
	{
		$current_date = date("Y-m-d");
		if($current_date < $_POST['checkin_date'] and $_POST['checkin_date'] < $_POST['checkout_date'])
		{
			try
			{
				$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
				$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$stmt=$connect->prepare("INSERT INTO Reservation (costumer_id, house_id, checkin_date, checkout_date) VALUES ('".$_SESSION['id']."', '".$_SESSION['Reserve']."', '".$_POST['checkin_date']."', '".$_POST['checkout_date']."')");
				$stmt->execute();
				if($stmt->rowCount() == 1)
				{
					echo <<<"EOT"
					<!DOCTYPE html>
					<html>
						<script>
							alert("Booking Successfully.");
							window.location.replace("reservation_manage.php");
						</script>
					</html>
EOT;
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
		}
		else
		{
			echo <<<"EOT"
				<!DOCTYPE>
 				<html>
 					<script>
 						alert("Please select the date in future and do not select the same checkin/checkout date.");
 						window.location.replace("create_reservation.php");
 					</script>
 				</html>
EOT;
		}

	}
	else
	{
		unset($_SESSION['Reserve']);
		echo <<<"EOT"
			<!DOCTYPE html>
            <html>
                <script>
                    window.location.replace("admin.php");
                </script>
	        </html>
EOT;
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>訂房頁面</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link type="text/css" rel="stylesheet" href="index.css">
    </head>
    <body>
    	<div class="board">
    		<h2>訂房資訊: </h2>
    		<form action="create_reservation.php" method="post">
    			訂房日期: <input type="date" name="checkin_date" value="<?php echo $_SESSION['Checkin_date']; ?>">
    			<br>
    			<br>
    			退房日期: <input type="date" name="checkout_date" value="<?php echo $_SESSION['Checkout_date']; ?>">
    			<br>
    			<br>
    			預訂房屋: <?php echo $house_name[0]; ?>
    			<br>
    			<br>
    			<table align='center'>
    				<tr>
    					<td>
							<input type="button" value="返回" onclick="location.href='http://people.cs.nctu.edu.tw/~tsejui210129/admin.php'">
    					</td>
    					<td>
    						<button type='submit' name='confirm' value='<?php echo $_SESSION['Reserve']; ?>'>確認訂房</button>
    					</td>
    				</tr>
    			</table>
    		</form>
    	</div>
    </body>
</html>