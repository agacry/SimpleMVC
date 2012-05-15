<?php
class Welcome extends Controller
{
	public function index()
	{
		$view_file			= BASEPATH.'app/views/index.php';
		$data['title']		= 'Welcome Page';
		$data['content']	= 'This is the welcome page which is located in: <code>'.$view_file.'</code>';
		
		include($view_file);
	}
}
?>
