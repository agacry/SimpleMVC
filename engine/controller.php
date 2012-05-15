<?php

class Controller
{
	public $load;
	
	public static $main_controller_object;
	
	public function __construct()
	{
		self::$main_controller_object	=& $this;
		$this->load = load_class('Loader', BASEPATH . 'engine/');
		
		// Just for reporting that you've accessed the constructor
		echo '<pre>Initialize class: '.(__CLASS__).'</pre>';
	}
}
?>
