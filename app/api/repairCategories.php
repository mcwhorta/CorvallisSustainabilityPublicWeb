<?php

// Get to repair categories from businesses

// Create an associative array for storing the (JSON) response
$response = ['errors' => []];
require_once '../config/db.php';

// Create the database connection
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($mysqli->connect_errno) {
    $response['errors'][] = 'Database connection failed';
}

//SELECT busMap.id as map_id, busMap.bus_id, busMap.cat_id, businesses.name as bus_name, Categories.name as cat_name FROM busMap INNER JOIN businesses on businesses.id = busMap.bus_id INNER JOIN Categories on Categories.id = busMap.cat_id
//$stmt = $mysqli->prepare( 'SELECT busMap.id as map_id, busMap.bus_id, busMap.cat_id, businesses.name as bus_name, Categories.name as cat_name FROM busMap INNER JOIN businesses on businesses.id = busMap.bus_id INNER JOIN Categories on Categories.id = busMap.cat_id' );
$stmt = $mysqli->prepare( 'SELECT busMap.cat_id, Categories.name as cat_name FROM busMap INNER JOIN businesses on businesses.id = busMap.bus_id INNER JOIN Categories on Categories.id = busMap.cat_id WHERE businesses.type = "repair" ' );

if ($stmt->execute()) {
  $result = $stmt->get_result();
  $items = $result->fetch_all(MYSQLI_ASSOC);
}

function unique_multidim_array($array, $key) {
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}

$items = unique_multidim_array($items, "cat_name");

$stmt->close();
$mysqli->close();
// If we make it this far then we'll simply return a successful response
http_response_code(200);
//header('Content-Type: application/json');
echo json_encode($items);
?>
