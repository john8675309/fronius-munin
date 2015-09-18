#!/usr/bin/php
<?php
#Fronius API JSON By John Hass
#john8675309 at gmail.com
#Licensed under GPLV2
$host="";
#-------------------------End Self Config-------------------------------
if ($host == "") {
	echo "edit $argv[0] and set the address of the inverter\n";
	exit;
}
if ($argc == 2) {
	if ($argv[1] == "config") {
		echo "graph_title Total Kw\n";
		echo "graph_vlabel Kilowatts\n";
		echo "graph_category Solar\n";
		echo "graph_info Total Kilowatts Made\n";
		echo "made_kw.label Made Kilowatts\n";
	}
	exit;
}


//curl the inverter data, I only care about the total
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, "http://$host/solar_api/v1/GetInverterRealtimeData.cgi?Scope=System&DataCollection=CommonInverterData"); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
$output = curl_exec($ch); 
curl_close($ch); 
//echo $output;
//Turn it into sexy JSON
$json=json_decode($output);
$total_watts=0;
foreach($json->Body->Data->PAC->Values as $i) {
	$total_watts=$total_watts +$i;
}
$total_kw = $total_watts / 1000;
//echo $total_kw ."\n";
echo "made_kw.value $total_kw\n";
?>
