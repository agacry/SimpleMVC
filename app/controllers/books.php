<?php
class Books extends Controller
{
	public function index()
	{
		echo (__CLASS__).' Controller';
		
		$this->load->model(array('users_model','books_model'));
		
		$this->books_model->get_books();
		
		$get_all	= $this->books_model->get_all();
		foreach ($this->books_model->result() as $row)
			echo '<pre>'.$row->name.'</pre>';
		
	}
	
	public function additional($a = '', $b = '')
	{
		echo 'Additional page in '.(__CLASS__).' class with parameter: '. $a . ' and ' . $b;
		
		$this->load->model('books_model');
		
		$data	= array('name' => 'my name', 'value' => 'my value');
		$this->books_model->insert($data);
	}
}
?>
