<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Película</title>
    <link rel="icon" href="../assets/images/logo.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="../css/estilo.css" />
</head>
<body>
    <div class="container">
        <h1 class="title">Eliminar Película</h1>

        <form action="eliminarpelicula.php" method="post" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta película?');">
            <label for="titulo">Introduce el título de la película que deseas eliminar:</label>
            <input type="text" name="titulo" required placeholder="Título de la película">

            <input type="submit" name="submit" value="Eliminar">
        </form>
        
        <img src="../assets/images/logo.png">

        <?php
        //Conexion Base de Datos
        $cadena_conexion = "mysql:dbname=videoclubonline;host=127.0.0.1";
        $usuario = "root";
        $clave = "";

        try {
            $pdo = new PDO($cadena_conexion, $usuario, $clave);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // ELIMINAR PELÍCULA
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                if (isset($_POST['titulo']) && !empty($_POST['titulo'])) {

                    // Recuperar y validar los datos del formulario
                    $titulo = $_POST['titulo'];

                    try {
                        $pdo = new PDO($cadena_conexion, $usuario, $clave);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $sql = "DELETE FROM peliculas WHERE titulo = :titulo";

                        $stmt = $pdo->prepare($sql);

                        $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);

                        $stmt->execute();

                        // Redirigir después de la eliminación
                        header("Location: administrador.php");
                        exit();
                    } catch (Exception $e) {
                        echo "Error en la eliminación: " . $e->getMessage();
                    }
                }
            }
        } catch (PDOException $e) {
            echo "La base de datos esta actualmente en mantenimiento";
        }
        ?>
    </div>
</body>
</html>
