
<!DOCTYPE html>
<html>
<head>
<body>

<?php
include "mydbcon.php";


session_start();
if(isset($_POST['cust_name'])) {
    $_SESSION['cust_name'] = $_POST['cust_name'];
}
if(isset($_POST['qnt'])) {
    $_SESSION['qnt'] = $_POST['qnt'];
}
if(isset($_POST['rid'])) {
    $_SESSION['rid'] = $_POST['rid'];
}
if(isset($_POST['datetime'])) {
    $_SESSION['datetime'] = $_POST['datetime'];
}

$custname = $_SESSION['cust_name'];

$qnt = $_SESSION['qnt'];
$rid = $_SESSION['rid'];
$datetime = $_SESSION['datetime'];
$sql = "SELECT cid from customer where cname = {$custname}";
$result = mysql_query( $sql, $con);
$value = mysql_fetch_object($result);
$result1 = $value->cid;

if ($result1) {
	$_SESSION['err'] = "0";
	$dateavailablity =  mysql_query("select r.rid from restaurant r join booking b on r.rid = b.rid where r.rid = {$rid} and b.btime = '{$datetime}'");
	if(mysql_num_rows($dateavailablity) != 0){
		$record1 = mysql_query(
			" select r.rid,r.rname,r.raddress,r.description,r.capacity from restaurant r join booking b on r.rid = b.rid where r.rid = {$rid} and b.btime = {$datetime} group by r.rid, b.btime having r.capacity != sum(b.quantity) and r.capacity-sum(b.quantity)>={$qnt}"
		);
		echo mysql_num_rows($dateavailablity);
		if(mysql_num_rows($record1) != 0){
			$_SESSION['err'] = "1";
		}else{
			$_SESSION['err'] = "0";
		}
	}else{
		$record1 = mysql_query("SELECT distinct r.rid,r.capacity from restaurant r join booking b on r.rid = b.rid where r.rid = {$rid} group by r.rid, b.btime having r.capacity >= {$qnt}");
		$value1 = mysql_num_rows($record1);
		if($value1 > 0){
			echo "true";
			$_SESSION['err'] = "1";
		}else{
			echo "false";
			$_SESSION['err'] = "0";
		}
		
	}
	
	$insertstatus = $_SESSION['err'];
	if($insertstatus == '1'){
		$record = mysql_query("INSERT INTO booking (btime,cid,quantity,rid)
								VALUES ({$datetime},{$result1},{$qnt},{$rid});"
		
		);
		$_SESSION['err'] = "1";
	}else{
		$_SESSION['err'] = "0";
	}
}else{
	echo "Booking is failed please try again....";
}


mysql_close($con);

?>

</body>
</html>