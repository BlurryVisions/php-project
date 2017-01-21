<?php

 ob_start();
 session_start();
 require 'dbh.php';
  if( isset($_SESSION['user'])!="" ){
        header("Location: login.php");
        exit;
  }
 
 $error = false;
 $emailError = 'should be a valid email id.';
 $passError = 'be tricky';
 $fpassError = '';

//register

 if ( isset($_POST['register']) ) {
  
  // clean user inputs to prevent sql injections

  $email = trim($_POST['email']);
  $email = strip_tags($email);
  $email = htmlspecialchars($email);
  
  $pass = trim($_POST['pass']);
  $pass = strip_tags($pass);
  $pass = htmlspecialchars($pass);
  $pass = filter_var($_POST['pass'] , FILTER_SANITIZE_STRING) ;
  
  if (empty($email)){
     $error = true;
     $emailError = "please enter email.";
   } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) )  {
     $error = true;
     $emailError = "invalid email address.";
   } 
  
  if (empty($pass)){
   $error = true;
   $passError = "please enter password.";
 } else if(strlen($pass) < 6) {
   $error = true;
   $passError = "minimum 6 characters.";
 }

  if( $error == false ) {
  	$sql = "INSERT INTO user (email, pass) VALUES (:email, :pass)";
	$stmt = $conn->prepare($sql);

    $stmt->bindParam(':email', $email);
	$stmt->bindParam(':pass', password_hash($pass, PASSWORD_BCRYPT));
    
   if( $stmt->execute() ):
		$_SESSION['user'] = $email;
		header("Location: home.php");
        unset($email);
        unset($pass);
	else:
	
    $logrecords = $conn->prepare('SELECT * FROM user WHERE email = :email');
	$logrecords->bindParam(':email', $_POST['email']);
    $logrecords->execute();
	$results = $logrecords->fetch(PDO::FETCH_ASSOC);
    $totalRows = $logrecords->rowCount();
	
	if($totalRows > 0 && password_verify($_POST['pass'], $results['pass'])){

		$_SESSION['user'] = $results['email'];
		header("Location: home.php");

	} else {

		$error = true;
        $emailError = "you're already registered ! :)";
        $passError = 'incorrect password tho :(';

	}

	endif;
    
   } 
} 

//login

if( isset($_POST['login']) ) { 

  $email = trim($_POST['email']);
  $email = strip_tags($email);
  $email = htmlspecialchars($email);
  
  $pass = trim($_POST['pass']);
  $pass = strip_tags($pass);
  $pass = htmlspecialchars($pass);
  $pass = filter_var($_POST['pass'] , FILTER_SANITIZE_STRING) ;
   
  if (empty($email)){
      $error = true;
      $emailError = "please enter email.";
    } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) )  {
     $error = true;
     $emailError = "invalid email address.";
   } 
 
   if(empty($pass)){
    $error = true;
    $passError = "Please enter your password.";
   }
  
  if ( $error == false ) {
 
    $records = $conn->prepare('SELECT * FROM user WHERE email = :email');
	$records->bindParam(':email', $_POST['email']);
    $records->execute();
	$results = $records->fetch(PDO::FETCH_ASSOC);
    $totalRows = $records->rowCount();
	
	if($totalRows > 0 && password_verify($_POST['pass'], $results['pass'])){

		$_SESSION['user'] = $results['email'];
		header("Location: home.php");

	} else {
		
        $emailError = 'credentials do not match';
        $passError = 'credentials do not match';

	}
  }
 }

if( isset($_POST['fpass']) ) { 

  $fpass = trim($_POST['forgot']);
  $fpass = strip_tags($fpass);
  $fpass = htmlspecialchars($fpass);

  if (empty($fpass)){
     $error = true;
     $fpassError = "please enter you registered email id.";
 } else if ( !filter_var($fpass,FILTER_VALIDATE_EMAIL) )  {
     $error = true;
     $fpassError = "invalid email address.";
 } 

if( $error == false ) {
  
  $frecords = $conn->prepare('SELECT * FROM user WHERE email = :email');
  $frecords->bindParam(':email', $_POST['forgot']);
  $frecords->execute();
  $results = $frecords->fetch(PDO::FETCH_ASSOC);
  $totalRows = $frecords->rowCount();

        if($totalRows > 0){

            $_SESSION['user'] =  $_POST['forgot']; 
            header("Location: forgot.php");
            
        }
        else{
        $fpassError = "no records found :(";
    }
    }
    
}

?>

<!DOCTYPE html>
<html>
<head>
<title>don't forget to</title>

    <link href="https://fonts.googleapis.com/css?family=Cagliostro" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assests/css/style.css">
    <link rel="stylesheet" type="text/css" href="assests/css/bubble.css">

    <meta charset="UFT-8">

    <meta name="Description" content="don't forget to do these things, we got your back'"/>
    <meta name="Keywords" content="to-do list, wishlist, list, to-do,to do list, online,task list"/>

</head>

<body>
    
    <div id="header">
        <h3>don't forget to..</h3>
    </div>
    
    <div class="content">
        <form id="logreg" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
            <div class="inbetweeen">login first..</div>

            <div>
                <label for="email"></label>
                <input type="email" id="email" placeholder="email id" name="email" maxlength="40" value="<?php echo $email ?>" required>
                <div class="bubble">
                    <p>
                        <?php echo $emailError; ?> 
                    </p>
                <div class="bubble-arrow bubble-arrowright active"></div>
                </div>
            </div>
           
            <div>
                <label for="paswword"></label>     
                <input type="password" id="password" placeholder="password" name="pass" maxlength="15" required>
                <div class="passbubble">
                    <p>
                        <?php echo $passError; ?> 
                    </p>
                <div class="bubble-arrow bubble-arrowright active"></div>
                </div>
            </div>
            
           
            <input type="submit" value="login" name="login">  
            <div class="divider"> or </div>
            <input type="submit" value="register" name="register">

        </form>

<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="assests/js/logreg.js"></script>

        <form id="flog" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
           
            <div class="inbetweeen">either of them works ;)</div>
            <div class="inbetweeen">'but not if you <a href="home.php">forgot your password</a> ↓</div>
            <div>
                
                <input type="email" id="email2" placeholder="enter your email id" name="forgot" required>
                
                <input type="submit" value="reset password" name="fpass">

                <label for="email2"><?php echo $fpassError; ?>
                
            </div> 

        </form>
          
     </div>
    <div class="push"> </div>
<div id="footer">
        <h3>don't forget to..</h3>
    </div>
    
    
    
</body>
</html>
<?php ob_end_flush(); ?>