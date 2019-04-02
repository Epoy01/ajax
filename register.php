<?php
include_once("header.php");
$msg_failed=false;
$msg=[];
if(isset($_REQUEST['register'])){
	extract($_REQUEST);
	$registration=$model->register($fname,$mname,$lname,$email,$pwd,$c_pwd,"user");
	$msg=$registration['msg'];
	if($registration['success']){
		header("Location:register.php?success=Successfully Registered");
		$msg_failed=false;
	}else{
		$msg_failed=true;
	}
}

?>
<body><br/>
<div class="container">
<?php
if(isset($_REQUEST['success'])){
echo <<<show
	<div class="alert alert-success alert-dismissible" id="myAlert">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{$_REQUEST['success']}</strong><br/>
  	</div>
    <br />
    <div class="alert alert-success alert-dismissible" id="myAlert">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <h4>Do you want to continue LogIn?</h4><a href="index.php"><input type="button" class="btn btn-default form-control col-sm-2" name="continue" value="Continue"></a>
  	</div>
    <br />
show;
}
if($msg_failed){
echo <<<show
	<div class="alert alert-danger alert-dismissible" id="myAlert">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>Registration Failed</strong>
  	</div>
    <br />
show;
}
?>	
</div>
<div class="row">
<div class="col-sm"></div>
<div class="col-sm-6">
	<div class="card bg-primary ">
	  <div class="card-header ">
	    Register
	  </div>
	  <div class="card-body bg-light">
	      <form method="post" action="register.php">
	          <div class="form-group">
	              <label for="fname">First Name:</label>
	              <input type="text" class="form-control" name="fname" value="<?php echo isset($_REQUEST['fname'])?$_REQUEST['fname']:'' ?>" required />
	              <p class="text-danger"><?php echo isset($msg['fname'])?$msg['fname']:''; ?></p>
	          </div>
	          <div class="form-group">
	              <label for="mname">Middle Name:</label>
	              <input type="text" class="form-control" name="mname" value="<?php echo isset($_REQUEST['mname'])?$_REQUEST['mname']:'' ?>"required/>
	              <p class="text-danger"><?php echo isset($msg['mname'])?$msg['mname']:''; ?></p>
	          </div>
	          <div class="form-group">
	              <label for="lname">Last Name:</label>
	              <input type="text" class="form-control" name="lname" value="<?php echo isset($_REQUEST['lname'])?$_REQUEST['lname']:'' ?>" required/>
	              <p class="text-danger"><?php echo isset($msg['lname'])?$msg['lname']:''; ?></p>
	          </div>
	          <div class="form-group">
	              <label for="email">Email Address:</label>
	              <input type="email" class="form-control" name="email" value="<?php echo isset($_REQUEST['email'])?$_REQUEST['email']:'' ?>" required/>
	              <p class="text-danger"><?php echo isset($msg['email'])?$msg['email']:''; ?></p>
	          </div>
	          <div class="form-group">
	              <label for="password">Password:</label>
	              <input type="password" class="form-control" name="pwd" value="<?php echo isset($_REQUEST['pwd'])?$_REQUEST['pwd']:'' ?>" required/>
	              <p class="text-danger"><?php echo isset($msg['pwd'])?$msg['pwd']:''; ?></p>
	          </div>
	          <div class="form-group">
	              <label for="password">Confirm Password:</label>
	              <input type="password" class="form-control" name="c_pwd" value="<?php echo isset($_REQUEST['c_pwd'])?$_REQUEST['c_pwd']:'' ?>" required/>
	              <p class="text-danger"><?php echo isset($msg['pwd'])?$msg['pwd']:''; ?></p>
	          </div>
	  </div>
	  <div class="card-footer bg-light">
	  		          <button type="submit" class="btn btn-primary form-control col-sm-6" name="register">Register Now</button>&nbsp;<a href="login.php"><input type="button" class="btn btn-danger form-control col-sm-3" name="cancel" value="Cancel"></a>
	      </form>
	  </div>
	</div>
</div>
<div class="col-sm"></div>
</div>
<br/>
</body>
</html>

 