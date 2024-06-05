<?php
require '..\vendor\autoload.php';

define('MONGO_URI', 'mongodb://localhost:27017'); // Replace with your connection string
define('MONGO_DATABASE', 'guvi');

$mongoClient = new MongoDB\Driver\Manager('mongodb://localhost:27017');
$mongoDb = $mongoClient->selectDatabase(MONGO_DATABASE);
?>