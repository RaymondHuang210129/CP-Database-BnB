<?php
	session_start();

	$dbservername = 'dbhome.cs.nctu.edu.tw';
	$dbname='tsejui210129_cs_DB_HW1';
	$dbaccount='tsejui210129_cs';
	$dbpassword='ltes123456';
	if(isset($_SESSION['Authenticated']) and $_SESSION['Authenticated'] == true and isset($_SESSION['Account']))
	{
		$account = $_POST['YourAccount'];
		try
		{
			$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt=$connect->prepare("UPDATE Account SET identities = 'admin' WHERE account = :account");
			$stmt->execute(array('account' => $account));
			if($stmt->rowCount() == 1)
			{
				echo <<<"EOT"
					<!DOCTYPE html>
					<html>
						<body>
							<script>
								alert("Account upgrade successfully.");
								window.location.replace("admin.php");
							</script>
						</body>
					</html>
EOT;
			}
			else
			{
				echo <<<"EOT"
					<!DOCTYPE html>
					<html>
						<body>
							<script>
								alert("No account match.");
								window.location.replace("admin.php");
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
							alert("Internal Error");
							window.location.replace("index.php");
						</script>
					</body>
				</html>
EOT;
		}
	}
	else
	{
		session_unset();
		session_destroy();
		echo <<<"EOT"
			<!DOCTYPE>
			<html>
				<script>
					alert("Redirect to the login page.");
					windows.location.replace("index.php");
				</script>
			</html>
EOT;
	}