<?php

// Create an associative array for storing the (JSON) response
$response = ['errors' => []];


//print_r($_POST);



// name      : $('#name').val(),
// address   : $('#address').val(),
// city      : $('#city').val(),
// state     : $('#state').val(),
// phone     : $('#phone').val(),
// website   : $('#website').val(),
// notes     : $('#notes').val(),
// latitude  : $('#latitude').val(),
// longitude : $('#longitude').val()

// Make sure the parameters are present and non-empty
$id_is_empty = (!isset($_POST['id']) || $_POST['id'] === '');
$name_is_empty = !isset($_POST['name']);
$address_is_empty = !isset($_POST['address']);
$city_is_empty = !isset($_POST['city']);
$state_is_empty = !isset($_POST['state']);
$phone_is_empty = !isset($_POST['phone']);
$website_is_empty = !isset($_POST['website']);
$notes_is_empty = !isset($_POST['notes']);
$latitude_is_empty = !isset($_POST['latitude']);
$longitude_is_empty = !isset($_POST['longitude']);

if ($id_is_empty || $name_is_empty || $address_is_empty || $city_is_empty || $state_is_empty || $phone_is_empty || $website_is_empty || $notes_is_empty || $latitude_is_empty || $longitude_is_empty) {
  // Formulate a response (to send back as JSON)
  if ($id_is_empty) {
    $response['errors'][] = '"id" parameter must be present.';
  }

  if ($name_is_empty) {
    $response['errors'][] = '"name" parameter must be present.';
  }

  if ($address_is_empty) {
    $response['errors'][] = '"address" parameter must be present.';
  }

  if ($city_is_empty) {
    $response['errors'][] = '"city" parameter must be present.';
  }

  if ($state_is_empty) {
    $response['errors'][] = '"state" parameter must be present.';
  }

  if ($phone_is_empty) {
    $response['errors'][] = '"phone" parameter must be present.';
  }

  if ($website_is_empty) {
    $response['errors'][] = '"website" parameter must be present.';
  }

  if ($notes_is_empty) {
    $response['errors'][] = '"notes" parameter must be present.';
  }

  if ($latitude_is_empty) {
    $response['errors'][] = '"latitude" parameter must be present.';
  }

  if ($longitude_is_empty) {
    $response['errors'][] = '"longitude" parameter must be present.';
  }


  // Send a 400 ("Bad Request") response code if we're missing parameters
  http_response_code(400);
  header('Content-Type: application/json');

  echo json_encode($response);
  exit;
}

require_once '../config/db.php';

// Create the database connection
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($mysqli->connect_errno) {
  // echo 'Connection error: (', $mysqli->connect_errno, ') ',
  //   $mysqli->connect_error;
    $response['errors'][] = 'Database connection failed';
}

$stmt = $mysqli->prepare("UPDATE businesses SET name = ? , type = ?,  address = ?,city = ?,state = ?,phone = ?,website = ?,notes = ?,latitude = ?,longitude = ? WHERE id = ?");
$stmt->bind_param('sssssssssss', $_POST['name'],
$_POST['type'],
$_POST['address'],
$_POST['city'],
$_POST['state'],
$_POST['phone'],
$_POST['website'],
$_POST['notes'],
$_POST['latitude'],
$_POST['longitude'],
$_POST['id'] );
if( $stmt->execute() ) {
  $response = ['success' => true];
}

// delete all items from item map for this business

// delete all old ones and save the new ones
$stmt = $mysqli->prepare("DELETE FROM busMap WHERE bus_id = ?");
$stmt->bind_param('i', $_POST['id'] );
if( $stmt->execute() ) {
  $response = ['success' => true];
} else {
    printf("Errormessage: %s\n", $mysqli->error);
}

// // create new ones
if ( isset($_POST['selected']) && count($_POST['selected']) > 0 ) {
  for($i=0; $i < count($_POST['selected']); $i++) {

    $stmt = $mysqli->prepare("INSERT INTO busMap (bus_id, cat_id) VALUES (?,?)");
    $stmt->bind_param('ii',$_POST['id'], $_POST['selected'][$i]["id"] );
    if( $stmt->execute() ) {
      $response = ['success' => true];
    }
  }
}



$stmt->close();
$mysqli->close();
// If we make it this far then we'll simply return a successful response
http_response_code(200);
header('Content-Type: application/json');
echo json_encode($response);
?>
