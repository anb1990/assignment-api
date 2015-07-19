<?php

/**
 * Handling database connection with Singelton Desgin Pattern
 *
 */
class DbConnect {

    private $_connection;
    private static $_instance; //The single instance
    private $_host = "localhost";
    private $_username = "root";
    private $_password = "VumilaatqDB@20156";
    private $_database = "yiiproject";

    /*
      Get an instance of the Database
      @return Instance
     */

    public static function getInstance() {
        if (!self::$_instance) { // If no instance then make one
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // Constructor
    private function __construct() {
        $this->_connection = new mysqli($this->_host, $this->_username, $this->_password, $this->_database);

        // Error handling
        if (mysqli_connect_error()) {
            trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(), E_USER_ERROR);
        }
    }

    // Clone is to prevent duplication of connection
    private function __clone() {
        
    }

    // Get mysqli connection
    public function getConnection() {
        return $this->_connection;
    }

}

?>