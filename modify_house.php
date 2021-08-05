<?php
	session_start();

	$dbservername = 'dbhome.cs.nctu.edu.tw';
	$dbname='tsejui210129_cs_DB_HW1';
	$dbaccount='tsejui210129_cs';
	$dbpassword='ltes123456';

	if(isset($_POST['unlike']) && $_POST['unlike'] != "")
	{
		try
		{
			$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt=$connect->prepare("DELETE FROM Favorite WHERE user_id = :user_id AND favorite_id = :favorite_id");
			$stmt->execute(array('user_id' => $_SESSION['id'], 'favorite_id' => $_POST['unlike']));
			if($stmt->rowCount() == 1)
			{
				echo <<<"EOT"
					<!DOCTYPE html>
					<html>
						<script>
							alert("remove from favorite successfully.");
							window.location.replace("favorite.php");
						</script>
					</html>
EOT;
			}
			else
			{
				echo $_SESSION['id']." ".$_POST['unlike'];
				echo <<<"EOT"
					<!DOCTYPE html>
					<html>
						<script>
							alert("error.");
							window.location.replace("favorite.php");
						</script>
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
						alert("Internel Error1.");
						window.location.replace("index.php");
					</script>
				</html>
EOT;
		}
	}
	elseif(isset($_POST['delete']) && $_POST['delete'] != "")
	{
		try
		{
			$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt=$connect->prepare("DELETE FROM House WHERE id = :id");
			$stmt->execute(array('id' => $_POST['delete']));
			if($stmt->rowCount() == 1)
			{
				echo <<<"EOT"
					<!DOCTYPE html>
					<html>
						<script>
							alert("delete successfully.");
							window.location.replace("admin.php");
						</script>
					</html>
EOT;
			}
			else
			{
				echo <<<"EOT"
					<!DOCTYPE html>
					<html>
						<script>
							alert("error.");
							window.location.replace("admin.php");
						</script>
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
						alert("Internel Error2.");
						window.location.replace("index.php");
					</script>
				</html>
EOT;
		}
	}
	elseif(isset($_POST['favorite']) && $_POST['favorite'] != "")
	{
		try
		{
			$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt=$connect->prepare("INSERT INTO Favorite (user_id, favorite_id) VALUES ( :user_id, :favorite_id)");
			$stmt->execute(array('user_id' => $_SESSION['id'], 'favorite_id' => $_POST['favorite']));
			if($stmt->rowCount() == 1)
			{
				echo <<<"EOT"
					<!DOCTYPE html>
					<html>
						<script>
							alert("add to favorite successfully.");
							window.location.replace("admin.php");
						</script>
					</html>
EOT;
			}
			else
			{
				echo <<<"EOT"
					<!DOCTYPE html>
					<html>
						<script>
							alert("error.");
							window.location.replace("admin.php");
						</script>
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
						alert("{$msg}");
						window.location.replace("index.php");
					</script>
				</html>
EOT;
		}
	}
	elseif(isset($_POST['submit']) && $_POST['submit'] != "")
	{
		if($_POST['location'] == "")
		{
			echo <<<"EOT"
				<!DOCTYPE html>
				<html>
					<script>
						alert("未勾選房屋位置");
						window.location.replace("house_manage.php");
					</script>
				</html>
EOT;
		}
		try
		{

			$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			if(isset($_POST['HouseOwner']))
			{
				$stmt=$connect->prepare("SELECT id FROM Account WHERE name = :name");
				$stmt->execute(array('name' => $_POST['HouseOwner']));
				$buff = $stmt->fetch();
				if($stmt->rowCount() != 0)
				{
					$stmt=$connect->prepare("UPDATE House SET name = :name, price = :price, location_id = :location_id, time = :time, owner_id = :owner_id WHERE id = :id");
					$stmt->execute(array('name' => $_POST['HouseName'], 'price' => $_POST['HousePrice'], 'location_id' => $_POST['location'][0], 'time' => $_POST['HouseTime'], 'owner_id' => $buff[0], 'id' => $_POST['submit']));
				}
				else
				{
					echo <<<"EOT"
					<!DOCTYPE html>
					<html>
						<script>
							alert("Owner does not exist.");
							window.location.replace("house_manage.php");
						</script>
					</html>
EOT;
				}
			}
			else
			{
				$stmt=$connect->prepare("UPDATE House SET name = :name, price = :price, location_id = :location_id, time = :time WHERE id = :id");
				$stmt->execute(array('name' => $_POST['HouseName'], 'price' => $_POST['HousePrice'], 'location_id' => $_POST['location'][0], 'time' => $_POST['HouseTime'], 'id' => $_POST['submit']));
			}
			$stmt=$connect->prepare("DELETE FROM Information WHERE house_id = :house_id");
			$stmt->execute(array('house_id' => $_POST['submit']));
			foreach($_POST['information'] as $info)
			{
				$stmt = $connect->prepare("INSERT INTO Information (information_id, house_id) VALUES (:information_id, :house_id)");
				$stmt->execute(array('information_id' => $info, 'house_id' => $_POST['submit']));
			}
			echo <<<"EOT"
				<!DOCTYPE html>
				<html>
					<script>
						alert("Update successfully.");
						window.location.replace("house_manage.php");
					</script>
				</html>
EOT;

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
						alert("{$msg}");
						window.location.replace("index.php");
					</script>
				</html>
EOT;
		}
	}
	elseif(isset($_POST['edit']) && $_POST['edit'] != "")
	{
		try
		{
			$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
			$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt=$connect->prepare("SELECT House.*, Account.name FROM House, Account WHERE House.id = :id AND House.owner_id = Account.id");
			$stmt->execute(array('id' => $_POST['edit']));
			if($stmt->rowCount() == 1)
			{
				if($_SESSION['Identity'] == "user")
				{
					$gray_owner = "disabled";				
				}
				else
				{
					$gray_owner = "";
				}
				$row = $stmt->fetch();
				$stmt=$connect->prepare("SELECT information_id FROM Information WHERE house_id = :house_id");
				$stmt->execute(array('house_id' => $row[0]));
				$all_info = $stmt->fetchAll();
				$info_id = array();
				foreach($all_info as $info)
				{
					array_push($info_id, $info[0]);
				}
			}
			else
			{
				echo <<<"EOT"
					<!DOCTYPE html>
					<html>
						<script>
							alert("error.");
							window.location.replace("index.php");
						</script>
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
						alert("{$msg}");
						window.location.replace("index.php");
					</script>
				</html>
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
				<body>
					<script>
						alert("Internal error3.");
						window.location.replace("index.php");
					</script>
				</body>
			</html>
EOT;
	}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>編輯房屋</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link type="text/css" rel="stylesheet" href="admin.css">
    </head>

    <body>
        <div class="board">
            <h2>編輯房屋</h2>
            <form action="modify_house.php" method="post">
                　房屋名稱: <input type="text" name="HouseName" value="<?php echo $row[1]; ?>">
                <br>
                <br>
                　房屋價格: <input type="number" name="HousePrice" value="<?php echo $row[2]; ?>">
                <br>
                <br>
				<table align="center">
					<tr>
						<td>
							房屋位置:
						</td>
						<td>
							<div class="container3">
								<table>
									<?php
										try
										{
											$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
					                        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					                        $stmt=$connect->prepare("SELECT * FROM Location WHERE 1 ORDER BY location ASC");
					                        $stmt->execute();
					                        while($row2 = $stmt->fetch())
					                        {
					                        	if($row2[0] == $row[3])
					                        	{
					                        		$select = " checked = 'checked' ";
					                        	}
					                        	else
					                        	{
					                        		$select = "";
					                        	}
					                        	echo "<tr><td><input type='radio' name='location[]' ".$select." value='".$row2[0]."'></td><td>".$row2[1]."</td></tr>";
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
				                                            alert("Internel Error4.");
				                                            window.location.replace("index.php");
				                                        </script>
					                                </html>
EOT;
										}
									?>
								</table>
							</div>
						</td>
					</tr>	
				</table>
                <br>
                <br>
                　　　時間: <input type="date" name="HouseTime" value="<?php echo $row[4]; ?>"> 
                <br>
                <br>
                　　擁有者: <input type="text" name="HouseOwner" value="<?php echo $row[6]; ?>" <?php echo $gray_owner; ?> >
                <br>
                <br>
                <table align="center">
	                <tr>
	                	<td>
	                		房屋資訊:	
	                	</td>
		           		<td>
			                <div class="container3">
				                <table>
									<?php
										try
										{
											$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
					                        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					                        $stmt=$connect->prepare("SELECT * FROM InfoList WHERE 1 ORDER BY information ASC");
					                        $stmt->execute();
					                        while($row2 = $stmt->fetch())
					                        {
					                            if(in_array($row2[0], $info_id))
					                            {
					                            	$check = "checked = 'checked'";
					                            }
					                            else
					                            {
					                            	$check = "";
					                            }
					                            echo "<tr><td><input type='checkbox' name='information[]' ".$check." value='".$row2[0]."' ></input></td><td>".$row2[1]."</td></tr>";
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
				                                            alert("Internel Error5.");
				                                            window.location.replace("index.php");
				                                        </script>
					                                </html>
EOT;
				                    	}
									?>
				                </table>
			            	</div>
		            	</td>
	            	</tr>
            	</table>
                <br>
                <br>
                <table>
                    <tr>
                        <td>
                            <button type='submit' name='submit' value="<?php echo $row[0]; ?>">更新</button>
                        </td>
                        <td>
                            <input type="button" value="返回" onclick="location.href='http://people.cs.nctu.edu.tw/~tsejui210129/house_manage.php'">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </body>
</html>