<?php
use core\mvc\Controller;
use core\mvc\View;

class Home_Controller extends Controller {

	public function index()
	{
		View::make('home.master');
	}

	public function test()
	{
		echo "test";
	}

	public function show()
	{
		$data['products'] = Product::where('title', 'like', '%'.$_POST['product_name'].'%')
						           ->get();

		View::make('home.master', $data);
	}

}