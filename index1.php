<html>
<head>
	<script src="jquery.min.js"></script>
	<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script> 
    <script type="text/javascript" src="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.pt-BR.js"></script>
	<link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="screen" href="http://tarruda.github.com/bootstrap-datetimepicker/assets/css/bootstrap-datetimepicker.min.css">
</head>
<body>
<form action="" method="post" style="margin: 50px" align="center">
  <div style="position: relative">
  Name: <input id ="namer"name="name" style="height: 30px" type="text" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" autocomplete="off" />
  <span class="error">*</span>
  <br><br>
  Keyword: <input name="keyword" style="height: 30px" type="text" value="<?php if (isset($_POST['keyword'])) echo $_POST['keyword']; ?>" autocomplete="off"/>
  Quantity: <input id="qnt" style="height: 30px" name="number" type="text" value="<?php if (isset($_POST['number'])) echo $_POST['number']; ?>" autocomplete="off"/>
  <br><br>
  <div id="datetimepicker" class="input-append date">
      <input id ="time" placeholder="YYYY-MM-DD HH:MM:SS" name="time1" style="height: 30px" type="text" value="<?php if (isset($_POST['time1'])) echo $_POST['time1']; ?>" autocomplete="off"></input>
      <span class="add-on">
        <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
      </span>
  </div>
  <br>
  <input class ="sendButton" name="submit" type="submit" />
  </div>
</form>

<?php
	include "mydbcon.php";
	if (empty($_POST["name"])) {
		$nameErr = "Name is required";
	}else {
		$name = $_POST['name'];
	}
	 
	$keyword = $_POST['keyword'];
	$number = $_POST['number'];
	//$date = $_POST['date1'];
	//$time = $_POST['time1'];
	$datetime = $_POST['time1'];
	if(isset($_POST['submit']))
	{
	$recordd= "select * from restaurant where (rname like '%{$keyword} %' or description like '%{$keyword}%')  and rid in
			(
				select rid from
				(
					select rid, (capacity-t2) as avb,capacity as cc, t2 as t3 from restaurant natural join (select rid, sum(quantity) as t2 from booking where btime='{$datetime}' group by rid UNION select rid, 0 as t2 from restaurant where rid not in
	(select rid from (select rid, sum(quantity) as t2 from booking where btime='{$datetime}' group by rid) a)) tmp
	) a where a.cc != a.t3 and  a.avb>='{$number}'
			)";
	$record = mysql_query( $recordd, $con);
	
	if(mysql_num_rows($record)== 0){
		$dispNone = "display:none";
		$dispNone1 = "display:block";
	}else{
		$dispNone = "display:block";
		$dispNone1 = "display:none";
	}
	 ?>
	<div align="center" style="<?=$dispNone1?>">No restaurant available....</div>
	<div style="<?=$dispNone?>">
		<table border ="2" align="center" >
			<th> Name </th>
			<th> Address </th>
			<th> Description </th>
			<th> Capacity </th>
			<th>  </th>
		<?php
			if ($record) {
				$i = 0;
				while($data = mysql_fetch_array($record,MYSQL_NUM))
				{
			?>
				 <tr>
				 <td><?php echo $data[1]; ?></td>
				 <td><?php echo $data[2]; ?></td>
				 <td><?php echo $data[3]; ?></td>
				 <td><?php echo $data[4]; ?></td>
				 <td><a class="bookclass" id="link-<?php echo ++$i; ?>" href= "#" value ="<?php echo $data[0]; ?>" >booking</a></td>
				 </tr>
			<?php
		 
				}
			}
			else {
				echo "0 results";
			}
			mysql_close($con);
	}
	?> 
		</table>
	</div>
 <script>
$(document).ready(function(){
	/*$('.sendButton').attr('disabled',true);
    $('#namer').keyup(function(){
        if($(this).val().length !=0)
            $('.sendButton').attr('disabled', false);            
        else
            $('.sendButton').attr('disabled',true);
    });*/
	
    $('a[id^="link-"]').on('click', function() {
		
		var name = $('#namer').val();
		var number = $("#qnt").val();
		//var date = $("#two").val();
		//var time = $("#time").val();
		var datetime = $("#time").val();
		var rid = $(this).attr('value');
		if($('#qnt').val() !== "" && $('#time').val() !== "" && $('#namer').val()) {
			$.post("insert.php", {cust_name : JSON.stringify(name), qnt : JSON.stringify(number), rid : JSON.stringify(rid), datetime : JSON.stringify(datetime)}, function(data){
				window.location.href = "custdetails.php";
			});
			//alert(1);
		}else{
			alert("Enter correct details for Name, date, time and quantity");
		}
	});
});
</script>
<script>
    $('#datetimepicker').datetimepicker({
        format: 'yyyy-MM-dd hh:00:00',
        language: 'pt-BR'
      });
</script>
</table>
</body>
</html>