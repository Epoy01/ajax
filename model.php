<?php

class model{
	private $host="localhost";
	private $user="root";
	private $db="clifford_db";
	private $pass="";
	private $conn=null;
	private $limit=5;
	private $rowCount=0;
	private $cur_page=1;

	public function __construct(){
		try{
			$this->conn=new PDO("mysql:host={$this->host};dbname={$this->db}",$this->user,$this->pass);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $err_msg){
			echo $err_msg;
		}
	}

	public function ajaxShowData($table,$cond){
		try{
			$offset=0;
			if(isset($_REQUEST['page'])){
				$this->cur_page=$_REQUEST['page'];
			}
			$offset=($this->cur_page*$this->limit)-$this->limit;
			$search=null;
			$wheres='';

			if(isset($_REQUEST['query_search'])){
				$wheres=$cond['search'];

			}else{
				$wheres=$cond['show'];
			}

			$sql="SELECT {$cond['select']} FROM {$table} {$wheres} LIMIT {$this->limit} OFFSET {$offset}";

			$q=$this->conn->query($sql) or die("Query Failed");
			
			echo '<table class="table table-striped table-bordered">
			      <thead class="table-primary">
			          <tr>
			            <td>ID</td>
			            <td>First Name</td>
			            <td>Middle Name</td>
			            <td>Last Name</td>
			            <td>Action</td>
			          </tr>
			      </thead>
			      <tbody>';
			if($q->rowCount()>0){
				$this->rowCount=$this->getAllPages($table,$wheres);
				
				while($result = $q->fetch(PDO::FETCH_ASSOC)){
					$td=null;

					foreach ($result as $value) {
						$td = $td.'<td>'.$value.'</td>';
					}
					//assign action buttons
					$td=$td.'<td>
                  		<a href="edit.php?uid='.$result['u_id'].'" class="btn btn-primary">Edit</a>
                  		<a href="delete.php?uid='.$result['u_id'].'" class="btn btn-danger">Delete</a>
                  		</td>';

					echo '<tr>'.$td.'</tr>';
				}
    			
			}else{
				echo '<tr><td colspan="100" align="center" class="table-danger">No Records Found</td></tr>';
			}
			echo '</tbody></table>';

			$this->paginate($table);
		}catch(PDOException $err_msg){
			echo $err_msg;
		}
	}


	public function getById($id,$table){
		try{
			$sql="SELECT * FROM {$table} WHERE u_id=:id";
			$q=$this->conn->prepare($sql) or die("Query Failed");
			$q->execute(array('id'=>$id)) or die("Query Failed");
			$data=[];

			if($q->rowCount()>0){
				$data=$q->fetch(PDO::FETCH_ASSOC);
			}
			return $data;

		}catch(PDOException $err_msg){
			echo $err_msg;
		}
	}

	public function insert($id,$fname,$mname,$lname,$table){
		try{
			$sql="INSERT INTO {$table} SET u_id=:id,fname=:fname,mname=:mname,lname=:lname";
			$q=$this->conn->prepare($sql) or die("Query Failed");
			if($q->execute(array('id'=>$id,'fname'=>$fname,'mname'=>$mname,'lname'=>$lname)) or die("Query Failed")){
				return true;
			}else{
				return false;
			}

		}catch(PDOException $err_msg){
			echo $err_msg;
		}
	}

	public function update($id,$fname,$mname,$lname,$table){
		try{
			$sql="UPDATE {$table} SET fname=:fname,mname=:mname,lname=:lname WHERE u_id=:id";
			$q=$this->conn->prepare($sql) or die("Query Failed");
			if($q->execute(array('id'=>$id,'fname'=>$fname,'mname'=>$mname,'lname'=>$lname)) or die("Query Failed")){
				return true;
			}else{
				return false;
			}

		}catch(PDOException $err_msg){
			echo $err_msg;
		}
	}

	public function delete($id,$table){
		try{
			$sql="DELETE FROM {$table} WHERE u_id=:id";
			$q=$this->conn->prepare($sql) or die("Query Failed");
			if($q->execute(array('id'=>$id)) or die("Query Failed")){
				return true;
			}else{
				return false;
			}

		}catch(PDOException $err_msg){
			echo $err_msg;
		}
	}
	
	public function register($fname,$mname,$lname,$email,$pwd,$c_pwd,$table){
		try{

			$sql_emailCheck="SELECT * FROM {$table} WHERE email=:email";
			$q_emailCheck=$this->conn->prepare($sql_emailCheck) or die("Query Failed");
			$q_emailCheck->execute(array('email'=>$email)) or die("Query Failed");
			$data=[];
			$data['msg']=[];
			$data['success']=true;
			if($q_emailCheck->rowCount()>0){
				$data['msg']['email']="Email Already Exist";
				$data['success']=false;
			}
			if($pwd!==$c_pwd){
				$data['msg']['pwd']="Password Did Not Match";
				$data['success']=false;
			}

			if($data['success']){
				$pwd=md5($pwd);
				$sql="INSERT INTO {$table} SET fname=:fname,mname=:mname,lname=:lname,email=:email,pwd=:pwd";
				$q=$this->conn->prepare($sql) or die("Query Failed");
				if($q->execute(array('fname'=>$fname,'mname'=>$mname,'lname'=>$lname,'email'=>$email,'pwd'=>$pwd)) or die("Query Failed")){
					$data['success']=true;
				}else{
					$data['success']=false;
				}
			}
			return $data;
		}catch(PDOException $err_msg){
			echo $err_msg;
		}
	}

	public function login($email,$pwd,$table){
		try{
			$pwd=md5($pwd);
			$sql="SELECT * FROM {$table} WHERE email=:email AND pwd=:pwd";
			$q=$this->conn->prepare($sql) or die("Query Failed");
			$q->execute(array('email'=>$email,'pwd'=>$pwd)) or die("Query Failed");
			$data=[];
			$data['msg']="";
			$data['success']=true;

			if($q->rowCount()<=0){
				$data['msg']="Invalid Email or Password!";
				$data['success']=false;
			}else{
				$data['msg']="";
				$data['success']=true;

				session_start();
				$_SESSION['login']=true;
				$_SESSION['email']=$email;
			}
			return $data;
		}catch(PDOException $err_msg){
			echo $err_msg;
		}
	}

	public function getAllPages($table,$wheres){
		try{
			$sql="SELECT * FROM {$table} {$wheres}";
			$q=$this->conn->query($sql) or die("Query Failed");
			if($q->rowCount()>0){
				return $q->rowCount();
			}
		}catch(PDOException $msg){
			echo $msg;
		}
	}
	public function paginate($table){
		$wheres='';
		$url_params='';

		$all=$this->rowCount;
		$pages=ceil($all/$this->limit);
		$paginate_pages=null;
		$next_btn=null;
		$prev_btn=null;

		if($this->cur_page<=1){
			$prev_btn="disabled";
		}

		if($this->cur_page>=$pages){
			$next_btn="disabled";
		}

		$prev=$this->cur_page-1;
		$next=$this->cur_page+1;

		$paginate_pages=$paginate_pages."
		<li class='page-item {$prev_btn}' aria-disabled='true' aria-label='First'>
		<button class='page-link' value='1' onclick='pager($(this).val())'>First</button>
		</li>
		<li class='page-item {$prev_btn}' aria-disabled='true' aria-label='« Previous'>
		<button class='page-link' value='{$prev}' onclick='pager($(this).val())'>‹</button>
		</li>";

		for ($page=1; $page <= $pages ; $page++) {
			$active="page-item";
			if($page==$this->cur_page){
				$active="page-item active";
				$paginate_pages=$paginate_pages."<li class='page-item active' aria-current='page'><input type='button' class='page-link' value='{$page}' disabled></li>";
			}else{
				$paginate_pages=$paginate_pages."<li class='page-item {$active}'><input type='button' class='page-link' value='{$page}' onclick='pager( $(this).val())' ></li></li>";
			}

			
		}

		$paginate_pages=$paginate_pages."
		<li class='page-item {$next_btn}' aria-disabled='true' aria-label='Next »'>
		<button class='page-link' value='{$next}' onclick='pager($(this).val())'>›</button>
		</li>
		<li class='page-item {$next_btn}' aria-disabled='true' aria-label='Last'>
		<button class='page-link' value='{$pages}' onclick='pager($(this).val())'>Last</button>
		</li>";

		if($pages>1){
			echo '<ul class="pagination" role="navigation">';
			echo $paginate_pages;
			echo '</ul>';	
		}
		

	}
}

?>