<?php
include 'db.php';
$mes_actual = "Abril";
$anio_actual = 2026;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Residentes con Adeudo - ITZ</title>
    <link rel="stylesheet" href="css/listaRegistro.css">
</head>
<body>
    <div class="main-fullscreen-container">
        <div class="content-wrapper-xl">
            <div class="header">
                <span class="back-btn" onclick="window.location.href='menuAdministrador.php'">←</span>
                <h1>Residentes con Adeudo</h1>
            </div>
            <p class="subtitle">Residentes que no han registrado pago de <?php echo "$mes_actual $anio_actual"; ?>.</p>

            <div class="cards-container">
                <?php            
                $query = "SELECT * FROM Residente 
                          WHERE id_residente NOT IN (
                              SELECT id_residente FROM Pago p
                              JOIN Cuota c ON p.id_cuota = c.id_cuota
                              WHERE c.mes = '$mes_actual' AND c.anio = $anio_actual
                          )";
                
                $res = mysqli_query($conexion, $query);

                if (mysqli_num_rows($res) == 0) {
                    echo "<p style='text-align:center;'>¡Excelente! Todos los residentes están al corriente.</p>";
                }

                while($row = mysqli_fetch_assoc($res)) {
                ?>
                <div class="resident-card" style="border-left: 5px solid #e74c3c;">
                    <div class="card-info">
                        <div class="user-header">
                            <span class="user-avatar-icon" style="background-color: #fceae9; color: #e74c3c;">!</span>
                            <div>
                                <label>Residente en Mora:</label>
                                <span class="resident-name"><?php echo $row['nombre'] . " " . $row['apellidoPaterno']; ?></span>
                            </div>
                        </div>
                        <div class="card-grid">
                            <div class="info-item">
                                <label>Número de Casa:</label>
                                <span class="tag" style="background-color:#fceae9; color:#e74c3c;"><?php echo $row['num_residencia']; ?></span>
                            </div>
                            <div class="info-item">
                                <label>Estado:</label>
                                <span class="data-text" style="color:#e74c3c; font-weight:bold;">Pago Pendiente</span>
                            </div>
                            <div class="info-item">
                                <label>Contacto:</label>
                                <span class="data-text"><?php echo $row['telefono']; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="btn-secondary" onclick="alert('Enviando recordatorio al correo...')">Notificar</button>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
