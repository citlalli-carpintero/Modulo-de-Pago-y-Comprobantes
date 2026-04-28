    <?php
    include 'db.php';

    if (isset($_POST['id_pago'])) {
        $id = $_POST['id_pago'];
        $nuevo_estado = $_POST['nuevo_estado'];
        $update = "UPDATE Pago SET estado = '$nuevo_estado' WHERE id_pago = $id";
        mysqli_query($conexion, $update);
        header("Location: validarComprobantes.php");
        exit();
    }
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Validar Pagos - Administración</title>
        <link rel="stylesheet" href="css/validarComprobante.css">
    </head>
    <body>
        <div class="main-fullscreen-container">
            <div class="content-wrapper-xl">
                <div class="header">
                    <span class="back-btn" onclick="window.location.href='menuAdministrador.php'">←</span>
                    <h1>Validación de Pagos</h1>
                </div>

                <div class="cards-container">
                    <?php
                    $query = "SELECT p.*, r.nombre, r.apellidoPaterno, r.num_residencia, c.mes 
                            FROM Pago p
                            JOIN Residente r ON p.id_residente = r.id_residente
                            JOIN Cuota c ON p.id_cuota = c.id_cuota
                            WHERE p.estado = 'Pendiente'
                            ORDER BY p.fecha_envio ASC";
                    
                    $res = mysqli_query($conexion, $query);

                    if (mysqli_num_rows($res) == 0) {
                        echo "<div style='text-align:center; width:100%; padding: 50px;'>
                                <h3>🎉 ¡Todo al día! No hay pagos pendientes.</h3>
                            </div>";
                    }

                    while($row = mysqli_fetch_assoc($res)) {
                    ?>
                    <div class="resident-card">
                        <div class="card-info">
                            <div class="user-header">
                                <span class="user-avatar-icon">👤</span>
                                <div>
                                    <label>RESIDENTE</label>
                                    <span class="resident-name">
                                        <?php echo $row['nombre'] . " " . $row['apellidoPaterno']; ?>
                                    </span>
                                </div>
                            </div>

                            <div class="card-grid">
                                <div class="info-item">
                                    <label>CONCEPTO</label>
                                    <span class="data-text">
                                        <?php echo !empty($row['concepto']) ? $row['concepto'] : $row['mes']; ?>
                                    </span>
                                </div>

                                <div class="info-item">
                                    <label>MONTO</label>
                                    <span class="tag tag-blue">$<?php echo number_format($row['monto_pagado'], 2); ?></span>
                                </div>

                                <div class="info-item">
                                    <label>CASA</label>
                                    <span class="data-text"><?php echo $row['num_residencia']; ?></span>
                                </div>
                            </div>

                            <div class="file-preview-container">
                                <label>COMPROBANTE:</label>
                                <div class="preview-box" onclick="window.open('uploads/<?php echo $row['archivo_comprobante']; ?>')">
                                    <img src="uploads/<?php echo $row['archivo_comprobante']; ?>" alt="Recibo">
                                    <div class="overlay">Ver pantalla completa</div>
                                </div>
                            </div>
                        </div>

                        <div class="card-actions">
                            <form method="POST" onsubmit="return confirm('¿Confirmar acción?')">
                                <input type="hidden" name="id_pago" value="<?php echo $row['id_pago']; ?>">
                                <button type="submit" name="nuevo_estado" value="Validado" class="btn-primary">Validar</button>
                                <button type="submit" name="nuevo_estado" value="Rechazado" class="btn-secondary">Rechazar</button>
                            </form>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </body>
    </html>
