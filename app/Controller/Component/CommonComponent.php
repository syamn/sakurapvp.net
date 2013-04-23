<?php

class CommonComponent extends Component {
	/**
	 * ランダムな自動生成文字列を返す
	 *
	 * @param int 生成する文字数
	 * @param string 生成するのに使用する文字列
	 * @return 生成された文字列
	 */
	public function getRandomString($length = 8, $chars = null) {
		// Default random chars. Don't use 'IL1 il O0o'
		if (empty($chars)) $chars = "abcdefghjkmnpqrstuvwxABCDEFGHJKMNPQRSTUVWXYZ23456789";
		$length = (int) $length;

		$chars = str_split($chars);
		for ($i = 0, $key = ''; $i < $length; $i++) {
			$pos = array_rand($chars, 1);
			$key .= $chars[$pos];
		}

		return $key;
	}
}