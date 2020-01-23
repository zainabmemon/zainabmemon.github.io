<?php
	session_start();
	if(isset($_SESSION['username']) and isset($_GET['user']))
	{
		if(isset($_POST['text']))
		{
			if($_POST['text'] != '')
			{
					$sender_name=$_SESSION['username'];
					$reciever_name=$_GET['user'];
					$message = $_POST['text'];
					$date = date("Y-m-d h:i:sa");
					
				$con=mysqli_connect("localhost","root","","project");	
				$q='insert into messages VALUES("'.$sender_name.'","'.$reciever_name.'","'.$message.'","'.$date.'")';
				$r=mysqli_query($con,$q);
						if($r)
						{
							?>
								<div class="grey-msg">
								<a href="#">Me</a>
								<p><?php echo $message;?></p>
								</div>
								<?php
						}
						else
						{
							echo $q;
						}
				
			}
			else
			{
				echo 'Please write something first';
			}
			
		}
		else
		{
			echo 'Problem with text';
		}
		
		}
	else{
		echo 'Please login or select a user to send a message';
		
	}

?>