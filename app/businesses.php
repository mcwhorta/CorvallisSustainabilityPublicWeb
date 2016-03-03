<?php
session_start();
session_regenerate_id();
function getCurrentUri2() {
  $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
  $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
  if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
  $uri = '/' . trim($uri, '/');
  $bodytag = str_replace($uri, "", "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
  return $bodytag;
}

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

require_once 'config/db.php';
// Create the database connection
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($mysqli->connect_errno) {
  echo 'Connection error: (', $mysqli->connect_errno, ') ', $mysqli->connect_error;
}

// Get the businesses from the database
$stmt = $mysqli->prepare('SELECT * FROM businesses ORDER BY id ASC;');
if ($stmt->execute()) {
  $result = get_result($stmt);
  $businesses = $result;
}
$stmt->fetch();
$stmt->close();

$stmt = $mysqli->prepare('SELECT businesses.id as bz_id, businesses.name as bz_name, categories.id as cat_id, categories.name as cat_name from businesses LEFT OUTER JOIN busMap ON busMap.bus_id = businesses.id LEFT OUTER JOIN categories on busMap.cat_id = categories.id ORDER BY businesses.id ASC;');
if ($stmt->execute()) {
  $result = get_result($stmt );
  $items = $result;
}


?>

<!DOCTYPE html>
<html>
<head>
  <title>Corvalis Sustainability App</title>
</head>
<style>
  .website {
    max-width: 200px;
    padding-right: 10px !important;
    overflow-wrap: break-word;
  }

  .bootstrap-tagsinput {
      width: 100% !important;
      min-height: 170px !important;
  }
  ul {
    display: inline;
  }
  li {
    float: left;
    width: auto;
    margin-left: 20px;
  }
  .items-td {
    border-top: none !important;
  }
  .group {
    margin: 0 auto;
    width: 376px;
    margin-bottom: 25px;
    margin-top: 25px;
    outline: 1px solid #606060;
    padding: 20px;
    text-align: center;
  }
  .row.group {
    margin: 0 auto;
    margin-bottom: 25px;
  }
  .inline {
    display: inline;
    width: 100%;
  }
  .inline:after {
     visibility: hidden;
     display: block;
     font-size: 0;
     content: " ";
     clear: both;
     height: 0;
  }
  .radio-inline {
    float:left;
    width: 100px;
  }
</style>

<body>
  <?php include 'partials/nav.php';?>
  <div class="container">

    <table class="table table-striped">
      <thead>
        <tr>
          <th>id</th>
          <th>Business Name</th>
          <th>Type</th>
          <th>Address</th>
          <th>City</th>
          <th>State</th>
          <th>Phone</th>
          <th class="website">Website</th>
          <th>Notes</th>
          <th>Latitude</th>
          <th>Longitude</th>
        </tr>
      </thead>
      <tbody>
        <?php
          foreach ($businesses as $b) {
            echo "<tr> <th scope='row' class='id'> $b[id] </th> <td class='name'> $b[name] </td> <td class='type'> $b[type] </td> <td class='address'> $b[address] </td> <td class='city'>$b[city]</td> <td class='state'> $b[state] </td> <td class='phone'>$b[phone]</td> <td class='website'>$b[website]</td> <td class='notes'>$b[notes]</td> <td class='latitude'>$b[latitude]</td> <td class='longitude'>$b[longitude]</td>";
            echo "</tr><tr class='items-tr'><td class='items-td' colspan='11'>  <div class='items-div'> <ul class='items-" . $b['id']  ."'>";
            // loop
            for($i=0; $i<count($items); $i++) {
              if ( $items[$i]['bz_id'] == $b['id'] )  echo "<li>  " . $items[$i]['cat_name'] . "  </li>";
            }
            echo "</ul> </div> </td></tr>";
          }
        ?>
      </tbody>
    </table>
  </div>
</body>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha256-7s5uDGW3AHqw6xtJmNNtr+OBRJUlgkNJEo78P4b0yRw= sha512-nNo+yCHEyn0smMxSswnf/OnX6/KwJuZTlNZBjauKhTK0c+zT+q5JOCx0UFhXQ6rJR9jg6Es8gPuD2uZcYDLqSw==" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha256-KXn5puMvxCw+dAYznun+drMdG1IFl3agK0p/pqT9KAo= sha512-2e8qq0ETcfWRI4HJBzQiA3UoyFk6tbNyG+qSaIBZLyW9Xf3sWZHN/lxe9fTh1U45DpPf07yj94KsUHHWe4Yk1A==" crossorigin="anonymous"></script>


<!-- typeahead -->
<script src="dependencies/typeahead.js/typeahead.bundle.min.js"></script>
<script src="dependencies/typeahead.js/typeahead.jquery.min.js"></script>
<script src="dependencies/typeahead.js/bloodhound.min.js"></script>
<link href="dependencies/typeahead.js/css.css" rel="stylesheet">

<script src="dependencies/tags/bootstrap-tagsinput.min.js"></script>
<link href="dependencies/tags/bootstrap-tagsinput.css" rel="stylesheet">


<!-- Sweet Alerts -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" rel="stylesheet" >
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

</script>
</html>