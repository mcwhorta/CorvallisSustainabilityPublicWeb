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
					content:  "<p>"+"<b><u>Albany-Corvallis ReUseIt</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> "+"</p>"+"<p>"+"<b>Phone:</b> "+"</p>"+"<p>"+"<b>Website:</b> https://groups.yahoo.com/neo/groups/albanycorvallisReUseIt/info"+"</p>"

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
					content:  "<p>"+"<b><u>Arc Thrift Stores (Corvallis)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 928 NW Beca Ave, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-929-3946 "+"</p>"+"<p>"+"<b>Website:</b> http://www.arcbenton.org/"+"</p>"

				});

				// Add an InfoWindow for Arc Thrift Stores (Corvallis)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));

				// Place a marker in Arc Thrift Stores (Philomath)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.54, -123.373 ),
					map:      map,
					content:  "<p>"+"<b><u>Arc Thrift Stores (Philomath)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 936 Main St, Philomath, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-753-8250 "+"</p>"+"<p>"+"<b>Website:</b> http://www.arcbenton.org/"+"</p>"

				});

				// Add an InfoWindow for Arc Thrift Stores (Philomath)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Beekman Plance Antique Mall
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5601, -123.267 ),
					map:      map,
					content:  "<p>"+"<b><u>Beekman Plance Antique Mall</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 601 SW Western Blvd, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-754-9011 "+"</p>"+"<p>"+"<b>Website:</b> https://www.antiquemalls.com/or/corvallis/97333/16882	"+"</p>"

				});

				// Add an InfoWindow for Beekman Plance Antique Mall
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Benton County Master Gardeners
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5833, -123.258 ),
					map:      map,
					content:  "<p>"+"<b><u>Benton County Master Gardeners</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 1849 NW 9th St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-766-6750 "+"</p>"+"<p>"+"<b>Website:</b> http://extension.oregonstate.edu/benton/horticulture/mg"+"</p>"

				});

				// Add an InfoWindow for Benton County Master Gardeners
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Book Bin
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.4939, -123.425 ),
					map:      map,
					content:  "<p>"+"<b><u>Book Bin</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 215 SW 4th St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-752-0040 "+"</p>"+"<p>"+"<b>Website:</b> http://bookbin.com/"+"</p>"

				});

				// Add an InfoWindow for Book Bin
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Browsers Bookstore
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5774, -123.258 ),
					map:      map,
					content:  "<p>"+"<b><u>Browsers Bookstore</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 121 NW 4th St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> (888)758-1121 "+"</p>"+"<p>"+"<b>Website:</b> http://www.browsersbookstore.com/"+"</p>"

				});

				// Add an InfoWindow for Browsers Bookstore
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Boys & Girls Club / STARS (after school programs)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.589, -123.246 ),
					map:      map,
					content:  "<p>"+"<b><u>Boys & Girls Club / STARS (after school programs)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 1112 NW Circle Blvd, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-757-1909"+"</p>"+"<p>"+"<b>Website:</b> http://www.bgccorvallis.org/"+"</p>"

				});

				// Add an InfoWindow for Boys & Girls Club / STARS (after school programs)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Buckingham Palace --Fri-Sun only
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5571, -123.265 ),
					map:      map,
					content:  "<p>"+"<b><u>Buckingham Palace --Fri-Sun only</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 600 SW 3rd St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-752-7980"+"</p>"+"<p>"+"<b>Website:</b> "+"</p>"

				});

				// Add an InfoWindow for Buckingham Palace --Fri-Sun only
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Calvary Community Outreach
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.6061, -123.275 ),
					map:      map,
					content:  "<p>"+"<b><u>Calvary Community Outreach</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 2125 NW Lester Ave, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-760-5941"+"</p>"+"<p>"+"<b>Website:</b> http://www.communityoutreachinc.org/"+"</p>"

				});

				// Add an InfoWindow for Calvary Community Outreach
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in CARDV (Center Against Rape/Domestic Violence)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5455, -123.329 ),
					map:      map,
					content:  "<p>"+"<b><u>CARDV (Center Against Rape/Domestic Violence)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 4786 SW Philomath Blvd, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-758-0219"+"</p>"+"<p>"+"<b>Website:</b> http://cardv.org/"+"</p>"

				});

				// Add an InfoWindow for CARDV (Center Against Rape/Domestic Violence)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Career Closet for Women (drop-off at)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.4939, -123.425 ),
					map:      map,
					content:  "<p>"+"<b><u>Career Closet for Women (drop-off at)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 942 NW 9th Ste. A, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-754-6979"+"</p>"+"<p>"+"<b>Website:</b> https://sicorvallis.wordpress.com/"+"</p>"

				});

				// Add an InfoWindow for Career Closet for Women (drop-off at)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Cats Meow Humane Society Thrift Shop
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5571, -123.265 ),
					map:      map,
					content:  "<p>"+"<b><u>Cats Meow Humane Society Thrift Shop</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 411 SW 3rd St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-757-0573"+"</p>"+"<p>"+"<b>Website:</b> http://www.heartlandhumane.org/"+"</p>"

				});

				// Add an InfoWindow for Cats Meow Humane Society Thrift Shop
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Childrens Farm Home
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5537, -123.303 ),
					map:      map,
					content:  "<p>"+"<b><u>Childrens Farm Home</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 4455 NE Hwy 20, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-757-1852"+"</p>"+"<p>"+"<b>Website:</b> http://www.heartlandhumane.org/"+"</p>"
				});

				// Add an InfoWindow for Childrens Farm Home
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Chintimini Wildlife Rehabilitation Ctr
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.6291, -123.24 ),
					map:      map,
					content:  "<p>"+"<b><u>Chintimini Wildlife Rehabilitation Ctr</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 311 Lewisburg Rd, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-745-5324"+"</p>"+"<p>"+"<b>Website:</b> http://www.chintiminiwildlife.org/"+"</p>"

				});

				// Add an InfoWindow for Chintimini Wildlife Rehabilitation Ctr
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Community Outreach (homeless shelter)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5733, -123.263 ),
					map:      map,
					content:  "<p>"+"<b><u>Community Outreach (homeless shelter)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 865 NW Reiman, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-758-3000"+"</p>"+"<p>"+"<b>Website:</b> http://www.communityoutreachinc.org/services/emergency-shelter-program/family-shelter/"+"</p>"

				});

				// Add an InfoWindow for Community Outreach (homeless shelter)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Corvallis Environmental Center
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5685, -123.278 ),
					map:      map,
					content:  "<p>"+"<b><u>Corvallis Environmental Center</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 214 SW Monroe Ave, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-753-9211"+"</p>"+"<p>"+"<b>Website:</b> http://www.corvallisenvironmentalcenter.org/"+"</p>"

				});

				// Add an InfoWindow for Corvallis Environmental Center
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Corvallis Bicycle Collective
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.4939, -123.425 ),
					map:      map,
					content:  "<p>"+"<b><u>Corvallis Bicycle Collective</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 33900 SE Roche Ln/Hwy 34, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-224-6885"+"</p>"+"<p>"+"<b>Website:</b> http://corvallisbikes.org/"+"</p>"

				});

				// Add an InfoWindow for Corvallis Bicycle Collective
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Corvallis Furniture
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.6288, -123.234 ),
					map:      map,
					content:  "<p>"+"<b><u>Corvallis Furniture</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 720 NE Granger Ave, Bldg J, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-231-8103"+"</p>"+"<p>"+"<b>Website:</b> http://corvallisfurniture.com/"+"</p>"

				});

				// Add an InfoWindow for Corvallis Furniture
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Corvallis-Uzhhorod Sister Cities/The TOUCH Project
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.4939, -123.425 ),
					map:      map,
					content:  "<p>"+"<b><u>Corvallis-Uzhhorod Sister Cities/The TOUCH Project</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-753-5170"+"</p>"+"<p>"+"<b>Website:</b> http://www.sistercities.corvallis.or.us/uzhhorod"+"</p>"

				});

				// Add an InfoWindow for Corvallis-Uzhhorod Sister Cities/The TOUCH Project
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Cosmic Chameleon
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5638, -123.26 ),
					map:      map,
					content:  "<p>"+"<b><u>Cosmic Chameleon</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 138 SW 2nd St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-752-9001"+"</p>"+"<p>"+"<b>Website:</b> "+"</p>"

				});

				// Add an InfoWindow for Cosmic Chameleon
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Craigslist (corvallis.craigslist.org)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.4939, -123.425 ),
					map:      map,
					content:  "<p>"+"<b><u>Craigslist (corvallis.craigslist.org)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> "+"</p>"+"<p>"+"<b>Website:</b> corvallis.craigslist.org"+"</p>"

				});

				// Add an InfoWindow for Craigslist (corvallis.craigslist.org)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Freecycle.org
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.4939, -123.425 ),
					map:      map,
					content:  "<p>"+"<b><u>Freecycle.org</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> "+"</p>"+"<p>"+"<b>Website:</b> freecycle.org"+"</p>"

				});

				// Add an InfoWindow for Freecycle.org
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in First Alternative Co-op Recycling Center
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5542, -123.265 ),
					map:      map,
					content:  "<p>"+"<b><u>First Alternative Co-op Recycling Center</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 1007 SE 3rd St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-753-3115"+"</p>"+"<p>"+"<b>Website:</b> http://firstalt.coop/"+"</p>"

				});

				// Add an InfoWindow for First Alternative Co-op Recycling Center
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in First Alternative Co-op Store (South store)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5542	-123.265 ),
					map:      map,
					content:  "<p>"+"<b><u>First Alternative Co-op Store (South store)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 1007 SE 3rd St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-753-3115"+"</p>"+"<p>"+"<b>Website:</b> http://firstalt.coop/"+"</p>"

				});

				// Add an InfoWindow for First Alternative Co-op Store (South store)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in First Alternative Co-op Store (North store)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5789, -123.283 ),
					map:      map,
					content:  "<p>"+"<b><u>First Alternative Co-op Store (North store)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 2855 NW Grant Ave, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-452-3115"+"</p>"+"<p>"+"<b>Website:</b> http://firstalt.coop/"+"</p>"

				});

				// Add an InfoWindow for First Alternative Co-op Store (North store)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Furniture Exchange
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.563, -123.261 ),
					map:      map,
					content:  "<p>"+"<b><u>Furniture Exchange</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 210 NW 2nd St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-833-0183"+"</p>"+"<p>"+"<b>Website:</b> http://www.furnitureexchange-usa.com/"+"</p>"

				});

				// Add an InfoWindow for Furniture Exchange
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Furniture Share (formerly Benton FS)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5482, -123.265 ),
					map:      map,
					content:  "<p>"+"<b><u>Furniture Share (formerly Benton FS)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 155 SE Lilly Ave, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-754-9511"+"</p>"+"<p>"+"<b>Website:</b> http://furnitureshare.org/"+"</p>"

				});

				// Add an InfoWindow for Furniture Share (formerly Benton FS)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Home Grown Gardens
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5122, -123.269 ),
					map:      map,
					content:  "<p>"+"<b><u>Home Grown Gardens</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 4845 SE 3rd St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-758-2137"+"</p>"+"<p>"+"<b>Website:</b> http://homegrowngardens77.vpweb.com/"+"</p>"

				});

				// Add an InfoWindow for Home Grown Gardens
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Garland Nursery
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5504, -123.313 ),
					map:      map,
					content:  "<p>"+"<b><u>Garland Nursery</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 5470 NE Hwy 20, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-753-6601"+"</p>"+"<p>"+"<b>Website:</b> http://www.garlandnursery.com/"+"</p>"

				});

				// Add an InfoWindow for Garland Nursery
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Goodwill Industries
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5833, -123.258 ),
					map:      map,
					content:  "<p>"+"<b><u>Goodwill Industries</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 1325 NW 9th St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-752-8278"+"</p>"+"<p>"+"<b>Website:</b> http://www.goodwill.org/locator/"+"</p>"

				});

				// Add an InfoWindow for Goodwill Industries
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Habitat for Humanity ReStore
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5455, -123.329 ),
					map:      map,
					content:  "<p>"+"<b><u>Habitat for Humanity ReStore</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 4840 SW Philomath Blvd, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-752-6637"+"</p>"+"<p>"+"<b>Website:</b> http://bentonhabitat.org/"+"</p>"

				});

				// Add an InfoWindow for Habitat for Humanity ReStore
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Happy Trails Records Tapes & CDs
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5571, -123.265 ),
					map:      map,
					content:  "<p>"+"<b><u>Happy Trails Records Tapes & CDs</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 100 SW 3rd St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-752-9032"+"</p>"+"<p>"+"<b>Website:</b> http://www.corvallisbusiness.com/happytrails.html"+"</p>"

				});

				// Add an InfoWindow for Happy Trails Records Tapes & CDs
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Heartland Humane Society
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5537, -123.269 ),
					map:      map,
					content:  "<p>"+"<b><u>Heartland Humane Society</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 398 SW Twin Oaks Cir, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-757-9000"+"</p>"+"<p>"+"<b>Website:</b> http://www.heartlandhumane.org/"+"</p>"

				});

				// Add an InfoWindow for Heartland Humane Society
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Home Life Inc. (for develop. Disabled)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5753, -123.276 ),
					map:      map,
					content:  "<p>"+"<b><u>Home Life Inc. (for develop. Disabled)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 2068 NW Fillmore, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-753-9015"+"</p>"+"<p>"+"<b>Website:</b> http://homelifeinc.org/"+"</p>"

				});

				// Add an InfoWindow for Home Life Inc. (for develop. Disabled)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Jackson Street Youth Shelter
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.8497, -123.242 ),
					map:      map,
					content:  "<p>"+"<b><u>Jackson Street Youth Shelter</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 555 NW Jackson St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-754-2404"+"</p>"+"<p>"+"<b>Website:</b> http://www.jsysi.org/"+"</p>"

				});

				// Add an InfoWindow for Jackson Street Youth Shelter
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Linn Benton Food Share (lg. food donations)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5737, -123.254 ),
					map:      map,
					content:  "<p>"+"<b><u>Linn Benton Food Share (lg. food donations)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 545 SW 2nd, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-752-1010"+"</p>"+"<p>"+"<b>Website:</b> http://communityservices.us/nutrition/detail/category/linn-benton-food-share/"+"</p>"

				});

				// Add an InfoWindow for Linn Benton Food Share (lg. food donations)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Lions Club (box inside Elks Lodge)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5833, -123.258 ),
					map:      map,
					content:  "<p>"+"<b><u>Lions Club (box inside Elks Lodge)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 1400 NW 9th St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-758-0222"+"</p>"+"<p>"+"<b>Website:</b> http://www.e-clubhouse.org/sites/midvalley/"+"</p>"

				});

				// Add an InfoWindow for Lions Club (box inside Elks Lodge)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Love INC (for low income citizens)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5924, -123.278 ),
					map:      map,
					content:  "<p>"+"<b><u>Love INC (for low income citizens)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 2330 NW Professional Dr #102, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-757-8111"+"</p>"+"<p>"+"<b>Website:</b> http://www.yourloveinc.org/	"+"</p>"

				});

				// Add an InfoWindow for Love INC (for low income citizens)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Mario Pastega House (Good Sam patient family housing)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.6012, -123.25 ),
					map:      map,
					content:  "<p>"+"<b><u>Mario Pastega House (Good Sam patient family housing)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 3505 NW Samaritan Dr, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-768-4650"+"</p>"+"<p>"+"<b>Website:</b> http://www.samhealth.org/locations/mariopastegahouse/Pages/default.aspx"+"</p>"

				});

				// Add an InfoWindow for Mario Pastega House (Good Sam patient family housing)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Mary's River Gleaners (for low income citizens)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.4939, -123.425 ),
					map:      map,
					content:  "<p>"+"<b><u>Mary's River Gleaners (for low income citizens)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> Po Box 2309, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-752-1010"+"</p>"+"<p>"+"<b>Website:</b> "+"</p>"

				});

				// Add an InfoWindow for Mary's River Gleaners (for low income citizens)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Midway Farms (Hway 20 btw Corvallis & Albany)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.4889, -122.537 ),
					map:      map,
					content:  "<p>"+"<b><u>Midway Farms (Hway 20 btw Corvallis & Albany)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 6980 US-20, Albany, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-740-6141"+"</p>"+"<p>"+"<b>Website:</b> http://www.midwayfarmsoregon.com/"+"</p>"

				});

				// Add an InfoWindow for Midway Farms (Hway 20 btw Corvallis & Albany)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Neighbor to Neighbor (food pantry)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.54, -123.37 ),
					map:      map,
					content:  "<p>"+"<b><u>Neighbor to Neighbor (food pantry)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 1123 Main, Philomath, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-929-6614"+"</p>"+"<p>"+"<b>Website:</b> "+"</p>"

				});

				// Add an InfoWindow for Neighbor to Neighbor (food pantry)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Osborn Aquatic Center
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5866, -123.263 ),
					map:      map,
					content:  "<p>"+"<b><u>Osborn Aquatic Center</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 1940 NW Highland Dr, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-766-7946"+"</p>"+"<p>"+"<b>Website:</b> http://www.corvallisoregon.gov/index.aspx?page=57"+"</p>"

				});

				// Add an InfoWindow for Osborn Aquatic Center
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in OSU Emergency Food Pantry
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5646, -123.283 ),
					map:      map,
					content:  "<p>"+"<b><u>OSU Emergency Food Pantry</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 2150 SW Jefferson Way, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-737-3473"+"</p>"+"<p>"+"<b>Website:</b> http://studentlife.oregonstate.edu/hsrc/osu-emergency-food-pantry"+"</p>"

				});

				// Add an InfoWindow for OSU Emergency Food Pantry
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in OSU Folk Club Thrift Shop
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5636, -123.26 ),
					map:      map,
					content:  "<p>"+"<b><u>OSU Folk Club Thrift Shop</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 144 NW 2nd St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-752-4733"+"</p>"+"<p>"+"<b>Website:</b> "+"</p>"

				});

				// Add an InfoWindow for OSU Folk Club Thrift Shop
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in OSU Organic Growers Club (Crop & Soil Science Dep't)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.4939, -123.425 ),
					map:      map,
					content:  "<p>"+"<b><u>OSU Organic Growers Club (Crop & Soil Science Dep't)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-737-6810"+"</p>"+"<p>"+"<b>Website:</b> http://cropandsoil.oregonstate.edu/organic_grower"+"</p>"

				});

				// Add an InfoWindow for OSU Organic Growers Club (Crop & Soil Science Dep't)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));				
				
				// Place a marker in Pak Mail (Timberhill Shopping Ctr)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5691, -123.275 ),
					map:      map,
					content:  "<p>"+"<b><u>Pak Mail (Timberhill Shopping Ctr)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 2397 NW Kings Blvd, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-754-8411"+"</p>"+"<p>"+"<b>Website:</b> http://www.pakmail.com/stores/pak-mail-corvallis/"+"</p>"

				});

				// Add an InfoWindow for Pak Mail (Timberhill Shopping Ctr)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));				
				
				// Place a marker in Parent Enhancement Program
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5774, -123.258 ),
					map:      map,
					content:  "<p>"+"<b><u>Parent Enhancement Program</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 421 NW 4th St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-758-8292"+"</p>"+"<p>"+"<b>Website:</b> http://www.downtowncorvallis.org/members/directory.php?show=779"+"</p>"

				});

				// Add an InfoWindow for Parent Enhancement Program
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));				
				
				// Place a marker in Pastors for Peace-Caravan to Cuba (Mike Beilstein)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.4939, -123.425 ),
					map:      map,
					content:  "<p>"+"<b><u>Pastors for Peace-Caravan to Cuba (Mike Beilstein)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-754-1858"+"</p>"+"<p>"+"<b>Website:</b> www.ifconews.org"+"</p>"

				});

				// Add an InfoWindow for Pastors for Peace-Caravan to Cuba (Mike Beilstein)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
								
				// Place a marker in Philomath Community Garden (Chris Shonnard)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.4939, -123.425 ),
					map:      map,
					content:  "<p>"+"<b><u>Philomath Community Garden (Chris Shonnard)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 	541-929-3524"+"</p>"+"<p>"+"<b>Website:</b> http://philomathcommunityservices.org"+"</p>"

				});

				// Add an InfoWindow for Philomath Community Garden (Chris Shonnard)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
								
				// Place a marker in Philomath Community Services (food & kids stuff)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5403, -123.366 ),
					map:      map,
					content:  "<p>"+"<b><u>Philomath Community Services (food & kids stuff)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 360 S 9th, Philomath, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-929-2499"+"</p>"+"<p>"+"<b>Website:</b> http://philomathcommunityservices.org/"+"</p>"

				});

				// Add an InfoWindow for Philomath Community Services (food & kids stuff)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
								
				// Place a marker in Play It Again Sports
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5833, -123.258 ),
					map:      map,
					content:  "<p>"+"<b><u>Play It Again Sports</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 1422 NW 9th St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-754-7529"+"</p>"+"<p>"+"<b>Website:</b> http://www.playitagainsportscorvallis.com/"+"</p>"

				});

				// Add an InfoWindow for Play It Again Sports
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));				
				
				// Place a marker in Presbyterian Piecemakers (cotton quilts)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5657, -123.266 ),
					map:      map,
					content:  "<p>"+"<b><u>Presbyterian Piecemakers (cotton quilts)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 114 SW 8th St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-753-7516"+"</p>"+"<p>"+"<b>Website:</b> http://1stpres.org/"+"</p>"

				});

				// Add an InfoWindow for Presbyterian Piecemakers (cotton quilts)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Public Library Corvallis, Friends of
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5685, -123.278 ),
					map:      map,
					content:  "<p>"+"<b><u>Public Library Corvallis, Friends of</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 645 NW Monroe Ave, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-766-6928"+"</p>"+"<p>"+"<b>Website:</b> http://cbcpubliclibrary.net/"+"</p>"

				});

				// Add an InfoWindow for Public Library Corvallis, Friends of
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Quilts From Caring Hands (cotton quilts)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.54, -123.357 ),
					map:      map,
					content:  "<p>"+"<b><u>Quilts From Caring Hands (cotton quilts)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 1495 NW 20th St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-758-8161"+"</p>"+"<p>"+"<b>Website:</b> http://www.quiltsfromcaringhands.com/"+"</p>"

				});

				// Add an InfoWindow for Quilts From Caring Hands (cotton quilts)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Rapid Refill Ink
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5631, -123.261 ),
					map:      map,
					content:  "<p>"+"<b><u>Rapid Refill Ink</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 254 SW Madison Ave, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-758-8444"+"</p>"+"<p>"+"<b>Website:</b> http://www.rapidinkandtoner.com/oregon/corvallis-store-0107"+"</p>"

				});

				// Add an InfoWindow for Rapid Refill Ink
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Replay Children's Wear
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5624, -123.26 ),
					map:      map,
					content:  "<p>"+"<b><u>Replay Children's Wear</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 250 NW 1st St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-753-6903"+"</p>"+"<p>"+"<b>Website:</b> "+"</p>"

				});

				// Add an InfoWindow for Replay Children's Wear
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Revolve (women's resale boutique)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.564, -123.26 ),
					map:      map,
					content:  "<p>"+"<b><u>Revolve (women's resale boutique)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 103 SW 2nd St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-754-1154"+"</p>"+"<p>"+"<b>Website:</b> http://www.revolveresale.com/"+"</p>"

				});

				// Add an InfoWindow for Revolve (women's resale boutique)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Second Glance
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5571, -123.265 ),
					map:      map,
					content:  "<p>"+"<b><u>Second Glance</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 312 SW 3rd Street, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-758-9099"+"</p>"+"<p>"+"<b>Website:</b> http://www.glanceagain.com/	"+"</p>"

				});

				// Add an InfoWindow for Second Glance
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in The Annex
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5646, -123.274 ),
					map:      map,
					content:  "<p>"+"<b><u>The Annex</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 214 SW Jefferson, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-758-9099"+"</p>"+"<p>"+"<b>Website:</b> http://www.glanceagain.com/"+"</p>"

				});

				// Add an InfoWindow for The Annex
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in The Alley
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5646, -123.274 ),
					map:      map,
					content:  "<p>"+"<b><u>The Alley</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 312 SW Jefferson, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-753-4069"+"</p>"+"<p>"+"<b>Website:</b> http://www.glanceagain.com/2011/11/second-glance-alley/"+"</p>"

				});

				// Add an InfoWindow for The Alley
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Senior Center of Corvallis
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5725, -123.28 ),
					map:      map,
					content:  "<p>"+"<b><u>Senior Center of Corvallis</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 2601 NW Tyler Ave, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-766-6959"+"</p>"+"<p>"+"<b>Website:</b> http://www.corvallisoregon.gov/index.aspx?page=257"+"</p>"

				});

				// Add an InfoWindow for Senior Center of Corvallis
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in South Corvallis Food Bank
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5571, -123.265 ),
					map:      map,
					content:  "<p>"+"<b><u>South Corvallis Food Bank</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 1798 SW 3rd St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-753-4263"+"</p>"+"<p>"+"<b>Website:</b> http://www.southcorvallisfoodbank.org/"+"</p>"

				});

				// Add an InfoWindow for South Corvallis Food Bank
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in St. Vincent de Paul Food Bank
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5727, -123.279 ),
					map:      map,
					content:  "<p>"+"<b><u>St. Vincent de Paul Food Bank</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 501 NW 25th St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-757-1988"+"</p>"+"<p>"+"<b>Website:</b> "+"</p>"

				});

				// Add an InfoWindow for St. Vincent de Paul Food Bank
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Stone Soup (St Mary's Church)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5727, -123.279 ),
					map:      map,
					content:  "<p>"+"<b><u>Stone Soup (St Mary's Church)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 501 NW 25th St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-757-1988"+"</p>"+"<p>"+"<b>Website:</b> http://www.stonesoupcorvallis.org/about.html"+"</p>"

				});

				// Add an InfoWindow for Stone Soup (St Mary's Church)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in UPS Store (Philomath)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5455, -123.329 ),
					map:      map,
					content:  "<p>"+"<b><u>UPS Store (Philomath)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 5060 SW Philomath Blvd, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-752-1830"+"</p>"+"<p>"+"<b>Website:</b> https://corvallis-or-5088.theupsstorelocal.com/"+"</p>"

				});

				// Add an InfoWindow for UPS Store (Philomath)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in UPS Stores (Corvallis)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.589, -123.246 ),
					map:      map,
					content:  "<p>"+"<b><u>UPS Stores (Corvallis)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 922 NW Circle Blvd #160, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-752-0056"+"</p>"+"<p>"+"<b>Website:</b> https://corvallis-or-5088.theupsstorelocal.com/"+"</p>"

				});

				// Add an InfoWindow for UPS Stores (Corvallis)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Vina Moses (for low income citizens)
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5831, -123.261 ),
					map:      map,
					content:  "<p>"+"<b><u>Vina Moses (for low income citizens)</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 968 NW Garfield Ave, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-753-1420"+"</p>"+"<p>"+"<b>Website:</b> http://www.vinamoses.org/"+"</p>"

				});

				// Add an InfoWindow for Vina Moses (for low income citizens)
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Spaeth Heritage House
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5411, -123.368 ),
					map:      map,
					content:  "<p>"+"<b><u>Spaeth Heritage House</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REUSE"+"</p>"+"<p>"+"<b>Address:</b> 135 N 13th St, Philomath, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-307-0349"+"</p>"+"<p>"+"<b>Website:</b> http://www.spaethlumber.com/main/home/main.aspx"+"</p>"

				});

				// Add an InfoWindow for Spaeth Heritage House
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Book binding
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5571, -123.265 ),
					map:      map,
					content:  "<p>"+"<b><u>Book binding</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REPAIR"+"</p>"+"<p>"+"<b>Address:</b> 108 SW 3rd St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> (541)757-9861"+"</p>"+"<p>"+"<b>Website:</b> http://www.cornerstoneassociates.com/bj-bookbinding-about-us.html"+"</p>"

				});

				// Add an InfoWindow for Book binding
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Cell Phone Sick Bay
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5631, -123.261 ),
					map:      map,
					content:  "<p>"+"<b><u>Cell Phone Sick Bay</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REPAIR"+"</p>"+"<p>"+"<b>Address:</b> 252 SW Madison Ave, Suite 110, Philomath, OR"+"</p>"+"<p>"+"<b>Phone:</b> (541)230-1785"+"</p>"+"<p>"+"<b>Website:</b> http://www.cellsickbay.com/index.html"+"</p>"

				});

				// Add an InfoWindow for Cell Phone Sick Bay
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Geeks 'N' Nerds
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.4939, -123.425 ),
					map:      map,
					content:  "<p>"+"<b><u>Geeks 'N' Nerds</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REPAIR"+"</p>"+"<p>"+"<b>Address:</b> 950 Southeast Geary St Unit D Albany, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> "+"</p>"+"<p>"+"<b>Website:</b> http://www.computergeeksnnerds.com/"+"</p>"

				});

				// Add an InfoWindow for Geeks 'N' Nerds
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Specialty Sewing By Leslie
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5634, -123.261 ),
					map:      map,
					content:  "<p>"+"<b><u>Specialty Sewing By Leslie</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REPAIR"+"</p>"+"<p>"+"<b>Address:</b> 225 SW Madison Ave, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> (541)758-4556"+"</p>"+"<p>"+"<b>Website:</b> http://www.specialtysewing.com/Leslie_Seamstress/Welcome.html"+"</p>"

				});

				// Add an InfoWindow for Specialty Sewing By Leslie
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Corvallis Technical
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.589, -123.246 ),
					map:      map,
					content:  "<p>"+"<b><u>Corvallis Technical</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REPAIR"+"</p>"+"<p>"+"<b>Address:</b> 966 NW Circle Blvd, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> (541)704-7009"+"</p>"+"<p>"+"<b>Website:</b> http://www.corvallistechnical.com"+"</p>"

				});

				// Add an InfoWindow for Corvallis Technical
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in OSU Repair Fair
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5636, -123.272 ),
					map:      map,
					content:  "<p>"+"<b><u>OSU Repair Fair</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REPAIR"+"</p>"+"<p>"+"<b>Address:</b> Oregon State University Property Services, Building 644, S.W. 13th St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-737-5398"+"</p>"+"<p>"+"<b>Website:</b> http://fa.oregonstate.edu/surplus"+"</p>"

				});

				// Add an InfoWindow for OSU Repair Fair
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Bellevue Computers
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5833, -123.258 ),
					map:      map,
					content:  "<p>"+"<b><u>Bellevue Computers</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REPAIR"+"</p>"+"<p>"+"<b>Address:</b> 1865 NW 9th St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-757-3487"+"</p>"+"<p>"+"<b>Website:</b> http://www.bellevuepc.com/"+"</p>"

				});

				// Add an InfoWindow for Bellevue Computers
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in P.K Furniture Repair & Refinishing
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5406, -123.381 ),
					map:      map,
					content:  "<p>"+"<b><u>P.K Furniture Repair & Refinishing</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REPAIR"+"</p>"+"<p>"+"<b>Address:</b> 5270 Corvallis-Newport Hwy, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> 541-230-1727"+"</p>"+"<p>"+"<b>Website:</b> http://www.pkfurniturerefinishing.net/"+"</p>"

				});

				// Add an InfoWindow for P.K Furniture Repair & Refinishing
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Furniture Restoration Center
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5403, -123.367 ),
					map:      map,
					content:  "<p>"+"<b><u>Furniture Restoration Center</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REPAIR"+"</p>"+"<p>"+"<b>Address:</b> 1321 Main St, Philomath, OR"+"</p>"+"<p>"+"<b>Phone:</b> (541)929-6681"+"</p>"+"<p>"+"<b>Website:</b> http://restorationsupplies.com"+"</p>"

				});

				// Add an InfoWindow for Furniture Restoration Center
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Power equipment
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.589, -123.246 ),
					map:      map,
					content:  "<p>"+"<b><u>Power equipment</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REPAIR"+"</p>"+"<p>"+"<b>Address:</b> 713 NE Circle Blvd, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> (541)757-8075"+"</p>"+"<p>"+"<b>Website:</b> https://corvallispowerequipment.stihldealer.net"+"</p>"

				});

				// Add an InfoWindow for Power equipment
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Robnett's
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5611, -123.262 ),
					map:      map,
					content:  "<p>"+"<b><u>Robnett's</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REPAIR"+"</p>"+"<p>"+"<b>Address:</b> 400 SW 2nd St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> (541)753-5531"+"</p>"+"<p>"+"<b>Website:</b> http://ww3.truevalue.com/robnetts/Home.aspx"+"</p>"

				});

				// Add an InfoWindow for Robnett's
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Footwise
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5634, -123.261 ),
					map:      map,
					content:  "<p>"+"<b><u>Footwise</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REPAIR"+"</p>"+"<p>"+"<b>Address:</b> 301 SW Madison Ave #100, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> (541)757-0875"+"</p>"+"<p>"+"<b>Website:</b> http://footwise.com/"+"</p>"

				});

				// Add an InfoWindow for Footwise
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Sedlack
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5627, -123.26 ),
					map:      map,
					content:  "<p>"+"<b><u>Sedlack</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REPAIR"+"</p>"+"<p>"+"<b>Address:</b> 225 SW 2nd St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> (541)752-1498"+"</p>"+"<p>"+"<b>Website:</b> http://www.sedlaksshoes.net/"+"</p>"

				});

				// Add an InfoWindow for Sedlack
				infowindow = new google.maps.InfoWindow();
				google.maps.event.addListener( marker, 'click', ( function( marker ) {

					return function() {
			
						infowindow.setContent( marker.content );
						infowindow.open( map, marker );
				
					}

				})( marker ));
				
				// Place a marker in Foam Man
				marker = new google.maps.Marker({

					position: new google.maps.LatLng( 44.5833, -123.258 ),
					map:      map,
					content:  "<p>"+"<b><u>Foam Man</u></b>"+"</p>"+"<p>"+"<b>Type:</b> REPAIR"+"</p>"+"<p>"+"<b>Address:</b> 2511 NW 9th St, Corvallis, OR"+"</p>"+"<p>"+"<b>Phone:</b> (541)754-9378"+"</p>"+"<p>"+"<b>Website:</b> http://www.thefoammancorvallis.com"+"</p>"

				});

				// Add an InfoWindow for Foam Man
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