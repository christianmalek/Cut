cut - URL shortener

Installation

1. Create a table in your mySQL Database  with the SQL Statement from the file "CREATE_TABLE.sql". Maybe you want to rename the database, before you create it.
2. After table creation open the file "api.php". In the middle of the file is the method "getDB()". Type in the database connection credentials. Don't forget to change the DB name if you changed it in step 1!
3. Now open the file "main.js". In the urlCutController you can find the variable $scope.domain. You need to change it to your domain.
4. Done.