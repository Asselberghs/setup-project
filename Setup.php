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
<form name="setup" action="<?php echo 'Setup-step1.php';?>" method="post">
Server: <input type="text" name="server" value=""><br />
User: <input type="text" name="user" value=""><br />
Password: <input type="password" name="password" value=""><br />
Confirm Password: <input type="password" name="passwordconfirm" value=""><br />
Database: <input type="text" name="database" value=""><br />
<input type="submit" name="submit" value="submit">
</form>
</body></html>
