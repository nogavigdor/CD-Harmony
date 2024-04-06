<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//require "./PHPMailer-master/src/PHPMailer.php";
//require "./PHPMailer-master/src/SMTP.php";
//require "./PHPMailer-master/src/Exception.php";

//returns the last part of the url
function getCurrentView() {
    $url = $_SERVER['REQUEST_URI'];
    $parts = explode('/', trim($url, '/'));
    $currentView = end($parts);
    return $currentView;
}

//converts the timestamp to date
function timeStampTodDate($timeStamp) {
    $dateTimeFormat = new DateTime($timeStamp);
    return  $dateTimeFormat->format('d F Y');
}

function sendMail($to, $subject, $message, $from='', $headers='') {

    try {
        //Server settings
        $mail = new PHPMailer(true);
                                
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->isSMTP();
        $mail->SMTPAuth = true;
                                    
        $mail->Host = "send.one.com";
        $mail->SMTPSecure = "ssl";
        $mail->SMTPDebug = 0; 
        $mail->Port = 465;
    
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;   
        //$mail->setFrom($email, $first_name);
        $mail->isHTML();
        $mail->From = $from;
        $mail->addAddress($to, "Noga");
    
        $mail->Subject = $subject;
        $mail->Body = $message;
                                        
                                        
                                        
         $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    //Accept a string of tags and return an array of tags trimmed out of any white space
    function tagsToArray($tags) {
        $tagsArray = explode(',', $tags);
        // Loop through the tags array and trim each tag
        foreach ($tagsArray as $index => $tag) {
            $tagsArray[$index] = trim($tag);
        }
        return $tagsArray;
    }
}
                            
