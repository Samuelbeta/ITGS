<?php
$status = isset($_GET['status']) ? $_GET['status'] : '';
$folio = isset($_POST['folio']) ? $_POST['folio'] : '';
$peticion = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($folio)) {
    require 'config/database.php';
    $db = new Database();
    $con = $db->conectar();

    $sql = $con->prepare("SELECT * FROM peticion WHERE folio = ?");
    $sql->execute([$folio]);
    $peticion = $sql->fetch(PDO::FETCH_ASSOC);

    if ($peticion) {
        session_start();
        $_SESSION['peticion'] = $peticion;
        header("Location: citas.php?status=found#detalles-peticion"); // Ancla a la sección de detalles
        exit();
    } else {
        header("Location: citas.php?status=not_found"); // Sin ancla si no se encuentra la petición
        exit();
    }
} else {
    session_start();
    $peticion = isset($_SESSION['peticion']) ? $_SESSION['peticion'] : null;
    unset($_SESSION['peticion']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Citas - Seguridad</title>
    <link rel="stylesheet" href="config/styles.css">
</head>
<body>
    <header>
        <div id="logo">
            <img src="img/ITGS_logo_resized.png" alt="ITGS Logo">
        </div>
        <button id="menu-toggle">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
        <nav id="nav-menu">
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="about.html">Sobre Nosotros</a></li>
                <li><a href="services.html">Servicios</a></li>
                <li><a href="contact.html">Contacto</a></li>
                <li><a href="citas.php">Citas</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="form-cita">
            <h2>Registrar Nueva Petición</h2>

            <!-- Formulario de registro de citas -->
            <form action="config/guardar.cita.php" method="post">
                <label for="direccion_linea1">Dirección Calle Principal:</label>
                <input type="text" id="direccion_linea1" name="direccion_linea1" placeholder="Ingresa la dirección principal" required>

                <label for="direccion_linea2">Dirección Calle Secundaria:</label>
                <input type="text" id="direccion_linea2" name="direccion_linea2" placeholder="Ingresa detalles adicionales (opcional)">

                <label for="ciudad">Ciudad:</label>
                <input type="text" id="ciudad" name="ciudad" placeholder="Ingresa la ciudad" required>

                <label for="colonia">Colonia:</label>
                <input type="text" id="colonia" name="colonia" placeholder="Ingresa la colonia" required>

                <label for="codigo_postal">Código Postal:</label>
                <input type="text" id="codigo_postal" name="codigo_postal" placeholder="Ingresa el código postal" required>

                <label for="numero">Número de Teléfono:</label>
                <input type="tel" id="numero" name="numero" placeholder="Ingresa el número de teléfono" required>

                <label for="correo">Correo Electrónico:</label>
                <input type="email" id="correo" name="correo" placeholder="Ingresa el correo electrónico" required>

                <label for="referencias">Referencias:</label>
                <textarea id="referencias" name="referencias" placeholder="Ingresa referencias adicionales (opcional)"></textarea>

                <button type="submit">Guardar</button>
            </form>
        </section>

        <section id="buscar-peticion">
            <h2>Buscar Petición por Folio</h2>
            <form method="post">
                <label for="folio">Ingresa tu Folio:</label>
                <input type="text" id="folio" name="folio" placeholder="Ingresa tu folio" required>
                <button type="submit">Buscar</button>
            </form>

            <?php if ($peticion): ?>
                <div id="detalles-peticion" class="detalles-peticion">
                    <h3>Detalles de la Petición</h3>
                    <p><strong>Folio:</strong> <?php echo htmlspecialchars($peticion['folio']); ?></p>
                    <p><strong>Dirección:</strong> <?php echo htmlspecialchars($peticion['direccion_linea1'] . " " . $peticion['direccion_linea2']); ?></p>
                    <p><strong>Ciudad:</strong> <?php echo htmlspecialchars($peticion['ciudad']); ?></p>
                    <p><strong>Colonia:</strong> <?php echo htmlspecialchars($peticion['colonia']); ?></p>
                    <p><strong>Código Postal:</strong> <?php echo htmlspecialchars($peticion['codigo_postal']); ?></p>
                    <p><strong>Número de Teléfono:</strong> <?php echo htmlspecialchars($peticion['numero']); ?></p>
                    <p><strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($peticion['correo']); ?></p>
                    <p><strong>Referencias:</strong> <?php echo htmlspecialchars($peticion['referencias']); ?></p>
                    <p><strong>Estado:</strong> 
                        <?php
                        if ($peticion['estado'] == 0) {
                            echo "Pendiente";
                        } elseif ($peticion['estado'] == 1) {
                            echo "En Proceso";
                        } elseif ($peticion['estado'] == 2) {
                            echo "Completada";
                        } else {
                            echo "Desconocido";
                        }
                        ?>
                    </p>
                </div>
            <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
                <p>No se encontró ninguna petición con el folio proporcionado.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Seguridad Inc. Todos los derechos reservados.</p>
    </footer>

    <!-- Modal para mostrar mensajes -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modal-message"></p>
        </div>
    </div>

    <script src="config/ventana.js"></script>
</body>
</html>













