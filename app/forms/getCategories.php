<?php

// Create an associative array for storing the (JSON) response
$response = ['errors' => []];
require_once '../config/db.php';
function get_result( $Statement ) {
    $RESULT = array();
    $Statement->store_result();
    for ( $i = 0; $i < $Statement->num_rows; $i++ ) {
        $Metadata = $Statement->result_metadata();
        $PARAMS = array();
        while ( $Field = $Metadata->fetch_field() ) {
            $PARAMS[] = &$RESULT[ $i ][ $Field->name ];
        }
        call_user_func_array( array( $Statement, 'bind_result' ), $PARAMS );
        $Statement->fetch();
    }
    return $RESULT;
}
// Create the database connection
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($mysqli->connect_errno) {
    $response['errors'][] = 'Database connection failed';
}
$stmt = $mysqli->prepare(
  'SELECT* FROM categories ORDER BY name ASC;'
);
if ($stmt->execute()) {
  $result = get_result( $stmt );
  $items = $result;
}

$stmt->close();
$mysqli->close();
// If we make it this far then we'll simply return a successful response
http_response_code(200);
//header('Content-Type: application/json');
echo json_encode($items);
?>
