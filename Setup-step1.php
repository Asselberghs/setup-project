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

if(!file_exists('Connect.php')) {

    
            $serverErrCheckIn = $_POST['server'];
            $userErrCheckIn = $_POST['user'];
            $passwordErrCheckIn = $_POST['password'];
            $passwordconfirmErrCheckIn = $_POST['passwordconfirm'];
            $databaseErrCheckIn = $_POST['database'];
            
            $serverErrCheck = ErrorControl($serverErrCheckIn);
            $userErrCheck = ErrorControl($userErrCheckIn);
            $passwordErrCheck = ErrorControl($passwordErrCheckIn);
            $passwordconfirmErrCheck = ErrorControl($passwordconfirmErrCheckIn);
            $databaseErrCheck = ErrorControl($databaseErrCheckIn);
            
            if($serverErrCheck == TRUE || $userErrCheck == TRUE || $passwordErrCheck == TRUE || $passwordconfirmErrCheck == TRUE || $databaseErrCheck == TRUE) {
	            
	            $ErrCheck = TRUE;
            }
            
            if($_POST['submit'] && $_POST['server'] != '' && $_POST['user'] != '' && $_POST['password'] != '' && $_POST['passwordconfirm'] != '' && $_POST['database'] != '' && $ErrCheck != TRUE) {
            
           
           		if($_POST['password'] != $_POST['passwordconfirm']) {
	           
	           			echo 'Passwords for the database connection file dosen�t match<br /><br />';
	           
           		}

            $server_match_local = preg_match('/localhost/i', $serverErrCheckIn);
            $server_match_IP = preg_match('/[0-9]{3}\.[0-9]{3}\.[0-9]{1,3}\.[0-9]{1,3}/i', $serverErrCheckIn);
            $server_match_URL = preg_match('/(www.)?[a-z\.]+\.[a-z]{2,3}/i', $serverErrCheckIn);

            /*Is the server localhost?*/

            if($server_match_local == 1) {
                $server = 'localhost';
            }
            /*Is the server an IP Address?*/
            else if($server_match_IP == 1) {
                $server = $serverErrCheckIn;
            }
            /*Is the server a domain?*/
            else if($server_match_URL == 1) {
                $server = $serverErrCheckIn;
            }

            /*It´s neither of the above*/
            else {
                die('Server Address is neither an IP nor localhost, nor a domain, check your mysql server address.');
            }
           
            $user = $userErrCheckIn;
            $password = $passwordErrCheckIn;
            $database = $databaseErrCheckIn;
           
            
            $content = '<?php '."\n\r\n\r".'$dsn = \'mysql:dbname='.$database.';host='.$server.'\';'."\n\r\n\r".'$user = \''.$user.'\';'."\n\r\n\r".'$pass = \''.$password.'\';'."\n\r\n\r".'try {'."\n\r\n\r".' $db = new PDO($dsn, $user, $pass);'."\n\r\n\r".' $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);'."\n\r\n\r".'}'."\n\r\n\r".'catch(PDOException $e) {'."\n\r\n\r".' die(\'Could not connect to database: <br />\' . $e->getMessage());'."\n\r\n\r".'}'."\n\r\n\r".'?>';   

            /*mysql_connect(\''.$server.'\', \''.$user.'\', \''.$password.'\') or die(\'could not connect to database\');'."\n\r".'mysql_select_db(\''.$database.'\') or die(\'Could not select database\');'."\n\r".'?>';*/
            
            $file = fopen('Connect.php', 'w ');
            
            fwrite($file, $content);
            
            fclose($file);
            
            
            
            echo 'file created succesfully<br /><br />';
				
				    echo 'Proceed to setup tables and users <a href="Setup-step2.php">Next</a>';
				
    
            } else {
	            	
	           	echo 'file could not be created either empty fields were submitted or the input data wasen�t valid';
	            	
            }
}  else {
	
	
  echo 'File already exists';
	
}

?>