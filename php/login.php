<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
  header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

  $user = $_POST['username'];
  $pass = $_POST['password'];

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "guvi";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepared statement to fetch user by email
  $sql = "SELECT * FROM auth WHERE useremail=?";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      $user_data = $result->fetch_assoc(); 

      if (password_verify($pass, $user_data['userpassword'])) { 
        $stmt->close();
        $text = $user;
        echo json_encode(array('status' => true, 'text' => $text, 'name' => "shivam" ));
      } else {
        $stmt->close();
        die(json_encode(array("status" => false, "msg" => "Invalid credentials")));
      }
    } else {
      $stmt->close();
      die(json_encode(array("status" => false, "msg" => "Invalid credentials")));
    }
  } else {
    die(json_encode(array("status" => false, "msg" => "Error preparing statement")));
  }

  $conn->close();
}