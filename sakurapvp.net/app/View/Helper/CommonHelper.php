<?php
class CommonHelper extends AppHelper {
	// CommonHelperクラスに存在しないメソッドはCommonComponentクラスを探す
	function __call($methodName, $args){
		App::import('Component', 'Common'); 
		//$common = new CommonComponent(new ComponentCollection());
		$comp = new ComponentCollection();
		$common = $comp->load('Common');
		return call_user_func_array(array($common, $methodName), $args);
	}
}