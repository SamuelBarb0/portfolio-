<?php

// Replace this with your own email address
$siteOwnersEmail = 'sambarbosa66@gmail.com';

$errors = [];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize input data
    $name = htmlspecialchars(trim($_POST['contactName']));
    $email = htmlspecialchars(trim($_POST['contactEmail']));
    $subject = htmlspecialchars(trim($_POST['contactSubject']));
    $contact_message = htmlspecialchars(trim($_POST['contactMessage']));

    // Validate inputs
    if (strlen($name) < 2) {
        $errors['name'] = "Por favor, introduce tu nombre.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Por favor, introduce un email válido.";
    }
    if (strlen($contact_message) < 15) {
        $errors['message'] = "Por favor, introduce tu mensaje. Debe tener al menos 15 caracteres.";
    }
    // If subject is empty, use default
    if (empty($subject)) {
        $subject = "Formulario de Contacto";
    }

    // Build email message
    $message .= "Email de: " . $name . "<br />";
    $message .= "Dirección de Email: " . $email . "<br />";
    $message .= "Mensaje: <br />";
    $message .= $contact_message;
    $message .= "<br /> ----- <br /> Este email ha sido enviado desde tu formulario de contacto. <br />";

    // Set From: header
    $from = $name . " <" . $email . ">";

    // Email headers
    $headers = "From: " . $from . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    // Send email if there are no errors
    if (empty($errors)) {
        if (mail($siteOwnersEmail, $subject, $message, $headers)) {
            echo "OK"; // Informa al cliente que el correo se envió correctamente
        } else {
            echo "Algo ha ido mal, por favor inténtalo de nuevo."; // Informa al cliente que hubo un problema al enviar el correo
        }
    } else {
        // If there are validation errors, construct and echo the error response
        $response = '';
        foreach ($errors as $error) {
            $response .= $error . "<br />";
        }
        echo $response;
    }
}
?>
