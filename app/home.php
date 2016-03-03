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
?>

<!DOCTYPE html>
<html>
	<head>
	<title>Corvalis Sustainability App</title>
	</head>
	<body>
		<?php include 'partials/nav.php';?>
		<div class="container">
			<img src="Sustainability-Fair.jpg" alt="Corvallis Sustainability Coalition" style="width:650px;height:228px;">
			<br><br>
			<h1><b>Corvallis Reuse and Repair Directory</b></h1>
			<br><br><br>
			<h2>Welcome!</h2>
			<p>To view a list of businesses that take reusable or repairable items, please select the 'Businesses' tab above.</p>
			<p>To view a list of categories and the items that fall in those categories, please select the 'Categories' tab above.</p>
			<p>To view a map of the businesses, please select the 'Map' tab above.</p>
			<br><br><br><br><br>
			<h5 style="background: white; color: black; line-height: 1em; border: 5px solid green">
				<p><b>Links to the Republic recycling depot can be found here:</b></p>
				<p>What is accepted: </p>
				<p><a href=http://site.republicservices.com/site/corvallis-or/en/documents/corvallisrecycleddepot.pdf_add_annotation>http://site.republicservices.com/site/corvallis-or/en/documents/corvallisrecycleddepot.pdf_add_annotation</a></p>
				<p>What can be placed in curbside recycling containers:</p>
				<p><a href=http://site.republicservices.com/site/corvallis-or/en/documents/detailedrecyclingguide.pdf>http://site.republicservices.com/site/corvallis-or/en/documents/detailedrecyclingguide.pdf</a></p>
			</h5>
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

</html>