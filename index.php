<?php 
    date_default_timezone_set('Asia/Kolkata');
	$dbhost = 'localhost';
	$dbuser = 'damacaibluelot';
	$dbpass = 'jKBUIdQI$%Y@';
	$dbname = 'tts_Corona';
	$conn = mysqli_connect($dbhost, $dbuser, $dbpass,$dbname);

	if(! $conn ) {
	die('Could not connect: ' . mysqli_error());
	}
	#echo 'Connected successfully<br>'; 
	
	$errormsg = $succmsg = $succmsgemail = '';
	$gasarr = $thisgas = array();
	$sql = 'SELECT * FROM apilog ORDER BY logid DESC LIMIT 5';
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			if(empty($thisgas)) {
				$thisgas = $row;
			}
			$gasarr[] = $row;
		}
	} else {
		echo "0 results"; exit;
	}
	mysqli_close($conn);
	if(isset($_POST['mobilenumber'])) {
		if(isset($_POST['mobilenumber']) && !empty($_POST['mobilenumber']) && is_numeric($_POST['mobilenumber']) && strlen($_POST['mobilenumber']) == 10) {
			$succmsg = "SMS sent successfully";
			
			$smsarr[] = array(
					"mobile" => $_POST['mobilenumber'],
					"message" => "Temperature : ".$thisgas['temperature']."## Respiration ".$thisgas['respiration']."## Heartrate ".$thisgas['heartrate'],
				);
			$smsinfo = array(
								"username" => 'wellness2',
								"password" => 'wellness2',
								"smslist" => $smsarr,
							);
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"http://www.damacaibluelot.net.in/sendsms/index");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8;"));
			$data = json_encode ($smsinfo);
			curl_setopt($ch, CURLOPT_POST, $data);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLINFO_HEADER_OUT, true);

			//Actual Call to Server
			$server_output = curl_exec($ch);
			curl_close($ch);
		} else {
			$errormsg = "Mobile number must be 10 digit and numeric";
		}
	}
	
	if(isset($_POST['email']) && !empty($_POST['email'])) {
	$succmsgemail = "Email sent successfully";
	//Sendemail
	$emailinfo = array(
		"fromemail" => 'ihjeeva@gmail.com',
		"fromname" => 'Admin',
		"toemail" => $_POST['email'],# $this->view->testemail,#
		"subject" =>"Corona : Current Body Status",
		"texthtml" =>"Dear customer,<br><br>Your status details are <br><br>
								<strong>Temperature :</strong>".$thisgas['temperature']."<br>
								<strong>Respiration :</strong> ".$thisgas['respiration']."<br>
								<strong>Heartrate :</strong> ".$thisgas['heartrate']."<br><br>
								Regards,<br>
								<strong>Corona</strong>",
	 );
#print_r($emailinfo);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"http://www.tripletechsoft.org/home/sendemailprojects");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8;"));
	$data = json_encode ($emailinfo);
	curl_setopt($ch, CURLOPT_POST, $data);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);

	//Actual Call to Server
	$server_output = curl_exec($ch);
	curl_close($ch);
}
	
  ?>
<html>
    <head>
        <title>Corona</title>
        <meta name="viewport" content="initial-scale=1.0">
        <meta charset="utf-8">
		<style>
		#customers {
		  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
		  border-collapse: collapse;
		  width: 100%;
		  padding-bottom:15px;
		}

		#customers td, #customers th {
		  border: 1px solid #ddd;
		  padding: 8px;
		}

		#customers tr:nth-child(even){background-color: #f2f2f2;}

		#customers tr:hover {background-color: #ddd;}

		#customers th {
		  padding-top: 12px;
		  padding-bottom: 12px;
		  text-align: left;
		  background-color: #4CAF50;
		  color: white;
		}
		.ccbutton{
			border: none;
			display: inline-block;
			padding: 8px 16px;
			vertical-align: middle;
			overflow: hidden;
			text-decoration: none;
			color: #fff !important;
			background-color: #4CAF50 !important;
			text-align: center;
			cursor: pointer;
			white-space: nowrap;
			margin: 5px 5px 5px 0;
			font-size: 16px;
		}
		.error{
			color:red;
		}
		</style>
    </head>    
    <body>        
        <div style="padding:10px">
			<h2 style="text-align: center;font-size: 40px;color:#4CAEDE;">Corona</h2>
		<div>
		<hr>
		<form method="POST" style="display:;">
			<table align="center">
				<tr>
					<td>
						<b>Mobile Number : </b>
					</td> 
					<td>
						<input type="text" id="mobilenumber" name="mobilenumber" value="" maxlength='10' placeholder="Mobile Number" />
					</td> 
					<td>
						<input type="submit" value="Send SMS" class="ccbutton"/>
					</td> 
				</tr>
				<?php if(!empty($errormsg)) { ?>
				<tr>
					<td colspan="3" style="color:red;font-size: 20px;"><hr><b><?php echo $errormsg; ?></b></td>
				</tr>
				<?php } ?>
				<?php if(!empty($succmsg)) { ?>
				<tr>
					<td colspan="3" style="color:green;font-size: 20px;"><hr><b><?php echo $succmsg; ?></b></td>
				</tr>
				<?php } ?>
			</table>
			<hr>
			</form>
			
		
		<form method="POST" style="display:;">
			<table align="center">
				<tr>
					<td>
						<b>Email : </b>
					</td> 
					<td>
						<input type="text" id="email" name="email" value="" maxlength='50' placeholder="Email" />
					</td> 
					<td>
						<input type="submit" value="Send Email" class="ccbutton"/>
					</td> 
				</tr>
				<?php if(!empty($errormsg)) { ?>
				<tr>
					<td colspan="3" style="color:red;font-size: 20px;"><hr><b><?php echo $errormsg; ?></b></td>
				</tr>
				<?php } ?>
				<?php if(!empty($succmsgemail)) { ?>
				<tr>
					<td colspan="3" style="color:green;font-size: 20px;"><hr><b><?php echo $succmsgemail; ?></b></td>
				</tr>
				<?php } ?>
			</table>
			<hr>
			</form>
			
			<table style="width: 100%;text-align: center;margin-top: 15px;" id="customers">
				<tr>
					<th>SI. NO</th>
					<th>Temperature</th>
					<th>Respiration</th>
					<th>Heartrate</th>
					<!--<th>IP </th>
					<th>Latitude</th>
					<th>Longitude</th>-->
					<th>Updated Date</th>
				</tr>
				<?php $inc = 1; foreach($gasarr as $info) { ?>
				<tr>
					<td><?php echo $inc; ?></td>
					<td><?php echo $info['temperature']; ?></td>
					<td><?php echo $info['respiration']; ?></td>
					<td><?php echo $info['heartrate']; ?></td>
				<!--	<td><?php echo $info['ipAddr']; ?></td>
					<td><?php echo $info['Latitude']; ?></td>
					<td><?php echo $info['Longitude']; ?></td> -->
					<td><?php echo date('d-m-Y h:i A', strtotime($info['created'])); ?></td>
				</tr>
				<?php $inc++; } ?>
			</table>
		</div>
    </body>    
</html>