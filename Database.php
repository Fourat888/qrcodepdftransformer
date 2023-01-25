<?php 
class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "fromq2035226";
        $error="";
        // Create connection
        // $this->conn = mysqli_connect($servername, $username, $password, $dbname);
        $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // Check connection
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
?>