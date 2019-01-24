<?php
    if(isset($_POST['dmail'])){
        date_default_timezone_set('Asia/Jakarta');
        $currentTime = date('YmdH');
        $targetTime = str_replace('-', '', $_POST['target_date']) . substr($_POST['target_hour'], 0, 2);
        $sender = $_POST['sender'];
        $target = $_POST['target'];
        $subject = $_POST['subject'];
        $message = $_POST['messagee'];
        
        $servername = "localhost";
        $username = "renziera_dmail";
        $password = 'I6DpyEE*3s^4%qgc';
        $database = "renziera_dmail";

        try{
            $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $insertSQL = "INSERT INTO dmail (target, sender, subject, message, sent_time, target_time, sent) VALUES (:target, :sender, :subject, :message, :sent_time, :target_time, 0)";
            $query = $conn->prepare($insertSQL);
            $query->bindParam(':target', $target );
            $query->bindParam(':sender', $sender );
            $query->bindParam(':subject', $subject );
            $query->bindParam(':message', $message );
            $query->bindParam(':sent_time', $currentTime );
            $query->bindParam(':target_time', $targetTime );
            $query->execute();
        }catch(PDOException $e){
            var_dump($e);
            echo 'Failed to mail';
        }finally{
            $futureTime = $_POST['target_date'];
            echo "$subject successfully mailed to $target in $futureTime<br>";
        }        
    }
?>

<!DOCTYPE html>
<html>
<body>
    <form action="" method="post" name="dmailForm" id="dmailForm">
        <input type="hidden" name="dmail">
        <input type="text" name="sender" maxlength="20" placeholder="Sender's name" required="required">
        <input type="email" name="target" maxlength="50" placeholder="Recipient's email address" required="required">
        <br>
        <input type="text" name="subject" maxlength="255" placeholder="Subject" required="required">
        <br>
        <textarea name="messagee" id="dmailForm" cols="30" rows="10" form="dmailForm" placeholder="Message" required="required"></textarea>
        <strong>Send to </strong>
        <input type="date" name="target_date" required="required">
        <input type="time" name="target_hour" required="required">
        <input type="submit" value="Send Mail"  style="float: right;">
    </form>
</body>
</html>