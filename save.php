
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
	
	
	function getUserIP()
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }

        return $ip;
    }

    $ipAddr = getUserIP();	
	if($ipAddr == '::1') {
		$ipAddr = '157.49.232.254';
	}
	
	$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	if(isset($_GET['temperature']) && !empty($_GET['temperature']) && isset($_GET['heartrate']) && !empty($_GET['heartrate']) && isset($_GET['respiration']) && !empty($_GET['respiration'])) {
		$temperature = (isset($_GET['temperature'])) ? trim($_GET['temperature']) : '';
		$heartrate = (isset($_GET['heartrate'])) ? trim($_GET['heartrate']) : '';
		$respiration = (isset($_GET['respiration'])) ? trim($_GET['respiration']) : '';
		$sql = ' INSERT INTO apilog(temperature, heartrate, respiration, created, actual_link) VALUES("'.$temperature.'", "'.$heartrate.'", "'.$respiration.'", "'.date('Y-m-d H:i:s').'", "'.$actual_link.'")';
		#echo $sql;
		if (mysqli_query($conn, $sql)) {
		  //echo "Record updated successfully";
		  echo json_encode(array("message" => "Record updated successfully")); exit;
		} else {
		  //echo "Error updating record: " . mysqli_error($conn);
		  echo json_encode(array("message" => "Error in insert process")); exit;
		}
	}
	echo json_encode(array("message" => "Error in insert process")); exit;
	mysqli_close($conn);
  ?>