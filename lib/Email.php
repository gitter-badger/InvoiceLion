<?php
namespace MintyPHP;

class Email
{
    public static $sender = 'invoicelion@ams01.usecue.nl';

    public static function send($to,$toName,$from,$fromName,$subject,$message,$attachment,$filename) {
        $mail = new PHPMailer\PHPMailer\PHPMailer(); 
        $mail->CharSet = 'UTF-8';
        $mail->From = static::$sender;
        $mail->FromName = $fromName;
        $mail->AddAddress($to, $toName);
        $mail->AddBCC($from, $fromName);
        $mail->AddReplyTo($from, $fromName);
        $mail->WordWrap = 50; // set word wrap
        $mail->IsHTML(true); // send as HTML
        $mail->Subject = $subject;
        $mail->Body = $message; //HTML Body
        //strip html behalve br's
        $text = strip_tags($message, '<br /><br><br/>');
        //replace br's with newlines
        $text = preg_replace('/<br(\s+)?\/?>/i', "\n", $text);
        //set the alternative display (plain text)
        $mail->AltBody = $text; //Text Body
        if($attachment) $mail->addStringAttachment($attachment, $filename);
        //handle errors
        if(!$mail->Send()) {
            return "Mailer Error: " . $mail->ErrorInfo;
        }
        return false;
    }
    
}
