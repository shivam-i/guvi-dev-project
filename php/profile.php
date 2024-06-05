<?php
require 'vendor/autoload.php';

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$db = $mongoClient->guvi;
$usersCollection = $db->user;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['profile'])) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

    $email = $_POST['email'];

    $filter = ['useremail' => $email];
    $user = $usersCollection->findOne($filter);

    if ($user) {
        $response = [
            "email" => $user['useremail'],
            "number" => $user['usernumber'],
            "dob" => $user['userdob'],
            "name" => $user['username'],
        ];
        die(json_encode($response));
    } else {
        die(json_encode(['message' => 'User not found']));
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    
    $name = $_POST["name"];
    $number = $_POST["number"];
    $email = $_POST["email"];
    $dob = $_POST["dob"];

    $filter = ['useremail' => $email];
    $update = [
        '$set' => [
            'useremail' => $email,
            'usernumber' => $number,
            'username' => $name,
            'userdob' => $dob,
        ]
    ];

    $updateResult = $usersCollection->updateOne($filter, $update);

    if ($updateResult->getMatchedCount() > 0) {
        die(json_encode(['status' => true]));
    } else {
        die(json_encode(['status' => false, 'message' => 'User not found']));
    }
}

// if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['profile'])) {

//     header('Access-Control-Allow-Origin: *');
//     header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
//     header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
//     $email = $_POST['email'];

//     $servername = "localhost";
//     $username = "root";
//     $password = "";
//     $dbname = "guvi";

//     $conn = new mysqli($servername, $username, $password, $dbname);

//     if ($conn->connect_error) {
//         die("Connection failed: " . $conn->connect_error);
//     }
//     $sql = "SELECT useremail,username,userdob,usernumber FROM auth where useremail='$email'";

//     $result = $conn->query($sql);

//     while ($row = $result->fetch_assoc()) {
//         die(json_encode(array("email" => $row["useremail"], "number" => $row["usernumber"], "dob" => $row["userdob"], "name" => $row["username"])));
//     }

//     $conn->close();
// }
// if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {

//     header('Access-Control-Allow-Origin: *');
//     header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
//     header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

//     $name = $_POST["name"];
//     $number = $_POST["number"];
//     $email = $_POST["email"];
//     $dob = $_POST["dob"];

//     $servername = "localhost";
//     $username = "root";
//     $password = "";
//     $dbname = "guvi";

//     $conn = new mysqli($servername, $username, $password, $dbname);

//     if ($conn->connect_error) {
//         die("Connection failed: " . $conn->connect_error);
//     }

//     $sql = "Update auth set useremail='$email' ,usernumber='$number',username='$name',userdob='$dob' where usernumber='$number'";

//     $result = $conn->query($sql);
//     echo ($result);

//     if ($result) {
//         die(json_encode((array("status" => true))));
//     } else {
//         die(json_encode((array("status" => false))));
//     }

//     $conn->close();
// }
