<?php
include_once("header.php");
session_start();
if(!isset($_SESSION['login'])&&!isset($_SESSION['email'])){
	header("Location:login.php");
}
$cond['show']='';

$cond['select']='u_id,fname,mname,lname';

	

?>
<body>
<div>

	<div class="row">
		<div class="col-sm"></div>
		<div class="col-sm-2">
			<a href="logout.php" class="pull-right">Log Out</a>
		</div>
	</div>

<div class="container">
<br/>


<?php
if(isset($_REQUEST['success'])){
echo <<<show
	<div class="alert alert-success alert-dismissible" id="myAlert">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{$_REQUEST['success']}</strong>
  	</div>
    <br />
show;
}
?>
    <h1>Contacts</h1><hr/>
  </div>

  <div class="container">
	  <div class="form-group">
	    <form action="index.php" method="get">
	      <div class="input-group">
	        <a href="insert.php" class="btn btn-success ">Add New Record</a>
	        <span class="col-sm-4"></span>
          <input class="col-sm" type="text" id="search_text" autocomplete="off" onkeyup="search($(this).val())" placeholder="Search Here">
	      </div>
	    </form>
	  </div>  	
  </div>

  <div class="container" id="results">
<?php
  $data=$model->ajaxShowData("user",$cond);
  
?>
  </div>

<div><br/><br/><br/><br/>

<script type="text/javascript">
  var page=1;
  var query_select="u_id,fname,mname,lname";
  var query_search=null;
  var query_show=null;
  var table="user";
function pager(val){
  var search_text=document.getElementById("search_text").value;
  if(search_text){
    query_search="WHERE u_id LIKE '%"+search_text+"%' OR fname LIKE '%"+search_text+"%' OR mname LIKE '%"+search_text+"%' OR lname LIKE '%"+search_text+"%'";
  }
  getData(query_show,query_search,query_select,table,val);
}

function search(val){
  var query_search="WHERE u_id LIKE '%"+val+"%' OR fname LIKE '%"+val+"%' OR mname LIKE '%"+val+"%' OR lname LIKE '%"+val+"%'";
  getData(query_show,query_search,query_select,table,page);
}

function getData(query_show,query_search,query_select,table,page){
  $.ajax({
        url: 'ajax.php',
        method: 'POST',
        data: {
          query_search:query_search,
          query_select:query_select,
          query_show:query_show,
          table:table,
          page:page
        },
        success: function(data)
        {
          $('#results').html(data);
          $('#results').css('display', 'block');
            
        }
  });
}

</script>

</body>
</html>



