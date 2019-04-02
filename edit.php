<?php
include_once("header.php");

session_start();
if(!isset($_SESSION['login'])&&!isset($_SESSION['email'])){
	header("Location:login.php");
}

if(empty($_REQUEST['uid'])){
	header("Location:index.php");
}

if(isset($_REQUEST['update'])){
	extract($_REQUEST);
	if($model->update($id,$fname,$mname,$lname,"user")){
		header("Location:index.php?success=Data Successfully Updated");
	}
}

?>

<body><br/>
<div class="row">
	<div class="col-sm"></div>
	<div class="col-sm-2">
		<a href="logout.php" class="pull-right">Log Out</a>
	</div>
</div>
<div class="row">
<div class="col-sm"></div>
<div class="col-sm-6">
	<div class="card bg-success ">
	  <div class="card-header ">
	    Edit Contact
	  </div>
	  <div class="card-body bg-light">
<?php
extract($model->getById($_REQUEST['uid'],"user"));
?>
	      <form method="post" action="edit.php">
	      	<input type="number" name="id" min="1" value="<?php echo $u_id ?>" hidden/>
	          <div class="form-group">
	              <label for="fname">First Name:</label>
	              <input type="text" class="form-control" name="fname" value="<?php echo isset($_REQUEST['fname'])?$_REQUEST['fname']:$fname ?>" required />
	          </div>
	          <div class="form-group">
	              <label for="mname">Middle Name:</label>
	              <input type="text" class="form-control" name="mname" value="<?php echo isset($_REQUEST['mname'])?$_REQUEST['mname']:$mname ?>"required/>
	          </div>
	          <div class="form-group">
	              <label for="lname">Last Name:</label>
	              <input type="text" class="form-control" name="lname" value="<?php echo isset($_REQUEST['lname'])?$_REQUEST['lname']:$lname ?>" required/>
	          </div>
	  </div>
	  <div class="card-footer bg-light">
	  		          <button type="submit" class="btn btn-success form-control col-sm-6" name="update">Update Record</button>&nbsp;<a href="index.php"><input type="button" class="btn btn-danger form-control col-sm-3" name="cancel" value="Cancel"></a>
	      </form>
	  </div>
	</div>
</div>
<div class="col-sm"></div>
</div>

</body>
</html>

