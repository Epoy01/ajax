<?php
include_once("header.php");

session_start();
if(!isset($_SESSION['login'])&&!isset($_SESSION['email'])){
	header("Location:login.php");
}

if(empty($_REQUEST['uid'])){
	header("Location:index.php");
}

if(isset($_REQUEST['delete'])){
	extract($_REQUEST);
	if($model->delete($id,"user")){
		header("Location:index.php?success=Data Successfully Deleted");
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
	<div class="card bg-danger ">
	  <div class="card-header ">
	    Delete Contact
	  </div>
	  <div class="card-body bg-light">
<?php
extract($model->getById($_REQUEST['uid'],"user"));
?>
	      <form method="post" action="delete.php">
	      	<input type="number" name="id" min="1" value="<?php echo $u_id ?>" hidden/>
	          <div class="form-group">
	              <h3>Are you sure you want to delete? </h3>
	              <p>
	              	ID: <?php echo $u_id; ?><br/>
	              	Name: <?php echo $fname.' '.$mname.' '.$lname; ?>
	              </p>
	          </div>
	  </div>
	  <div class="card-footer bg-light">
	  		          <button type="submit" class="btn btn-danger" name="delete">Yes, Delete Record</button>&nbsp;<a href="index.php"><input type="button" class="btn btn-default" name="cancel" value="Cancel"></a>
	      </form>
	  </div>
	</div>
</div>
<div class="col-sm"></div>
</div>

</body>
</html>
