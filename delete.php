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
			$stmt=$connect->prepare("SELECT * FROM Account WHERE account = :account");
			$stmt->execute(array('account' => $account));
			$rows = $stmt->fetchAll();
			if (count($rows) == 1)
			{
				$stmt=$connect->prepare("DELETE FROM Account WHERE account = :account LIMIT 1");
				$stmt->execute(array('account' => $account));
				if($stmt->rowCount() == 1)
				{
					echo <<<"EOT"
						<!DOCTYPE html>
						<html>
							<body>
								<script>
									alert("Account delete successfully.");
									window.location.replace("member_manage.php");
								</script>
							</body>
						</html>
EOT;
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
									alert("Internal error.");
									window.location.replace("Index.php");
								</script>
							</body>
						</html>
EOT;
				}
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