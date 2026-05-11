<?php
if (!defined('APPROOT')) {
    die('Accès interdit');
}
// app/Helpers/mail_helper.php

/**
 * Envoie un email formaté en HTML
 * * @param string $to L'adresse de destination
 * @param string $subject Le sujet
 * @param string $body Le contenu (peut être du HTML)
 * @return bool
 */
function sendEmail($to, $subject, $body){
    
    // 1. En-têtes pour un email HTML propre
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    
    // 2. L'expéditeur (Doit être une adresse valide de votre domaine pour éviter le spam)
    // Idéalement: no-reply@exotikha.com
    $headers .= 'From: Exotikha <no-reply@exotikha.com>' . "\r\n";
    
    // 3. Mise en forme du message
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { padding: 20px; border: 1px solid #eee; border-radius: 10px; max-width: 600px; margin: 0 auto; }
            .header { background: #0f172a; color: white; padding: 15px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { padding: 20px; background: #f8fafc; }
            .footer { font-size: 12px; text-align: center; margin-top: 20px; color: #888; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Exotikha Notification</h2>
            </div>
            <div class='content'>
                $body
            </div>
            <div class='footer'>
                &copy; " . date('Y') . " Exotikha. All rights reserved.
            </div>
        </div>
    </body>
    </html>
    ";

    // 4. Tentative d'envoi
    // Note: Sur WAMP (Local), ceci retournera FALSE si sendmail n'est pas configuré.
    // Sur un vrai hébergeur (LWS, O2Switch, etc.), ceci retournera TRUE.
    if(mail($to, $subject, $message, $headers)){
        return true;
    } else {
        return false;
    }
}