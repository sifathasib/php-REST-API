<?php
    //reqiure headers
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    //import script
    include_once '../../config/Database.php';
    include_once '../../object/Product.php';

    $database = new Databse();
    $db = $database->getConnection();
    $product = new Product($db);

    //get product id
    $data =  json_decode(file_get_contents("php://input"));
    $product->id = $data->id;

    if($product->delete()){
        http_response_code(200);
        echo json_encode(array('message'=>'product is deleted'));
    }else{
        http_response_code(300);
        echo json_encode(array('message'=>'product doesn not exists'));
    }



?>