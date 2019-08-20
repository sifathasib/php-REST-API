<?php 
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../../object/Product.php';
    include_once '../../config/Database.php';

    $database =  new Databse();
    $db= $database->getConnection();
    $product = new Product($db);
    //GET keywords from query
    $keywords = isset($_GET['s'])? $_GET['s']:"";
    $resultset = $product->search($keywords);
    $num = $resultset->rowCount();
    if($num>0){
        $product_arr = array();
        while($row= $resultset->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $product_item= array(
                "id" => $id,
                "name" => $name,
                "description" => html_entity_decode($description),
                "price" => $price,
                "category_id" => $category_id,
                "category_name" => $category_name
            );
            array_push($product_arr,$product_item);
        }
        http_response_code(200);
        echo json_encode($product_arr);
    }else{
        http_response_code(404);
        echo json_encode(array('message'=>'product does not exists'));
    }



?>