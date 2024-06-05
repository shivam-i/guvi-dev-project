<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $user = $_POST['username'];
  $pass = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password before storing
  $email = $_POST['useremail'];
  $number = $_POST['number'];
  $date = $_POST['date'];

  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept');
  header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "guvi";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Prepared statement to check for existing email
  $sql = "SELECT * FROM auth WHERE useremail=?";
  $stmt = $conn->prepare($sql);

  if ($stmt) {
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
      die(json_encode(array('status' => false, "msg" => "Account Exists with this mail")));
    } else {
      $stmt->close();

      // Prepared statement for registration
      $sql = "INSERT INTO auth (username, useremail, userpassword, usernumber, userdob) VALUES (?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);

      if ($stmt) {
        $stmt->bind_param("sssss", $user, $email, $pass, $number, $date);
        $stmt->execute();

        if ($stmt->affected_rows === 1) {
          die(json_encode(array('status' => true, "msg" => "Account Created")));
        } else {
          die(json_encode(array('status' => false, "msg" => "Something went wrong")));
        }

        $stmt->close();
      } else {
        die(json_encode(array('status' => false, "msg" => "Error preparing statement")));
      }
    }
  } else {
    die(json_encode(array('status' => false, "msg" => "Error preparing statement")));
  }

  $conn->close();
}