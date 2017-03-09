<?php
session_start();
require_once("class.user.php");
$user = new User();

if($user->is_logged_in())
{
	$user->redirect('home.php');
}

if(isset($_POST['btn-login']))
{
	$username = strip_tags($_POST['username_email']);
	$email = strip_tags($_POST['username_email']);
	$password = strip_tags($_POST['password']);
		
	if($user->login($username,$email,$password))
	{
		$user->redirect('home.php');
	}
	else
	{
		$error = "Incorrect username or password!";
	}	
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Twitter : Login</title>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="style.css" type="text/css"  />
</head>
<body>

<div class="signin-form">

	<div class="container">
     
        
       <form class="form-signin" method="post" id="login-form">
      
        <h2 class="form-signin-heading">Sign in to Twitter</h2><hr />
        
        <div id="error">
        <?php
			if(isset($error))
			{
				?>
                <div class="alert alert-danger">
                   <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?> !
                </div>
                <?php
			}
            else if(isset($_GET['joined']))
            {
                 ?>
                 <div class="alert alert-info">
                      <i class="glyphicon glyphicon-log-in"></i> &nbsp; Successfully registered!
                 </div>
                 <?php
            }
		?>
        </div>
        
        <div class="form-group">
        <input type="text" class="form-control" name="username_email" placeholder="Username or email" required />
        <span id="check-e"></span>
        </div>
        
        <div class="form-group">
        <input type="password" class="form-control" name="password" placeholder="Password" />
        </div>
       
     	<hr />
        
        <div class="form-group">
            <button type="submit" name="btn-login" class="btn btn-default">
                	<i class="glyphicon glyphicon-log-in"></i> Sign in
            </button>
        </div>  
      	<br />
            <label>Don't have account? <a href="register.php">Register</a></label>
      </form>

    </div>
    
</div>

</body>
</html>