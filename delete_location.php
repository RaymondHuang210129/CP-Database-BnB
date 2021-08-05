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
		$stmt=$connect->prepare("DELETE FROM Location WHERE id = :id");
		$stmt->execute(array('id' => $_POST['delete']));
		echo <<<"EOT"
			<!DOCTYPE html>
			<html>
				<script>
					window.location.replace("modify_location.php");
				</script>
			</html>
EOT;
	}
	catch(PDOException $e)
	{
		$msg = $e->getMessage();
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