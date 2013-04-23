<?php
class CommonHelper extends AppHelper {
	// CommonHelperクラスに存在しないメソッドはCommonComponentクラスを探す
	function __call($methodName, $args){
		App::import('Component', 'Common'); 
		$common = new CommonComponent(new ComponentCollection());
		return call_user_func_array(array($common, $methodName), $args);
	}
}