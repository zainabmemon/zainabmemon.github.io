<?php 
  session_start(); 
if(isset($_SESSION['username'])){
	//echo "how are you ".$_SESSION['username'];
	//echo '<a href="logout.php">LogOut</a>';
?>
<!doctype html>
<html>
	<head> 
		<link rel="stylesheet" type="text/css" href="style1.css">
		<style>
		
#container{
	box-shadow:2px 2px 10px #000000;
	width:1350px;
	margin:2% auto;
	height:89%;
	border-radius:1%;
	overflow:hidden;
	}
#menu{
	background:#233070;
	color:white;
	padding:1%;
	font-size:30px;
}
#left-col,#right-col{
	position:realtive;
	float:left;
	height:90%;
}
#left-col{
	width:30%;
}
#right-col{
	width:69.8%; border:1px solid blue;
}
#left-col-container,#right-col-container{
	width:100%;
	height:100%;
margin:0px auto;height:100%;overflow:auto;
}
.grey-back{
	border:1px solid black;padding:5px;background-color:lightgrey;
	margin:0px auto;margin-top:2px;overflow:auto;
}
.image{
	float:left; 
	margin-right:5px;
	width:50px;
	height:50px;
}
#message-container{
	height:85%;
	overflow:auto;
}
.textarea{
	width:99%;
	height:10%;
	bottom:1%;
	margin-top:15px;
}
.grey-msg,.white-msg{
	border:1px solid black;
	width:96%;
	padding:5px;
	margin:0px auto;
	margin-top:2px;
	overflow:auto;
}
.grey-msg{
	background:#efefef;
}
#new-message{
	display: none;
	box-shadow: 2px 10px 30px black;
	background: white;
	width:500px;
	position: fixed;
	top: 10%;
	z-index: 2;
	left: 50%;
	transform: translate(-50%,0);
	border-radius: 5px;
	overflow: auto;
}
.m-header, .m-footer
{
background: midnightblue;
margin: 0px;
color: white;
padding: 5px

}
.m-header{
	font-size: 20px;
	text-align: center;
}
.m-body{
	padding: 5px;
}
.message-input{
	width: 96%;
}
		</style>
<script src="JQuery.js"></script>		

<script>
//document.getElementById("send").disabled=true;
function check_in_DB(){
	
	var user_name=document.getElementById("user_name").value;
	$.post("check_in_DB.php",
	{
		user:user_name
	},
	function(data,status){
		//alert(data);
		//document.getElementById('user').innerHTML=data;
		if(data=='<option value="no user">'){
			document.getElementById('send').disabled=true;
		}else{
			document.getElementById('send').disabled=false;
		}
	}
	);
}	
</script>
	</head>	
	<body>
		<div id="new-message">
			<p class="m-header">New Message
			</p>
			<p class="m-body">
			<form align="center" method="post">
			<input type="text" list="user" onkeyup="check_in_DB()" class="message-input" name="user_name" id="user_name" placeholder="To"/><br/><br/>
			<datalist id="user"></datalist>
			<br/><br/>
			<textarea class="message-input" name="message" placeholder="Message"></textarea><br/><br/>
			<input type="submit" name="send" value="Send" id="send"/><br/><br/>
			<button onclick="document.getElementById('new-message').style.display='none'">Cancel</button>
			</form>
			</p>
			<p class="m-footer">Click send Message</p>
		</div>
<?php
		$con=mysqli_connect("localhost","root","","project");
		if(isset($_POST['send'])){
			$sender_name=$_SESSION['username'];
			$reciever_name=$_POST['user_name'];
			$message=$_POST['message'];
			$date=date("Y-m-d h:i:sa");
			$q='insert into messages VALUES("'.$sender_name.'","'.$reciever_name.'","'.$message.'","'.$date.'")';
		$r=mysqli_query($con,$q);
		if($r){
			header("location:index.php?user=".$reciever_name);
			}
		else{echo $q;}
		}

?>
		<div id="container">
			<div id="menu">
			<?php
			echo $_SESSION['username'];
			echo '<a style="float:right;
							color:white;"
				 href="logout.php">LogOut</a>';
			?>
			</div>
	<div id="left-col">
	<?php
//session_start();
	$con=mysqli_connect("localhost","root","","project");
				
	$q='Select DISTINCT receiver,sender from messages where sender="'.$_SESSION['username'].'" or
	receiver="'.$_SESSION['username'].'"
	order by date desc
	';
					
		$r=mysqli_query($con,$q);
		if($r){
			if(mysqli_num_rows($r)>0){
				$added_user = array();					
				$counter = 0;
				while($row=mysqli_fetch_assoc($r)){
					$sender_name=$row['sender'];
					$reciever_name=$row['receiver'];
							if($_SESSION['username']==$sender_name){
								
								if(in_array($reciever_name,$added_user)){
								
							}
							else
							{
								?>
								<div class="grey-back">
								<img src="profilepic.png" class="image"/>
								<?php echo '<a href="?user='.$reciever_name.'">'.$reciever_name.'</a>'; ?>
								</div>
								<?php
								
								$added_user=array($counter => $reciever_name);
								$counter++;
							}
						
							}
							elseif($_SESSION['username']==$reciever_name){
								
								if(in_array($sender_name,$added_user)){
								
								}
								else{
									?>
									<div class="grey-back">
									<img src="profilepic.png" class="image"/>
									<?php echo'<a href="?user='.$sender_name.'">'.$sender_name.'</a>'; ?>
									</div>
									<?php
									
									$added_user=array($counter => $sender_name);
									$counter++;
								}
								
							}
					
					}
				
			}
			else{ echo "No user";}
				
		}else{echo $q;}
		?>
		<div id="left-col-container">

		<div class="white-back" onclick="document.getElementById('new-message').style.display='block'">
		<p align="center">New Message</p>
		</div>		
	
					
			

					
					
					
					
				</div>
			</div>
			<div id="right-col">
				<div id="right-col-container">
					<div id="message-container">
					<?php
					$no_message= false;
					$con=mysqli_connect("localhost","root","","project");
					if(isset($_GET['user'])){
						$_GET['user']=$_GET['user'];
					}
					else{
						$q='select sender,receiver from messages
						where sender="'.$_SESSION['username'].'"
						or receiver="'.$_SESSION['username'].'"
						order by date DESC LIMIT 1';
						
						$r=mysqli_query($con,$q);
						if($r){
							if(mysqli_num_rows($r)>0){
								while($row=mysqli_fetch_assoc($r)){
									$sender_name=$row['sender'];
									$reciever_name=$row['receiver'];
									
									if($_SESSION['username']==$sender_name){
										$_GET['user']=$reciever_name;
									}else{
										$_GET['user']=$sender_name;
									}
								}
							}
							else{echo "no message";
							$no_message = true;
							}
						}else{ echo $q;}
						
					}
					if($no_message ==false){
					$q='select * from messages where sender="'.$_SESSION['username'].'" 
					and receiver="'.$_GET['user'].'"
					or 
					sender="'.$_GET['user'].'" 
					and receiver="'.$_SESSION['username'].'" ';
					$r=mysqli_query($con,$q);
					if($r){
						while($row=mysqli_fetch_assoc($r)){
							$sender_name=$row['sender'];
							$reciever_name=$row['receiver'];
							$message=$row['message'];
							if($sender_name==$_SESSION['username'])
							{?>
						<div class="grey-msg">
						<a href="#">Me</a>
						<p><?php echo $message;?></p>
						</div>
						<?php
								
							}
							else{
								?>
						<div class="white-msg">
						<a href="#"><?php echo $sender_name;?></a>
						<p><?php echo $message;?></p>
						</div>
								<?php
							}
						}
					}else{echo $q;}
					}
					?>
						
						
					</div>
				<form method="post" id="message-form">	
				<textarea placeholder="Type your message..." class="textarea" id="message_text"></textarea>
				</form>
				</div>
				<script>
				
				$("document").ready(function(event){
					
					$("#right-col-container").on('submit','#message-form',function(){
					var message_text=$("#message_text").val();
					
					$.post("sending_process.php?user=<?php echo $_GET['user'];?>",{
							text:message_text,
						},
						function(data,status){
							//alert(data);
							$("#message_text").val("");
							document.getElementById("#message-container").innerHTML +=data;
						}
						);
					
					});
					
					
					$("#right-col-container").keypress(function(e){
						if(e.keyCode==13 && !e.shiftKey){
						$("#message-form").submit();
					}
						
					});
				});
					
				</script>
			</div>
		</div>
<script>document.getElementById('send').disabled=true;</script>	


	</body>
</html>
<?php
}
  else {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  
?>
