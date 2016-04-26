<!-- 
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
-->
<html><head><title>Movie Database Setup Script</title></head><body>
<form name="setup" action="<?php echo 'Setup-step3.php';?>" method="post">
Script User: <input type="text" name="scriptuser" value=""><br />
Script Password: <input type="password" name="scriptpass" value=""><br />
Confirm Script Password: <input type="password" name="scriptpassconfirm" value=""> <br />
Yubikey: <input type="text" name="yubikey"><br>
E-mail: <input type="text" name="Email"><br />
Note: Your e-mail will only be stored in your own database, I am not using it neither for myself nor for a third party.<br /> 
The reason you should enter your e-mail is, because of the Backup function in the admin area.<br /> 
It will send you a dump of your database on your e-mail, provided you enter your e-mail here.<br /><br />
<b>Select Databases to Install</b><br /><br />
<input type="checkbox" name="movie">Movie<br />
<input type="checkbox" name="game">Game<br />
<input type="checkbox" name="book">Book<br />
<input type="submit" name="submit" value="submit">
