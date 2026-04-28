<?php
include 'db.php';
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!isset($_SESSION['id_residente'])) {
        die("Error: No se detectó la sesión del residente. Por favor, vuelve a iniciar sesión.");
    }

    $id_residente = $_SESSION['id_residente'];
    $concepto     = $_POST['concepto'];
    $monto        = $_POST['monto'];
    $folio        = $_POST['folio'];
    $fecha_pago   = $_POST['fecha_pago'];


    $res_cuota = mysqli_query($conexion, "SELECT id_cuota FROM Cuota LIMIT 1");
    if (mysqli_num_rows($res_cuota) > 0) {
        $fila_cuota = mysqli_fetch_assoc($res_cuota);
        $id_cuota = $fila_cuota['id_cuota'];
    } else {

        mysqli_query($conexion, "INSERT INTO Cuota (mes, anio, monto, descripcion) VALUES ('Abril', 2026, 500.00, 'Mantenimiento')");
        $id_cuota = mysqli_insert_id($conexion);
    }

    $carpeta_destino = "uploads/";
    
    if (!file_exists($carpeta_destino)) {
        mkdir($carpeta_destino, 0777, true);
    }

    $nombre_archivo = time() . "_" . basename($_FILES['comprobante']['name']);
    $ruta_final = $carpeta_destino . $nombre_archivo;

    if (move_uploaded_file($_FILES['comprobante']['tmp_name'], $ruta_final)) {
        
        $sql = "INSERT INTO Pago (id_residente, id_cuota, monto_pagado, archivo_comprobante, estado, concepto, folio) 
                VALUES ('$id_residente', '$id_cuota', '$monto', '$ruta_final', 'Pendiente', '$concepto', '$folio')";

        if (mysqli_query($conexion, $sql)) {
            echo "<script>
                    alert('¡Pago registrado con éxito! Pendiente de validación.');
                    window.location.href='misPagos.php';
                  </script>";
        } else {
            echo "Error al insertar registro: " . mysqli_error($conexion);
        }

    } else {
        echo "Error: No se pudo subir el archivo del comprobante. Revisa los permisos de la carpeta 'uploads'.";
    }
}
?>
