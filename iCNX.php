<?php
//$cnx=mysqli_connect("localhost","root","","dbtienda") or die("<font color=red> el sitio no se puede conectar");
define('DB_SERVER', 'mysql:host=localhost;dbname=dbtienda;charset=utf8');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
//define('DB_DATABASE', 'db_procastinar');
try{
    /* este codigo es para un servidor local
    $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    mysqli_query($db, "set names 'utf8' ");
    echo 'Conexion Exitosa!!'. "\n";
    */
    //throw new Exception("No se pudo abrir el archivo");
    $pdo = new PDO(DB_SERVER, DB_USERNAME, DB_PASSWORD);
    // Configurar el modo de error para excepciones
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "todo al 100";

}catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
