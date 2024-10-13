<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "expedientes_escolares";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $numero_expediente = $_POST['numero_expediente'];
    $solicitante = $_POST['solicitante'];
    $asunto = $_POST['asunto'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $tipo_documento = $_POST['tipo_documento'];
    $num_folios = $_POST['num_folios'];
    $area_destino = $_POST['area_destino'];
    $requisitos = json_encode($_POST['requisitos']); 
    $upload_dir = "uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $documentos_adjuntos = '';
    if (!empty($_FILES['documentos_adjuntos']['name'][0])) {
        $total_files = count($_FILES['documentos_adjuntos']['name']);
        for ($i = 0; $i < $total_files; $i++) {
            $file_name = $_FILES['documentos_adjuntos']['name'][$i];
            $target_file = $upload_dir . basename($file_name);
            if (move_uploaded_file($_FILES['documentos_adjuntos']['tmp_name'][$i], $target_file)) {
                $documentos_adjuntos .= $target_file . ',';
            } else {
                echo "Error al subir el archivo: " . $file_name . "<br>";
            }
        }
        $documentos_adjuntos = rtrim($documentos_adjuntos, ','); // Eliminar la última coma
    }

    $sql = "INSERT INTO expedientes (numero_expediente, solicitante, asunto, fecha, hora, tipo_documento, num_folios, area_destino, requisitos, documentos_adjuntos) 
            VALUES ('$numero_expediente', '$solicitante', '$asunto', '$fecha', '$hora', '$tipo_documento', '$num_folios', '$area_destino', '$requisitos', '$documentos_adjuntos')";

    if ($conn->query($sql) === TRUE) {
        echo "Expediente registrado con éxito.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
