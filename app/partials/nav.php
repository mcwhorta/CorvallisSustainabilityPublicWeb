<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha256-7s5uDGW3AHqw6xtJmNNtr+OBRJUlgkNJEo78P4b0yRw= sha512-nNo+yCHEyn0smMxSswnf/OnX6/KwJuZTlNZBjauKhTK0c+zT+q5JOCx0UFhXQ6rJR9jg6Es8gPuD2uZcYDLqSw==" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha256-KXn5puMvxCw+dAYznun+drMdG1IFl3agK0p/pqT9KAo= sha512-2e8qq0ETcfWRI4HJBzQiA3UoyFk6tbNyG+qSaIBZLyW9Xf3sWZHN/lxe9fTh1U45DpPf07yj94KsUHHWe4Yk1A==" crossorigin="anonymous"></script>

<?php
/*
The http://blogs.shephertz.com/2014/05/21/how-to-implement-url-routing-in-php/ following function will strip the script name from URL i.e.  http://www.something.com/search/book/fitzgerald will become /search/book/fitzgerald
*/
function getCurrentUri() {
  $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
  $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
  if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
  $uri = '/' . trim($uri, '/');
  $bodytag = str_replace($uri, "", "http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");
  echo "$bodytag";
  //return $bodytag;
}
?>
<script>
$(document).ready(function() {
  function _reset() {
	$('.home').removeClass('active');
    $('.business').removeClass('active');
    $('.users').removeClass('active');
    $('.categories').removeClass('active');
	$('.map').removeClass('active');
  }
  if (window.location.pathname.indexOf('home.php') !== -1 )
  {
    _reset();
    $('.home').addClass('active');
  }
  if (window.location.pathname.indexOf('businesses.php') !== -1 )
  {
    _reset();
    $('.business').addClass('active');
  }
  if (window.location.pathname.indexOf('users.php') !== -1 )
  {
    _reset();
    $('.users').addClass('active');
  }
  if (window.location.pathname.indexOf('categories.php') !== -1 )
  {
    _reset();
    $('.categories').addClass('active');
  }
  if (window.location.pathname.indexOf('map.php') !== -1 )
  {
    _reset();
    $('.map').addClass('active');
  }
  //active
});

</script>
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Corvalis Sustainability</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
		<li class="home"><a href="<?php getCurrentUri() ?>/home.php">Home</a></li>
        <li class="business"><a href="<?php getCurrentUri() ?>/businesses.php">Businesses</a></li>
        <li class="categories"><a href="<?php getCurrentUri() ?>/categories.php">Categories</a></li>
		<li class="map"><a href="<?php getCurrentUri() ?>/map.php">Map</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
