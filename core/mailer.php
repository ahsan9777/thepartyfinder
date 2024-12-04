<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer {

    public function test($cto)
    {
            $subject = "Mail Test";
            $to = $cto;
            //$to = "ahsannawaz9777@gmail.com";
            //$to = "aqeelashraf@gmail.com";
            //$to = "www.ank.niazi@gmail.com";

            $message = "Hi,<br>
				<br>Hello<br>
				<br><br>Message:         
				<br><br>This is an automatic generated message. Do not reply to this message.";

                //print($message);die();
            $this->sendEmail($to, $subject, $message, 1, 0);
            //print($ret);
    }


    public function contact_us($name, $phone, $email, $csubject, $cmessage)
    {
            $subject = "Contact Request Form";
            $to = "ahsannawaz9777@gmail.com";

            $message = "Hi Admin,<br>
				<br>A new contact request has been submitted, please see the details below:<br>
				<br>Name: ".$name."
				<br>Email: ".$email."
				<br>Phone: ".$phone."
				<br>Subject: ".$csubject."
				<br><br>Message:
				<br>             ".$cmessage."
				<br><br>This is an automatic generated message. Do not reply to this message.";
            $this->sendEmail($to, $subject, $message, 1, 0);
    }
    
    public function form_submit($event_name, $vanue_name, $full_name, $phone_no)
    {
        $subject = "Booking Inquiry";
        $to = "noreply@thepartyfinder.com";

        $message = "Hi Admin,<br>
            <br>A new contact request has been submitted, please see the details below:<br>
            <br>Event Name: ".$event_name."
            <br>Venue Name: ".$vanue_name."
            <br>Full Name: ".$full_name."
            <br>Phone Number: ".$phone_no."
            <br><br>
            <br><br>This is an automatic generated message. Do not reply to this message.";
        $this->sendEmail($to, $subject, $message, 1, 0);
    }
    
    public function form_submit_bk($cto, $csubject, $name, $cmessage)
    {
            $subject = $csubject;
            $to = $cto;
            //$to = "ahsannawaz9777@gmail.com";

            $message = "Hi ".$name.",<br>
				<br>For information about your booking inquiry please see the details below<br>
				<br>".$cmessage."
				<br><br>This is an automatic generated message. Do not reply to this message.";
            $this->sendEmail($to, $subject, $message, 1, 0);
    }

    
    public function sendEmail($to, $subject, $message, $sendToCC = 0, $sendToBcc = 0){
        $dir = '';
        if ((strpos($_SERVER['SCRIPT_NAME'], '/admin/') !== false) || (strpos($_SERVER['SCRIPT_NAME'], '/dashboard/') !== false) || (strpos($_SERVER['SCRIPT_NAME'], '/cron/') !== false) || (strpos($_SERVER['SCRIPT_NAME'], '/api/') !== false) ) {
            $dir = '../';
        }
        //require_once($dir . "lib/class.phpmailer.php");
        //require $dir.'vendor/autoload.php';
        require 'vendor/autoload.php';
        try {
            $mail = new PHPMailer(true);
            $body             = $message;
            $mail->IsSMTP();
            $mail->SMTPAuth   = true;
            $mail->Port       = 465;
            $mail->SMTPSecure = 'ssl';
            //$mail->SMTPSecure = 'tls';
            //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Host       = "thepartyfinder.com";
            $mail->Username   = "noreply@thepartyfinder.com";
            $mail->Password   = 'LTQ7CtlxX&tv';
            $mail->CharSet = "UTF-8";
            $mail->Priority = 1;
            $mail->From       = "noreply@thepartyfinder.com";
            $mail->FromName   = "The Party Finder";
            //$mail->AddReplyTo('partyfindersdxb@gmail.com', 'Chughtai Public Library');
            if($sendToCC == 1){
                //$mail->AddCC('kathryn@partyfindersdxb.com', 'The Global Toy Box');
            }
            if($sendToBcc == 1){
                //$mail->AddBCC('kathryn@partyfindersdxb.com', 'The Global Toy Box');
            }

            $mail->AddAddress($to);
            $mail->Subject    = $subject;
            $mail->AltBody    = $message;
            $mail->WordWrap   = 80;
            $mail->MsgHTML($message);
            $mail->IsHTML(true);
            if(!$mail->Send()) {
                $str = 'Mailer Error: <br />' . $mail->ErrorInfo;
            } else {
                $str = 'Mailer Success';
            }
        } catch (phpmailerException $e) {
        //$str = 'Mailer Exception';
           $str = $e->getMessage();
        }
        echo $str;die;
        return $str;
    }

}