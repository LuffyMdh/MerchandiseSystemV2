<?php


    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    $root = dirname(__FILE__, 2);
    require $root . '/vendor/phpmailer/phpmailer/src/Exception.php';
    require $root . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require $root . '/vendor/phpmailer/phpmailer/src/SMTP.php';
    require $root . '/vendor/autoload.php';

    class Mail {

        private $sendTheMailBro;
        private $userEmail;
        private $userPassword;
        private $canSend = false;
        private $recipients;

        function __construct($name, $email, $password, $subject, $body, $isHTML, $recipients, $cc) {
            $this->sendTheMailBro = new PHPMailer(true);
            $this->sendTheMailBro->SMTPDebug = 0;
            $this->sendTheMailBro->isSMTP();
            $this->sendTheMailBro->SMTPAuth = true;
            $this->sendTheMailBro->Host = 'smtp.gmail.com';
            $this->sendTheMailBro->SMTPSecure = 'tls'; 
            $this->sendTheMailBro->Port = 587;

            $this->sendTheMailBro->Username = $email;
            $this->sendTheMailBro->Password = $password;
            $this->sendTheMailBro->setFrom($email, $name);

            $this->sendTheMailBro->isHTML($isHTML);
            $this->sendTheMailBro->Subject = $subject;
            $this->sendTheMailBro->Body = $body;

            if (is_array($recipients)) {
                foreach($recipients as $email => $name) {
                    $this->sendTheMailBro->addAddress($email, $name);                   
                }
                $this->canSend = true;
            }

            if (!is_null($cc)) {
                if (is_array($cc)) {
                    foreach($cc as $email) {
                        $this->sendTheMailBro->addCC($email);
                    }
                }
            }
        }

        // Start Setter/Getter Recipients
        function setRecipients($recipients) {
            $this->recipients = $recipients;
        }

        function getRecipients() {
            return $this->recipients;
        }
        // End Setter/Getter Recipients


        // Start Setter isHTML
        function setIsHTML($isHTML) {
            $this->sendTheMailBro->isHTML($isHTML);
        }
        // End Setter isHTML

        // Start Setter Subject
        function setSubject($subject) {
            $this->sendTheMailBro->Subject = $subject;
        }
        // End Setter Subject

        // Start Setter Body
        function setBody($body) {
            $this->sendTheMailBro->Body = $body;
        }
        // End Setter Body

        function addCC($ccList) {
            if (is_array($ccList)) {
                foreach($ccList as $email => $name) {
                    $this->sendTheMailBro->addCC($email);
                }
            } else {
                $this->canSend = false;
            }

            
        }

        // Start Send the email bro
        function sendTheEmail() {
            if ($this->canSend) {
                if ($this->sendTheMailBro->send()) {
                    return 200;
                } else {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }  
            }

        }
        // End Send the email bro
        
    }
?>