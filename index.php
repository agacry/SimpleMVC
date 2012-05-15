<?php
// Starting the session
session_start();

// Get the path of this application
$basepath	= realpath(dirname(__FILE__));

// Define BASEPATH of the application path
define('BASEPATH',	str_replace('\\', '/', $basepath) .'/' );

include BASEPATH.'engine/common.php';
include BASEPATH.'engine/route.php';
include BASEPATH.'engine/controller.php';


function load_class($class, $path)
{
	$filename	= $path . strtolower($class) . '.php';
	
	if ( file_exists($filename) )
	{
		require_once($filename);
		
		$class_name	= ucwords($class);
		
		return new $class_name();
	}
	die('Cannot find class : '. ucwords($class) .' in '. $filename);
}


function &get_object_controller()
{
	return Controller::$main_controller_object;
}

$R	= new Route();

$R->set_route();

?>
