<?php
class Route
{
	private	$request_uri;
	private	$segments;
	private	$default_controller	= 'welcome';
	private	$default_method		= 'index';
	private	$controller;
	
	public function __construct()
	{
		// Get the request URI and then explode it by using '/' sparator
		$this->request_uri	= explode('/', $_SERVER['REQUEST_URI']);
		
		/**
		 * This is for hiding accessed file name for example http://localhost/mvc/index.php
		 * This will allows user to access:
		 *			http://localhost/mvc/		or http://localhost/mvc/index.php
		 *			http://localhost/mvc/page/	or http://localhost/mvc/index.php/page/
		 */
		$scriptName = explode('/',$_SERVER['SCRIPT_NAME']);
		
		for($i= 0;$i < count($scriptName);$i++)
		{
			if ($this->request_uri[$i] == $scriptName[$i])
				unset($this->request_uri[$i]);

		}
		
		// Sets each segments to array which is started from first index
		$this->segments		= array_values($this->request_uri);
		
		// Just for reporting that you've accessed the constructor
		echo '<pre>Initialize class: '.(__CLASS__).'</pre>';
	}
	
	public function set_controller()
	{
		$controller	= (isset($this->segments[0]) AND trim($this->segments[0]) != '') ? $this->segments[0] : $this->default_controller;
		
		// Set the requested controller filename
		$filename	= BASEPATH.'app/controllers/'. strtolower($controller) .'.php';
		
		// Checking for the existing controller filename
		if ( file_exists($filename) )
		{
			// Calls the requested controller filename
			require_once($filename);
			
			// Set the controller class name using ucwords format
			$class_name	= ucwords($controller);
			
			// Checking for the existing controller class
			if (class_exists($class_name) )
			{
				// Ready to instance? Let's instance it
				$this->controller	= new $class_name();
				
			}
			else
				// @see	./engine/common.php
				error_404('Error 404', 'Unable to find method: '. $filename);
		}
	}
	
	public function set_route()
	{
		// Start set the routing
		$this->set_controller();
		
		// Checking for second URI segment (controller method)
		if (isset($this->segments[1]) AND $this->segments[1] != '')
		{	
			$method	= $this->segments[1];
			
			// Checking for the existing method
			if (method_exists($this->controller, $method) )
			{
				$params	= array();
				
				/**
				 * For what the lines below?
				 * This will detect any method parameters which will be used in
				 * call_user_func_array(array('object class', 'method name'), array('param1','param2')) function.
				 */
				if (count($this->segments) > 2)
					for ($i=2; $i<count($this->segments); $i++)
					{
						$params[]	= $this->segments[$i];
					}
				
				// Use this function to accessing the method controller
				// @see	http://php.net/manual/en/function.call-user-func-array.php
				call_user_func_array(array($this->controller, $method), $params);
			}
			else
				// @see	./engine/common.php
				error_404('Error 404', 'Unable to find method: '. $method);
		}
		else
		{
			// Set the controller and also default controller
			$controller	= $this->controller;
			$method		= $this->default_method;
			
			// Checking for the existing method in controller
			if (method_exists($controller, $method) )
				$controller->$method();
			else
				// @see	./engine/common.php
				error_404('Error 404', 'Unable to find method: '. $method);
		}
	}
	
	public function set_default_controller($controller = '')
	{
		if (trim($controller) != '')
			$this->default_controller	= $controller;
	}
	
	public function set_default_method($method = '')
	{
		if (trim($method) != '')
			$this->default_method	= $method;
	}
}
?>
