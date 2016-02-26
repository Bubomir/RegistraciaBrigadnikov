<?php
       
    //Database login parameters
    $dbhost = 'localhost';
    $dbuser = 'root';
    $dbpass = '';
    $dbname = 'bubo';

    //Create connection
    $db = mysqli_connect($dbhost,$dbuser,$dbpass);
    
    //Check connection
    if(!$db){
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }

    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS ".$dbname;
    if (!mysqli_query($db, $sql)) {
        echo "Error creating database: " . mysqli_error($db). PHP_EOL;
    }

    mysqli_close($db);
    // sql to create table
    $db=mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
    if(!$db){
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
     
    // CREATE TABLE ZAMESTNANTCI
    $table_employees="employees";
    
    //SuperAdmin parameters

    $first_name_admin = 'Admin';
    $surname_admin = 'Super';
    $password_admin = md5(mysqli_real_escape_string($db,'root'));
    $email_admin =  'admin@vtstudentplanner.cz';
    $permissions_admin = 'admin';

    $first_name_brigadnici = 'Brigádnici';
    $surname_brigadnici = ' ';
    $password_brigadnici = md5(mysqli_real_escape_string($db,'brigadnici'));
    $email_brigadnici = 'brigadnici@vtstudentplanner.sk';
    $permissions_brigadnici = 'supervizor';

    $sql = 'CREATE TABLE IF NOT EXISTS '.$table_employees.'(
        User_ID INT( 5 ) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        First_Name VARCHAR(255) NOT NULL,
        Surname VARCHAR(255) NOT NULL,
        Password VARCHAR(255) NOT NULL,
        Email VARCHAR(255) NOT NULL,
        Permissions VARCHAR(10) NOT NULL,
        Mobile_Number VARCHAR(255) NOT NULL,
        Change_Number INT(255),
        Reset_Password VARCHAR(50) NOT NULL DEFAULT 0,
        Session_ID VARCHAR(255) NOT NULL,
        UNIQUE (`Email`,`User_ID`) 
    )';

    if (!mysqli_query($db, $sql)) {
       echo "Error creating table or table already exists: " . mysqli_error($db);
    }


    $result=mysqli_query($db,"SELECT * FROM $table_employees WHERE Email='$email_admin'");
    $row=mysqli_fetch_array($result);
   
    $result_2=mysqli_query($db,"SELECT * FROM $table_employees WHERE Email='$email_brigadnici'");
    $row_2=mysqli_fetch_array($result_2); 
   
    if($row['Email']!=$email_admin){
        
        //Insesrt Admin account
        if(!mysqli_query($db,"INSERT INTO $table_employees(First_Name,Surname,Password,Email,Permissions) VALUES('$first_name_admin','$surname_admin','$password_admin','$email_admin','$permissions_admin')")){
                echo 'Issue while creating SuperAdmin account';
        }        
    }
    if($row_2['Email'] != $email_brigadnici){
        // Instert Brigadnici account
        if(!mysqli_query($db,"INSERT INTO $table_employees(First_Name,Surname,Password,Email,Permissions) VALUES('$first_name_brigadnici','$surname_brigadnici','$password_brigadnici','$email_brigadnici','$permissions_brigadnici')")){
                echo 'Issue while creating Brigadnici account';
        }
    }
      
    // CREATE TABLE SMENY
    $table_calendar = 'calendar';
    
    //inicialization parameters for table SMENY ...this table is connecting with the zamestnanci table
    $sql_calendar = 'CREATE TABLE IF NOT EXISTS '.$table_calendar.'(
        ID INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        p_Email VARCHAR(255) NOT NULL,                                   
        Start_Date TIMESTAMP NOT NULL,
        End_Date TIMESTAMP NOT NULL,
        Capacity INT(5) NULL,
        Logged_In INT(5) NULL,
        Color VARCHAR(50),
        FOREIGN KEY (p_Email) REFERENCES '.$table_employees.'(Email)
    )';

    // Check if table was create
    if (!mysqli_query($db, $sql_calendar)) {
        echo "Error creating table: " . mysqli_error($db);
    }

    // CREATE TABLE NOTIFICATION
    $table_notification = 'notification';

    $sql_notification = 'CREATE TABLE IF NOT EXISTS '.$table_notification.'(
        ID INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        p_Email_KTO VARCHAR(255) NOT NULL,
        p_Email_KOMU VARCHAR(255) NOT NULL,
        p_Email_KOHO VARCHAR(255) NOT NULL,
        Activity VARCHAR(255) NOT NULL,
        Start_Date TIMESTAMP NOT NULL,
        TimeStamp TIMESTAMP NOT NULL,
        FOREIGN KEY (p_Email_KTO) REFERENCES '.$table_employees.'(Email),
        FOREIGN KEY (p_Email_KOMU) REFERENCES '.$table_employees.'(Email),
        FOREIGN KEY (p_Email_KOHO) REFERENCES '.$table_employees.'(Email)
        )';

    // Check if table was create
    if (!mysqli_query($db, $sql_notification)) {
        echo "Error creating table: " . mysqli_error($db);
    }
   
?>
