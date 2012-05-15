<?php
/**
 * Create a local URL based on your basepath.
 *
 * @param	void
 * @return	string
 */
function base_url()
{
	if (isset($_SERVER['HTTP_HOST']))
	{
		$url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
		$url .= '://'. $_SERVER['HTTP_HOST'];
		$url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
	}
	else
		$url = 'http://localhost/';
	
	return	$url;
}

/**
 * Header redirect in two flavors
 *
 * @param	string
 * @return	mixed
 */
function redirect($url = '')
{
	if (trim($url) == '')
		$url	= base_url();
	
	header("Refresh:0;url=".$url);
	exit();
}

/**
 * Takes a any string as input and creates a
 * human-friendly URL string with either a dash
 * or an underscore as the word separator.
 *
 * @param	string
 * @return	string
 */
function dash_url($str = '')
{
	if (trim($str) == '')
		return	'';
	
	$search		= '_';
	$replace	= '-';
	
	$trans = array(
					'&\#\d+?;'				=> '',
					'&\S+?;'				=> '',
					'\s+'					=> $replace,
					'[^a-z0-9\-\._]'		=> '',
					$replace.'+'			=> $replace,
					$replace.'$'			=> $replace,
					'^'.$replace			=> $replace,
					'\.+$'					=> ''
				);
	
	$str = strip_tags(strtolower($str));
	
	foreach ($trans as $key => $val)
		$str = preg_replace("#".$key."#i", $val, $str);
	
	return trim(stripslashes($str));
}

/**
 * This function takes an error message as input
 * (either as a string or an array) and displays
 * it using the specified template.
 *
 * @param	string
 * @param	string
 * @return	string
 */
function error_404($title = '', $message = '')
{
	$file	= BASEPATH.'app/views/404.php';
	
	if (!file_exists($file))
		die('The page you requested was not found!');
	
	require($file);
	exit();
}

/**
 * Create an alert message for reporting
 *
 * @param	string
 * @param	array	style
 * @return	string
 */
function alert($str = '', $css = array())
{
	$style	= array();
	if (count($css) != 0)
	{
		foreach ($css as $key => $val)
			$style[]	= ' '. $key .'="'. $val .'"';
	}
	
	return	'<div'. implode('', $style) .'>'. $str .'</div>';
}

/**
 * Remove html tags for checking the post input
 *
 * @param	string
 * @return	string
 */
function trim_html_tags($str = '')
{
	$patern		= array('<br>','<br/>','<br />','<p>','</p>');
	$replace	= array('','','','','');
	
	return str_replace($patern, $replace, trim($str));
}

/**
 * Get "now" time
 *
 * Returns time() or its GMT equivalent
 *
 * @param	void
 * @return	integer
 */
function now()
{
	return	mktime(gmdate("H", time()), gmdate("i", time()), gmdate("s", time()), gmdate("m", time()), gmdate("d", time()), gmdate("Y", time()));
}

/**
 * Convert MySQL Style Datecodes
 *
 * This function is identical to PHPs date() function,
 * except that it allows date codes to be formatted using
 * the MySQL style.
 *
 * The benefit of doing dates this way is that you don't
 * have to worry about escaping your text letters that
 * match the date codes.
 *
 * @param	string
 * @param	integer
 * @return	integer
 */
function gdate($format = '', $time = '')
{
	if (trim($format) == '')
		return '';
	
	if (trim($time) == '')
		$time = now();
	
	return gmdate($format, $time);
}

/**
 * Converts GMT time to a localized value
 *
 * Takes a Unix timestamp (in GMT) as input, and returns
 * at the local value based on the timezone and DST setting
 * submitted
 *
 * @param	integer Unix timestamp
 * @param	string	timezone
 * @return	integer
 */
function local_date($time = '', $timezone = 0)
{
	if (trim($time) == '')
		$time	= now();
	
	$time += $timezone * 3600;
	
	return $time;
}

/**
 * Create a Random String
 *
 * Useful for generating passwords or hashes.
 *
 * @param	string	type of random string.  alpha, alpha_numeric, numeric, and nozero
 * @param	integer	number of characters
 * @return	string
 */
function random_string($type = 'alpha_numeric', $len = 8)
{
	$str	= '';
	
	switch ($type)
	{
		case 'alpha'	:
			$patern	= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		break;
		case 'alpha_numeric'	:
			$patern	= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		break;
		case 'numeric'	:
			$patern	= '0123456789';
		break;
		case 'nozero'	:
			$patern	= '123456789';
		break;
	}
	
	if (isset($patern))
		for ($i=0; $i < $len; $i++)
			$str .= substr($patern, mt_rand(0, strlen($patern) -1), 1);
	
	return	(!empty($str)) ? $str : mt_rand();
}


/**
 * Send Email
 *
 * @param	array	the configuration of send email: name (the name sender), form (email sender), to (email recipient), subject, message
 * @return	boolean
 */
function send_email($config = array())
{
	if (count($config) == 0)
		return;
		
	$_default	= array('name','from','to','subject','message');
	foreach ($_default as $row)
		if (!isset($config[strtolower($row)]) )
			return;
	
	$header	= 'From: '. $config['name'] .' <'.$config['from'].'>\n';
	$header	.= 'To: '. $config['to'] .'\n';
	$header	.= 'Return-Path: <'.$config['from'].'>\n';
	$header	.= 'Reply-To: '. $config['name'] .' <'.$config['from'].'>\n';
	$header	.= 'X-Sender: '.$config['from'].'\n';
	$header	.= 'X-Mailer: CodeIgniter\n';
	$header	.= 'X-Priority: 3 (Normal)\n';
	$header	.= 'Message-ID: <'. uniqid('').strstr($config['from'], '@').'>\n';
	$header	.= 'Mime-Version: 1.0\n';
	$header .= "Content-Type: text/plain; charset=utf-8\n";
	$header .= "Content-Transfer-Encoding: 8bit";
	
	if (! mail($config['to'], $config['subject'], $config['message'], $header, "-f ". $config['from']) )
		return;
	return TRUE;
}
?>
