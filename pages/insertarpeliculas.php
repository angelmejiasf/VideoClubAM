<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Realizo la conexión
        $cadena_conexion = "mysql:dbname=videoclubonline;host=127.0.0.1";
        $usuario = "root";
        $clave = "";

        $opciones = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $conexion = new PDO($cadena_conexion, $usuario, $clave, $opciones);

        // Recopilar datos del formulario
        $titulo = $_POST['titulo'];
        $genero = $_POST['genero'];
        $pais = $_POST['pais'];
        $anyo = $_POST['anyo'];
        $cartel = $_POST['cartel'];

        // Comprobar si la película ya existe
        $sqlCheckMovie = "SELECT id FROM peliculas WHERE titulo = ?";
        $stmtCheckMovie = $conexion->prepare($sqlCheckMovie);
        $stmtCheckMovie->execute([$titulo]);
        $resultCheckMovie = $stmtCheckMovie->fetch();

        if (!$resultCheckMovie) {
            // Si la película no existe, insertarla
            $sqlInsertMovie = "INSERT INTO peliculas (titulo, genero, pais, anyo, cartel) VALUES (?, ?, ?, ?, ?)";
            $stmtInsertMovie = $conexion->prepare($sqlInsertMovie);
            $stmtInsertMovie->execute([$titulo, $genero, $pais, $anyo, $cartel]);

            // Obtener el ID de la película
            $idPelicula = $conexion->lastInsertId();

            header("Location:administrador.php");
        } else {
            echo "La película ya existe.";
        }
    } catch (PDOException $e) {
        echo "Error al insertar la película";
    }
} else {
    echo "Acceso no permitido.";
}
?>
