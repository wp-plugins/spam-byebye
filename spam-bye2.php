<?php
/*
Plugin Name: spam-byebye
Plugin URI: http://cmf.ohtanz.com/
Description: コメントスパム対策用プラグイン
Author: ohtan
Version: 2.2.0
Author URI: http://cmf.ohtanz.com/
License: GPL2
*/

/*	@2008-2012 ohtan
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('SB2_CONFIG_DEF', WP_PLUGIN_DIR."/spam-byebye/config.default.php");
define('SB2_CONFIG_MAIN', WP_CONTENT_DIR."/spam-byebye.config.php");
define('SB2_SETUP_FILE', WP_PLUGIN_DIR."/spam-byebye/setup.php");
define('SB2_CONFIG_FILE', (file_exists(SB2_CONFIG_MAIN) ? SB2_CONFIG_MAIN : SB2_CONFIG_DEF));

add_filter('preprocess_comment', 'spambye2Load', 1);
add_action('admin_menu', 'spambye2SetupLoad');
add_filter('plugin_action_links', 'spambye2AddLink', 10, 2);
add_action('admin_head', 'spambye2AddJs', 100);

function spambye2Load($inData)
{
	if (is_user_logged_in() === FALSE && file_exists(SB2_CONFIG_FILE)) {
		include_once(SB2_CONFIG_FILE);

		$sb2 = & new spamBye2();
		$sb2->spamBye2Check(
			$inData['comment_author'],
			$inData['comment_author_url'],
			$inData['comment_content'],
			$inData['comment_author_email']
		);
	}

	return $inData;
}

function spambye2SetupLoad()
{
	if (file_exists(SB2_CONFIG_FILE) && file_exists(SB2_SETUP_FILE)) {
		$sb2s = & new spamBye2Setup();
		add_submenu_page('options-general.php', 'SPAM-BYEBYE設定', 'SPAM-BYEBYE設定', 8, 'spam-byebye', array($sb2s, 'spambye2SetupCheck'));
	}
}

function spambye2AddLink($links, $file)
{
	if ($file === plugin_basename(__FILE__)) {
		$settings_link = '<a href="options-general.php?page=spam-byebye">設定</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}

function spambye2AddJs()
{
	if ($_GET['page'] === "spam-byebye") {
		echo '<script type="text/javascript" src="' . plugin_dir_url(__FILE__) . 'setup.js"></script>' . "\n";
	}
}

class spamBye2Setup
{
	var $error = 0;

	function spambye2SetupCheck()
	{
		if (isset($_POST['spam-byebye_update'])) {
			$this->sb2SetupSave();
		}

		if (!$this->error) {
			$this->sb2SetupSetDefine();
		}

		include_once(SB2_SETUP_FILE);
	}

	function sb2SetupSave()
	{
		$tmpConfigFile = WP_PLUGIN_DIR."/spam-byebye/_config.php";

		$this->sb2SetupValid();

		if (!$this->error) {
			$objectNum = 1;
			$line = "<?php\n";

			foreach ((array)$_POST as $key=>$val) {
				if (preg_match("/^SB2_(.+)$/", $key)) {
					$val = str_replace(array("'", ","), array("\\'", "\\,"), $val);

					switch (true) {
						case preg_match("/^SB2_OBJECT_([0-9]+)$/", $key, $matches):
							$line .= "define('SB2_OBJECT_${objectNum}', '" . $this->sb2SetupSreplace($val) . "');\n";
							if ($val[0] === "sb2NgWord") {
								$line .= "define('SB2_NGWORD_${objectNum}', '"
									. $this->sb2SetupSreplace(str_replace(array("'", ","), array("\\'", ""), $_POST['SB2_NGWORD_'.$matches[1]][0])) . "');\n";
							}
							$objectNum++;
							break;
						case preg_match("/^SB2_DNSBL_HOSTS$/", $key):
						case preg_match("/^SB2_URIBL_HOSTS$/", $key):
						case preg_match("/^SB2_WHITE_LISTS$/", $key):
							$line .= "define('${key}', '" . $this->sb2SetupSreplace($val[0]) . "');\n";
							break;
						case preg_match("/^SB2_NGWORD_[0-9]+$/", $key):
							break;
						default:
							if (is_numeric($val[0])) {
								$line .= "define('${key}', {$val[0]});\n";
							} else {
								$line .= "define('${key}', '{$val[0]}');\n";
							}
							break;
					}
				}
			}

			$line .= "?>";

			$buff = fopen($tmpConfigFile,"w")
						or wp_die("Can't open ${tmpConfigFile}");

			rewind($buff);
			fwrite($buff, $line, strlen($line));
			fclose($buff);

			rename($tmpConfigFile, SB2_CONFIG_MAIN)
				or wp_die("Can't rename ${tmpConfigFile} to ".SB2_CONFIG_MAIN);

			$_POST['_SB2_RESULT'] = "保存しました";
		} else {
			$_POST['_SB2_RESULT'] = "設定に誤りがあります";
		}

		return;
	}

	function sb2SetupValid()
	{
		if (!is_numeric($_POST['SB2_SPAM_LEVEL'][0])) {
			$_POST['SB2_SPAM_LEVEL']['error'] = "値を正しく入力してください";
			$this->error = 1;
		}

		if (!is_numeric($_POST['SB2_SPAM_ACTION'][0])) {
			$_POST['SB2_SPAM_ACTION']['error'] = "値を選択してください";
			$this->error = 1;
		} else {
			if (!$_POST['SB2_SPAM_ACTION'][0] && $_POST['SB2_SPAM_MESSAGE'][0] === "") {
				$_POST['SB2_SPAM_MESSAGE']['error'] = "値を正しく入力してください";
				$this->error = 1;
			} elseif ($_POST['SB2_SPAM_ACTION'][0] && !preg_match('/^(https?)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $_POST['SB2_SPAM_REDIRECT'][0])) {
				$_POST['SB2_SPAM_REDIRECT']['error'] = "値を正しく入力してください";
				$this->error = 1;
			}
		}

		foreach (preg_split("/[\s]+/", $_POST['SB2_DNSBL_HOSTS'][0]) as $val) {
			if ($val !== "" && !preg_match("/^[\w\-\.]*[\w\-]+\.[a-z]+$/i", $val)) {
				$_POST['SB2_DNSBL_HOSTS']['error'] = "値を正しく入力してください";
				$this->error = 1;
				break;
			}
		}
		foreach (preg_split("/[\s]+/", $_POST['SB2_URIBL_HOSTS'][0]) as $val) {
			if ($val !== "" && !preg_match("/^[\w\-\.]*[\w\-]+\.[a-z]+$/i", $val)) {
				$_POST['SB2_URIBL_HOSTS']['error'] = "値を正しく入力してください";
				$this->error = 1;
				break;
			}
		}
		foreach (preg_split("/[\s]+/", $_POST['SB2_WHITE_LISTS'][0]) as $val) {
			if ($val !== "" && !preg_match("/^[0-9A-Za-z._\-]+@[0-9A-Za-z.\-]+$/i", $val)) {
				$_POST['SB2_WHITE_LISTS']['error'] = "値を正しく入力してください";
				$this->error = 1;
				break;
			}
		}

		foreach ($_POST as $key=>$val) {
			if (preg_match("/^SB2_OBJECT_([0-9]+)$/", $key, $match)) {
				switch ($val[0]) {
					case "sb2CharactorKana":
					case "sb2Charactor":
					case "sb2Dnsbl":
					case "sb2Uribl":
					case "sb2NgWord":
					case "sb2UrlCount":
					case "sb2Length":
					case "sb2FeedCount":
						break;
					default:
						$_POST[$key]['error'] = "チェック内容を選択してください";
						$this->error = 1;
						break;
				}

				switch ($val[1]) {
					case "author":
					case "url":
					case "content":
						break;
					default:
						if ($val[0] !== "sb2Dnsbl") {
							$_POST[$key]['error'] = "チェック対象を選択してください";
							$this->error = 1;
						}
						break;
				}

				switch ($val[0]) {
					case "sb2CharactorKana":
					case "sb2Charactor":
					case "sb2Uribl":
					case "sb2NgWord":
					case "sb2UrlCount":
					case "sb2Length":
					case "sb2FeedCount":
						if (!is_numeric($val[2])) {
							$_POST[$key]['error'] = "値を正しく入力してください";
							$this->error = 1;
						}
						break;
					case "sb2Dnsbl":
						if (!is_numeric($val[1]) || !is_numeric($val[2])) {
							$_POST[$key]['error'] = "値を正しく入力してください";
							$this->error = 1;
						}
						break;
					default:
						break;
				}

				switch ($val[0]) {
					case "sb2Length":
					case "sb2FeedCount":
					case "sb2NgWord":
					case "sb2UrlCount":
					case "sb2Uribl":
						if (!is_numeric($val[3])) {
							$_POST[$key]['error'] = "値を正しく入力してください";
							$this->error = 1;
						}
						break;
					default:
						break;
				}

				switch ($val[0]) {
					case "sb2Length":
					case "sb2FeedCount":
					case "sb2UrlCount":
						if (!is_numeric($val[4])) {
							$_POST[$key]['error'] = "値を正しく入力してください";
							$this->error = 1;
						}
						break;
					default:
						break;
				}

				switch ($val[0]) {
					case "sb2NgWord":
						if (str_replace(",", "", $this->sb2SetupSreplace($_POST['SB2_NGWORD_'.$match[1]][0])) === "") {
							$_POST[$key]['error'] = "値を正しく入力してください";
							$this->error = 1;
						}
						break;
					default:
						break;
				}
			}
		}

		return;
	}

	function sb2SetupSetDefine()
	{
		include_once(SB2_CONFIG_FILE);

		$_POST['SB2_SPAM_LEVEL'][0] = SB2_SPAM_LEVEL;
		$_POST['SB2_SPAM_ACTION'][0] = SB2_SPAM_ACTION;
		$_POST['SB2_SPAM_MESSAGE'][0] = SB2_SPAM_MESSAGE;
		$_POST['SB2_SPAM_REDIRECT'][0] = SB2_SPAM_REDIRECT;
		$_POST['SB2_DNSBL_HOSTS'][0] = str_replace(",", "\r", SB2_DNSBL_HOSTS);
		$_POST['SB2_URIBL_HOSTS'][0] = str_replace(",", "\r", SB2_URIBL_HOSTS);
		$_POST['SB2_WHITE_LISTS'][0] = (defined('SB2_WHITE_LISTS') ? str_replace(",", "\r", SB2_WHITE_LISTS) : null);
		$_POST['SB2_ENTRY_OBJECT'][0] = SB2_ENTRY_OBJECT;

		for ($i = 1; $i <= SB2_ENTRY_OBJECT; $i++) {
			$_POST['SB2_OBJECT_'.$i] = explode(",", constant('SB2_OBJECT_'.$i));

			if ($_POST['SB2_OBJECT_'.$i][0] === "sb2NgWord") {
				$_POST['SB2_NGWORD_'.$i][0] = str_replace(",", "\r", constant('SB2_NGWORD_'.$i));
			}
		}

		return;
	}

	function sb2SetupSreplace($str)
	{
		if (is_array($str)) {
			$strAfter = implode(",", $str);
		} else {
			$strAfter = trim(preg_replace("/\s+/", ",", $str), ",");
		}

		return $strAfter;
	}
}

class spamBye2
{
	var $uriblHosts;
	var $dnsblHosts;
	var $reverseaddr;
	var $sb2Badpoint = 0;

	function spamBye2Check($author, $url, $content, $email)
	{
		(array)$white_list = $this->sb2Explode(SB2_WHITE_LISTS, ',');
		if (in_array($email, $white_list)) return;

		if (!function_exists("mb_convert_encoding") || !function_exists("mb_strlen") || !function_exists("mb_detect_encoding")) {
			wp_die('mbstring function is not found');
		}

		$this->sb2CharConv($author);
		$this->sb2CharConv($url);
		$this->sb2CharConv($content);

		if (!$this->sb2DefineCheck('SB2_ENTRY_OBJECT')) wp_die('SB2_ENTRY_OBJECT is not found');
		if (!$this->sb2DefineCheck('SB2_SPAM_LEVEL')) wp_die('SB2_SPAM_LEVEL is not found');
		if (!$this->sb2DefineCheck('SB2_SPAM_ACTION')) wp_die('SB2_SPAM_ACTION is not found');

		$this->uriblHosts  = ($this->sb2DefineCheck('SB2_URIBL_HOSTS') ? $this->sb2Explode(SB2_URIBL_HOSTS, ',') : null);
		$this->dnsblHosts  = ($this->sb2DefineCheck('SB2_DNSBL_HOSTS') ? $this->sb2Explode(SB2_DNSBL_HOSTS, ',') : null);
		$this->reverseaddr = implode('.', $this->sb2ExplodeToreverse($_SERVER['REMOTE_ADDR'], '.'));

		for ($c = 1; $c <= SB2_ENTRY_OBJECT; $c++) {
			if ($this->sb2DefineCheck('SB2_OBJECT_'.$c)) {
				$sb2ObjectList = $this->sb2Explode($this->sb2SetConstant('SB2_OBJECT_'.$c), ',');

				list($funcName, $checkObject, $spamPoint, $maxPoint, $safeCount) = $this->sb2SetObject($sb2ObjectList);

				if (method_exists($this,$funcName)) {
					if ($funcName === "sb2Dnsbl") {
						$this->$funcName($spamPoint,$maxPoint);
					} elseif ($funcName === "sb2NgWord") {
						if ($this->sb2DefineCheck('SB2_NGWORD_'.$c)) {
							$this->$funcName($$checkObject, $spamPoint, $this->sb2Explode($this->sb2SetConstant('SB2_NGWORD_'.$c), ','), $maxPoint);
						} else {
							wp_die('SB2_NGWORD_'.$c.' is not found');
						}
					} elseif ($funcName === "sb2CharactorKana" || $funcName === "sb2Charactor") {
						$this->$funcName($$checkObject, $spamPoint);
					} elseif ($funcName === "sb2UrlCount" || $funcName === "sb2Length" || $funcName === "sb2FeedCount") {
						$this->$funcName($$checkObject, $spamPoint, $maxPoint, $safeCount);
					} else {
						$this->$funcName($$checkObject, $spamPoint, $maxPoint);
					}
				} else {
					wp_die('method name('.$funcName.') is bad');
				}

				if (SB2_SPAM_LEVEL <= $this->sb2Badpoint) {
					if (SB2_SPAM_ACTION) {
						if (!$this->sb2DefineCheck('SB2_SPAM_REDIRECT')) wp_die('SB2_SPAM_REDIRECT is not found');

						header("Location: ".SB2_SPAM_REDIRECT);
					} else {
						if (!$this->sb2DefineCheck('SB2_SPAM_MESSAGE')) wp_die('SB2_SPAM_MESSAGE is not found');

						wp_die(SB2_SPAM_MESSAGE);
					}
				}
			} else {
				wp_die('SB2_OBJECT_'.$c.' is not found');
			}
		}

		return;
	}

	function sb2SetObject($objectList)
	{
		if ($objectList[0] === "sb2Dnsbl") {
			return array($objectList[0], null, $objectList[1], $objectList[2], null);
		} elseif ($objectList[0] === "sb2CharactorKana" || $objectList[0] === "sb2Charactor") {
			return array($objectList[0], $objectList[1], $objectList[2], null, null);
		} elseif ($objectList[0] === "sb2UrlCount" || $objectList[0] === "sb2Length" || $objectList[0] === "sb2FeedCount") {
			return array($objectList[0], $objectList[1], $objectList[2], $objectList[3], $objectList[4]);
		} else {
			return array($objectList[0], $objectList[1], $objectList[2], $objectList[3], null);
		}
	}

	function sb2SetConstant($constName)
	{
		return constant($constName);
	}

	function sb2DefineCheck($defineName)
	{
		return defined($defineName);
	}

	function sb2CharConv(&$str)
	{
		if (!$str) return;
		$str = mb_convert_encoding($str, 'UTF-8', 'auto');

		return;
	}

	function sb2Charactor($str, $point)
	{
		if (mb_detect_encoding($str) == 'ASCII') {
			$this->sb2Badpoint += $point;
		}

		return;
	}

	function sb2CharactorKana($str, $point)
	{
		if (!preg_match('/(\xe3\x81[\x81-\xbf]|\x82[\x80-\x93]|\x83\xbc)/', $str)) {
			$this->sb2Badpoint += $point;
		}

		return;
	}

	function sb2UrlCount($str, $point, $maxpoint, $safecount)
	{
		$urlcount  = substr_count($str, 'ttp://');
		$pluspoint = ($urlcount > $safecount ? ($point * $urlcount) : 0);
		$this->sb2Badpoint += $this->sb2PlusPoint($maxpoint, $pluspoint);

		return;
	}

	function sb2Length($str, $point, $maxpoint, $safelength)
	{
		$pluspoint = 0;
		foreach ((array)preg_split("/\n/", $str) as $val) {
			if (mb_strlen($val, 'UTF-8') > $safelength) {
				$pluspoint += $point;
			}
		}

		$this->sb2Badpoint += $this->sb2PlusPoint($maxpoint, $pluspoint);

		return;
	}

	function sb2FeedCount($str, $point, $feedcount, $totalcount)
	{
		$pattern = '/[\r\n]{' . $feedcount . ',}/';
		preg_match_all($pattern, $str, $linefeeds);

		$pluscount = 0;
		foreach ((array)$linefeeds[0] as $val) {
			$cr = substr_count($val, "\r");
			$lf = substr_count($val, "\n");
			$pluscount += ($lf >= $cr ? $lf : $cr);
		}

		if ($pluscount > $totalcount) $this->sb2Badpoint += $point;

		return;
	}

	function sb2NgWord($str, $point, $ngword, $maxpoint)
	{
		$pluspoint = 0;
		foreach ((array)$ngword as $val) {
			$pluspoint += (strstr($str, $val) ? $point : 0);
		}

		$this->sb2Badpoint += $this->sb2PlusPoint($maxpoint, $pluspoint);

		return;
	}

	function sb2Dnsbl($point, $maxpoint)
	{
		$pluspoint = 0;
		foreach ((array)$this->dnsblHosts as $val) {
			if ($this->sb2SearchDns($this->reverseaddr.'.'.$val)) {
				$pluspoint += $point;
			}
		}

		$this->sb2Badpoint += $this->sb2PlusPoint($maxpoint, $pluspoint);

		return;
	}

	function sb2Uribl($str,$point, $maxpoint)
	{
		preg_match_all('/ttp\:\/\/[\w\.\-]+/i', $str, $urllist);

		$fqdnlist = array();
		foreach ((array)$urllist[0] as $val) {
			if ($this->sb2Ipv4Check($val)) {
				$val = implode('.', $this->sb2ExplodeToreverse($val, '.'));
			} else {
				$val = $this->sb2SearchHost($val);
			}

			array_push($fqdnlist, str_replace('ttp://', '', $val));
		}

		$fqdnlist = array_unique($fqdnlist);

		$pluspoint = 0;
		foreach ((array)$fqdnlist as $val) {
			foreach ((array)$this->uriblHosts as $val2) {
				if ($this->sb2SearchDns($val.'.'.$val2)) {
					$pluspoint += $point;
				}
			}
		}

		$this->sb2Badpoint += $this->sb2PlusPoint($maxpoint, $pluspoint);

		return;
	}

	function sb2SearchHost($fqdn)
	{
		$divfqdn = $this->sb2ExplodeToreverse($fqdn, '.');
		$tld     = array_shift($divfqdn);

		foreach ((array)$divfqdn as $val) {
			$fqdn = $tld = $val . '.' . $tld;

			if ($this->sb2Ipv4Check(gethostbyname($fqdn))) {
				break;
			}
		}

		return $fqdn;
	}

	function sb2SearchDns($fqdn)
	{
		return checkdnsrr($fqdn, "A");
	}

	function sb2Ipv4Check($addr)
	{
		return preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $addr);
	}

	function sb2Explode($str,$delimiter)
	{
		return explode($delimiter, $str);
	}

	function sb2ExplodeToreverse($str, $delimiter)
	{
		return array_reverse(explode($delimiter, $str));
	}

	function sb2PlusPoint($maxpoint, $pluspoint)
	{
		return ($maxpoint > 0 && $pluspoint > $maxpoint ? $maxpoint : $pluspoint);
	}
}

?>