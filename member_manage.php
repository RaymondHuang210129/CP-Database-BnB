<?php
	session_start();

	$dbservername = 'dbhome.cs.nctu.edu.tw';
	$dbname='tsejui210129_cs_DB_HW1';
	$dbaccount='tsejui210129_cs';
	$dbpassword='ltes123456';
	if(isset($_SESSION['Authenticated']) and $_SESSION['Authenticated'] == true and isset($_SESSION['Account']))
	{
		$account = $_SESSION['Account'];
		try
		{
			$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt=$connect->prepare("SELECT * FROM Account WHERE account = :account");
			$stmt->execute(array('account' => $account));
			if ($stmt->rowCount() == 1)
			{
				$row = $stmt->fetch();
				$name = $row[1];
				$account = $row[2];
				$email = $row[3];
			}
			$stmt = $connect->prepare("SELECT * FROM Account WHERE 1");
			$stmt->execute();
			$NameList = "";
			$AccountList = "";
			$EmailList = "";
			$IdentityList = "";
			while ($row = $stmt->fetch())
			{
				$NameList = $NameList.$row[1]."\r\n" ;
				$AccountList = $AccountList.$row[2]."\r\n";
				$EmailList = $EmailList.$row[3]."\r\n";
				$IdentityList = $IdentityList.$row[4]."\r\n";
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
 							window.location.replace("logout.php");
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
 					window.location.replace("logout.php");
 				</script>
 			</html>
EOT;
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Administrator Interface</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link type="text/css" rel="stylesheet" href="admin.css">
	</head>
	<body>
		<div class="board">
			<h2>管理系統</h2>
			<table align="right">
				<tr>
					<td>
						<input type="button" value="首頁" onclick="location.href='http://people.cs.nctu.edu.tw/~tsejui210129/admin.php'">
					</td>
					<td>
						<input type="button" value="登出" onclick="location.href='http://people.cs.nctu.edu.tw/~tsejui210129/logout.php'">
					</td>
				</tr>
			</table>
			<br>
			<h4>管理員資訊: </h4>
			名稱: <?php echo $name; ?>
			帳號: <?php echo $account; ?>
			郵件: <?php echo $email; ?>
			<br>
			<h4>帳戶資料</h4>
			<div style="overflow-y:scroll; width:700px; height:150px;">
				<table>
					<tr>
						<th>Name</th>
						<th>Account</th>
						<th>Email</th>
						<th>Identity</th>
					</tr>
					<tr>
						<td><pre><?php echo $NameList; ?></pre></td>
						<td><pre><?php echo $AccountList; ?></pre></td>
						<td><pre><?php echo $EmailList; ?></pre></td>
						<td><pre><?php echo $IdentityList; ?></pre></td>
					</tr>
				</table>
			</div>
			<h4>管理功能:</h4>
			<table>
				<tr valign="top">
					<td>
						<form action="create.php" method="post">
							<h5>新增帳號</h5>
							使用者名稱: <input type="text" name="YourName">
							<br>
							<br>
							　電子郵件: <input type="text" name="YourMail">
							<br>
							<br>
							　　　帳號: <input type="text" name="YourAccount">
							<br>
							<br>
							　　　密碼: <input type="password" name="YourPassword">
							<br>
							<br>
							　確認密碼: <input type="password" name="ConfirmPassword">
							<br>
							<br>
							<select name = "Option">
								<option value="admin">管理員</option>
								<option value="user">使用者</option>
							</select>
							<br>
							<br>
							<input type="submit" value="創建">
						</form>
					</td>
					<td>
						<form action="delete.php" method="post">
							<h5>刪除帳號</h5>
							　　　帳號: <input type="text" name="YourAccount">
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<input type="submit" value="刪除">
						</form>
					</td>
					<td>
						<form action="upgrade.php" method="post">
							<h5>帳戶升級管理員</h5>
							　　　帳號: <input type="text" name="YourAccount">
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<br>
							<input type="submit" value="升級">
						</form>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>