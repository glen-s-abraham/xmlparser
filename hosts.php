<?php
session_start();
include 'bootstrap.php';
include'header.php';
?>
<br>
<table class="table">
	<thead class="thead-dark">
	<tr>
		<th>IP</th><th>MAC</th><th>OS</th><th>Vendor</th>
		
	</tr>
</thead>

<?php
$con=mysqli_connect("localhost","root","","nmap");
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}

$pid=$_SESSION['pid'];
$q="select * from host where pid=".$pid;

$res=mysqli_query($con,$q);
while($row=mysqli_fetch_assoc($res))
{
	echo "<tr>";
	echo"<td>".$row['ip']."</td>";
	echo"<td>".$row['mac']."</td>";
	echo"<td>".$row['os']."</td>";
	echo"<td>".$row['vendor']."</td>";
	echo "</tr>";
}
?>
</table>
<br>
<?php include'footer.php';?>