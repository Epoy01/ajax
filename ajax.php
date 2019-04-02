<?php
function __autoload($class){
  include_once($class.".php");
}

$model= new model("model.php");

  if (isset($_POST['query_search'])&&isset($_POST['query_select'])){
    $cond['search']=$_POST['query_search'];
    $cond['select']=$_POST['query_select'];
    $cond['show']=$_POST['query_show'];
    $table=$_POST['table'];
    $data=$model->ajaxShowData($table,$cond);
  }
  
?>