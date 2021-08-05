<?php
	session_start();

	$dbservername='dbhome.cs.nctu.edu.tw';
	$dbname='tsejui210129_cs_DB_HW1';
	$dbaccount='tsejui210129_cs';
	$dbpassword='ltes123456';


	if(!isset($_POST['HouseOwner']))
	{
		$_POST['HouseOwner'] = $_SESSION['Name'];
	}
	if(trim($_POST['HouseName']) != "" and trim($_POST['HousePrice']) != "" and $_POST['Location'] != "" and trim($_POST['HouseTime']) != "" and trim($_POST['HouseOwner']) != "")
	{
		
		$HouseName = trim($_POST['HouseName']);
		$HousePrice =  trim($_POST['HousePrice']);
		$HouseTime = $_POST['HouseTime'];
		$HouseOwner =trim($_POST['HouseOwner']);
		try
		{
			$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $connect->prepare("SELECT * FROM House WHERE name = :housename");
			$stmt->execute(array('housename' => $HouseName));
			unset($errMsg);
			if ($stmt->rowCount() == 1)
			{
				if(!isset($errMsg))
				{
					$errMsg = "";
				}
				$errMsg = $errMsg."House name already exist.     ";
			}
			$stmt = $connect->prepare("SELECT id FROM Account WHERE name = :username");
			$stmt->execute(array('username' => $HouseOwner));
			if($stmt->rowCount() == 0)
			{
				if(!isset($errMsg))
				{
					$errMsg = "";
				}
				$errMsg = $errMsg."Owner does not exist.     ";
			}
			else
			{
				$row = $stmt->fetch();
				$HouseOwnerId = $row[0];
			}
			if(isset($errMsg))
			{
				echo <<<"EOT"
				<!DOCTYPE html>
				<html>
					<body>
						<script>
							alert("{$errMsg}");
							window.location.replace("new_house.php");
						</script>
					</body>
				</html>
EOT;
			}
			else
			{
				$stmt = $connect->prepare("INSERT INTO House (name, price, location_id, time, owner_id) VALUES (:name, :price, :location_id, :time, :owner_id)");
				$stmt->execute(array('name' => $HouseName, 'price' => $HousePrice, 'location_id' => $_POST['Location'], 'time' => $HouseTime, 'owner_id' => $HouseOwnerId));
				$stmt = $connect->prepare("SELECT id FROM House WHERE name = :name");
				$stmt->execute(array('name' => $HouseName));
				$row = $stmt->fetch();
				$HouseId = $row[0];
				if(isset($_POST['information']) and !empty($_POST['information']))
				{
					foreach ($_POST['information'] as $info) 
					{
						$stmt = $connect->prepare("INSERT INTO Information (information_id, house_id) VALUES (:information_id, :house_id)");
						$stmt->execute(array('information_id' => $info, 'house_id' => $HouseId));
					}
				}
				echo <<<"EOT"
				<!DOCTYPE html>
				<html>
					<body>
						<script>
							alert("House added successfully.");
							window.location.replace("house_manage.php");
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
		echo <<<"EOT"
			<!DOCTYPE html>
			<html>
				<body>
					<script>
						alert("Do not leave any field with blank.");
						window.location.replace("new_house.php");
					</script>
				</body>
			</html>
EOT;
	}
?>