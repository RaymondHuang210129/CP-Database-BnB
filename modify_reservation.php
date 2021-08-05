<?php
	session_start();

	$dbservername = 'dbhome.cs.nctu.edu.tw';
	$dbname='tsejui210129_cs_DB_HW1';
	$dbaccount='tsejui210129_cs';
	$dbpassword='ltes123456';

	if(isset($_POST['delete']) and $_POST['delete'] != "")
	{
		try
		{
			$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt=$connect->prepare("DELETE FROM Reservation WHERE id = :id");
			$stmt->execute(array('id' => $_POST['delete']));
			if($stmt->rowCount() == 1)
			{
				echo <<<"EOT"
					<!DOCTYPE html>
					<html>
						<script>
							alert("Deleted Successfully");
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
	elseif(isset($_POST['modify']) and $_POST['modify'] != "")
	{
		$_SESSION['modify'] = $_POST['modify'];
		try
		{
			$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt=$connect->prepare("SELECT * FROM Reservation WHERE id = ".$_SESSION['modify']);
			$stmt->execute();
			$row = $stmt->fetch();
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
	elseif(isset($_POST['update']) and $_POST['update'] != "")
	{
		try
		{
			$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt=$connect->prepare("SELECT house_id FROM Reservation WHERE id = ".$_POST['update']);
			$stmt->execute();
			$house_id = $stmt->fetch();

			$begin = new DateTime($_POST['checkin_date']);
			$end = new DateTime($_POST['checkout_date']);
			//$end = $end->sub(new DateInterval('P1D'));
			$daterange = new DatePeriod($begin, new DateInterval('P1D'), $end);
			$errmsg = "";
			foreach ($daterange as $date)
			{
				$date2 = $date->format("Y-m-d");
				$stmt=$connect->prepare("SELECT * FROM Reservation WHERE '".$date2."' >= checkin_date AND '".$date2."' < checkout_date AND house_id = ".$house_id[0]." AND Reservation.id != ".$_POST['update']);
				$stmt->execute();
				if($stmt->rowCount() != 0)
				{
					$errmsg = $errmsg.$date2.", ";
				}
			}
			if($errmsg != "")
			{
				$errmsg = $errmsg."has been occupied, please select other date.";
				echo <<<"EOT"
					
					<html>
						<script>
							alert("{$errmsg}");
							window.location.replace("modify_reservation.php");
						</script>
					</html>
EOT;
			}
			else
			{
				$stmt=$connect->prepare("UPDATE Reservation SET checkin_date = :checkin_date, checkout_date = :checkout_date WHERE id = :id");
				$stmt->execute(array('checkin_date' => $_POST['checkin_date'], 'checkout_date' => $_POST['checkout_date'], 'id' => $_POST['update']));
				if($stmt->rowCount() == 1)
				{
					echo <<<"EOT"
					<!DOCTYPE html>
					<html>
						<script>
							alert("updated successfully.");
							window.location.replace("reservation_manage.php");
						</script>
					</html>
EOT;
				}
				else
				{
					echo <<<"EOT"
					<!DOCTYPE html>
					<html>
						<script>
							alert("Remain.");
							window.location.replace("reservation_manage.php");
						</script>
					</html>
EOT;
				}
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

		try
		{
			$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt=$connect->prepare("SELECT * FROM Reservation WHERE id = ".$_SESSION['modify']);
			$stmt->execute();
			$row = $stmt->fetch();
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

?>

<!DOCTYPE html>
<html>
	<head>
		<title>修改訂房日期</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link type="text/css" rel="stylesheet" href="index.css">
    </head>
    <body>
    	<div class="board">
    		<h2>修改日期: </h2>
    		<form action="modify_reservation.php" method="post">
    			訂房日期: <input type="date" name="checkin_date" value="<?php echo $row[3]; ?>">
    			<br>
    			<br>
    			退房日期: <input type="date" name="checkout_date" value="<?php echo $row[4]; ?>">
    			<br>
    			<br>
    			<table align='center'>
    				<tr>
    					<td>
							<input type="button" value="返回" onclick="location.href='http://people.cs.nctu.edu.tw/~tsejui210129/reservation_manage.php'">
    					</td>
    					<td>
    						<button type='submit' name='update' value='<?php echo $row[0]; ?>'>更新日期</button>
    					</td>
    				</tr>
    			</table>
    		</form>
    	</div>
    </body>
</html>
