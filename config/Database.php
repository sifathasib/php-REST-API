<?php
    class Databse{

        // config database
        private $host ='localhost' ;
        private $db_name ='api_db';
        private $user = 'root';
        private $password = '123456';
        public $conn;

        //connection to databse
        public function getConnection(){
            $this->conn = null;
            try{
                $this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->db_name, $this->user, $this->password);
                $this->conn->exec("set names utf8");
            }catch(PDOException $e){
                echo "connection error: ".$e->getMessage();
            }
            return $this->conn;
        }
    }
?>