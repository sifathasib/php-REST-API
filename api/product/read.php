<?php 
    // required headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    //daabse conn here
    include_once '../../config/Database.php';
    include_once '../../object/Product.php';


    //instance of databse and product objects
    $database = new Databse();
    $db = $database->getConnection();
    $product = new Product($db);
    //query products
    $resultset =  $product->read();
    $num = $resultset->rowCount();
    
    
    if($num >0){
        $products_arr = array();
        $products_arr["records"] = array();

        while($row = $resultset->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $product_item = array(
                "id" => $id,
                "name" => $name,
                "description" => $description,
                "price" => $price,
                "category_id" => $category_id,
                "category_name" => $category_name
            );
            array_push($products_arr['records'],$product_item);
        }
            http_response_code(200);
            echo json_encode($products_arr);
    }
    else{
 
        // set response code - 404 Not found
        http_response_code(404);
     
        // tell the user no products found
        echo json_encode(
            array("message" => "No products found.")
        );
    }

?>