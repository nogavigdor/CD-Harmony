<?php
namespace Controllers;



class TestController 
{
    public function __construct() {
       
    }

    public function insertData() {
        try {
            include_once './sql/insertData.php';
        }
        catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }

    public function showTest()
    {
        try {   
            echo ' test mail';
            try {
            $email="noga.vigdor@gmail.com";
            $first_name = 'Noga';
            $title = 'test from test controller';
            $message = 'test from test controller';


                         
                             $mail = new PHPMailer(true);
                       
                            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                            $mail->isSMTP();
                             $mail->SMTPAuth = true;
                           
                             $mail->Host = "send.one.com";
                             $mail->SMTPSecure = "ssl";
                            
                             $mail->Port = 465;

                            $mail->Username = SMTP_USERNAME;
                             $mail->Password = SMTP_PASSWORD;   
                            //$mail->setFrom($email, $first_name);
                            $mail->isHTML();
                            $mail->From = "contact@cdhrmny.dk";
                             $mail->addAddress("contact@cdhrmny.dk", "Noga");

                             $mail->Subject = $title;
                             $mail->Body = "<h2 style='color:red;'>Email From:</h2> ".$email."<br />".$message;
                            
                            
                             if(!$mail->send()){
                                echo "Failed to send email";
                             }else{
                                echo "Email sent";
                             }

                             exit;

                        } catch (Exception $e) {
                            echo json_encode("Message could not be sent. Mailer Error: {$mail->ErrorInfo}"); 
                        }
                        
        } catch (\PDOException $ex) {
            error_log('PDO Exception: ' . $ex->getMessage());
        }
    }
}
?>