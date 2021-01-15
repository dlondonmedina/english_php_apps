<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" href="/css/base2017.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<?php echo isset($custom_header) ? $custom_header : ''; ?>
	<title>Rob's CodeIgniter Test</title>
</head>
<body>

<div id="masthead">
	<div id="bannerimg"></div>
	<div class="inner">
		<a id="logo" href="/engl" title="home">
			<img src="/engl/img/english-wordmark-purple.png" alt="Department of English">
		</a>
		<div class="uni-w">
			<a id="wlogolink" href="http://www.washington.edu">University of Washington</a>
		</div>
	</div>
</div>

	<nav class="navbar navbar-default" role="navigation">
	</nav>
	<div id="inner">
		<div id="content">
			<h1><?=$title;?></h1>
