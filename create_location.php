<?php
	session_start();

	$dbservername = 'dbhome.cs.nctu.edu.tw';
	$dbname='tsejui210129_cs_DB_HW1';
	$dbaccount='tsejui210129_cs';
	$dbpassword='ltes123456';

	try
	{
		$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
		$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$stmt=$connect->prepare("SELECT location FROM Location WHERE location = :location");
		$stmt->execute(array('location' => $_POST['location']));
		if($stmt->rowCount() != 0)
		{
			echo <<<"EOT"
				<!DOCTYPE html>
				<html>
					<script>
						alert("Location already existed.");
						window.location.replace("modify_location.php");
					</script>
				</html>
EOT;
		}
		else
		{
			$stmt=$connect->prepare("INSERT INTO Location (location) VALUES (:location)");
			$stmt->execute(array('location' => $_POST['location']));
			if($stmt->rowCount() == 1)
			{
				echo <<<"EOT"
					<!DOCTYPE html>
					<html>
						<script>
							window.location.replace("modify_location.php");
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
?>