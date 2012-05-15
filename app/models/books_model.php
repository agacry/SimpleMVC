<?php

class Books_model
{
	private static $resource;
	private	$table_name	= 'tabel_1';
	
	public function __construct()
	{
		// Just for reporting that you've accessed the constructor
		echo '<pre>Initialize class: '. (__CLASS__);
	}
	
	public function get_books()
	{
		echo '<pre>Initialize method: '. (__METHOD__);
	}
	
	public function get_all()
	{
		self::$resource	= mysql_query("SELECT * FROM $this->table_name") or die(mysql_error());
	}
	
	public function result()
	{
		// Initialize return values
		$return	= array();
		
		/**
		 * Why we use array_push() function?
		 * This will allow us to use foreach() function, not while function
		 * when fetching data from database because foreach() function will
		 * fetch array format like this: array(array())
		 */
		while ($row = mysql_fetch_object(self::$resource) )
		{
			array_push($return, $row);
		}
		
		// Unset the latest resource of query
		self::$resource	= NULL;
		
		return $return;
	}
	
	public function insert($values = array())
	{
		if (is_array($values) AND count($values) != 0)
		{
			$keys	= array();
			$vals	= array();
			
			foreach ($values as $key => $val)
			{
				$keys[]	= $key;
				$vals[]	= $val;
			}
			
			$query	= "INSERT INTO $this->table_name (". implode(", ", $keys) .") VALUES('". implode("', '", $vals) ."')";
			
			// If you're still in testing progress, you should checking the correct syntax
			echo '<pre>'.$query.'</pre>';
			
			// Not in testing progress? Or the syntax is ready to use?
			// Just remove the '//' bellow and remove 'echo' statement above
			//mysql_query($query) or die(mysql_error());
		}
		
	}
}

?>
