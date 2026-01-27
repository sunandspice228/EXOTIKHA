<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail {
    private $mail;

    public function __construct(){
        $this->mail = new PHPMailer(true);
        
        // Configuration Serveur
        $this->mail->isSMTP();
        $this->mail->Host       = SMTP_HOST;
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = SMTP_USER;
        $this->mail->Password   = SMTP_PASS;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // ou ENCRYPTION_SMTPS pour le port 465
        $this->mail->Port       = SMTP_PORT;

        // Expéditeur par défaut
        $this->mail->setFrom(SMTP_FROM, SMTP_NAME);
        $this->mail->isHTML(true);
        $this->mail->CharSet = 'UTF-8';
    }

    // Envoyer un email simple
    public function send($to, $subject, $body, $attachment = null, $attachmentName = 'Facture.pdf'){
        try {
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;

            // Ajouter la pièce jointe (PDF) si elle existe
            if($attachment){
                // addStringAttachment permet d'attacher le PDF généré en mémoire sans l'enregistrer sur le disque
                $this->mail->addStringAttachment($attachment, $attachmentName);
            }

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            // En prod, on log l'erreur, mais on ne l'affiche pas forcément au client
            error_log("Erreur Mail: {$this->mail->ErrorInfo}");
            return false;
        }
    }
}