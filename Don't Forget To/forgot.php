<?php

ob_start();
session_start();
require 'dbh.php';


$error = false;

if( isset($_POST['reset']) ) { 

  $fpass1 = trim($_POST['pass1']);
  $fpass1 = strip_tags($fpass1);
  $fpass1 = htmlspecialchars($fpass1);

  $fpass2 = trim($_POST['pass2']);
  $fpass2 = strip_tags($fpass2);
  $fpass2 = htmlspecialchars($fpass2);

 if (empty($fpass1)) {
     $error = true;
     $fpassError = "cannot be empty";
 }
 if (empty($fpass2)) {
     $error = true;
     $fpassError = "cannot be empty";
 } 

if( $error == false ) {
  
  $nrecords = $conn->prepare('SELECT * FROM user WHERE email = :email');
  $nrecords->bindParam(':email', $_SESSION['user']);
  $nrecords->execute();
  $results = $nrecords->fetch(PDO::FETCH_ASSOC);
  $totalRows = $nrecords->rowCount();

        if($totalRows > 0){

            $crecords = $conn->prepare("UPDATE user SET pass = :pass WHERE email = :email");
            $crecords->bindParam(':email', $_SESSION['user']);
            $crecords->bindParam(':pass', password_hash($fpass2, PASSWORD_BCRYPT));
            $crecords->execute();
            
            

            header("Location: home.php");

        }
        else {
            
            header("Location: https://www.google.co.in/webhp?sourceid=chrome-instant&ion=1&espv=2&ie=UTF-8#q=eye%20check%20up");
            
        }
        
    }
    else{
            header("Location: logout.php");
    }
  }


?>

<!DOCTYPE html>
<html>
<head>
    <title>don't forget to</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assests/css/forgot.css">

    <meta charset="UFT-8">
    
    <meta name="Description" content="don't forget to do these things, we got your back'"/>
    <meta name="Keywords" content="to-do list, wishlist, list, to-do,to do list, online,task list"/>

</head>
<body>
    
    <div id="header">
        <h3>don't forget to...</h3>
    </div>
    
    <div class="content">

        <form id="cpass" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        
        <div class="inbetweeen">reset your password..</div>
        
        <input type="text" placeholder="new password" name="pass1" maxlength="15" required>
        <div class="label"><label><?php echo $totalRows; ?></label></div>
        <input type="text" placeholder=" confirm password" name="pass2" maxlength="15" required>
        <input type="submit" value="reset" name="reset">
        <div class="inbetweeen">still facing issues ? write in us at dont@forgetto.com</div> 
        
        </form>
        
    </div>
    <div class="push"> </div>
    <div id="footer">
        <h3>don't forget to..</h3>
    </div>
       
</body>
</html>
<?php ob_end_flush(); ?>
