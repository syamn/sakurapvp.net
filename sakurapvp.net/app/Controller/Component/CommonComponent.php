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

	/**
	 * Unix秒を相対時間にして返す
	 *
	 * @param int 対象のUnix秒
	 * @param int 起点とするUnix秒 指定しなければ現在時刻
	 * @param bool 接尾語(前/後)を付加するかどうか 指定しなければtrue
	 * @return 相対時間表記の文字列
	 */
	public function getRelativeTime($target, $base = null, $addSuffix = true){
		if (empty($base)) $base = time();
		$target = (int) $target;
		$base = (int) $base;

		$time = $base - $target;
		$suffix = ($time >= 0) ? '前' : '後';
		$time = abs($time);
		$str = '';

		if ($time < 60){
			$str = $time . '秒';
		}
		elseif ($time < (60 * 60)){
			$str = floor($time / 60) . '分';
		}
		elseif ($time < (60 * 60 * 24)){
			$str .= floor($time / (60 * 60)) . '時間';
		}
		else {
			$days = floor($time / (60 * 60 * 24));
			if ($days <= 15) {
				$str .= $days . '日';
			}else{
				// 15日以上前
				if (date('Y', $target) === date('Y', $base)){
					$str .= date('n月j日', $target);
				}else{
					$str .= date('y年 n月j日', $target);
				}				
				$addSuffix = false;
			}
		}

		if ($addSuffix){
			$str .= $suffix;
		}

		return $str;
	}
}