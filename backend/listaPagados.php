<?php
include 'db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Residentes al Corriente - ITZ</title>
    <link rel="stylesheet" href="css/listaRegistro.css">
</head>
<body>
    <div class="main-fullscreen-container">
        <div class="content-wrapper-xl">
            <div class="header">
                <span class="back-btn" onclick="window.location.href='menuAdministrador.php'">←</span>
                <h1>Residentes al Corriente</h1>
            </div>
            <p class="subtitle">Lista de residentes con pagos validados en el sistema.</p>

            <div class="cards-container">
                <?php                
                $query = "SELECT p.*, r.nombre, r.apellidoPaterno, r.num_residencia, c.mes, c.anio 
                          FROM Pago p
                          JOIN Residente r ON p.id_residente = r.id_residente
                          JOIN Cuota c ON p.id_cuota = c.id_cuota
                          WHERE p.estado = 'Validado'
                          ORDER BY p.fecha_envio DESC";
                
                $res = mysqli_query($conexion, $query);

                if (mysqli_num_rows($res) == 0) {
                    echo "<p style='text-align:center;'>No hay registros de pagos validados aún.</p>";
                }

                while($row = mysqli_fetch_assoc($res)) {
                ?>
                <div class="resident-card" style="border-left: 5px solid #2ecc71;">
                    <div class="card-info">
                        <div class="user-header">
                            <span class="user-avatar-icon" style="background-color: #e8f5e9; color: #27ae60;">✔</span>
                            <div>
                                <label>Residente:</label>
                                <span class="resident-name"><?php echo $row['nombre'] . " " . $row['apellidoPaterno']; ?></span>
                            </div>
                        </div>
                        <div class="card-grid">
                            <div class="info-item">
                                <label>Concepto Pagado:</label>
                                <span class="data-text">
                                    <?php                                         
                                        $conceptoReal = trim($row['concepto']);
                                        echo !empty($conceptoReal) ? $conceptoReal : ($row['mes'] . " " . $row['anio']); 
                                    ?>
                                </span>
                            </div>
                            <div class="info-item">
                                <label>Monto Recibido:</label>
                                <span class="tag tag-blue">$<?php echo number_format($row['monto_pagado'], 2); ?></span>
                            </div>
                            <div class="info-item">
                                <label>Casa:</label>
                                <span class="data-text"><?php echo $row['num_residencia']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
