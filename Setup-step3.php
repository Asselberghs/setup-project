<?php
include('ErrorControl.php');
include('Connect.php');

$scriptuser = $_POST['scriptuser'];
$scriptpassword = $_POST['scriptpass'];
$scriptpasswordconfirm = $_POST['scriptpassconfirm'];
$scriptemail = $_POST['Email'];


$scriptuserErrCheck = ErrorControl($scriptuser);
$scriptpassErrCheck = ErrorControl($scriptpassword);
$scriptpassconfirmErrCheck = ErrorControl($scriptpasswordconfirm);
$scriptemailErrCheck = ErrorControl($scriptemail);

if($scriptuserErrCheck == TRUE || $scriptpassErrCheck == TRUE || $scriptpassconfirmErrCheck == TRUE || $scriptemailErrCheck == TRUE) {
	            
	            $ErrCheck = TRUE;
            }
			
if($_POST['submit'] && $_POST['scriptuser'] != '' && $_POST['scriptpass'] != '' && $_POST['scriptpassconfirm'] != '' && $_POST['Email'] != '' && $ErrCheck != TRUE) {

	if($_POST['scriptpass'] != $_POST['scriptpassconfirm']) {
	           
	           echo 'Passwords for the user in the database dosen�t match try again <br /><br />';
	           
    }
		   
	$create_movie_table_query = 'CREATE TABLE Movie 
            	    (
            	    ID int(11) NOT NULL AUTO_INCREMENT,
            	    PRIMARY KEY(ID),
            	    Title text NOT NULL,
            	    Format varchar(8) NOT NULL,
            	    Production_Year int(11) NOT NULL,
            	    Actor text NOT NULL,
            	    Director text NOT NULL,
            	    Lend varchar(11) NOT NULL,
            	    Loaner varchar(20) NOT NULL,
            	    Genre varchar(20) NOT NULL                         
            	    )';
					
	$create_game_table_query = 'CREATE TABLE Game 
            	    (
            	    ID int(11) NOT NULL AUTO_INCREMENT,
            	    PRIMARY KEY(ID),
            	    Title text NOT NULL,
            	    Platform varchar(15) NOT NULL,
            	    Genre varchar(20) NOT NULL,
            	    Developer varchar(30) NOT NULL,
            	    Lend varchar(11) NOT NULL,
            	    Loaner varchar(20) NOT NULL           	                             
            	    )';
					
	$create_book_table_query = 'CREATE TABLE Book 
            	    (
            	    ID int(11) NOT NULL AUTO_INCREMENT,
            	    PRIMARY KEY(ID),
            	    Title text NOT NULL,
            	    Author text NOT NULL,
            	    Genre varchar(20) NOT NULL,
					Series text NOT NULL,
 					Copyright int(11) NOT NULL,
					Publisher text NOT NULL,
					ISBN varchar(20) NOT NULL,
					Price int(11) NOT NULL,
					Format varchar(9) NOT NULL,
            	    Lend varchar(11) NOT NULL,
            	    Loaner varchar(20) NOT NULL           	                             
            	    )';								
            
    $create_users_table_query = 'CREATE TABLE Users
            	    (
            	    ID int(11) NOT NULL AUTO_INCREMENT,
            	    PRIMARY KEY(ID),
            	    User varchar(20) NOT NULL,
            	    Password text NOT NULL,
                      SALT text NOT NULL,
                      logged_out_at datetime NOT NULL,
                      Email varchar(50) NOT NULL
            	    )';
                if(isset($_POST['movie'])) {
                	try {
                       $stmt_movie = $db->prepare($create_movie_table_query);
                       $stmt_movie->execute();

                    }catch(PDOException $e) {
                        echo $e->getMessage();
                    }
                }
                if(isset($_POST['game'])) {
                    try {
                        $stmt_game = $db->prepare($create_game_table_query);
                        $stmt_game->execute();
                    }catch(PDOException $e) {
                        echo $e->getMessage();
                    }
                }
                if(isset($_POST['book'])) {
                    try {
                        $stmt_book = $db->prepare($create_book_table_query);
                        $stmt_book->execute();
                    }catch(PDOException $e) {
                        echo $e->getMessage();
                    }
                } if(!isset($_POST['movie']) && !isset($_POST['game']) && !isset($_POST['book'])) {
                    die('You haven´t selected any databases to install');
                }
                try {
                    $stmt_users = $db->prepare($create_users_table_query);
                    $stmt_users->execute();
                }catch(PDOException $e) {
                    echo $e->getMessage();
                }

                /*mysql_query($create_movie_table_query) or die('Could not create Movie table');
            	mysql_query($create_game_table_query) or die('Could not create Game table');
			    mysql_query($create_book_table_query) or die('Could not create Book table');
			    mysql_query($create_users_table_query) or die('Could not create Users table');*/
					
	           	$passwordSALT = time().uniqid(rand(),TRUE);
			    $hashresult = hash('sha512', $scriptpassword.$passwordSALT);
            	    
                $populate_user = $db->prepare("INSERT INTO Users (User, Password, SALT, Email) VALUES (:user,:hash,:password,:email)");
            
                $populate_user->bindParam(':user', $scriptuser, PDO::PARAM_STR);
                $populate_user->bindParam(':hash', $hashresult, PDO::PARAM_STR);
                $populate_user->bindParam(':password', $passwordSALT, PDO::PARAM_STR);
                $populate_user->bindParam(':email', $scriptemail, PDO::PARAM_STR);

                try {
                    $populate_user->execute();
                }catch(PDOException $e) {
                    echo $e->getMessage();
                }


            	/*$populate_user = "INSERT INTO Users (User, Password, SALT, Email) VALUES ('".$scriptuser."','".$hashresult."','".$passwordSALT."', '".$scriptemail."')";
            
            	mysql_query($populate_user) or die('Could not create user for the database');*/
					
				echo 'Database created and user inserted<br /><br />';
				echo 'Setup complete';
	
} else {

		echo 'Setup cannot continue some error occurred with the submitted data';
	}


?>