<?php

require_once('dbconfig.php');

class Tweet
{	
	private $conn;
	private $user;
	private $content;
	private $date;
	
	public function __construct()
	{
		$database = new Database();
		$db = $database->dbConnection();
		$this->conn = $db;
    }

    public function save($user,$content,$date)
	{
		try
		{
			
			$stmt = $this->conn->prepare("INSERT INTO tweets(user_id,content,date) 
		                                               VALUES(:user_id, :content, :date)");
												  
			$stmt->bindparam(":user_id", $user);
			$stmt->bindparam(":content", $content);
			$stmt->bindparam(":date", $date);										  
				
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}

	public function addComment($user,$tweet,$content,$date)
	{
		try
		{
			
			$stmt = $this->conn->prepare("INSERT INTO comments(user_id,tweet_id,content,date) 
		                                               VALUES(:user_id, :tweet_id, :content, :date)");
												  
			$stmt->bindparam(":user_id", $user);
			$stmt->bindparam(":tweet_id", $tweet);
			$stmt->bindparam(":content", $content);
			$stmt->bindparam(":date", $date);										  
				
			$stmt->execute();	
			
			return $stmt;	
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}				
	}

	public function getComments($tweet)
	{
		try
		{
			$stmt = $this->conn->prepare("SELECT comments.content,users.username, users.id FROM comments INNER JOIN tweets ON comments.tweet_id=tweets.id INNER JOIN users ON users.id=comments.user_id WHERE tweet_id=:tweet ORDER BY comments.date ASC;");
			$stmt->bindparam(":tweet", $tweet);
			$stmt->execute();
			$comments=$stmt->fetchAll(PDO::FETCH_ASSOC);

			return $comments;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}

	public function all()
	{
		try
		{
			if(isset($_GET['user']))
			{
				$stmt = $this->conn->prepare("SELECT id,content,user_id FROM tweets WHERE user_id=:user ORDER BY date DESC;");
				$stmt->bindparam(":user", $_GET['user']);
			}
			else
			{
				$stmt = $this->conn->prepare("SELECT id,content,user_id FROM tweets ORDER BY date DESC;");
			}
			$stmt->execute();
			$tweets=$stmt->fetchAll(PDO::FETCH_ASSOC);

			return $tweets;
		}
		catch(PDOException $e)
		{
			echo $e->getMessage();
		}
	}
}