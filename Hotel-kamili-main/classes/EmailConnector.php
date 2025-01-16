<?php

namespace classes;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

class EmailConnector {
    private $fromAddress;
    private $fromName;
    private $toAddress;
    private $toName;
    private $replyAddress;
    private $replyName;
    private $subject;
    private $body;

    public function __construct() {
        $this->fromAddress = '';
        $this->fromName = '';
        $this->toAddress = '';
        $this->toName = '';
        $this->replyAddress = '';
        $this->replyName = '';
        $this->subject = '';
        $this->body = '';
    }

    public function setFromAddress($fromAddress) {
        $this->fromAddress = $fromAddress;
    }

    public function setFromName($fromName) {
        $this->fromName = $fromName;
    }

    public function setToAddress($toAddress) {
        $this->toAddress = $toAddress;
    }

    public function setToName($toName) {
        $this->toName = $toName;
    }

    public function setReplyAddress($replyAddress) {
        $this->replyAddress = $replyAddress;
    }

    public function setReplyName($replyName) {
        $this->replyName = $replyName;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function setBody($body) {
        $this->body = $body;
    }

    public static function getEmailConnection() {
        try {
            $mail = new PHPMailer(true);
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ayshacader2001@gmail.com'; 
            $mail->Password = 'wdfo xhkb tlmy ovkh';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            return $mail;
        } catch (Exception $e) {
            die("ERROR in EmailConnector class getEmailConnection function: " . $e->getMessage());
        }
    }

    public function sendEmail() {
        $mail = self::getEmailConnection();
        try {
            $mail->setFrom($this->fromAddress, $this->fromName);
            $mail->addAddress($this->toAddress, $this->toName);
            if (!empty($this->replyAddress)) {
                $mail->addReplyTo($this->replyAddress, $this->replyName);
            }
            $mail->isHTML(true);
            $mail->Subject = $this->subject;
            $mail->Body = $this->body;
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
