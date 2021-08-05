<?php
	session_start();

	$dbservername = 'dbhome.cs.nctu.edu.tw';
	$dbname='tsejui210129_cs_DB_HW1';
	$dbaccount='tsejui210129_cs';
	$dbpassword='ltes123456';
	

	if(isset($_POST['page']) and $_POST['page'] != "")
	{


		$_SESSION['Page'] = $_POST['page'];

	}
	else
	{
		$_SESSION['Page'] = 1;
	}
	if(isset($_POST['checkin_date']) and isset($_POST['checkout_date']))
	{
		if($_POST['checkin_date'] != "" and $_POST['checkout_date'] != "")
		{
			$current_date = date("Y-m-d");
			if($current_date < $_POST['checkin_date'] and $_POST['checkin_date'] < $_POST['checkout_date'])
			{
				$_SESSION['Checkin_date'] = $_POST['checkin_date'];
				$_SESSION['Checkout_date'] = $_POST['checkout_date'];
			}
			else
			{
				$_SESSION['Checkin_date'] = "";
				$_SESSION['Checkout_date'] = "";
				unset($_POST['checkin_date']);
				unset($_POST['checkout_date']);
				echo <<<"EOT"
					<!DOCTYPE>
	 				<html>
	 					<script>
	 						alert("Please select the date in future and do not select the same checkin/checkout date.");
	 						window.location.replace("admin.php");
	 					</script>
	 				</html>
EOT;
			}
		}
		else
		{
			$_SESSION['Checkin_date'] = "";
			$_SESSION['Checkout_date'] = "";
			unset($_POST['checkin_date']);
			unset($_POST['checkout_date']);
			echo <<<"EOT"
				<!DOCTYPE>
	 			<html>
	 				<script>
	 					alert("Please select your checkin/checkout date.");
	 					window.location.replace("admin.php");
	 				</script>
	 			</html>
EOT;
		}
	}


	if(isset($_POST['search']) && $_POST['search'] != "")
	{
		$_SESSION['Search'] = $_POST['search'];
		$_SESSION['Sort'] = "";
	}
	elseif(isset($_POST['page']))
	{

	}
	else
	{
		$_SESSION['Search'] = "";	
	}

	if(isset($_POST['sort']) && $_POST['sort'] != "")
	{
		$_SESSION['Sort'] = $_POST['sort'];
		$_SESSION['Search'] = "";
	}
	elseif(isset($_POST['page']))
	{

	}
	else
	{
		$_SESSION['Sort'] = "";
	}

	if(isset($_POST['information']))
	{
		$_SESSION['Information'] = $_POST['information'];

	}
	elseif(isset($_POST['page']))
	{

	}
	else
	{
		$_SESSION['Information'] = array();
	}
	
	if(isset($_POST['interval']) && $_POST['interval'] != "interval")
	{
		$_SESSION['Interval'] = $_POST['interval'];
		if($_SESSION['Interval'] == "1200~")
		{
			$select[1] = "selected='selected'";
			$select[0] = $select[2] = $select[3] = $select[4] = "";
		}
		elseif($_SESSION['Interval'] == "600~1200")
		{
			$select[2] = "selected='selected'";
			$select[1] = $select[0] = $select[3] = $select[4] = "";
		}
		elseif($_SESSION['Interval'] == "300~600")
		{
			$select[3] = "selected='selected'";
			$select[1] = $select[2] = $select[0] = $select[4] = "";
		}
		else
		{
			$select[4] = "selected='selected'";
			$select[1] = $select[2] = $select[3] = $select[0] = "";
		}
	}
	elseif(isset($_POST['page']))
	{

	}
	else
	{
		$_SESSION['Interval'] = "interval";
		$select[0] = "selected='selected'";
		$select[1] = $select[2] = $select[3] = $select[4] = "";
	}
	if(isset($_POST['id_keyword']) && $_POST['id_keyword'] != "")
	{
		$_SESSION['InputId'] = $_POST['id_keyword'];
	}
	elseif(isset($_POST['page']))
	{

	}
	else
	{
		$_SESSION['InputId'] = "";
	}
	if(isset($_POST['name_keyword']) && $_POST['name_keyword'] != "")
	{
		$_SESSION['InputName'] = $_POST['name_keyword'];
	}
	elseif(isset($_POST['page']))
	{

	}
	else
	{
		$_SESSION['InputName'] = "";
	}
	if(isset($_POST['location_keyword']) && $_POST['location_keyword'] != "")
	{
		$_SESSION['InputLocation'] = $_POST['location_keyword'];
	}
	elseif(isset($_POST['page']))
	{

	}
	else
	{
		$_SESSION['InputLocation'] = "";
	}
	if(isset($_POST['time_keyword']) && $_POST['time_keyword'] != "")
	{
		$_SESSION['InputTime'] = $_POST['time_keyword'];
	}
	elseif(isset($_POST['page']))
	{

	}
	else
	{
		$_SESSION['InputTime'] = "";
	}
	if(isset($_POST['owner_keyword']) && $_POST['owner_keyword'] != "")
	{
		$_SESSION['InputOwner'] = $_POST['owner_keyword'];
	}
	elseif(isset($_POST['page']))
	{

	}
	else
	{
		$_SESSION['InputOwner'] = "";
	}
	if($_SESSION['Identity'] == "admin")
	{
		$gray_button = "";
	}
	else
	{
		$gray_button = " disabled='disabled'";
	}
	$inputInterval = $_SESSION['Interval'];
	$inputId = $_SESSION['InputId'];
	$inputName = $_SESSION['InputName'];
	$inputLocation = $_SESSION['InputLocation'];
	$inputTime = $_SESSION['InputTime'];
	$inputOwner = $_SESSION['InputOwner'];
	if(isset($_SESSION['Authenticated']) and $_SESSION['Authenticated'] == true and isset($_SESSION['Account']))
	{
		$account = $_SESSION['Account'];
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
 					window.location.replace("index.php");
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
			<h2>房屋首頁(<?php echo $_SESSION['Identity']; ?>)</h2>使用者名稱：<?php echo $_SESSION['Name'] ?>
			<br>
			<table align="right">
				<tr>
					<td>
						<input type="button" value="我的訂房" onclick="location.href='http://people.cs.nctu.edu.tw/~tsejui210129/reservation_manage.php'">
					</td>
					<td>
						<input type="button" value="房屋管理" onclick="location.href='http://people.cs.nctu.edu.tw/~tsejui210129/house_manage.php'">
					</td>
					<td>
						<input type="button" value="我的最愛" onclick="location.href='http://people.cs.nctu.edu.tw/~tsejui210129/favorite.php'">
					</td>
					<td>
						<input type="button" value="會員管理"<?php echo $gray_button; ?> onclick="location.href='http://people.cs.nctu.edu.tw/~tsejui210129/member_manage.php'">
					</td>
					<td>
						<input type="button" value="登出" onclick="location.href='http://people.cs.nctu.edu.tw/~tsejui210129/logout.php'">
					</td>
				</tr>
			</table>
			<br>
			<h4>房屋列表</h4>
			<table>
				<tr>
					<form action="admin.php" method="post">
						<table>
							<tr>
								<td>
									<input type="text" name="id_keyword" placeholder="id" size="3" value="<?php echo $inputId; ?>">
								</td>
								<td>
									<input type="text" name="name_keyword" placeholder="name" value="<?php echo $inputName; ?>" size="10">
								</td>
								<td>
									<table>
										<tr>　</tr>
										<tr>
											<td>
												<select name="interval">
													<option <?php echo $select[0] ?> value="interval">interval</option>
													<option <?php echo $select[1] ?> value="1200~">1200~</option>
													<option <?php echo $select[2] ?> value="600~1200">600~1200</option>
													<option <?php echo $select[3] ?> value="300~600">300~600</option>
													<option <?php echo $select[4] ?> value="0~300">0~300</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>
												<button type="submit" name="sort" value="price_sort_high">▼</button>
												<button type="submit" name="sort" value="price_sort_low">▲</button>
											</td>
										</tr>
									</table>
								</td>
								<td>
									<input type="text" name="location_keyword" placeholder="location" value="<?php echo $inputLocation; ?>" size="10">
								</td>
								<td>
									<table>
										<tr>　</tr>
										<tr>
											<td>
												<input type="date" name="time_keyword" placeholder="time" value="<?php echo $inputTime; ?>" size="10">
											</td>
										<tr>
										</tr>
											<td>
												<button type="submit" name="sort" value="date_sort_new">▼</button>
											
												<input type="reset" value="重設">
											
												<button type="submit" name="sort" value="date_sort_old">▲</button>
											</td>					
										</tr>
									</table>
								</td>
								<td>
									<input type="text" name="owner_keyword" placeholder="owner" value="<?php echo $inputOwner; ?>" size="10">
								</td>

								<td>
									<div class="container">
										<table width = "200">
											<?php
												try
												{
													$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
													$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
													$stmt=$connect->prepare("SELECT * FROM InfoList WHERE 1");
													$stmt->execute();
													while($row = $stmt->fetch())
													{
														if(in_array($row[0], $_SESSION['Information']))
														{
															$check = "checked='checked'";
														}
														else
														{
															$check = "";
														}
														echo "<tr><td><input type='checkbox' name='information[]' value='".$row[0]."' ".$check."></td><td>".$row[1]."</td></tr>";
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
																#window.location.replace("index.php");
															</script>
														</html>
EOT;
												}
											?>
										</table>
									</div>
								</td>
								<td>
									<table>
										<tr>
											<td>
												<input name="checkin_date" type="date" size="8" placeholder="time" value="<?php echo $_SESSION['Checkin_date']; ?>">
											</td>
										</tr>
										<tr>
											<td>至</td>
										</tr>
										<tr>
											<td>
												<input name="checkout_date" type="date" size="8" placeholder="time" value="<?php echo $_SESSION['Checkout_date'] ?>">
											</td>
										</tr>
									</table>
								</td>
								<td>
									<table>
										<tr>
											<button type="submit" name="search" value="search">搜尋</button>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<div class="container2">
							<table>
								<tr>
									<th>編號</th>
									<th>房屋名稱</th>
									<th>房屋價格</th>
									<th>地點</th>
									<th>時間</th>
									<th>擁有者</th>
									<th>其他資訊</th>
								</tr>
								<tr>
								</tr>
								<?php
									try
									{
										$owner_id = 0;
										$connect = new PDO("mysql:host=".$dbservername.";dbname=".$dbname, $dbaccount, $dbpassword);
										$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
										$searchCommand = "SELECT House.id, House.name, House.price, Location.location, House.time, Account.name AS user FROM House LEFT JOIN Location ON House.location_id = Location.id, Account WHERE House.owner_id = Account.id";
										if($inputId != "")
										{
											$searchCommand = $searchCommand." AND House.id = :id";
											$idtag = 0;
										}
										if($inputName != "")
										{
											$searchCommand = $searchCommand." AND House.name LIKE :name";
											$nametag = 0;
										}
										if($inputLocation != "")
										{
											$searchCommand = $searchCommand." AND location LIKE :location";
											$locationtag = 0;
										}
										if($inputTime != "")
										{
											$searchCommand = $searchCommand." AND time = :time";
											$timetag = 0;
										}
										if($inputOwner != "")
										{
											$searchCommand = $searchCommand." AND owner_id = :owner_id";
											$stmt=$connect->prepare("SELECT * FROM Account WHERE name LIKE :name");
											$inputOwner2 = "%".$inputOwner."%";
											$stmt->execute(array('name' => $inputOwner2));
											if($stmt->rowCount() == 1)
											{
												$row = $stmt->fetch();
												$owner_id = $row[0];
											}
											else
											{
												$owner_id = 0;
											}
											$ownertag = 0;
										}
										if($inputInterval != "interval")
										{
											if($inputInterval == "1200~")
											{
												$searchCommand = $searchCommand." AND price >= 1200";
											}
											elseif ($inputInterval == "600~1200") 
											{
												$searchCommand = $searchCommand." AND price >= 600 AND price < 1200";
											}
											elseif ($inputInterval == "300~600")
											{
												$searchCommand = $searchCommand." AND price >= 300 AND price < 600";
											}
											elseif ($inputInterval == "0~300")
											{
												$searchCommand = $searchCommand." AND price < 300";
											}
										}
										if(isset($_SESSION['Checkin_date']) and $_SESSION['Checkin_date'] != "" and isset($_SESSION['Checkout_date']) and $_SESSION['Checkout_date'] != "")
										{
											$checkin = $_SESSION['Checkin_date'];
											$checkout = $_SESSION['Checkout_date'];
											$searchCommand = $searchCommand." AND House.id NOT IN (SELECT House.id FROM House INNER JOIN (SELECT Reservation.house_id FROM Reservation WHERE (Reservation.checkin_date > '".$checkin."' AND Reservation.checkin_date < '".$checkout."') OR (Reservation.checkout_date > '".$checkin."' AND Reservation.checkin_date < '".$checkout."') GROUP BY Reservation.House_id) AS R ON R.house_id = House.id)";
										}
										if(!empty($_SESSION['Information']))
										{
											foreach ($_SESSION['Information'] as $value)
											{
												$searchCommand = $searchCommand." AND House.id IN (SELECT House.id FROM House INNER JOIN Information ON House.id = Information.house_id WHERE Information.information_id = ".$value.")";
											}
										}
										$infoCommand = "SELECT information FROM Information LEFT JOIN InfoList ON Information.information_id = InfoList.id WHERE house_id = :house_id";



										// $infoCommand = "SELECT information FROM Information LEFT JOIN InfoList ON Information.information_id = InfoList.id WHERE house_id = :house_id AND ( 0";
										// if(!empty($_SESSION['Information']))
										// {
										// 	foreach ($_SESSION['Information'] as $value)
										// 	{
										// 		$infoCommand = $infoCommand." OR information_id = '".$value."'";
										// 	}
										// }
										// $infoCommand = $infoCommand.")";
										if($_SESSION['Sort'] != "")
										{
											if($_SESSION['Sort'] == "date_sort_new")
											{
												$searchCommand = $searchCommand." ORDER BY time DESC";
											}
											elseif($_SESSION['Sort'] == "date_sort_old")
											{
												$searchCommand = $searchCommand." ORDER BY time ASC";
											}
											elseif($_SESSION['Sort'] == "price_sort_high")
											{
												$searchCommand = $searchCommand." ORDER BY price DESC";
											}
											elseif($_SESSION['Sort'] == "price_sort_low")
											{
												$searchCommand = $searchCommand." ORDER BY price ASC";
											}
										}
										else
										{
											$searchCommand = $searchCommand." ORDER BY id ASC";
										}
										///////////////
										
										$stmt=$connect->prepare($searchCommand);
										if(isset($idtag))
										{
											$stmt->bindparam(":id", $inputId);
										}
										if(isset($nametag))
										{
											$inputName = "%".$inputName."%";
											$stmt->bindparam(":name", $inputName);
										}
										if(isset($locationtag))
										{
											$inputLocation = "%".$inputLocation."%";
											$stmt->bindparam(":location", $inputLocation);
										}
										if(isset($timetag))
										{
											$stmt->bindparam(":time", $inputTime);
										}
										if(isset($ownertag))
										{
											$stmt->bindparam(":owner_id", $owner_id);
										}

										$stmt->execute();
										$num_record = $stmt->rowCount();
										$num_page = ceil($num_record / 5);
										if($num_page == 0)
										{
											$num_page = 1;
										}
										$searchCommand = $searchCommand." LIMIT ".(($_SESSION['Page'] - 1) * 5).", 5";
										$stmt=$connect->prepare($searchCommand);
										if(isset($idtag))
										{
											$stmt->bindparam(":id", $inputId);
										}
										if(isset($nametag))
										{
											$stmt->bindparam(":name", $inputName);
										}
										if(isset($locationtag))
										{
											$stmt->bindparam(":location", $inputLocation);
										}
										if(isset($timetag))
										{
											$stmt->bindparam(":time", $inputTime);
										}
										if(isset($ownertag))
										{
											$stmt->bindparam(":owner_id", $owner_id);
										}
										$stmt->execute();
										if($stmt->rowCount() == 0)
										{
											echo "沒有任何房子";
										}
										else
										{
											$rows = $stmt->fetchAll();
											foreach ($rows as $row)
											{
												if(!empty($_SESSION['Information']))
												{
													$stmt=$connect->prepare($infoCommand);
													$stmt->execute(array('house_id' => $row['id']));
													// if($stmt->rowCount() != count($_SESSION['Information']))
													// {
													// 	continue;
													// }
												}
												$stmt=$connect->prepare("SELECT * FROM Favorite WHERE favorite_id = :favorite_id AND user_id = :user_id");
												$stmt->execute(array('favorite_id' => $row['id'], 'user_id' => $_SESSION['id']));
												if($stmt->rowCount() == 0)
												{
													$favor_gray = "";
													$favor_word = "加入最愛";
												}
												else
												{
													$favor_gray = "disabled = 'disabled'";
													$favor_word = "已經加入最愛";
												}
												$stmt=$connect->prepare("SELECT information FROM Information INNER JOIN InfoList WHERE house_id = :house_id AND Information.information_id = InfoList.id");
												$stmt->execute(array('house_id' => $row['id']));
												$infoHTML = "<table>";
												while($info = $stmt->fetch())
												{
													$infoHTML = $infoHTML."<tr><td>".$info[0]."</td></tr>"; 
												}
												$infoHTML = $infoHTML."</table>";
												if($row['location'] == "")
												{
													$row['location'] = "未知";
												}
												$rowId = $row['id'];
												echo "
													<tr>
														<td valign='top'>".$row['id']."</td>
														<td valign='top'>".$row['name']."</td>
														<td valign='top'>".$row['price']."</td>
														<td valign='top'>".$row['location']."</td>
														<td valign='top'>".$row['time']."</td>
														<td valign='top'>".$row['user']."</td>
														<td valign='top'>".$infoHTML."</td>
														<td valign='top'>
															<form></form>
															<form action='modify_house.php' method='post'>
																<button type='submit' name='delete' value='$rowId'".$gray_button.">刪除</button>
															</form>
														</td>
														<td valign='top'>
															<form action='modify_house.php' method='post'>
																<button type='submit' name='favorite' value='$rowId' ".$favor_gray.">".$favor_word."</button>
															</form>
														</td>
														<td valign='top'>
															<form action='create_reservation.php' method='post'>
																<button type='summit' name='reserve' value='$rowId'>訂房</button>
															</form>
														</td>
													</tr>";
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
													window.location.replace("index.php");
												</script>
											</html>
EOT;
									}
								?>
							</table>
						</div>
					</form>
				</tr>
			</table>
			<br>
			<br>
			<form action="admin.php" method="post">
				<table align="center">
					<tr>
						<td>
							第
						</td>
						<?php
							for($i = 1; $i <= $num_page; $i++)
							{
								if($i == $_SESSION['Page'])
								{
									$disabled="disabled";
								}
								else
								{
									$disabled="";
								}
								echo "<td><button type='summit' name='page' value='".$i."' ".$disabled." >".$i."</td>";
							}
						?>
						<td>
							頁
						</td>
					</tr>
				</table>
			</form>
		</div>
	</body>
</html>