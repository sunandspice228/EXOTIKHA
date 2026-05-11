<?php
// Fichier : app/Libraries/Mail.php

// Assurez-vous d'avoir installé PHPMailer via Composer :
// composer require phpmailer/phpmailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail {
    private $mail;

    public function __construct(){
        $this->mail = new PHPMailer(true);
        
        try {
            // Configuration Serveur
            // $this->mail->SMTPDebug = 2; // Décommenter pour voir les logs d'erreur dans le navigateur
            $this->mail->isSMTP();
            $this->mail->Host       = SMTP_HOST;
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = SMTP_USER;
            $this->mail->Password   = SMTP_PASS;
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
            $this->mail->Port       = SMTP_PORT;

            // Configuration de l'encodage
            $this->mail->CharSet = 'UTF-8';
            $this->mail->Encoding = 'base64';

            // Expéditeur par défaut
            $this->mail->setFrom(SMTP_FROM, SMTP_NAME);
            $this->mail->isHTML(true);

        } catch (Exception $e) {
            error_log("Erreur Constructeur Mail: {$this->mail->ErrorInfo}");
        }
    }

    // Envoyer un email
    public function send($to, $subject, $body, $attachment = null, $attachmentName = 'Facture.pdf'){
        try {
            // ⚠️ IMPORTANT : On nettoie les destinataires et pièces jointes précédents
            // Sinon, si on envoie 2 mails à la suite, le 2ème reçoit aussi le mail du 1er !
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            $this->mail->clearCCs();
            $this->mail->clearBCCs();

            // Destinataire
            $this->mail->addAddress($to);

            // Contenu
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;
            
            // Version Texte Brut (Anti-Spam)
            // On enlève les balises HTML pour créer une version lecture seule
            $this->mail->AltBody = strip_tags(str_replace(['<br>', '<br/>'], "\n", $body));

            // Ajouter la pièce jointe (PDF en string)
            if($attachment){
                // addStringAttachment permet d'attacher le PDF généré en mémoire sans l'enregistrer sur le disque
                $this->mail->addStringAttachment($attachment, $attachmentName);
            }

            $this->mail->send();
            return true;

        } catch (Exception $e) {
            // On log l'erreur serveur pour le développeur
            error_log("Erreur Envoi Mail à $to : {$this->mail->ErrorInfo}");
            return false;
        }
    }
}