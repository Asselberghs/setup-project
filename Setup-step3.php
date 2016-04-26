<?php
/*
    This is a setup script for the media databases.
    Copyright (C) 2013 Nick Tranholm Asselberghs

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
include('ErrorControl.php');
include('Connect.php');
include('Yubico.php');

$scriptuser = $_POST['scriptuser'];
$scriptpassword = $_POST['scriptpass'];
$scriptpasswordconfirm = $_POST['scriptpassconfirm'];
$scriptemail = $_POST['Email'];
$yubikey = $_POST['yubikey'];

$scriptuserErrCheck = ErrorControl($scriptuser);
$scriptpassErrCheck = ErrorControl($scriptpassword);
$scriptpassconfirmErrCheck = ErrorControl($scriptpasswordconfirm);
$scriptemailErrCheck = ErrorControl($scriptemail);
$yubikeyErrCheck=ErrorControl($yubikey);

if($scriptuserErrCheck == TRUE || $scriptpassErrCheck == TRUE || $scriptpassconfirmErrCheck == TRUE || $scriptemailErrCheck == TRUE || $yubikeyErrCheck == TRUE) {
	            
	            $ErrCheck = TRUE;
            }
			
if($_POST['submit'] && $_POST['scriptuser'] != '' && $_POST['scriptpass'] != '' && $_POST['scriptpassconfirm'] != '' && $_POST['Email'] != '' && $ErrCheck != TRUE) {

    $otp = $_POST['yubikey'];

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
            	    Genre varchar(20) NOT NULL,
                    Price int(11) NOT NULL,
                    User varchar(20) NOT NULL                         
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
            	    Loaner varchar(20) NOT NULL,
                    Price int(11) NOT NULL,
                    User varchar(20) NOT NULL           	                             
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
            	    Loaner varchar(20) NOT NULL,
                    User varchar(20) NOT NULL           	                             
            	    )';								
            
    $create_users_table_query = 'CREATE TABLE Users
            	    (
            	    ID int(11) NOT NULL AUTO_INCREMENT,
            	    PRIMARY KEY(ID),
            	    User varchar(20) NOT NULL,
            	    Password text NOT NULL,
                    SALT text NOT NULL,
                    logged_out_at datetime NOT NULL,
                    Email varchar(50) NOT NULL,
                    Yubikey varchar(12) NOT NULL,
                    Yubikey_Used varchar(5) NOT NULL
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


                if($_POST['yubikey']) {

                    //Yubikey Authentication    
                    $yubi = new Auth_Yubico('28274', 'eqp96B8xrLUvu7+VybDGd9l14no=');
                    $auth = $yubi->verify($otp);
                    if (PEAR::isError($auth)) {
                        print "<p>Authentication failed: " . $auth->getMessage()."</p>";
                        print "<p>Debug output from server: " . $yubi->getLastResponse()."</p>";
                    } else {
                            $otp_id = substr($otp, 0, 12);
                            $yubikey_used = TRUE;
                            $populate_user->bindParam(':yubikey', $otp_id, PDO::PARAM_STR);
                            $populate_user->bindParam(':yubikeyused', $yubikey_used, PDO::PARAM_STR);
                        }
                $populate_user = $db->prepare("INSERT INTO Users (User, Password, SALT, Email, Yubikey, Yubikey_Used) VALUES (:user,:hash,:password,:email,:yubikey,:yubikeyused)");
                } else {
            	$populate_user = $db->prepare("INSERT INTO Users (User, Password, SALT, Email) VALUES (:user,:hash,:password,:email)");
                }

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