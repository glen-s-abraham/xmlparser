<?php
session_start();
include 'bootstrap.php';
include'header.php';
?>

<table class="table">
	<thead class="thead-dark">
	<tr>
		<th>Port</th><th>State</th><th>Service</th><th>Product</th>
		
	</tr>
</thead>

<?php
$con=mysqli_connect("localhost","root","","nmap");
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}

$pid=$_SESSION['pid'];

?>
<br>
<form action="" method="get">
<div class="form-inline">
<div class="form-group mx-sm-3 mb-2">
<select class="form-control form-control-lg" name="host">	
<?php
	$q="select hid,ip from host where pid= ".$pid;

	$res=mysqli_query($con,$q);
	if($res){
		while($row=mysqli_fetch_assoc($res))
		{
			echo '<option value="'.$row['hid'].':'.$row['ip'].'">'.$row['ip'].'</option>';
			
		}
	}
?>
</select>
</div>
<div class="form-group mx-sm-3 mb-2">
<input type="text" class="form-control form-control-lg" name="key" placeholder="Search ">
</div>
<div class="form-group mx-sm-3 mb-2">
<input type="submit" name="sort" class="btn btn-secondary">
</div>
</div>
</form>

<?php
if(isset($_GET['sort'])&&isset($_GET['host'])){

	$host=explode(":",$_GET['host']);

	$key=$_GET['key'];

	set_table($host[0],$host[1],$key);
}
function set_table($id,$ip,$key){

	$con=mysqli_connect("localhost","root","","nmap");
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  exit();
	}
	$q="select * from ports where pnumber like('".$key."%') or pservice like('".$key."%') or pproduct like('%".$key."%') and hid= ".$id;
	//echo $q;

	$res=mysqli_query($con,$q);
	echo "<br><h5>Host:".$ip."</h5><br>";
	while($row=mysqli_fetch_assoc($res))
	{
		if($row['hid']==$id)
		{
			echo "<tr>";
			echo"<td>".$row['pnumber']."</td>";
			echo"<td>".$row['pstate']."</td>";
			echo"<td>".$row['pservice']."</td>";
			echo"<td>".$row['pproduct']."</td>";
			echo "</tr>";
		}
	}
}

?>
</table>
<?php include'footer.php';?>
