<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Paragma, Authorization, Accept, Accept-Encoding");
// include createdAt, updatedAt in json_encode

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {

    // open .con to db

    $conn = mysqli_connect("localhost","root","Lollakas123","API")
    or die("Error " . mysqli_error($conn));

    // fetch from db

    $sql = "SELECT * FROM posts";
    $result = mysqli_query($conn, $sql) or die("Error in Selecting"
        . mysqli_error($conn));

    $userArr = array();
    while ($row = mysqli_fetch_assoc($result))
    {

        $userArr[] = $row;

    }
    // $data = array('data'=>$userArr);

    echo json_encode($userArr);

    // close db conn

    mysqli_close($conn);

}

elseif ($method == 'PUT') {
    $id = (int) filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_NUMBER_INT);
    $data = file_get_contents('php://input');

    $jsonData = json_decode($data);

    $title = $jsonData-> title;
    $body = $jsonData-> body;

    // open .con to db

    $conn = mysqli_connect("localhost","root","Lollakas123","API")
    or die ("Error " . mysqli_error($conn));

    // db query

    $sql = "UPDATE posts SET title='$title', body='$body' WHERE id='$id'";

    if(mysqli_query($conn,$sql)) {
        $data = array("title"=>$title, "body"=>$body, "id"=>$id);
        echo json_encode($data);
    } else {
        http_response_code(404);
    }
    mysqli_close($conn);

} elseif ($method == 'POST') {

    $data = file_get_contents('php://input');

    $jsonData = json_decode($data);

    $title =  $jsonData->title;
    $body = $jsonData->body;

    // open .con to db

    $conn = mysqli_connect("localhost","root","Lollakas123","API")
    or die ("Error " . mysqli_error($conn));

    // db query
    $sql = "INSERT INTO posts (title, body) VALUES ('$title', '$body')";

    if (mysqli_query($conn, $sql)) {

        $data = array("title"=>$title, "body"=>$body);

        echo json_encode($data);

    } else {
        echo "Could not create a new user";
    }

    mysqli_close($conn);

} elseif ($method == 'DELETE'){

    var_dump($_SERVER['REQUEST_URI']);
    $id = (int) filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_NUMBER_INT);

// open.con to db
    $conn = mysqli_connect("localhost","root","Lollakas123","API")
    or die ("Error " . mysqli_error($conn));

// db query

    $sql = "DELETE FROM posts WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {
        http_response_code(204);
    } else {
        http_response_code(404);
    }

    mysqli_close($conn);

} else {
    echo json_encode(array('message'=>'method unknown')
    );
}

?>
