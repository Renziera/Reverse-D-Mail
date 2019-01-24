<?php
    /**
     * This uses the easy method to send email
     * wp_mail function obviously only works in wordpress
     * and make sure SMTP plugin is active
     */

    $servername = "localhost";
    $username = "renziera_dmail";
    $password = 'I6DpyEE*3s^4%qgc';
    $database = "renziera_dmail";

    try{
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    }catch(PDOException $e){

    }

    $selectQuery = 'SELECT * FROM dmail WHERE sent = 0';
    $query = $conn->prepare($selectQuery);
    $query->execute();
    $result = $query->fetchall();

    date_default_timezone_set('Asia/Jakarta');
    $currentTime = date('YmdH');

    foreach ($result as $dmail) {
        if($dmail['target_time'] <= $currentTime){
            echo $dmail['subject'];
        }
    }
?>