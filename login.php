<?php
include_once("header.php");
$msg_failed=false;
$msg=null;
if(isset($_REQUEST['login'])){
	extract($_REQUEST);
	$login=$model->login($email,$pwd,"user");

	$msg=$login['msg'];
	
	if($login['success']){
		header("Location:index.php");
	}else{
		$msg_failed=true;
	}
}

?>
<body><br/>
<div class="container">
<?php
if($msg_failed){
echo <<<show
	<div class="alert alert-danger alert-dismissible" id="myAlert">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>LogIn Failed</strong>
  	</div>
    <br />
show;
}
?>	
</div>
<div class="row">
<div class="col-sm"></div>
<div class="col-sm-4">
	<div class="card bg-primary ">
	  <div class="card-header ">
	    Login
	  </div>
	  <div class="card-body bg-light">
	      <form method="post" action="login.php">
	      	  <div class="form-group">
	            <p class="text-danger"><?php echo isset($msg)?$msg:''; ?></p>
	          </div>
	          <div class="form-group">
	              <label for="fname">Not yet registered?</label><a href="register.php">Register here</a>
	          
	          </div>
	          <div class="form-group">
	              <label for="email">Email Address:</label>
	              <input type="email" class="form-control" name="email" value="<?php echo isset($_REQUEST['email'])?$_REQUEST['email']:'' ?>" required/>
	          </div>
	          <div class="form-group">
	              <label for="password">Password:</label>
	              <input type="password" class="form-control" name="pwd" value="<?php echo isset($_REQUEST['pwd'])?$_REQUEST['pwd']:'' ?>" required/>
	          </div>

	  </div>
	  <div class="card-footer bg-light">
	  		          <button type="submit" class="btn btn-primary form-control col-sm-3" name="login">LogIn</button>
	      </form>
	  </div>
	</div>
</div>
<div class="col-sm"></div>
</div>
<br/>
</body>
</html>

 