<?php
 ob_start();
 session_start();
 require_once 'dbh.php';
 
 $user = $_SESSION['user'];
 $empty = "nothing to do do yet :p";
 $done="0";

 if( !isset($_SESSION['user']) ) {
  header("Location: login.php");
  exit;
 }

    $workquery = $conn->prepare("SELECT * FROM work WHERE email = :email AND done = :done");
    $workquery->bindParam(':email', $user);
    $workquery->bindParam(':done', $done);
    $workquery->execute();
    $workresults = $workquery->fetchAll(PDO::FETCH_ASSOC);
    $worktotalRows = $workquery->rowCount();

    $persquery = $conn->prepare("SELECT * FROM personal WHERE email = :email AND done = :done");
    $persquery->bindParam(':email', $user);
    $persquery->bindParam(':done', $done);
    $persquery->execute();
    $persresults = $persquery->fetchAll(PDO::FETCH_ASSOC);
    $perstotalRows = $persquery->rowCount();
 
if ( isset($_POST['work']) ) {

    $title = trim($_POST['title']);
    $descrip = trim($_POST['description']);
    $type = "work";

    $workinsert = $conn->prepare("INSERT INTO work (title, descrip, email , type, done) VALUES (:title, :descrip, :email, :type, :done)");
    $workinsert->bindParam(':title', $title);
    $workinsert->bindParam(':descrip', $descrip);
    $workinsert->bindParam(':email', $user);
    $workinsert->bindParam(':type', $type);
    $workinsert->bindParam(':done', $done);
    $workinsert->execute();
    
    $workquery->execute();
    $workresults = $workquery->fetchAll(PDO::FETCH_ASSOC);
    $worktotalRows = $workquery->rowCount();


}

if ( isset($_POST['personal']) ) {

    $title = trim($_POST['title']);
    $descrip = trim($_POST['description']);
    $type = "personal";

    $persinsert = $conn->prepare("INSERT INTO personal (title, descrip, email , type, done) VALUES (:title, :descrip, :email, :type, :done)");
    $persinsert->bindParam(':title', $title);
    $persinsert->bindParam(':descrip', $descrip);
    $persinsert->bindParam(':email', $user);
    $persinsert->bindParam(':type', $type);
    $persinsert->bindParam(':done', $done);
    $persinsert->execute();
  
    $persquery->execute();
    $persresults = $persquery->fetchAll(PDO::FETCH_ASSOC);
    $perstotalRows = $persquery->rowCount();


}

if ( isset($_POST['wdelete']) ) {

    $title = $_POST['htitle'];
    $wdelquery = $conn->prepare("UPDATE work SET done = 1 WHERE email = :email AND title = :title");
    $wdelquery->bindParam(':email', $user);
    $wdelquery->bindParam(':title', $title);
    $wdelquery->execute();

    $workquery->execute();
    $workresults = $workquery->fetchAll(PDO::FETCH_ASSOC);
    $worktotalRows = $workquery->rowCount();

}

if ( isset($_POST['pdelete']) ) {

    $title = $_POST['htitle'];
    $pdelquery = $conn->prepare("UPDATE personal SET done = 1 WHERE email = :email AND title = :title");
    $pdelquery->bindParam(':email', $user);
    $pdelquery->bindParam(':title', $title);
    $pdelquery->execute();

    $persquery->execute();
    $persresults = $persquery->fetchAll(PDO::FETCH_ASSOC);
    $perstotalRows = $persquery->rowCount();

}

if ( isset($_POST['wupdate']) ) {

    $title = $_POST['htitle'];
    $ntitle = $_POST['title'];
    $ndescrip = $_POST['description'];
    $wupdquery = $conn->prepare("UPDATE work SET title = :ntitle, descrip = :ndescrip WHERE email = :email AND title = :title");
    $wupdquery->bindParam(':email', $user);
    $wupdquery->bindParam(':title', $title);
    $wupdquery->bindParam(':ntitle', $ntitle);
    $wupdquery->bindParam(':ndescrip', $ndescrip);
    $wupdquery->execute();

    $workquery->execute();
    $workresults = $workquery->fetchAll(PDO::FETCH_ASSOC);
    $worktotalRows = $workquery->rowCount();

}

if ( isset($_POST['pupdate']) ) {

    $title = $_POST['htitle'];
    $ntitle = $_POST['title'];
    $ndescrip = $_POST['description'];
    $pupdquery = $conn->prepare("UPDATE personal SET title = :ntitle, descrip = :ndescrip WHERE email = :email AND title = :title");
    $pupdquery->bindParam(':email', $user);
    $pupdquery->bindParam(':title', $title);
    $pupdquery->bindParam(':ntitle', $ntitle);
    $pupdquery->bindParam(':ndescrip', $ndescrip);
    $pupdquery->execute();

    $persquery->execute();
    $persresults = $persquery->fetchAll(PDO::FETCH_ASSOC);
    $perstotalRows = $persquery->rowCount();

}

$keyword = $_GET['keyword'];

if ( isset($_GET['search']) ) {
  
    $wsearchquery = $conn->prepare("SELECT * FROM work WHERE title LIKE '%".$keyword."%'");
    $wsearchquery->execute();
    $wsearchtotalRows = $wsearchquery->rowCount();
    $wsearchresults = $wsearchquery->fetchAll(PDO::FETCH_ASSOC);

    $psearchquery = $conn->prepare("SELECT * FROM personal WHERE title LIKE '%".$keyword."%'");
    $psearchquery->execute();
    $psearchtotalRows = $psearchquery->rowCount();
    $psearchresults = $psearchquery->fetchAll(PDO::FETCH_ASSOC);
    
}

if ( isset($_POST['wredo']) ) {

    $title = $_POST['htitle'];
    $wdelquery = $conn->prepare("UPDATE work SET done = 0 WHERE email = :email AND title = :title");
    $wdelquery->bindParam(':email', $user);
    $wdelquery->bindParam(':title', $title);
    $wdelquery->execute();

    $workquery->execute();
    $workresults = $workquery->fetchAll(PDO::FETCH_ASSOC);
    $worktotalRows = $workquery->rowCount();

}

if ( isset($_POST['predo']) ) {

    $title = $_POST['htitle'];
    $pdelquery = $conn->prepare("UPDATE personal SET done = 0 WHERE email = :email AND title = :title");
    $pdelquery->bindParam(':email', $user);
    $pdelquery->bindParam(':title', $title);
    $pdelquery->execute();

    $persquery->execute();
    $persresults = $persquery->fetchAll(PDO::FETCH_ASSOC);
    $perstotalRows = $persquery->rowCount();

}

?>
<!DOCTYPE html>
<html>
<head>
    <title>don't forget to</title>

    <link href="https://fonts.googleapis.com/css?family=Cagliostro" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="assests/css/todo.css">
    <link rel="stylesheet" type="text/css" href="assests/css/modal.css">
 
    <meta charset="UFT-8">

    <meta name="Description" content="don't forget to do these things, we got your back'"/>
    <meta name="Keywords" content="to-do list, wishlist, list, to-do,to do list, online,task list"/>    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>
    
    <div id="header">
        <h3>don't forget to..</h3>
    </div>
    
    <div class="content">
         <div class="info">
            <div><p> hello <?php echo $user; ?> </p></div> 
            <div><p><a href="logout.php">Sign Out</a></p></div>
        </div>

        <div class="board">
            
                <div class="type">

                    <div class="heads">
                        <label>WORK</label>
                    </div>

                    <form id="waddin" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="addinputs">
            
                        <div class="writenadd">
                        <div class="title"><textarea rows="1" placeholder="title" name="title" maxlength="140" required></textarea></div>
                        <div class="textarea"><textarea rows="3" placeholder="got more detail ?" name="description" maxlength="240"></textarea></div>
                        <div class="add"><input type="submit" value="add to worklist" name="work" class="work"></div>
                        </div>

                    </div>
                    </form>

                    
                    <div class="display">
                        
                        <?php if(!empty($worktotalRows)): ?>
                        <ul class="items">
                            <div class="listcontent">

                                <?php foreach($workresults as $item): ?>
                                <?php $title = $item['title']; ?>
                                <?php $descrip = $item['descrip']; ?>
                                <div class="boxnlabel">
                                <form id="showde" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                <div class="box"><input type="checkbox" value="<?php echo $title; ?>" name="htitle"></div>
                                <div class="labels">                                                                    
                                    <div class="titlelabel"><textarea rows="1" name="title" value="<?php echo $title; ?>" maxlength="140" required><?php echo $title; ?></textarea></div>                                    
                                    <div class="descriplabel"><textarea rows="3" name="description" value="<?php echo $descrip; ?>" maxlength="240"><?php echo $descrip; ?></textarea></div>
                                        <div class="options">
                                            <div><input type="submit" value="update" name="wupdate"></div>
                                            <div><input type="submit" value="delete" name="wdelete"></div>
                                        </div>
                                </div>    
                                </div>
                                </form> 
                                <?php endforeach; ?>
                            </div>    
                        </ul>
                        <?php endif; ?>
                    </div>
                                               
                </div>

                <div class="type">

                    <div class="heads">
                        <label>PERSONAL</label>
                    </div>                            
                    <form id="paddin" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="addinputs">
            
                        <div class="writenadd">
                        <div class="title"><textarea rows="1" placeholder="title" name="title" maxlength="140" required></textarea></div>
                        <div class="textarea"><textarea rows="3" placeholder="got more detail ?" name="description" maxlength="240"></textarea></div>
                        <div class="add"><input type="submit" value="add to personallist" name="personal" class="personal"></div>
                        </div>

                    </div>
                    </form>

                    <div class="display">  

                        <?php if(!empty($perstotalRows)): ?>
                        <ul class="items">
                            <div class="listcontent">
                                <?php foreach($persresults as $item): ?>
                                <?php $title = $item['title']; ?>
                                <?php $descrip = $item['descrip']; ?>
                                <div class="boxnlabel">
                                <form id="wshow" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                <div class="box"><input type="checkbox" value="<?php echo $title; ?>" name="htitle"></div>
                                <div class="labels">
                                    <div class="titlelabel"><textarea rows="1" name="title" maxlength="140" required><?php echo $title; ?></textarea></div>                                    
                                    <div class="descriplabel"><textarea rows="3" name="description" maxlength="240"><?php echo $descrip; ?></textarea></div>
                                    <div class="options">
                                            <div><input type="submit" value="update" name="pupdate"></div>
                                            <div><input type="submit" value="delete" name="pdelete"></div>
                                    </div>
                                </div>    
                            </div>
                            </form>
                            <?php endforeach; ?>
                            </div>   
                        </ul>
                        <?php endif; ?>

                    </div>
                                                
                </div>
                
                <div class="type">

                    <div class="heads">
                        <label>STILL LOST ?</label>
                    </div>                            
                    <form id="search" method="GET" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="otherinfo">
                        <div class="inbetweeen"><a href=forgot.php>want to change your password ?</a></div>
                        <div class="inbetweeen"> get in touch with us at dont@forgetto.com</div>
                        
                    </div>
                    <div class="addinputs">

                        <div class="inbetweeen">search your list.</div>
                        <div class="writenadd">
                        <div class="title"><input type ="search" placeholder="title of the task" name="keyword" maxlength="140" autocomplte="off" required></div>
                        <div class="add"><input type="submit" value="search history" name="search" class="search"></div>
                        </div>
                        
                    </div>
                    </form>


                    <div class="display">

                        <?php if(!empty($wsearchtotalRows)): ?>
                        <ul class="items">
                            <div class="listcontent">
                                <?php foreach($wsearchresults as $wlist): ?>
                                <?php $wtitle = $wlist['title']; ?>
                                <?php $wdescrip = $wlist['descrip']; ?>
                                <div class="boxnlabel">
                                <form id="wsshow" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                <div class="box"><input type="checkbox" value="<?php echo $wtitle; ?>" name="htitle"></div>
                                <div class="labels">
                                    <div class="titlelabel"><textarea rows="1" name="title" maxlength="140" required><?php echo $wtitle; ?></textarea></div>                                    
                                    <div class="descriplabel"><textarea rows="3" name="description" maxlength="240"><?php echo $wdescrip; ?></textarea></div>                                    
                                    <div class="options">
                                            <?php if($wlist['done'] == "1") : ?>
                                            <div><input type="submit" value="wanna re-do it ?" name="wredo"></div>
                                            <?php else :?>
                                            <div><label>not done yet :(</label></div>
                                            <?php endif; ?>
                                    </div>
                                </div>    
                            </div>
                            </form>
                            <?php endforeach; ?>
                            </div>   
                        </ul>
                        <?php endif; ?>
                   
                        <?php if(!empty($psearchtotalRows)): ?>
                        <ul class="items">
                            <div class="listcontent">
                            <?php foreach($psearchresults as $plist): ?>
                                <?php $ptitle = $plist['title']; ?>
                                <?php $pdescrip = $plist['descrip']; ?>
                                <div class="boxnlabel">
                                <form id="psshow" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                <div class="box"><input type="checkbox" value="<?php echo $ptitle; ?>" name="htitle"></div>
                                <div class="labels">
                                    <div class="titlelabel"><textarea rows="1" name="title" maxlength="140" required><?php echo $ptitle; ?></textarea></div>                                    
                                    <div class="descriplabel"><textarea rows="3" name="description" maxlength="240"><?php echo $pdescrip; ?></textarea></div>  
                                    <div class="options">
                                            <?php if($plist['done'] == "1") : ?>
                                            <div><input type="submit" value="wanna re-do it ?" name="wredo"></div>
                                            <?php else :?>
                                            <div><label>not done yet :(</label></div>
                                            <?php endif; ?>
                                    </div>
                                </div>    
                            </div>
                            </form>
                            <?php endforeach; ?>
                            </div>   
                        </ul>
                        <?php endif; ?>                                     
                    </div>
                    
                </div>              
        </div>
    </div>


    <div id="footer">
        <h3>don't forget to..</h3>
    </div>
       
</body>
</html>
<?php ob_end_flush(); ?>