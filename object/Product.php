<?php
    class Product{
        //database conn & table name
        private $conn;
        private $tablename = 'products';
        // properties of table
        public $id;
        public $name;
        public $description;
        public $price;
        public $category_id;
        public $category_name;
        public $created;


        function __construct($db)
        {
            $this->conn = $db;
        }

        function read(){
            $query = "SELECT
                    c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                      FROM
                    " . $this->tablename . " p
                    LEFT JOIN
                    categories c
                    ON p.category_id = c.id
                    ORDER BY
                    p.created DESC";
            //prepare query
            $stmnt = $this->conn->prepare($query);
            //exequte query
            $stmnt->execute();
            return $stmnt;
        }

        function create(){
            // query to insert record
            $query = "INSERT INTO
                " .$this->tablename. "
            SET
                name=:name, price=:price, description=:description, category_id=:category_id, created=:created";

            // prepare query
            $stmt = $this->conn->prepare($query);

            // sanitize
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->price=htmlspecialchars(strip_tags($this->price));
            $this->description=htmlspecialchars(strip_tags($this->description));
            $this->category_id=htmlspecialchars(strip_tags($this->category_id));
            $this->created=htmlspecialchars(strip_tags($this->created));

            // bind values
            $stmt->bindParam(":name", $this->name);
            $stmt->bindParam(":price", $this->price);
            $stmt->bindParam(":description", $this->description);
            $stmt->bindParam(":category_id", $this->category_id);
            $stmt->bindParam(":created", $this->created);

            // execute query
            if($stmt->execute()){
                return true;
            }

                return false;
        }

        function readOne(){
            $query ="SELECT c.name as category_name,p.id,p.name,p.description,p.price,c.id as category_id
            from products p
            LEFT JOIN categories c
            on p.category_id = c.id
            where p.id = ?
            LIMIT 0,1";

            $stmnt = $this->conn->prepare($query);
            // bind id of product to be updated
            $stmnt->bindParam(1, $this->id);
            // execute query
            $stmnt->execute();
        
            // get retrieved row
            // $row = $stmnt->fetch(PDO::FETCH_ASSOC);
        
            // // set values to object properties
            // $this->name = $row['name'];
            // $this->price = $row['price'];
            // $this->description = $row['description'];
            // $this->category_id = $row['category_id'];
            // $this->category_name = $row['category_name'];

            return $stmnt;
        }
        function update(){
 
            // update query
            $query = "UPDATE
                        " . $this->tablename . "
                    SET
                        name = :name,
                        price = :price,
                        description = :description,
                        category_id = :category_id
                    WHERE
                        id = :id ";
         
            // prepare query statement
            $stmt = $this->conn->prepare($query);
         
            // sanitize
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->price=htmlspecialchars(strip_tags($this->price));
            $this->description=htmlspecialchars(strip_tags($this->description));
            $this->category_id=htmlspecialchars(strip_tags($this->category_id));
            $this->id=htmlspecialchars(strip_tags($this->id));
         
            // bind new values
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':price', $this->price);
            $stmt->bindParam(':description', $this->description);
            $stmt->bindParam(':category_id', $this->category_id);
            $stmt->bindParam(':id', $this->id);
         
            // execute the query
            if($stmt->execute()){
                return true;
            }
         
            return false;
        }
        //delete operation
        function delete(){
            
            $query = 'delete from products where id = :id';
            $stmnt = $this->conn->prepare($query);
            $this->id = htmlspecialchars(strip_tags($this->id));
            $stmnt->bindParam(':id',$this->id);
            if($stmnt->execute()) return true; 
            return false;
        }

        function search($keywords){
            $query= "SELECT
                    c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                     FROM products p
                    LEFT JOIN
                    categories c
                    ON p.category_id = c.id
                    WHERE
                    p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ? 
                    ORDER BY
                    p.created DESC";

            //query preapre
            $stmnt = $this->conn->prepare($query);
            //sanitize
            $keywords = htmlspecialchars(strip_tags($keywords));
            $keywords = "%{$keywords}%";
            //bind
            $stmnt -> bindParam(1,$keywords);
            $stmnt -> bindParam(2,$keywords);
            $stmnt ->bindParam(3,$keywords);
            //execute
            $stmnt->execute();
            return $stmnt;

        }
        public function readPaging($from_record_num, $records_per_page){
 
            // select query
            $query = "SELECT
                        c.name as category_name, p.id, p.name, p.description, p.price, p.category_id, p.created
                    FROM
                        " . $this->tablename . " p
                        LEFT JOIN
                            categories c
                                ON p.category_id = c.id
                    ORDER BY p.created DESC
                    LIMIT ?, ?";
         
            // prepare query statement
            $stmt = $this->conn->prepare( $query );
         
            // bind variable values
            $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
            $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
         
            // execute query
            $stmt->execute();
         
            // return values from database
            return $stmt;
        }

        // used for paging products
        public function count(){
            $query = "SELECT COUNT(*) as total_rows FROM " . $this->tablename . "";
        
            $stmt = $this->conn->prepare( $query );
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
            return $row['total_rows'];
        }
 
    }

?>