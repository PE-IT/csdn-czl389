<?php

	 //suppress notices
	error_reporting(E_ALL ^ E_NOTICE);
    // enable sessions
	session_start();
	// connect to database
	$conn=new mysqli("localhost", "root", "","test");
	$conn->query("set names utf8");
	if($conn->connect_errno)
	{
		die('Failed to connect to the database:'.mysqli_connect_error());
	}
	
    //判断学员姓名、电话是否填写
	if(isset($_POST["name"]) && isset($_POST["tel"]) && !empty($_POST["name"]) && !empty($_POST["tel"]))
	{		//此处存在BUG，因为大学生登录、家长注册都是用的SESSION，所以如果访问者做了双重行为，
		    //大学生页的退出就会清除家长注册的session，这种情况除非故意很少发生，即便发生也影响微小
			$_SESSION["appointed"]=true;

			$name =mysqli_real_escape_string($conn,$_POST["name"]);
			$grade =mysqli_real_escape_string($conn,$_POST["grade"]);
			$gender = mysqli_real_escape_string($conn,$_POST["gender"]);
			$address = mysqli_real_escape_string($conn,$_POST["address"]);
			$detailed = mysqli_real_escape_string($conn,$_POST["detailed"]);
			$tel = mysqli_real_escape_string($conn,$_POST["tel"]);
			if(!empty($_POST["subject"]))
			{
				$array=$_POST["subject"];
				$subjects=implode(',',$array);
			}
			else
				$subjects="";
			$timepay = mysqli_real_escape_string($conn,$_POST["timepay"]);  
			$want = mysqli_real_escape_string($conn,$_POST["want"]);
			date_default_timezone_set('prc');
			$time=date('y-m-d H:i:s',time());
			$succeed=0;

			 // prepare query
			$query = "INSERT INTO orders (name,grade,gender,address,detailed,tel,subject,timepay,want,time,succeed)
			 VALUES('$name','$grade','$gender','$address','$detailed','$tel','$subjects','$timepay','$want','$time','$succeed')";

			if ($conn->query($query) == FALSE) {
			die('INSERT attempt failed');
			}

			$query="SELECT LAST_INSERT_ID()";
			$result=$conn->query($query);
			$id=$result->fetch_row();
	}
	else
	{
		$host = $_SERVER["HTTP_HOST"];
		$path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
		header("Location: http://$host$path/?page=appoint");
	}
	$result->free();
	$conn->close();
?>