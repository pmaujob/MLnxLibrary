<?php

//require_once '/PHPMailer/PHPMailer.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\phpMyAdmin\vendor\autoload.php';
require dirname(__FILE__) . '/PHPMailer/src/Exception.php';
require dirname(__FILE__) . '/PHPMailer/src/PHPMailer.php';
require dirname(__FILE__) . '/PHPMailer/src/SMTP.php';

require_once dirname(__FILE__) . '/../Formats/ConvertFormats.php';

class LnxMail {

    private $phpMailer;

    public function construct() {

        $this->phpMailer = new PHPMailer(true);

        $this->phpMailer->SMTPDebug = 2;
        //$this->phpMailer->IsSMTP();
        $this->phpMailer->CharSet = 'UTF-8';
        $this->phpMailer->SMTPAuth = true;
        $this->phpMailer->Port = 587;
        $this->phpMailer->Host = "smtp.gmail.com";
        $this->phpMailer->SMTPSecure = 'TLS';
        $this->phpMailer->Username = "planeacionbpid@gmail.com";
        $this->phpMailer->Password = "bpid2017";
        $this->phpMailer->setFrom('planeacionbpid@gmail.com', 'Bpid Planeación');
    }

    public function setAddress($addressEmail) {
        $this->phpMailer->addAddress($addressEmail);
    }

    public function addCopy($copyEmail) {
        $this->phpMailer->addCC($copyEmail);
    }

    public function buildMail($subject, $msg, $altBody) {
        $this->phpMailer->Subject = $subject;

        $body = str_replace('HORA_SISTEMA', ConvertFormats::formatDate(date("m/d/Y")), file_get_contents(dirname(__FILE__) . '/../htmlBodies/CelabMailBody.php'));
        $body = str_replace('MSG_REPLACE', $msg, $body);

        $this->phpMailer->msgHTML($body);
        $this->phpMailer->AltBody = $altBody;
    }

    public function addFPDFAttachment($attachment, $fileName) {
        $this->phpMailer->addStringAttachment($attachment, $fileName);
    }

    public function send() {

        $sendedMail = $this->phpMailer->send();

        $tries = 1;
        while ((!$sendedMail) && ($tries < 3)) {
            sleep(5);
            $sendedMail = $this->phpMailer->send();
            $tries++;
        }

        return $sendedMail;
    }

}
?>

