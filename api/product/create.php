<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    include_once '../../config/Database.php';
    include_once '../../object/Product.php';
    $database  = new Databse();
    $db = $database->getConnection();
    $product = new Product($db);
    //get posted data
    $data = json_decode(file_get_contents("php://input"));
    if(!empty($data->name) && !empty($data->price) && !empty($data->description) && !empty($data->category_id)){
        $product->name = $data->name;
        $product->price = $data->price;
        $product->description = $data->description;
        $product->category_id = $data->category_id;
        $product->created = date('Y-m-d H:i:s');

        if($product->create()){
            http_response_code(201);
            echo json_encode(array('message'=>'product is created'));
        }else{
            http_response_code(500);
            echo json_encode(array('message'=>'unable to create product'));
        }
    }

?>