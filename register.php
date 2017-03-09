<?php
session_start();
require_once('class.user.php');
$user = new User();

if($user->is_logged_in())
{
	$user->redirect('home.php');
}

if(isset($_POST['btn-register']))
{
	$username = strip_tags($_POST['username']);
	$email = strip_tags($_POST['email']);
	$password = strip_tags($_POST['password']);
	$password2 = strip_tags($_POST['password2']);	
	
	if($username=="")	{
		$error[] = "Username required!";	
	}
	if($email=="")	{
		$error[] = "Email required!";	
	}
	else if(!filter_var($email, FILTER_VALIDATE_EMAIL))	{
	    $error[] = 'Please enter a valid email address!';
	}
	if($password=="")	{
		$error[] = "Password required!";
	}
	else if (strlen($password) < 6) {
		$error[] = "Password must be at least 6 characters!";
	}
	else if($password!=$password2)	{
		$error[] = "Passwords do not match!";
	}
	if(!isset($error))
	{
		try
		{
			$stmt = $user->query("SELECT username, email FROM users WHERE username=:uname OR email=:umail");
			$stmt->execute(array(':uname'=>$username, ':umail'=>$email));
			$row=$stmt->fetch(PDO::FETCH_ASSOC);
				
			if($row['username']==$username) {
				$error[] = "Username already registered!";
			}
			else if($row['email']==$email) {
				$error[] = "Email already registered!";
			}
			else
			{
				if($user->register($username,$email,$password)){	
					$user->redirect('index.php?joined');
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}	
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Twitter : Register</title>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="style.css" type="text/css"  />
</head>
<body>

<div class="signin-form">

<div class="container">
    	
        <form method="post" class="form-signin">
            <h2 class="form-signin-heading">Register</h2><hr />
            <?php
			if(isset($error))
			{
			 	foreach($error as $error)
			 	{
					 ?>
                     <div class="alert alert-danger">
                        <i class="glyphicon glyphicon-warning-sign"></i> &nbsp; <?php echo $error; ?>
                     </div>
                     <?php
				}
			}
			?>
            <div class="form-group">
            <input type="text" class="form-control" name="username" placeholder="Username" value="<?php if(isset($error)){echo $username;}?>" />
            </div>
            <div class="form-group">
            <input type="text" class="form-control" name="email" placeholder="Email" value="<?php if(isset($error)){echo $email;}?>" />
            </div>
            <div class="form-group">
            	<input type="password" class="form-control" name="password" placeholder="Password" />
            	<input type="password" class="form-control" name="password2" placeholder="Repeat password" />
            </div>
            <div class="clearfix"></div><hr />
            <div class="form-group">
            	<button type="submit" class="btn btn-default" name="btn-register">
                	<i class="glyphicon glyphicon-open-file"></i>Register
                </button>
            </div>
            <br />
            <label>Already registered? <a href="index.php">Sign in</a></label>
        </form>
       </div>
</div>

</div>

</body>
</html>