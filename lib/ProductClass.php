<?php

/**
 * Description of ProductClass
 *
 * @author tudor.gologan
 */

require_once (__DIR__ . "/conf.php");
class ProductClass {
    //put your code here
    private $conn;
    
    function __construct(){
        $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASS, DB_NAME);
    }
    
    function getProducts(){
        $query = "SELECT * FROM products";        
        $result = $this->conn->query($query);
        
        $retval = array();
        while ($row = $result->fetch_assoc()) {
            $retval[] = $row;
        }
        return $retval;
        
    }
    
}
