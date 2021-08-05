<?php
	session_start();

	$dbservername = 'dbhome.cs.nctu.edu.tw';
	$dbname='tsejui210129_cs_DB_HW1';
	$dbaccount='tsejui210129_cs';
	$dbpassword='ltes123456';


?>

<!DOCTYPE html>
<html>
	<head>
		<title>Information manager</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link type="text/css" rel="stylesheet" href="house.css">
	</head>
	<body>
		<div class="board">
			<h2>管理資訊</h2>
			<br>
			<table align="right">
				<tr>
					<td>
						<input type="button" value="返回" onclick="location.href='http://people.cs.nctu.edu.tw/~tsejui210129/house_manage.php'">
					</td>
					<td>
						<input type="button" value="登出" onclick="location.href='http://people.cs.nctu.edu.tw/~tsejui210129/logout.php'">
					</td>
				</tr>
			</table>
			<br>
			<h4>資訊列表</h4>
			<br>
			<br>
			<div class="container2">
				<table>
					<?php
						try
						{
							$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
							$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
							$stmt=$connect->prepare("SELECT * FROM InfoList WHERE 1");
							$stmt->execute();
							echo "<table>";
							while($row = $stmt->fetch())
							{
								echo "<tr><td>".$row[1]."</td><td><form></form><form action='delete_information.php' method='post'><button type='submit' name='delete' value='".$row[0]."'>刪除</button></form></td></tr>";
							}
							echo "</table>";
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
			<form action='create_information.php' method='post'>
				<input type="text" name="information" placeholder="new information" value="">
				<button type="submit" name="create">新增
				</button> 
			</form>
			<table>
				<tr>
					<td>
						
					</td>
					<td>

					</td>
				</tr>
			</table>
		</div>
	</body>
</html>