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

	<body>
		<?php include 'partials/nav.php';?>
		<style type="text/css">
			#map-canvas {
				position: absolute;
				top: 50px;
				left: 0px;
				right: 0px;
				bottom: 0px;
				width:    100%;
				height:   100%;
			}
		</style>

		<div id="map-canvas"></div><!-- #map-canvas -->

		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&signed_in=true"></script>

		<script type="text/javascript">
			google.maps.event.addDomListener( window, 'load', gmaps_results_initialize );
			/**
			* Renders a Google Maps centered on Atlanta, Georgia. This is done by using
			* the Latitude and Longitude for the city.
			*
			* Getting the coordinates of a city can easily be done using the tool availabled
			* at: http://www.latlong.net
			*
			* @since    1.0.0
			*/
			function gmaps_results_initialize() {

				var map, marker, infowindow, i;

				map = new google.maps.Map( document.getElementById( 'map-canvas' ), {

					zoom:           9,
					center:         new google.maps.LatLng( 44.3356457, -123.26204 ),

				});
				
				
				// Place a marker on Albany-Corvallis ReUseIt
				marker = new google.maps.Marker({
					position: new google.maps.LatLng( 44.5445, -122.108 ),
					map:      map,
					content:  "<p>" + "Albany-Corvallis ReUseIt" + "</p>"

				});

				// Add an InfoWindow for Albany-Corvallis ReUseIt
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
			
					}

				})( marker ));

				// Place a marker in Arc Thrift Stores (Corvallis)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5781, -123.261 ),
					map:      map,
					content:  "Arc Thrift Stores (Corvallis)"

				});

				// Add an InfoWindow for Arc Thrift Stores (Corvallis)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));

			}
		</script>
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
	
</html>