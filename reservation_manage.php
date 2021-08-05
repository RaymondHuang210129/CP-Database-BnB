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
	<title>reservation manage</title>
	<meta http-equiv="Content-Type" content="text/html; charset="utf-8">
	<link type="text/css" rel="stylesheet" href="house.css">
</head>
<body>
	<div class="board">
		<h2>我的訂房</h2>
		<br>
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
		<h4>訂房列表</h4>
		<br>
		<br>
		<div class="container2">
			<table>
				<tr>
					<th>房屋名稱</th>
					<th>房屋價格</th>
					<th>地點</th>
					<th>房屋日期</th>
					<th>屋主</th>
					<th>房屋其他資訊</th>
					<th>訂房日期</th>
					<th>退房日期</th>
				</tr>
				<tr>
				</tr>
				<?php
					try
					{
						$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
						$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$stmt=$connect->prepare("SELECT House.name, House.price, Location.location, House.time, Account.name, Reservation.checkin_date, Reservation.checkout_date, Reservation.id, House.id  FROM Reservation INNER JOIN House ON House.id = Reservation.house_id INNER JOIN Location ON House.location_id = Location.id INNER JOIN Account ON Account.id = House.owner_id WHERE Reservation.costumer_id = :costumer_id");
						$stmt->execute(array('costumer_id' => $_SESSION['id']));
						$rows = $stmt->fetchAll();
						foreach ($rows as $row) 
						{
							$stmt=$connect->prepare("SELECT information FROM Information INNER JOIN InfoList WHERE house_id = ".$row[8]." AND Information.information_id = InfoList.id");
							$stmt->execute();
							$infoHTML = "<table>";
							while($info = $stmt->fetch())
							{
								$infoHTML = $infoHTML."<tr><td>".$info[0]."</td></tr>"; 
							}
							$infoHTML = $infoHTML."</table>";
							echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td><td>".$infoHTML."</td><td>".$row[5]."</td><td>".$row[6]."</td><td><form action='modify_reservation.php' method='post'><button type='submit' name='modify' value='".$row[7]."'>修改日期</button></form></td><td><form action='modify_reservation.php' method='post'><button type='submit' name='delete' value='".$row[7]."'>刪除</button></form></td></tr>";
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
			</table>
		</div>
	</div>
</body>
</html>