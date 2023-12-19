<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>VideoClub Inicia Sesion</title>
        <link rel="icon" href="./assets/images/logo.ico" type="image/x-icon">

        <link rel="stylesheet" type="text/css" href="./css/estilo.css" />

    </head>
    <body>

        <div class="container">

            <section class="section">
                <h1>Bienvenidos al VideoClub</h1>
                <article>

                    <form action="index.php" method="post">

                        <h1>Iniciar Sesion</h1>


                        <label for="username">Nombre</label>
                        <input type="text" id="username" name="username" required>

                        <label for="password">Clave</label>
                        <input type="password" id="password" name="password" required>

                        <button type="submit">Iniciar sesión</button>
                    </form>

                </article>

                <img src="./assets/images/logo.png">
                <?php
                session_start();

                //Mostrar hora de la ultima conexion
                if (isset($_COOKIE['ultima_conexion'])) {
                    $ultimaConexion = $_COOKIE['ultima_conexion'];
                    echo "<p>Última conexión: $ultimaConexion</p>";
                } else {
                    echo "Es la primera vez que te conectas";
                }


                $horaActual = date('Y-m-d H:i');
                setcookie('ultima_conexion', $horaActual, time() + (86400 * 30), "/");

                //Conexion a la base de datos
                try {
                    $cadena_conexion = "mysql:dbname=videoclubonline;host=127.0.0.1";
                    $usuario = "root";
                    $clave = "";

                    $opciones = [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ];

                    $conexion = new PDO($cadena_conexion, $usuario, $clave, $opciones);

                    //CREACION DE LOS USUARIOS (lo comento para que no de conflictos una vez creados)
                    /* try {
                      $pdo = new PDO($cadena_conexion, $usuario, $clave);
                      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                      // Hash de las contraseñas
                      $hash_clave_angel = password_hash('1234', PASSWORD_DEFAULT);
                      $hash_clave_mejias = password_hash('5678', PASSWORD_DEFAULT);

                      // Inserciones con contraseñas cifradas
                      $sql = "INSERT INTO `usuarios` (`id`, `username`, `password`, `rol`) VALUES
                      (6, 'angel', :hash_clave_angel, 1),
                      (7, 'mejias', :hash_clave_mejias, 0)";

                      $stmt = $pdo->prepare($sql);

                      // Bind de los valores hash
                      $stmt->bindParam(':hash_clave_angel', $hash_clave_angel, PDO::PARAM_STR);
                      $stmt->bindParam(':hash_clave_mejias', $hash_clave_mejias, PDO::PARAM_STR);

                      $stmt->execute();


                      } catch (PDOException $e) {
                      echo "Error de conexión: " . $e->getMessage();
                      } */
                    // Recoge los datos del formulario de inicio de sesión
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $username = $_POST["username"];
                        $password = $_POST["password"];

                        // Busca el usuario en la base de datos
                        $sql = "SELECT * FROM usuarios WHERE username = :username";
                        $stmt = $conexion->prepare($sql);
                        $stmt->bindParam(':username', $username);
                        $stmt->execute();

                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($row && password_verify($password, $row["password"])) {
                            $_SESSION["username"] = $username;
                            $_SESSION["rol"] = $row["rol"];

                            // Redirige según el rol
                            if ($row["rol"] == 0) {
                                header("Location: ./pages/usuario.php");
                            } elseif ($row["rol"] == 1) {
                                header("Location: ./pages/administrador.php");
                            }
                            exit();
                        } else {

                            echo "<p class='error'>Credenciales incorrectas</p>";
                        }
                    }
                } catch (PDOException $e) {
                    echo "La base de datos está actualmente en mantenimiento ";
                }
                ?>


            </section>



        </div>
    </body>
</html>



