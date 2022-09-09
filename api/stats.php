<?php
	header('content-type: application/json');
	//get counter
	$Counter = curl_init();
	if (!array_key_exists('HTTP_X_VERCEL_IP_CITY', $_SERVER)) {
		$VPN = true;
	} else {
		if (array_key_exists('HTTP_X_VERCEL_IP_COUNTRY', $_SERVER)) $Country = $_SERVER['HTTP_X_VERCEL_IP_COUNTRY'];
		else $Country = 'Unknown';
		if ($Country != "CN") $foreign = true;
		if (array_key_exists('HTTP_X_VERCEL_IP_CITY', $_SERVER)) $City = $_SERVER['HTTP_X_VERCEL_IP_CITY'];
		else $City = 'Unknown';
		$Location = $Country . ',' . $City;
	}
	curl_setopt_array($Counter, array(
		CURLOPT_URL => 'https://api.beishifengqiban.cf/1.1/classes/Counter/631b1a93afc96e49751355f1',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
		CURLOPT_HTTPHEADER => array(
			'X-LC-Id: yKkVFyPPYHeHuF9gSe3OhCQE-MdYXbMMI',
			'X-LC-Key: dZDy0LOa22IdkqUlRoIBTRIK',
			'Content-Type: application/json'
		),
	));
	$Counterdata = json_decode(curl_exec($Counter))->Data;
	//get ip
	$Ip = $_SERVER["HTTP_X_REAL_IP"];
	//find same ip
	$FindIp = curl_init();
	curl_setopt_array($FindIp,array(
        CURLOPT_URL => 'https://api.beishifengqiban.cf/1.1/classes/Stats',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS => "where=%7B%22Ip%22%3A%20%22$Ip%22%7D&count=1&limit=0",
        CURLOPT_HTTPHEADER => array(
            'X-LC-Id: yKkVFyPPYHeHuF9gSe3OhCQE-MdYXbMMI',
            'X-LC-Key: dZDy0LOa22IdkqUlRoIBTRIK',
            'Content-Type: application/x-www-form-urlencoded'
        ),
    ));
	$FindIpdata = json_decode(curl_exec($FindIp))->count;
	curl_close($FindIp);
	if($FindIpdata == '0'){
		//update counter
		curl_setopt_array($Counter, array(
			CURLOPT_CUSTOMREQUEST => 'PUT',
			CURLOPT_POSTFIELDS =>'{"Data":{"__op":"Increment","amount":1}}',
		));
		curl_exec($Counter);
		//update ip
		$UpdateIp = curl_init();
		curl_setopt_array($UpdateIp, array(
			CURLOPT_URL => 'https://api.beishifengqiban.cf/1.1/classes/Stats',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => "{\"Ip\":\"$Ip\",\"Location\":\"$Location\"}",
			CURLOPT_HTTPHEADER => array(
				'X-LC-Id: yKkVFyPPYHeHuF9gSe3OhCQE-MdYXbMMI',
				'X-LC-Key: dZDy0LOa22IdkqUlRoIBTRIK',
				'Content-Type: application/json'
			),
		));
		curl_exec($UpdateIp);
		curl_close($UpdateIp);
	}
	curl_close($Counter);
	echo json_encode(['ip' => $Ip, 'counter' => $Counterdata]);
?>