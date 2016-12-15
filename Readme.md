Web Application: EverFocus Employee Management System Overview
------------------------------------------------------------------------------------

This a client-server architecture that would display employees' information in a web
page. Added some individual features.
Set up the MySQL account first, and use Chrome(best) or other browsers to open the index.html

------------------------------------------------------------------------------------
+ Main Features(Basic Requirements):
 - By default, home page would be a display panel showing all the information stored in the employee database.
     - If there is no existing database for this information, server script will automatically create a new database.
         - databse name: "EV_EMPLOYEE"
         - Automatically load a csv file as the default value
             - csv should be splitted by semi-colon ";", enclosed by double-quote '"'.
             - start with a integer, then followed by 4 strings.

 - In the "personal information" session, user can input their employee information and save on server
     - Input validation done on the server side for security concerns.
     - Server would report the success/failure information and instructions of correct input
     - On successful insertion, the table would refresh and display all information updated.

 - The page size would automatically fit as the display device varies.
     - Fill the screen when using mobile browser.
     - Half the width when using desktop.

+ Other Features:
 - User can display with an option:
   - Engineer, Sales , Marketing information
   - Gender Information
 - CSS panel-app style degisn.

------------------------------------------------------------------------------------
+ File Descriptions:
 - index.html
   - The client webpage of this app. Written in html with JavaScript(AJAX,jQuery). All the data-involved operation are hidden from user.

 - style.css
   - the UI design document of index.html. 

 - dataServer.php
   - the server script of this app. Interact with client and database. If you are running this script, make sure it supports PHP,PDO and MySQL.
     - default database config: all those need to be configured before use.
       - MySQL
       - User: root
       - Pass: password
       - dbname: EV_EMPLOYEE
       - table:EVCODECHALLENGE

 - jquery-2.1.0.min.js
    - support file for jQuery. Can be deleted then use the online script from Google or Microsoft
  
 - listEmployee.js
    - Display function of the client page

 - dataModel.js
    - Background jQuery. Intended for splitting functions between client and server. ALso security concerns.

 - EverFocusCodeChallenge.dll, ExecHelloDLL.exe,DLLSource Folder
    - Support files for displaying "Hello World" using dll,

 - Readme.txt and Bug_log.txt
    - User manual and development log.
    
------------------------------------------------------------------------------------
Other notes:
   - About  .dll
     - the source folder contains development files of this dll.
   - Coding Style:
     -Write functionality before a block of codes. Add single line comment where complex and necessary. 
     -Four-space/tab indent. 
