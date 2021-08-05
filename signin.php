<?php
	session_start();
	$_SESSION['Authenticated']=false;

	$dbservername='dbhome.cs.nctu.edu.tw';
	$dbname='tsejui210129_cs_DB_HW1';
	$dbaccount='tsejui210129_cs';
	$dbpassword='ltes123456';
	if(trim($_POST['YourName']) != "" and trim($_POST['YourMail']) != "" and trim($_POST['YourAccount']) != "" and trim($_POST['YourPassword']) != "" and trim($_POST['ConfirmPassword']) != "")
	{
		if(!($_POST['YourPassword'] == $_POST['ConfirmPassword']))
		{
			session_unset();
			session_destroy();
			echo <<<"EOT"
				<!DODTYPE html>
				<html>
					<body>
						<script>
							alert("Confirm password not match.");
							window.location.replace("index2.php");
						</script>
					</body>
				</html>
EOT;
		}
		else
		{
			$YourName=trim($_POST['YourName']);
			$YourMail=trim($_POST['YourMail']);
			$YourAccount=trim($_POST['YourAccount']);
			$YourPassword=trim($_POST['YourPassword']);
			$HashedPassword=hash('sha256', $YourPassword);
			unset($errMsg);
			if(!preg_match("/^[a-zA-Z ]*$/", $YourName))
			{
				if(!isset($errMsg))
				{
					$errMsg = "";
				}
				$errMsg = $errMsg."Invalid name format(Only letters and white space allowed.)     ";
			}
			if (!filter_var($YourMail, FILTER_VALIDATE_EMAIL)) 
			{
  				if(!isset($errMsg))
				{
					$errMsg = "";
				}
  				$errMsg = $errMsg."Invalid email format.     "; 
			}
			if(!preg_match("/^[a-zA-Z0-9]*$/", $YourAccount))
			{
				if(!isset($errMsg))
				{
					$errMsg = "";
				}
				$errMsg = $errMsg."Invalid account format(Only letters and numbers allowed.)     ";
			}
			if(!preg_match("/^[a-zA-Z0-9]*$/", $YourPassword))
			{
				if(!isset($errMsg))
				{
					$errMsg = "";
				}
				$errMsg = $errMsg."Invalid password format(Only letters and numbers allowed.)     ";
			}
			if(isset($errMsg))
			{
				$errMsg = "'".$errMsg."'";
				session_unset();
				session_destroy();
				echo <<<"EOT"
					<!DODTYPE html>
					<html>
						<body>
							<script>
								alert({$errMsg});
								window.location.replace("index2.php");
							</script>
						</body>
					</html>
EOT;
			}
			else
			{
				try
				{
					$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
					$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$stmt = $connect->prepare("SELECT * FROM Account WHERE account = :account");
					$stmt->execute(array('account' => $YourAccount));
					unset($errMsg);
					if ($stmt->rowCount() == 1)
					{
						if(!isset($errMsg))
						{
							$errMsg = "";
						}
						$errMsg = $errMsg."Account already exist.     ";
					}
					$stmt = $connect->prepare("SELECT * FROM Account WHERE email = :email");
					$stmt->execute(array('email' => $YourMail));
					if ($stmt->rowCount() == 1)
					{
						if(!isset($errMsg))
						{
							$errMsg = "";
						}
						$errMsg = $errMsg."Email already exist.     ";
					}
					if(isset($errMsg))
					{
						$errMsg = "'".$errMsg."'";
						session_unset();
						session_destroy();
						echo <<<"EOT"
							<!DOCTYPE html>
							<html>
								<body>
									<script>
										alert({$errMsg});
										window.location.replace("index2.php");
									</script>
								</body>
							</html>
EOT;
					}
					else
					{
						$stmt=$connect->prepare("SELECT * FROM `Account` WHERE 1");
						$stmt->execute();
						$stmt=$connect->prepare("INSERT INTO Account (name, account, email, identities, password) VALUES (:name, :account, :email, 'user', :password)");
						$stmt->execute(array('name' => $YourName, 'account' => $YourAccount, 'email' => $YourMail, 'password' => $HashedPassword));
						if($stmt->rowCount() == 1)
						{
							session_unset();
							session_destroy();
							echo <<<"EOT"
								<!DOCTYPE html>
								<html>
									<body>
										<script>
											alert("Account Successfully Created.");
											window.location.replace("index.php");
										</script>
									</body>
								</html>
EOT;
						}
						else
						{
							echo "internal error during creating";
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
								window.location.replace("index2.php");
							</script>
						</html>
EOT;
				}
			}
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
					window.location.replace("index2.php");
				</script>
			</html>
EOT;
	}