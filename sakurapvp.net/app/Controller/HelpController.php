<?php
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
 
class HelpController extends AppController { 
	public function map_making($page = 0) {
		$page = (int) $page;
		if ($page < 0 || $page >= 2){
			$page = 0;
		}

		$this->set('tabId', $page);
		$this->set('title_for_layout', 'マップの製作について');
	}
}