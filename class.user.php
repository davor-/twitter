<?php

require_once('dbconfig.php');

class User
{	

	private $conn;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }
	
	public function query($sql)
	{
		$stmt = $this->conn->prepare($sql);
		return $stmt;
	}
	
	public function register($username,$email,$password)
	{
		try
		{
			$new_password = password_hash($password, PASSWORD_DEFAULT);
			
			$stmt = $this->conn->prepare("INSERT INTO users(username,email,password) 
		                                               VALUES(:uname, :umail, :upass)");
												  
			$stmt->bindparam(":uname", $username);
			$stmt->bindparam(":umail", $email);
			$stmt->bindparam(":upass", $new_password);										  
				
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}
	
	
	public function login($username,$email,$password)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT id, username, email, password FROM users WHERE username=:uname OR email=:umail ");
			
			$stmt->bindparam(":uname", $username);
			$stmt->bindparam(":umail", $email);
			$stmt->execute();	

			$user=$stmt->fetch(PDO::FETCH_ASSOC);
			if($stmt->rowCount() == 1)
			{
				if(password_verify($password, $user['password']))
				{
					$_SESSION['user_session'] = $user['id'];
					return true;
				}
				else
				{
					return false;
				}
			}
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function logout()
	{
		session_destroy();
		unset($_SESSION['user_session']);
		return true;
	}
	
	public function is_logged_in()
	{
		return isset($_SESSION['user_session']);
	}
	
	public function redirect($url)
	{
		header("Location: $url");
	}
}
?>