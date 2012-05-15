<?php if (! defined('BASEPATH') ) die('No direct script access allowed!'); ?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title><?php echo $title;?></title>
	<link rel="stylesheet" href="<?php echo base_url();?>content/css/style.css" media="all" />
</head>
<body>
	<div id="wrapper" class="error404">
		<h1><?php echo $title; ?></h1>
		<h2><?php echo $message; ?></h2>
	</div>
</body>
</html>