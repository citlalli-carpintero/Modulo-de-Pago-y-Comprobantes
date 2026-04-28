<?php
session_start();
include 'db.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'residente') {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Pago - Sistema Residencial</title>
    <link rel="stylesheet" href="css/registroP.css">
</head>
<body>

<div class="app-container">
    <div class="header">
        <span class="back-btn" onclick="window.location.href='menuResidente.php'"> ← </span>
        <h2>Registro pago mantenimiento</h2>
    </div>

    <form id="pagoForm" action="procesar_pago.php" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
            <label>Selecciona el gasto a cubrir</label>
            <select name="concepto" id="concepto" required>
                <option value="" disabled selected>Concepto</option>
                <option value="alberca">Alberca</option>
                <option value="parque">Parque</option>
                <option value="canchas">Canchas</option>
                <option value="mantenimiento_mensual">Mantenimiento Mensual</option>
            </select>
        </div>

        <div class="row">
            <div class="col">
                <label>Banco cuenta origen</label>
                <input type="text" name="banco" placeholder="Ej. BBVA" required>
            </div>
            <div class="col">
                <label>Últimos 4 dígitos tarjeta</label>
                <input type="text" name="tarjeta" maxlength="4" placeholder="1234" required>
            </div>
        </div>

        <div class="form-group">
            <label>Folio transacción</label>
            <input type="text" name="folio" id="folio" maxlength="16" placeholder="xxxxxxxxxxxxxxxx" required>
        </div>

        <div class="row">
            <div class="col">
                <label>Fecha pago</label>
                <input type="date" name="fecha_pago" id="fecha" required>
            </div>
            <div class="col">
                <label>Cantidad pagada</label>
                <input type="number" name="monto" step="0.01" placeholder="$ 0.00" required>
            </div>
        </div>

        <div class="upload-area" onclick="document.getElementById('fileInput').click()">
            <span style="font-size: 24px;">📁</span>
            <p id="fileName" style="font-size: 12px; color: #777;">Haz clic para subir captura de pago</p>
            <input type="file" name="comprobante" id="fileInput" style="display: none;" accept="image/*,.pdf" required onchange="updateFileName()">
        </div>

        <button type="submit" class="btn-main">Registrar Pago</button>
    </form>
</div>

<script>
    function updateFileName() {
        const input = document.getElementById('fileInput');
        const label = document.getElementById('fileName');
        if (input.files.length > 0) {
            label.innerText = "Archivo seleccionado: " + input.files[0].name;
            label.style.color = "#27ae60";
        }
    }
</script>

</body>
</html>
