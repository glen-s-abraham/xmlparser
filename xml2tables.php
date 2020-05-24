<?php
session_start();
include 'bootstrap.php';
include'header.php';
?>
<form action="" method="post" enctype="multipart/form-data">
  Select File to upload:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Upload" name="submit">
</form>
<?php
if(isset($_POST['submit']))
{
	$target_dir = "uploads/";
	$filename=$_FILES["fileToUpload"]["tmp_name"];
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	
	if($FileType=="xml"){
		move_uploaded_file($filename,'uploads/data.xml');
		echo "File Recieved";
	}


$con=mysqli_connect("localhost","root","","nmap");
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}
else{
	echo"established database connection<br><br>";
}

$xml = simplexml_load_file("uploads/data.xml") or die("Error: Cannot create object");
$pid=$_SESSION['pid'];
$hid=0;
foreach ($xml->children() as $row) {
    $ip = $row->address[0]['addr'];
    if(is_null($ip)){continue;}
    $mac = $row->address[1]['addr'];
    $vendor=$row->address[1]['vendor'];
    $status=$row->status['state'];
    $ports=$row->ports;
    $os=$row->os->osmatch['name'];
    $q="INSERT INTO HOST(ip,mac,vendor,os,pid) VALUES('".$ip."','".$mac."','".$vendor."','".$os."',".$pid.")";
    echo $q."<br>";
    if(mysqli_query($con,$q)){
    	$q="select hid from host where mac='".$mac."' and pid=".$pid;
    	$res=mysqli_query($con,$q);
    	if($res){$r=mysqli_fetch_assoc($res);$hid=$r['hid'];}else{die("Unable to execute");}
    }else{die("Unable to execute");}

      
    foreach ($ports as $port) {
    	if(is_null($port)){break;}
    	foreach ($port as $p ) {
    		$protocol=$p['protocol'];
    		$portid=$p['portid'];
    		$state=$p->state['state'];
    		$service=$p->service['name'];
    		$product=$p->service['product'];
    		$q="INSERT INTO PORTS(hid,pnumber,pstate,pservice,pproduct,pprotocol) VALUES(".$hid.",'".$portid."','".$state."','".$service."','".$product."','".$protocol."')";
    		//if(mysqli_query($con,$q)){echo "Added Hosts<br>";}else{die("Unable to execute");}
    		mysqli_query($con,$q);
    		//echo $q;
    		

    		# code...
    	}

    	
    }
    
   	
   	}
   	echo "Database populated SuccessFully";	
   	header('location:hosts.php');

   }
?>
<?php include'footer.php';?>