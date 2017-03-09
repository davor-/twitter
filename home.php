<?php

	require_once("session.php");
  require_once("class.tweet.php");

	$auth_user = new User();
  $tweet = new Tweet();
	
	$id = $_SESSION['user_session'];
	
	$stmt = $auth_user->query("SELECT * FROM users WHERE id=:id");
	$stmt->execute(array(":id"=>$id));
	
	$user=$stmt->fetch(PDO::FETCH_ASSOC);

  if(isset($_POST['btn-post']))
  {
    $user_id = $id;
    $content = $_POST['tweet'];
    $date = date('Y-m-d H:i:s');

    if ($content){
      if($tweet->save($user_id,$content,$date)){  
        $auth_user->redirect('home.php');
      }
    }
    

  }

  if(isset($_POST['btn-comment']))
  {
    $user_id = $id;
    $tweet_id = $_POST['tweet_id'];
    $content = $_POST['comment'];
    $date = date('Y-m-d H:i:s');
    if ($content){
      if($tweet->addComment($user_id,$tweet_id,$content,$date)){  
        $auth_user->redirect('home.php');
      }
    }

  }
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
  <link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
  <script type="text/javascript" src="jquery-3.1.1.min.js"></script>
  <script src='//cloud.tinymce.com/stable/tinymce.min.js'></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script>
  tinymce.init({
    selector: '#mytextarea'
  });
  </script>
  <link rel="stylesheet" href="style.css" type="text/css"  />
  <title>Twitter home</title>
</head>

<body>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
      <ul class="nav navbar-nav navbar-right"> 
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
	         <span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo $user['username']; ?>&nbsp;<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="home.php?user=<?php echo $id; ?>"><span class="glyphicon glyphicon-user"></span>&nbsp;View my tweets</a></li>
            <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Sign Out</a></li>
          </ul>
        </li>
      </ul>
  </div>
</nav>

    
<div class="container-fluid" style="margin-top:80px;">
	
    <div class="container">
    
    	<label class="h3">Welcome <?php print($user['username']); ?></label>
        <hr />
        <a href="home.php"><span class="glyphicon glyphicon-home"></span> Home</a></h1>
        <br />
        <button type="button" class="btn btn-info" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
          <span class="glyphicon glyphicon-pencil"></span> Write a new Tweet
        </button>
        <hr />
       
       
        <div class="collapse" id="collapseExample">
          <form method="post">
            <textarea id="mytextarea" name="tweet" ></textarea>
            <button type="submit" class="btn btn-info" name="btn-post">
              <i class="glyphicon glyphicon-send"></i>&nbsp; Post
            </button>
          </form>

         <br />
        </div>

        <div class="list-group">
          <?php 
            $tweets = $tweet->all();
            foreach($tweets as $t){
              $stmt = $auth_user->query("SELECT username,id FROM users WHERE id=:id");
              $stmt->execute(array(":id"=>$t['user_id']));
              $author=$stmt->fetch(PDO::FETCH_ASSOC);
              $comments = $tweet->getComments($t['id']);
              
              echo "<h2><span class='label label-success'>User <a href='home.php?user=".$author['id']."'>@". $author['username']. "</a> said:</span>";
              echo '<a class="list-group-item" data-toggle="collapse" data-target="#comments'.$t['id'].'" aria-expanded="false" aria-controls="comments'.$t['id'].'">'.$t['content'].'<span class="badge">'.count($comments).'</span></a></h2>';
              
              echo '
              <div id="comments'.$t['id'].'" class="collapse">
                <form method="post">
                <ul class="list-group"> ';
                  foreach($comments as $com){
                    echo '<li class="list-group-item"><a href="home.php?user='.$com['id'].'"">@'. $com['username'].'</a>: '.$com['content'].'</li>';
                  }
              echo '  </ul>
                <textarea class="form-control" name="comment"></textarea>
                <input type="hidden" name="tweet_id" value="'.$t['id'].'" />
                <button type="submit" class="btn btn-info" name="btn-comment">Comment</button>
                </form>
              </div>
              <br />
              ';
            }
          ?>
        </div>
</div>


</body>
</html>