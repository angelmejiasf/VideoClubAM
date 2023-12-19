<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>VideoClub Administrador</title>
        <link rel="icon" href="../assets/images/logo.ico" type="image/x-icon">
        <link rel="stylesheet" type="text/css" href="../css/estilo.css" />


    </head>
    <body>

        <div class="container">


            <?php
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

                // Consultar películas con actores mediante JOIN y agrupar por película
                $sql = "SELECT p.id, p.titulo, p.genero, p.pais, p.anyo, p.cartel, 
                   GROUP_CONCAT(CONCAT(a.nombre, ' ', a.apellidos, ' ', a.fotografia) SEPARATOR '|') AS actores
                    FROM peliculas p
                    LEFT JOIN actuan ac ON p.id = ac.idPelicula
                    LEFT JOIN actores a ON ac.idActor = a.id
                    GROUP BY p.id";

                $stmt = $conexion->prepare($sql);
                $stmt->execute();
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $peliculas = [];

                // Obtengo los datos de todas las películas
                foreach ($resultados as $fila) {
                    $actores = explode('|', $fila['actores']);
                    $pelicula = new Pelicula($fila['titulo'], $fila['genero'], $fila['pais'], $fila['anyo'], $fila['cartel']);
                    $pelicula->actores = array_map(function ($actor) {
                        $datos = explode(' ', $actor);
                        return [
                    'nombre' => $datos[0],
                    'apellidos' => $datos[1],
                    'fotografia' => $datos[2]
                        ];
                    }, $actores);
                    $peliculas[] = $pelicula;
                }
            } catch (PDOException $e) {
                echo "Error de conexión: " . $e->getMessage();
            }

            //Clase para la pelicula
            class Pelicula {

                public $id;
                public $titulo;
                public $genero;
                public $pais;
                public $anyo;
                public $cartel;
                public $actores;

                public function __construct($titulo, $genero, $pais, $anyo, $cartel) {
                    $this->titulo = $titulo;
                    $this->genero = $genero;
                    $this->pais = $pais;
                    $this->anyo = $anyo;
                    $this->cartel = $cartel;
                }
            }
            ?>

            <section class="section_peliculas">
                <?php
                if (isset($peliculas) && !empty($peliculas)) {
                    // Mostrar las películas
                    echo "<div class='peliculas'>";

                    foreach ($peliculas as $pelicula) {
                        echo "<div class='card'>";
                        echo "<h1> {$pelicula->titulo}</h1>"
                        . "<p>Genero: {$pelicula->genero}</p>"
                        . "<p>Pais: {$pelicula->pais}</p>"
                        . "<p>Año: {$pelicula->anyo}</p>";

                        echo "<img src='../assets/images/{$pelicula->cartel}' width='200px'>";

                        // Muestra los actores
                        echo "<h3>Reparto</h3>";
                        echo "<div class='actores'>";
                        foreach ($pelicula->actores as $actor) {
                            echo "<div class='actor'>";
                            echo "<p>{$actor['nombre']} {$actor['apellidos']}</p>"
                            . "<img src='../assets/images/{$actor['fotografia']}' width='150px'>";
                            echo "</div>";
                        }
                        echo "</div>";

                        echo "</div>";
                    }

                    echo "</div>";
                } else {
                    echo "No hay películas disponibles.";
                }
                ?>



                <?php

                use PHPMailer\PHPMailer\PHPMailer;
                use PHPMailer\PHPMailer\Exception;

                require '../phpmailer/src/PHPMailer.php';
                require '../phpmailer/src/SMTP.php';
                require '../phpmailer/src/Exception.php';
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["enviar"])) {




                    $mail = new PHPMailer(true);

                    try {
                        // Configuración del servidor SMTP
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'videoclub20232024@gmail.com';
                        $mail->Password = 'nnyu ngrk xoct qpyh';
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        // Configuración del correo
                        $mail->setFrom('angelmejiasfigueras2002@gmail.com', 'Angel');
                        $mail->addAddress('videoclub20232024@gmail.com', 'VideoClub');
                        $mail->isHTML(true);
                        $mail->Subject = $_POST["asunto"];
                        $mail->Body = $_POST["mensaje"];

                        // Envío del correo
                        $mail->send();
                        echo "<p class='success'>Correo enviado con éxito.</p>";
                    } catch (Exception $e) {
                        echo "<p class='error'>Error al enviar el correo: {$mail->ErrorInfo}</p>";
                    }
                }
                ?>
            </section>




            <article class="formulario">
                <h1 class="title">Enviar Incidendia</h1>

                <form action="usuario.php" method="post">


                    <label for="asunto">Asunto:</label>
                    <input type="text" name="asunto" required placeholder="Asunto del correo">

                    <label for="mensaje">Mensaje:</label>
                    <textarea name="mensaje" required placeholder="Escribe tu mensaje"></textarea>

                    <input type="submit" name="enviar" value="Enviar Correo">
                </form>
            </article>
        </div>
    </body>
</html>

