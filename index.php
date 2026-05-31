<?php
session_start();

// Base de datos de usuarios simulada en PHP (para coincidir con database.js)
$usuarios_db = [
    [
        'id' => 1,
        'nombre' => 'Ana Garcia',
        'email' => 'ana@email.com',
        'password' => 'ana123',
        'avatar' => 'AG'
    ],
    [
        'id' => 2,
        'nombre' => 'Carlos Lopez',
        'email' => 'carlos@email.com',
        'password' => 'carlos456',
        'avatar' => 'CL'
    ],
    [
        'id' => 3,
        'nombre' => 'Admin',
        'email' => 'admin@tienda.com',
        'password' => 'admin000',
        'avatar' => 'AD'
    ]
];

$error_login = null;

// Manejo del Login (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $usuario_encontrado = null;
        foreach ($usuarios_db as $u) {
            if ($u['email'] === $email && $u['password'] === $password) {
                $usuario_encontrado = $u;
                break;
            }
        }

        if ($usuario_encontrado) {
            $_SESSION['usuario'] = $usuario_encontrado;
            if (!isset($_SESSION['carrito'])) {
                $_SESSION['carrito'] = [];
            }
            header("Location: index.php");
            exit;
        } else {
            $error_login = "Correo o contrasena incorrectos.";
        }
    }
}

// Manejo del Logout (GET)
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    unset($_SESSION['usuario']);
    unset($_SESSION['carrito']);
    session_destroy();
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8" />

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    />

    <title>
        <?= htmlspecialchars($titulo ?? 'Elite Moda') ?>
    </title>

    <meta name="description"
          content="Elite Moda es una tienda online de ropa, accesorios y moda con compras seguras y envíos rápidos en Colombia.">

    <meta name="keywords"
          content="ropa, moda, tienda online, ecommerce, accesorios, Colombia, Elite Moda">

    <meta name="robots"
          content="index, follow">

    <meta name="author"
          content="Elite Moda">

    <meta property="og:title"
          content="Elite Moda - Tienda Online">

    <meta property="og:description"
          content="Compra ropa y accesorios de moda de forma fácil y segura en Elite Moda.">

    <meta property="og:type"
          content="website">

    <meta property="og:url"
          content="https://ecommerce-tiendaelite.onrender.com">

    <meta property="og:site_name"
          content="Elite Moda">

    <meta property="og:locale"
          content="es_CO">

    <link rel="canonical"
          href="https://ecommerce-tiendaelite.onrender.com">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/variables.css">
    <link rel="stylesheet" href="assets/css/base.css">
    <link rel="stylesheet" href="assets/css/carrito.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/hero.css">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/modal.css">
    <link rel="stylesheet" href="assets/css/navbar.css">
    <link rel="stylesheet" href="assets/css/productos.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <link rel="stylesheet" href="assets/css/toast.css">

</head>


<body>

    <!-- NAVBAR -->
    <?php include 'views/navbar.phtml'; ?>


    <!-- LOGIN -->
    <?php include 'views/login.phtml'; ?>


    <!-- HERO -->
    <?php include 'includes/hero.phtml'; ?>


    <!-- PRODUCTOS -->
    <?php include 'views/productos.phtml'; ?>


    <!-- FOOTER -->
    <?php include 'includes/footer.phtml'; ?>


    <!-- CARRITO -->
    <?php include 'views/carrito.phtml'; ?>


    <!-- TOAST -->
    <div id="views-toast">

        <div class="toast" id="toast"></div>

    </div>


    <!-- MODAL PRODUCTO -->
    <?php include 'views/modal-producto.phtml'; ?>


    <!-- MODAL ALERTA -->
    <?php include 'views/modal-alerta.phtml'; ?>


    <!-- TEMPLATES -->
    <?php include 'views/templates.phtml'; ?>


    <!-- JAVASCRIPT -->
    <script src="assets/js/database.js"></script>
    <script src="assets/js/ui.js"></script>
    <script src="assets/js/sesion.js"></script>
    <script src="assets/js/carrito.js"></script>
    <script src="assets/js/catalogo.js"></script>
    <script src="assets/js/audio.js"></script>

    <!-- SINCRONIZACIÓN DE ESTADO PHP -> JS -->
    <script>
        usuarioActual = <?= isset($_SESSION['usuario']) ? json_encode($_SESSION['usuario']) : 'null' ?>;
        carrito = <?= isset($_SESSION['carrito']) ? json_encode(array_values($_SESSION['carrito'])) : '[]' ?>;
    </script>


    <script>

        document.addEventListener("DOMContentLoaded", () => {

            if (usuarioActual) {
                if (typeof uiMostrarSesionActiva === "function") {
                    uiMostrarSesionActiva(usuarioActual);
                }
            }

            if (typeof renderizarProductos === "function" &&
                typeof obtenerProductos === "function") {

                renderizarProductos(obtenerProductos());
            }

            if (typeof iniciarAudio === "function") {
                iniciarAudio();
            }

            if (typeof actualizarBadge === "function") {
                actualizarBadge();
            }

            <?php if (!empty($error_login)): ?>
                if (typeof abrirLogin === "function") {
                    abrirLogin();
                    if (typeof uiMostrarErrorLogin === "function") {
                        uiMostrarErrorLogin(<?= json_encode($error_login) ?>);
                    }
                }
            <?php endif; ?>

        });


        function irAProductos() {

            const seccion = document.getElementById("productos");

            if (seccion) {

                seccion.scrollIntoView({
                    behavior: "smooth"
                });

            }

        }

    </script>

</body>

</html>