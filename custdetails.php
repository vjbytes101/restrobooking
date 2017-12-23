
<!DOCTYPE html>
<html>
<head>
<body>
<a href ="index1.php">Back to main page</a>

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

$custname = $_SESSION['cust_name'];
$qnt = $_SESSION['qnt'];
$rid = $_SESSION['rid'];
$insertstatus = $_SESSION['err'];
$sql = "SELECT cid from customer where cname = {$custname}";
$result = mysql_query( $sql, $con);
$value = mysql_fetch_object($result);
$result1 = $value->cid;
if($insertstatus == "0"){
	$dispNone001 = "display:none";
	$dispNone111 = "display:block";
}else{
	$dispNone001 = "display:block";
	$dispNone111 = "display:none";
}
if(empty($value)){
	$dispNone01 = "display:none";
	$dispNone11 = "display:block";
}else{
	$dispNone01 = "display:block";
	$dispNone11 = "display:none";
}
?>
<div align="center" style="<?=$dispNone11?>">Customer id is invalid....</div>
<div style="<?=$dispNone01?>">
<div align="center" style="<?=$dispNone001?>">
<h4> Previous booking of <?php echo $custname; ?></h4><br><br>
<table border ="2">
<th> Restaurant Name </th>
<th> Number of Seats </th>
<th> Booking Time </th>
<?php
if($result1){
$sql1 = "SELECT r.rname,b.quantity,b.btime FROM booking as b, restaurant as r where b.cid = {$result1} and b.rid = r.rid ORDER BY b.bid ASC";
$result2 = mysql_query( $sql1, $con);
if ($result2) {
    // output data of each row
    while($data = mysql_fetch_array($result2,MYSQL_NUM)){
       ?>
		 <tr>
		 <td><?php echo $data[0]; ?></td>
		 <td><?php echo $data[1]; ?></td>
		 <td><?php echo $data[2]; ?></td>
		 </tr>
  <?php
    }
} else {
    echo "0 results";
}
}
else{
	echo "Customer id not found";
}
mysql_close($con);

?>
</table>
</div>
<div align="center" style="<?=$dispNone111?>">Booking is failed due to invalid quantity please try again....</div>
</div>
</body>
</html>