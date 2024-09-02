<?php

require 'config/database.php';
$db = new Database();
$con = $db->conectar();

$sql = $con->prepare("SELECT id, nombre, precio, descripcion FROM productos WHERE estado = 1");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITGS</title>
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
        <section id="hero">
            <h1>Bienvenido a Nuestra Página de Seguridad</h1>
            <p>Protege tu negocio</p>
        </section>
        <section id="catalogo">
            <h1>Catalogo</h1>
            <div class="products">
                <?php foreach ($resultado as $row) { ?>
                    <?php
                    $id = $row['id'];
                    $imagen = "img/" . $id . "/principal.jpg";

                    if (!file_exists($imagen)) {
                        $imagen = "img/nofoto.png";
                    }
                    ?>

                    <div class="product">
                        <img src="<?php echo $imagen; ?>" alt="<?php echo $row['nombre']; ?>">
                        <div>
                            <h5><?php echo $row['nombre']; ?></h5>
                            <p><?php echo number_format($row['precio'], 2, '.', ','); ?></p>
                            <p><?php echo $row['descripcion']; ?></p>
                        </div>
                    </div>

                <?php } ?>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 ITGS. Todos los derechos reservados.</p>
    </footer>
    <script src="config/script.js"></script>
</body>
</html>