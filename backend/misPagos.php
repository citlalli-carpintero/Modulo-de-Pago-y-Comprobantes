<?php
session_start();
include 'db.php';

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'residente') {
    header("Location: login.html");
    exit();
}

$id_residente = $_SESSION['id_residente'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Pagos - Sistema Residencial</title>
    <link rel="stylesheet" href="css/listaRegistro.css">
    <style>
        .status-pendiente { background-color: #f1c40f; color: #fff; padding: 4px 8px; border-radius: 4px; }
        .status-validado { background-color: #27ae60; color: #fff; padding: 4px 8px; border-radius: 4px; }
        .status-rechazado { background-color: #e74c3c; color: #fff; padding: 4px 8px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="main-fullscreen-container">
        <div class="content-wrapper-xl">
            <div class="header">
                <span class="back-btn" onclick="window.location.href='menuResidente.php'">←</span>
                <h1>Mi Historial de Pagos</h1>
            </div>

            <div class="cards-container">
                <?php
                
                $query = "SELECT p.*, c.mes, c.anio 
                          FROM pago p
                          JOIN cuota c ON p.id_cuota = c.id_cuota
                          WHERE p.id_residente = '$id_residente'
                          ORDER BY p.fecha_envio DESC";
                
                $res = mysqli_query($conexion, $query);

                if (mysqli_num_rows($res) == 0) {
                    echo "<p style='text-align:center;'>Aún no has registrado ningún pago.</p>";
                }

                while($row = mysqli_fetch_assoc($res)) {
                    $clase_estado = "";
                    if($row['estado'] == 'Pendiente') $clase_estado = "status-pendiente";
                    elseif($row['estado'] == 'Validado') $clase_estado = "status-validado";
                    else $clase_estado = "status-rechazado";
                ?>
                <div class="resident-card">
                    <div class="card-info">
                        <div class="user-header">
                            <span class="user-avatar-icon">💰</span>
                            <div>
                                <label>Concepto:</label>
                                <span class="resident-name"><?php echo !empty($row['concepto']) ? $row['concepto'] : ($row['mes'] . " " . $row['anio']); ?></span>
                            </div>
                        </div>
                        <div class="card-grid">
                            <div class="info-item">
                                <label>Monto pagado:</label>
                                <span class="data-text">$<?php echo number_format($row['monto_pagado'], 2); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Fecha de envío:</label>
                                <span class="data-text"><?php echo date('d/m/Y', strtotime($row['fecha_envio'])); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Estado:</label>
                                <span class="<?php echo $clase_estado; ?>"><?php echo $row['estado']; ?></span>
                            </div>
                        </div>
                        
                        <?php if($row['estado'] == 'Rechazado' && !empty($row['motivo_rechazo'])): ?>
                        <div style="margin-top: 10px; color: #e74c3c; font-size: 0.9em;">
                            <strong>Nota:</strong> <?php echo $row['motivo_rechazo']; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
