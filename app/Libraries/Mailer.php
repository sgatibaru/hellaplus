<?php


namespace App\Libraries;


use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mailer extends PHPMailer
{
    public function sendEmail($subject, $message, $email, $name = '')
    {
        try {
            //Server settings
            if(TRUE){
                $this->SMTPDebug = FALSE;                      // Enable verbose debug output
                $this->isSMTP();                                            // Send using SMTP
                $this->Host       = 'mail.dirflix.co.ke';                    // Set the SMTP server to send through
                $this->SMTPAuth   = true;                                   // Enable SMTP authentication
                $this->Username   = 'no-reply@dirflix.co.ke';                     // SMTP username
                $this->Password   = 'VTsJ8z)4M(0G';                               // SMTP password
                $this->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                $this->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            }
            //Recipients
            $this->setFrom('no-reply@dirflix.co.ke', 'DirFlix');
            $this->addAddress($email, $name);     // Add a recipient

            // Content
            $this->isHTML(true);                                  // Set email format to HTML
            $this->Subject = $subject;
            $this->Body    = $message;
            $this->AltBody = strip_tags($message);
            
            if ($this->send()) {
                return TRUE;
            }
        } catch (Exception $e) {
            $this->setError($e->getMessage());
        }

        return FALSE;
    }
}