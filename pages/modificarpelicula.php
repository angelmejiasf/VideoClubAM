<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Película</title>
    <link rel="icon" href="../assets/images/logo.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="../css/estilo.css" />
</head>
<body>
    <div class="container">
        <h1 class="title">Actualizar Película</h1>

        <form action="modificarpelicula.php" method="post">
            <label for="titulo">Introduce el título de la película que deseas actualizar:</label>
            <input type="text" name="titulo" required placeholder="Título de la película">

            <label for="nuevo_titulo">Nuevo Título:</label>
            <input type="text" name="nuevo_titulo" placeholder="Nuevo Título">

            <label for="nuevo_genero">Nuevo Género:</label>
            <input type="text" name="nuevo_genero" placeholder="Nuevo Género">

            <label for="nuevo_anyo">Nuevo Año:</label>
            <input type="number" name="nuevo_anyo" placeholder="Nuevo Año">

            <input type="submit" name="submit" value="Actualizar">
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

            //MODIFICAR PELÍCULA
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                if (isset($_POST['titulo']) && !empty($_POST['titulo']) &&
                    isset($_POST['nuevo_titulo']) && !empty($_POST['nuevo_titulo']) &&
                    isset($_POST['nuevo_genero']) && isset($_POST['nuevo_anyo'])) {

                    // Recuperar y validar los datos del formulario
                    $titulo = $_POST['titulo'];
                    $nuevo_titulo = $_POST['nuevo_titulo'];
                    $nuevo_genero = $_POST['nuevo_genero'];
                    $nuevo_anyo = $_POST['nuevo_anyo'];

                    try {
                        $pdo = new PDO($cadena_conexion, $usuario, $clave);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $sql = "UPDATE peliculas SET titulo = :nuevo_titulo, genero = :nuevo_genero, anyo = :nuevo_anyo WHERE titulo = :titulo";

                        $stmt = $pdo->prepare($sql);

                        $stmt->bindParam(':nuevo_titulo', $nuevo_titulo, PDO::PARAM_STR);
                        $stmt->bindParam(':nuevo_genero', $nuevo_genero, PDO::PARAM_STR);
                        $stmt->bindParam(':nuevo_anyo', $nuevo_anyo, PDO::PARAM_INT);
                        $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);

                        $stmt->execute();

                        // Redirigir después de la actualización
                        header("Location: administrador.php");
                        exit();
                    } catch (Exception $e) {
                        echo "Error en la modificiacion";
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
