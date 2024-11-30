<?php
include 'create/con.php';
$oid = $_GET['oid'];
$mid = ""; // Your MID Here
function call($url){
  $curl = curl_init($url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_POST, false);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  $curl_response = curl_exec($curl);
  curl_close($curl);
  return $curl_response;
}
$sql = "SELECT * FROM payment WHERE oid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $oid); 
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $amo = $row['amo'];
} else {
    echo "No data found for ID: " . $id_to_retrieve;
}
$result = call("https://paytm-txn-api.vercel.app/api/?mid=$mid&txn=$oid");
$darshan = json_decode($result, true);
$status = $darshan['STATUS'];
$rep = $darshan['RESPMSG'];
$amooo = $darshan['TXNAMOUNT'];
if($status == "TXN_SUCCESS" && $rep == "Txn Success"){
	echo 200;
	$sql = "UPDATE payment SET status = 'Success' WHERE oid = '$oid'";
$upstatus = $conn->query($sql);

date_default_timezone_set("Asia/Calcutta");
          $date = date("h:i:s A");
          $time = date("d-M-Y");
          $successStatus = "Success";
$sql = "SELECT * FROM payment WHERE status = '$successStatus'";
$result = $conn->query($sql);
$add = mysqli_num_rows($result);
$tex='<b>🥳 New Accept Amount Successful 

♂️ Status :- '. $rep.'
🤑 Amount :- ₹'. $amooo.' 

🔶 Order Id :- '.$oid.'
📅 Date :- '.$time.' '.$date.'

🤑 Updated Accepts : '.$add.'

⚡ Powered By Fast Back
</b>';
  $text=urlencode("$tex");
  file_get_contents('https://api.telegram.org/bot6591127943:AAF5fISfxcO9bPQh2y1bMAU-SUd5WyAKSc0/sendMessage?chat_id=1516610662&text='.$text.'&parse_mode=html');
}else{
echo 400;
}
?>