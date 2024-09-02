<?php

// Habilitar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'database.php';
require '../libraries/PHPMailer-6.9.1/src/Exception.php';
require '../libraries/PHPMailer-6.9.1/src/PHPMailer.php';
require '../libraries/PHPMailer-6.9.1/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$db = new Database();
$con = $db->conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $direccion_linea1 = trim($_POST['direccion_linea1']);
    $direccion_linea2 = isset($_POST['direccion_linea2']) ? trim($_POST['direccion_linea2']) : null;
    $ciudad = trim($_POST['ciudad']);
    $colonia = trim($_POST['colonia']);
    $codigo_postal = trim($_POST['codigo_postal']);
    $numero = trim($_POST['numero']);
    $correo = trim($_POST['correo']);
    $referencias = isset($_POST['referencias']) ? trim($_POST['referencias']) : null;
    $estado = 0; // Por defecto, estado pendiente

    $errors = [];

    // Validación del campo dirección
    if (empty($direccion_linea1)) {
        $errors[] = "La dirección principal es obligatoria.";
    }

    // Validación del campo ciudad
    if (empty($ciudad)) {
        $errors[] = "La ciudad es obligatoria.";
    }

    // Validación del código postal (solo números y longitud)
    if (!preg_match('/^[0-9]{5}$/', $codigo_postal)) {
        $errors[] = "El código postal debe ser un número de 5 dígitos.";
    }

    // Validación del número de teléfono (solo números)
    if (!preg_match('/^[0-9]{10}$/', $numero)) {
        $errors[] = "El número de teléfono debe tener 10 dígitos.";
    }

    // Validación del correo electrónico
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "El correo electrónico no es válido.";
    }

    if (empty($errors)) {
        // Si no hay errores, continúa con la inserción en la base de datos
        $sql = $con->prepare("INSERT INTO peticion (direccion_linea1, direccion_linea2, ciudad, colonia, codigo_postal, numero, correo, referencias, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $sql->execute([$direccion_linea1, $direccion_linea2, $ciudad, $colonia, $codigo_postal, $numero, $correo, $referencias, $estado]);

        $id = $con->lastInsertId();
        $folio = 'PET-' . date('Ymd') . '-' . str_pad($id, 5, '0', STR_PAD_LEFT);

        $sql = $con->prepare("UPDATE peticion SET folio = ? WHERE id = ?");
        $sql->execute([$folio, $id]);

        // Enviar el folio por correo electrónico
        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = 2;  // Para depuración. Cambia a 0 para desactivar la depuración o 2 para ver detalles
            $mail->Debugoutput = function($str, $level) {
                file_put_contents('phpmailer.log', gmdate('Y-m-d H:i:s') . "\t$level\t$str\n", FILE_APPEND);
            };

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';  // Servidor SMTP de Gmail
            $mail->SMTPAuth = true;
            $mail->Username = 'betancurtsamuel13@gmail.com';  // Tu correo de Gmail
            $mail->Password = 'ekig mogw dbew scph';  // Tu contraseña de Gmail (o contraseña de aplicación)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;  // Puerto para TLS

            $mail->setFrom('betancurtsamuel13@gmail.com', 'ITGS');
            $mail->addAddress($correo);  // Correo del destinatario

            $mail->isHTML(false);
            $mail->Subject = 'Folio de tu petición';
            $mail->Body    = "Hola,\n\nTu folio es: $folio.\nGracias por usar nuestro servicio.";

            if ($mail->send()) {
                echo 'El mensaje ha sido enviado';
            } else {
                echo 'No se pudo enviar el mensaje';
            }
        } catch (Exception $e) {
            echo "No se pudo enviar el mensaje. Error de PHPMailer: {$mail->ErrorInfo}";
        }

        header("Location: ../citas.php?status=success&folio=$folio");
        exit();
    } else {
        // Si hay errores, redirige con los errores como parámetros GET o muestra los errores
        header("Location: ../citas.php?status=error&message=" . urlencode(implode(", ", $errors)));
        exit();
    }
}
?>





