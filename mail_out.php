<?php
    /**
     * This uses the easy method to send email
     * wp_mail function obviously only works in wordpress
     * and make sure SMTP plugin is active
     */

    require_once '../wp-load.php';

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
            $sentTime = $dmail['sent_time'];
            $sentTime = substr($sentTime, 8, 2) . ':00 ' . substr($sentTime, 6, 2) . '-' . substr($sentTime, 4, 2) . '-' . substr($sentTime, 0, 4);
            $message = $dmail['message'];
            $message = $message . '\n\n\n' . $dmail['sender'] . ' sent you this mail from ' . $sentTime . '.\nFor more information, see https://renziera.web.id/reverse-d-mail/';
            $status = wp_mail($dmail['target'], $dmail['subject'], $message);
            if($status){
                $updateQuery = 'UPDATE dmail SET sent = 1 WHERE id = :id';
                $query = $conn->prepare($updateQuery);
                $query->bindParam(':id', $dmail['id']);
                $query->execute();
            }
        }
    }

?>