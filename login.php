<?php
	session_start();
	$_SESSION['Authenticated']=false;

	$dbservername='dbhome.cs.nctu.edu.tw';
	$dbname='tsejui210129_cs_DB_HW1';
	$dbaccount='tsejui210129_cs';
	$dbpassword='ltes123456';
	if (trim($_POST['YourAccount']) != "" and trim($_POST['YourPassword']) != "")
	{
		$YourAccount=trim($_POST['YourAccount']);
		$YourPassword=trim($_POST['YourPassword']);
		$HashedPassword=hash('sha256', $YourPassword);
		try
		{
			$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt=$connect->prepare("SELECT * FROM Account WHERE account = :account AND password = :password");
			$stmt->execute(array('account' => $YourAccount, 'password' => $HashedPassword));
			if ($stmt->rowCount()==1)
			{
				ob_start();
				$row = $stmt->fetch();
				$_SESSION['Authenticated']=true;
				$_SESSION['Account']=$row[2];
				$_SESSION['Identity']=$row[4];
				$_SESSION['id']=$row[0];
				$_SESSION['Name']=$row[1];
				if($row[4] == "admin")
				{
					header("Location: admin.php");
				}
				else
				{
					header("Location: admin.php");
				}
				ob_end_flush();
				exit();
			}
			else
			{
				session_unset();
				session_destroy();
				echo <<<"EOT"
					<!DOCTYPE html>
					<html>
						<body>
							<script>
								alert("Login failed.");
								window.location.replace("index.php");
							</script>
						</body>
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
					<body>
						<script>
							alert("Internal Error.");
							window.location.replace("index.php");
						</script>
					</body>
				<html>
EOT;
		}
	}
	else
	{
		session_unset();
		session_destroy();
		echo <<<"EOT"
			<!DOCTYPE html>
			<html>
				<script>
					alert("Do not leave any field with blank.");
					window.location.replace("index.php");
				</script>
			</html>
EOT;
	}
?>