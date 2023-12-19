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
                    $actores = isset($fila['actores']) ? explode('|', $fila['actores']) : [];

                    $pelicula = new Pelicula($fila['titulo'], $fila['genero'], $fila['pais'], $fila['anyo'], $fila['cartel']);

                    if (!empty($actores)) {
                        $pelicula->actores = array_map(function ($actor) {
                            $datos = explode(' ', $actor);
                            return [
                        'nombre' => isset($datos[0]) ? $datos[0] : '',
                        'apellidos' => isset($datos[1]) ? $datos[1] : '',
                        'fotografia' => isset($datos[2]) ? $datos[2] : ''
                            ];
                        }, $actores);
                    }

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
                        echo "<h1>{$pelicula->titulo}</h1>"
                        . "<p>Genero: {$pelicula->genero}</p>"
                        . "<p>Pais: {$pelicula->pais}</p>"
                        . "<p>Año: {$pelicula->anyo}</p>";

                        echo "<img src='../assets/images/{$pelicula->cartel}' width='200px'>";

                        echo "<form action='modificarpelicula.php' method='get'>";
                        echo "<button type='submit'>Modificar</button>";
                        echo "<input type='hidden' name='id' value='{$pelicula->id}'>";
                        echo "</form>";

                        echo "<form action='eliminarpelicula.php' method='get'>";
                        echo "<button type='submit'>Eliminar</button>";
                        echo "<input type='hidden' name='id' value='{$pelicula->id}'>";
                        echo "</form>";
                        if (isset($pelicula->actores) && is_array($pelicula->actores)) {
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
                        } else {
                            echo "<p>No hay información de actores para esta película.</p>";
                        }

                        echo "</div>";
                    }

                    echo "</div>";
                } else {
                    echo "No hay películas disponibles.";
                }
                ?>


                <section class="section_insertar">
                    <h2>Insertar Nueva Película</h2>
                    <form action="insertarpeliculas.php" method="post">
                        <label for="titulo">Título:</label>
                        <input type="text" name="titulo" required>

                        <label for="genero">Género:</label>
                        <input type="text" name="genero" required>

                        <label for="pais">País:</label>
                        <input type="text" name="pais" required>

                        <label for="anyo">Año:</label>
                        <input type="number" name="anyo" required>

                        <label for="cartel">Nombre del Cartel (imagen):</label>
                        <input type="text" name="cartel" required>



                        <button type="submit">Insertar Película</button>
                    </form>
                </section>


            </section>






        </div>
    </body>
</html>

