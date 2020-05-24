<?php
session_start();
include 'bootstrap.php';
?>
<nav class="navbar navbar-dark bg-dark">
  <span class="navbar-brand mb-0 h1">Nmap Organizer</span>
</nav>
<br><br>
<div class="container">
<div class="jumbotron">
	<h4>Select an Option to Continue</h4>
<br><br>
<form action="" method="post">
	
 <div class="form-inline">
 <div class="form-group mb-2">
    <label for="staticEmail2" class="sr-only">Projet</label>
    <input type="text" readonly class="form-control-plaintext" id="staticEmail2" value="Enter Project Name">
  </div>
<div class="form-group mx-sm-3 mb-2">
<input type="text" class="form-control form-control-lg"  name="projectname" required>
</div>

<input type="submit" class="btn btn-primary"name="New" value="New">
</div>

</form>
<br>

<br>
<form method="post">
<div class="form-inline">
 <div class="form-group mb-2">
  <label for="staticEmail2" class="sr-only">Projet</label>
    <input type="text" readonly class="form-control-plaintext" id="staticEmail2" value="Choose existing">
  </div>	
<div class="form-group mx-sm-3 mb-2">
<select class="form-control form-control-lg" name="project" width="100">
<?php

$con=mysqli_connect("localhost","root","","nmap");
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}
else{
	$q="select * from project";
	$res=mysqli_query($con,$q);
	print_r($res);
	if($res){
		while ($row = mysqli_fetch_assoc($res)) 
		{
				
		        echo "<option value=".$row["pid"].":".$row["pname"].">".$row["pname"]."</option>";
		}

        
    }	
    

	
	
}
?>
</select>
</div>
<div class="form-group mx-sm-3 mb-2">
<input type="submit" class="btn btn-primary" name="select" value="Select">
</div>
<div class="form-group mx-sm-3 mb-2">
<input type="submit" class="btn btn-primary" name="delete" value="Delete">
</div>
</form>
</div>
</div>
</div>

<?php

if(isset($_POST['New'])){
	
	if(isset($_POST['projectname']) && $_POST['projectname']!=" ")
	{
		$q="INSERT INTO PROJECT(pname) VALUES('".$_POST['projectname']."')";
	    if(mysqli_query($con,$q))
	    	{
	    		echo "New Project added<br>";
	    		$_SESSION['pname']=$_POST['projectname'];
	    		$q="select pid from project where pname='".$_POST['projectname']."'";
	    		$res=mysqli_query($con,$q);
	    		$id=mysqli_fetch_assoc($res);
	    		$_SESSION['pid']=$id['pid'];
	    		header('location:hosts.php');

	    		
	    	}
	    	else{
	    		die("Unable to execute as name may already Exist");
	    	}
   }
   else{echo "Enter project name";}
	
 }
if(isset($_POST['select'])){
	if(isset($_POST['project']))
		$project=explode(":",$_POST['project']);
	$_SESSION["pid"]=$project[0];
	$_SESSION["pname"]=$project[1];
	header('location:hosts.php');

		//echo $project;
}
if(isset($_POST['delete'])){
	if(isset($_POST['project']))
		$project=explode(":",$_POST['project']);
	$pid=$project[0];
	$q="select  hid from host where pid=".$pid;
	
	$res=mysqli_query($con,$q);
	while($row=mysqli_fetch_assoc($res))
	{
		$q="delete  from ports where hid=".$row['hid'];
		
	    mysqli_query($con,$q);
	}
	
	
	$q="delete  from host where pid=".$pid;
	
	mysqli_query($con,$q);

	$q="delete  from project where pid=".$pid;
	echo $q.",";
	mysqli_query($con,$q);
	

	//header('location:index.php');

		//echo $project;
}
?>
</form>
</div>
<?php include'footer.php';?>