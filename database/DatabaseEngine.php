<?php

class DatabaseEngine {
    private static $_HOST = "localhost";
    private static $_TABLE = "website";
    private static $_USER = "main";
    private static $_PASSWORD = "main";

    private $connection = null;

    public function __construct () {
        $conn = new mysqli(self::$_HOST, self::$_USER, self::$_PASSWORD, self::$_TABLE);

        if ($conn->connect_error) {
            throw new Exception("Database connection failed: " . $conn->connect_error);
        } 

        //Setup the connection to be used troughout the class
        $this->connection = $conn;
    }

    //
    // Get a Full Table via stored procedure
    //  [
    //      [data, ...],
    //      [data, ...],
    //  ]
    //
    public function GetTableAsFlatArray(string $tableName) : array {
        //PL only matches letters. Everything not a letter is bad
        $tableName = preg_replace('/\PL/u', '', $tableName);
        $sql = "SELECT * FROM $tableName";
        $result = $this->connection->query($sql);
        
        $data = mysqli_fetch_all($result);

        return $data;
    } 

}


?>