<?php
    //required headers
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: access");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Allow-Credentials: true");
    header('Content-Type: application/json');

    //import scripts
    include_once '../../config/Database.php';
    include_once '../../object/Product.php';
    //get databse connection
    $database =  new Databse();
    $db= $database->getConnection();
    //get product class
    $product = new Product($db);
    //set id from query
    $product->id = isset($_GET['id'])? $_GET['id']:die();
    $resultSet=$product->readOne();
    // //fetch data
    // $row = $resultSet->fetch(PDO::FETCH_ASSOC);
    
    if($resultSet->rowCount()>0){
        $row = $resultSet->fetch(PDO::FETCH_ASSOC);
        extract($row);
        $product_arr = array(
            "id" => $id,
            "name" => $name,
            "description" => $description,
            "price" => $price,
            "category_id" => $category_id,
            "category_name" => $category_name
        );
        http_response_code(200);
        echo json_encode($product_arr);
    }
    else{
        http_response_code(404);
        echo json_encode(array('message'=>'product does not exists'));
    }
    // if($product->name!=null){
    //     $product_arr = array(
    //         'id'=> $product->id,
    //         'name'=>$product->name,
    //         'price'=>$product->price,
    //         'description'=>$product->description,
    //         'category_id'=>$product->category_id,
    //         'category_name'=>$product->category_name,
    //     );
    //     http_response_code(200);
    //     echo json_encode($product_arr);
    // }
    // else{
    //     http_response_code(404);
    //     echo json_encode(array('message'=>'product does not exists'));
    // }

?>