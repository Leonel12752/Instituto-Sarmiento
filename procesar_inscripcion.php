<?php
// procesar_inscripcion.php

// --- Configuración ---
// Dirección de correo electrónico a la que se enviarán los datos del formulario.
// Puedes cambiarla aquí fácilmente.
$recipient_email = "skarfazr12752@gmail.com";

// Asunto del correo electrónico
$email_subject = "Nueva Solicitud de Inscripción al Colegio";

// URL a la que redirigir después de enviar el formulario (puedes crear una página de 'gracias.html')
$success_redirect_url = "gracias.html";
// URL a la que redirigir si hay un error
$error_redirect_url = "error.html";

// --- Procesamiento del Formulario ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Función para limpiar y validar los datos de entrada
    function sanitize_input($data) {
        $data = trim($data); // Elimina espacios en blanco al principio y al final
        $data = stripslashes($data); // Elimina barras invertidas
        $data = htmlspecialchars($data); // Convierte caracteres especiales a entidades HTML
        return $data;
    }

    // Recolectar y sanitizar los datos del formulario
    $nombre_alumno = sanitize_input($_POST['nombre_alumno'] ?? '');
    $fecha_nacimiento = sanitize_input($_POST['fecha_nacimiento'] ?? '');
    $genero = sanitize_input($_POST['genero'] ?? '');
    $grado_inscribir = sanitize_input($_POST['grado_inscribir'] ?? '');
    $nombre_tutor = sanitize_input($_POST['nombre_tutor'] ?? '');
    $email_tutor = filter_var($_POST['email_tutor'] ?? '', FILTER_SANITIZE_EMAIL); // Sanitiza el email
    $telefono_tutor = sanitize_input($_POST['telefono_tutor'] ?? '');
    $direccion = sanitize_input($_POST['direccion'] ?? '');
    $como_se_entero = sanitize_input($_POST['como_se_entero'] ?? '');
    $comentarios = sanitize_input($_POST['comentarios'] ?? '');

    // Construir el cuerpo del mensaje de correo electrónico
    $email_body = "Se ha recibido una nueva solicitud de inscripción:\n\n";
    $email_body .= "--- Datos del Alumno ---\n";
    $email_body .= "Nombre Completo: " . $nombre_alumno . "\n";
    $email_body .= "Fecha de Nacimiento: " . $fecha_nacimiento . "\n";
    $email_body .= "Género: " . $genero . "\n";
    $email_body .= "Grado a Inscribir: " . $grado_inscribir . "\n\n";

    $email_body .= "--- Datos del Padre/Tutor ---\n";
    $email_body .= "Nombre Completo: " . $nombre_tutor . "\n";
    $email_body .= "Correo Electrónico: " . $email_tutor . "\n";
    $email_body .= "Teléfono de Contacto: " . $telefono_tutor . "\n";
    $email_body .= "Dirección: " . $direccion . "\n\n";

    $email_body .= "--- Información Adicional ---\n";
    $email_body .= "¿Cómo se enteró?: " . $como_se_entero . "\n";
    $email_body .= "Comentarios: " . ($comentarios ? $comentarios : "Ninguno") . "\n";

    // Encabezados del correo electrónico
    $headers = "From: " . $email_tutor . "\r\n"; // El remitente será el email del tutor
    $headers .= "Reply-To: " . $email_tutor . "\r\n";
    $headers .= "Content-type: text/plain; charset=UTF-8\r\n"; // Asegura que los caracteres especiales se muestren correctamente

    // --- Envío del Correo Electrónico ---
    // La función mail() de PHP requiere que tu servidor tenga un servidor de correo configurado (como Sendmail o Postfix).
    // Para entornos de producción, se recomienda usar librerías como PHPMailer para un envío de correo más robusto y con autenticación SMTP.
    // Instalar PHPMailer usando Composer: composer require phpmailer/phpmailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.tudominio.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tuusuario@tudominio.com';
        $mail->Password = 'tucontraseña';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('tuusuario@tudominio.com', 'Nombre del Remitente');
        $mail->addAddress($recipient_email);

        $mail->Subject = $email_subject;
        $mail->Body = $email_body;

        $mail->send();
        header("Location: " . $success_redirect_url);
        exit;
    } catch (Exception $e) {
        header("Location: " . $error_redirect_url);
        exit;
    }
} else {
    // Si alguien intenta acceder a este script directamente sin enviar el formulario,
    // puedes redirigirlo a la página de inscripción o mostrar un mensaje de error.
    header("Location: inscripcion.html");
    exit;
}
?>