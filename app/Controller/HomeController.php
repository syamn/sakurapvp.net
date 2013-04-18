<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
 
class HomeController extends AppController { 
	public function index() {
		//$this->layout = "Sample"; // Use default layout
		//$this->set("msg", "This is home controller!");
		$this->set('title_for_layout', 'SakuraPVP');
	}
}