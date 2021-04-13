<?php
$Srvr = $_SERVER['SERVER_NAME'];
$appExist = true;$app = null;if(file_exists('app.json')){$appFile = 'app.json';}elseif(file_exists('admin/app.json')){$appFile = 'admin/app.json';}elseif(file_exists('../admin/app.json')){$appFile = '../admin/app.json';}else{$appExist = false;}
if($appExist){$app = json_decode(file_get_contents($appFile),true);}
$app["constants"]["dateinterval"] = new DateInterval('P0Y0DT0H0M');
$dataExist = true;$data = null;if(file_exists('data.json')){$dataFile = 'data.json';}elseif(file_exists('admin/data.json')){$dataFile = 'admin/data.json';}elseif(file_exists('../admin/data.json')){$dataFile = '../admin/data.json';}else{$dataExist = false;}
if($dataExist){$data = json_decode(file_get_contents($dataFile),true);}
ini_set("date.timezone",$app["constants"]["timezone"]);session_start();setcookie(session_name(),session_id(),time()+$app["constants"]["session"]["lifetime"]);
if(isSet($_GET['lang'])){$lang = $_GET['lang'];$_SESSION['lang']=$lang;setcookie('lang',$lang,time()+ $app["constants"]["session"]["lifetime"]);}else if(isSet($_SESSION['lang'])){$lang = $_SESSION['lang'];}else if(isSet($_COOKIE['lang'])){$lang = $_COOKIE['lang'];$_SESSION['lang'] = $lang;}else{$lang = $app["constants"]["defaults"]["language"];$_SESSION['lang'] = $lang;setcookie('lang',$lang,time()+ $app["constants"]["session"]["lifetime"]);}
$dirc = 'ltr';if(isset($app["languages"][strtolower($lang)]["direction"])){$dirc = $app["languages"][strtolower($lang)]["direction"];}

trait chronology
{
	static function stamp(){$MDate = getdate();return sprintf("%04d",$MDate['year']).sprintf("%02d",$MDate['mon']).sprintf("%02d",$MDate['mday']).sprintf("%02d",$MDate['hours']).sprintf("%02d",$MDate['minutes']).sprintf("%02d",$MDate['seconds']);}
	static function dateinterval(){return  new DateInterval('P0Y0DT0H0M');}
	static function timezone($timezone='Europe/London'){date_default_timezone_set($timezone);}
	static function datetime($string=null){$temp = array();$cdte = date_create($string);$cdte = date_add($cdte,$GLOBALS['app']['constants']['dateinterval']);$temp["string"] = $string;$temp["date"] = date_format($cdte,"Y-m-d");$temp["time"] = date_format($cdte,"H:i");$temp["fulltime"] = date_format($cdte,"H:i:s");$temp["short"] = date_format($cdte,"Y-m-d H:i");$temp["long"] = date_format($cdte,"Y-m-d H:i:s");return $temp;}
	static function show($string=null,$format='Y-m-d h:i:s'){$cdte = date_create($string);$cdte = date_add($cdte,$GLOBALS['app']['constants']['dateinterval']);return date_format($cdte,$format);}
}
trait icons
{
	static function awesome($icon=null,$type='s',$class=null){if(!empty($class)){$class = " ".$class;}return "<i class='fa".$type." fa-".$icon.$class."'></i>";}
	static function counter($icon=null,$countericon=null,$type='s',$countertype='s',$layerclass=null,$class=null,$counterclass=null){if(!empty($layerclass)){$layerclass = " ".$layerclass;}if(!empty($class)){$class = " ".$class;}if(!empty($counterclass)){$counterclass = " ".$counterclass;}return "<span class='fa-layers fa-fw".$layerclass."'><i class='fa".$type." fa-".$icon."'></i><span class='fa-layers-counter".$counterclass."'><i class='fa".$countertype." fa-".$countericon."'></i></span></span>";}
	static function battery($number=0,$class=null){if(!empty($class)){$class = " ".$class;}switch($number){case 0:return "<i class='fas fa-battery-empty'></i>";break;case 1:return "<i class='fas fa-battery-quarter'></i>";break;case 2:return "<i class='fas fa-battery-half'></i>";break;case 3:return "<i class='fas fa-battery-three-quarters'></i>";break;case 4:return "<i class='fas fa-battery-full'></i>";break;}}
}
trait arrays
{
	static function unique($array=null){if(empty($array)){return;}return array_unique($array);}
}
trait json
{
	static function encode($array=null){return json_encode($array,JSON_UNESCAPED_UNICODE);}
	static function decode($string=null){return json_decode($string,true);}
}
trait format
{
	/*date_parse function used to get year,month,day,hour,minute,second,fraction*/
	static function email($address=null,$display=null){if(empty($address)){return;}else{if(!filter_var($address,FILTER_VALIDATE_EMAIL)){return;}if(empty($display)){return $address;}else{return $display." <".$address.">";}}}
	static function emails($array=null){$temp = array();if(is_array($array)){for($i=0;$i<count($array);$i++){$address = "";if(!empty($array[$i]["address"])){$address = $array[$i]["address"];}$display = "";if(!empty($array[$i]["display"])){$display = $array[$i]["display"];}$temp[] = self::email($address,$display);}}return implode(',',$temp);}
	static function spaces($number=1,$html=false){$char == " ";if($html){$char == "&nbsp;";}if($number > 0){$temp="";for($i=1;$i<=$number;$i++){$temp=$temp+$char;}return $temp;}}
	static function digits($number=null,$digits=1){if(!is_numeric($number)){return;}return sprintf("%0".$digits."d",$number);}
	static function fullname($firstname=null,$lastname=null){if(empty($firstname) && empty($lastname)){return;}else{if(empty($firstname) && !empty($lastname)){return $lastname;}if(!empty($firstname) && empty($lastname)){return $firstname;}return $firstname." ".$lastname;}}
	static function numerals($string){return str_replace(explode(',',$GLOBALS['app']['constants']['eastern-numerals']),explode(',',$GLOBALS['app']['constants']['arabic-numerals']),$string);}
}
trait help
{
	static function image($image='help.png'){echo " ".$GLOBALS['app'][$GLOBALS['dirc']]['open-bracket']."<a href='javascript:void(0);' class='w3-tooltip'><span style='position:absolute;".$GLOBALS['app'][$GLOBALS['dirc']]['with'].":0;bottom:18px' class='w3-text w3-tag w3-padding w3-round w3-theme w3-animate-opacity'><img alt=null src='".$image."'style=null></span>".$GLOBALS['app'][$GLOBALS['dirc']]['question-mark']."</a>".$GLOBALS['app'][$GLOBALS['dirc']]['close-bracket'];}
}
trait output
{
	static function heading($string=null,$level=1){echo "<h".$level.">".$string."</h".$level.">";}
	static function breaks($number=1){for($i=0;$i<$number;$i++){echo "<br>";}}
	static function output($string=null){echo $string;}
	static function type($string=null,$color=null){if(empty($color)){echo $string."<br>";}else{echo "<span style='color:#".$color."'>".$string."</span><br>";}}
	static function strong($string=null){echo "<strong>".$string."</strong>";}
	static function horizontal($number=1){for($i=0;$i<$number;$i++){echo "<hr>";}}
	static function capitalize($string=null){echo ucfirst($string);}
	static function printer($array=null){print_r($array);echo "<br>";}
	static function content($id=null,$echo=true,$format=null){if(empty($id)){return;}$id=strtoupper($id);if(empty($GLOBALS['contents'][$id])){return;}$temp = $GLOBALS['contents'][$id];if(!empty($format)){switch(strtolower($format)){case 'small':$temp = strtolower($temp);break;case 'upper':$temp = strtoupper($temp);break;case 'capitalized':$temp = ucfirst($temp);break;}}if($echo){echo $temp;}return $temp;}
}
trait meta
{
	static function charset($charset='utf-8'){return "<meta charset='".$charset."'>";}
	static function http_equiv($name='X-UA-Compatible',$content='IE=edge'){return "<meta http-equiv='".$name."' content='".$content."'>";}
	static function meta($name=null,$content=null){return "<meta name='".$name."' content='".$content."'>";}
	static function og($name=null,$content=null,$type='og'){switch($type){case 'og':return "<meta property='og:".$name."' content='".$content."'>";break;case 'twitter':return "<meta name='twitter:".$name."' content='".$content."'>";break;}}
	
}
class tags
{
	use meta;
	static function title($title=null){return "<title>".$title."</title>";}
	static function linktag($href=null,$rel='stylesheet'){if($href == null){return;}return "<link rel='".$rel."' href='".$href."'>";}
	static function script($src=null,$inner=null,$attribute=null){if(!empty($attribute)){$attribute = " $attribute";}if(!empty($src)){$src = " src='$src'";}return "<script".$attribute.$src."></script>";}
	static function head($array=null)
	{
		$oneroot = false;if(isset($array['one-root'])){$oneroot = $array['one-root'];}
		if($oneroot){if(empty($array['pointer'])){$array['pointer'] = null;}$styles = $array['pointer'];$scripts = $array['pointer'];$plugins = $array['pointer'];}
		else{if(empty($array['pointer'])){$array['pointer'] = null;}$styles = $array['pointer']."styles/";$scripts = $array['pointer']."scripts/";$plugins = $array['pointer']."plugins/";}
		if(empty($array['offline'])){$array['offline'] = false;}
		if(empty($array["arabic-font"])){$array["arabic-font"] = false;}
		$defaultfont = $styles."default-font.css";
		if($array['offline'])
		{
			$fontawesome = $scripts."fontawesome-all.min.js";
			$droidarabickufi = $styles."droid-arabic-kufi.css";
			$w3_css = $styles."w3-4.css";
			$animate = $styles."animate.min.css";
			$w3_js =  $scripts."w3.js";
			$chart =  $scripts."chart-min.js";
			$clipboard =  $scripts."clipboard-min.js";
		}
		else
		{
			$fontawesome = "https://kit.fontawesome.com/9390afbaad.js";
			$droidarabickufi = "https://fontlibrary.org//face/droid-arabic-kufi";
			$w3_css = "https://www.w3schools.com/w3css/4/w3.css";
			$animate = "https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css";
			$w3_js = "https://www.w3schools.com/lib/w3.js";
			$chart = "https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js";
			$clipboard = "https://cdn.jsdelivr.net/npm/clipboard@2.0.8/dist/clipboard.min.js";
		}
		$temp = "<head>";
		$charset = 'utf-8';if(!empty($array['charset'])){$charset = $array['charset'];}$temp = $temp."\n".self::charset($charset);
		$temp = $temp."\n".self::http_equiv();
		$temp = $temp."\n".self::meta('viewport','width=device-width,initial-scale=1');
		$title = null;if(!empty($array['title'])){$title = $array['title'];}$temp = $temp."\n".self::title($title);
		$description = null;if(!empty($array['description'])){$description = $array['description'];}$temp = $temp."\n".self::meta('description',$description);
		$author = null;if(!empty($array['author'])){$author = $array['author'];}$temp = $temp."\n".self::meta('author',$author);
		$keywords = null;if(!empty($array['keywords'])){$keywords = $array['keywords'];}$temp = $temp."\n".self::meta('keywords',$keywords);
		$robots = null;if(!empty($array['robots'])){$robots = $array['robots'];}$temp = $temp."\n".self::meta('robots',$robots);
		$mobile_web_app_capable = 'yes';if(!empty($array['mobile-web-app-capable'])){$mobile_web_app_capable = $array['mobile-web-app-capable'];}$temp = $temp."\n".self::meta('mobile-web-app-capable',$mobile_web_app_capable);
		$apple_mobile_web_app_capable = 'yes';if(!empty($array['apple-mobile-web-app-capable'])){$apple_mobile_web_app_capable = $array['apple-mobile-web-app-capable'];}$temp = $temp."\n".self::meta('apple-mobile-web-app-capable',$apple_mobile_web_app_capable);
		if($array[$GLOBALS['dirc']."-font"] != null){$temp = $temp."\n".self::linktag(${$array[$GLOBALS['dirc']."-font"]});}
		else{$temp = $temp."\n".self::linktag($defaultfont);}
		if(!empty($array['fontawesome'])){$fontawesome = $array['fontawesome'];}$temp = $temp."\n".self::script($fontawesome,null,'crossorigin="anonymous"');
		if(!empty($array['w3-css'])){$w3_css = $array['w3-css'];}$temp = $temp."\n".self::linktag($w3_css);
		if(!empty($array['animate'])){$animate = $array['animate'];}$temp = $temp."\n".self::linktag($animate);
		if(empty($array['w3-js'])){$array['w3-js'] = false;}if($array['w3-js']){$temp = $temp."\n".self::script($w3_js);}
		$theme_color = $GLOBALS['app']['constants']['theme']['THBC'];if(!empty($array['theme-color'])){$theme_color = $array['theme-color'];}$temp = $temp.self::meta('theme-color',"#".$theme_color);
		$icon = "favicon.png";if(!empty($array['icon'])){$icon = $array['icon'];}$temp = $temp."\n".self::linktag($icon,'icon');
		$canonical = null;if(!empty($array['canonical'])){$temp = $temp."\n".self::linktag($array['canonical'],'canonical');}
		if(!empty($array['og']))
		{
			$site_name = null;if(!empty($array['og']['site-name'][$GLOBALS['lang']])){$site_name = $array['og']['site-name'][$GLOBALS['lang']];}$temp = $temp."\n".self::og('site_name',$site_name);
			$url = null;if(!empty($array['og']['url'])){$url = $array['og']['url'];}$temp = $temp."\n".self::og('url',$url);
			$title = null;if(!empty($array['og']['title'][$GLOBALS['lang']])){$title = $array['og']['title'][$GLOBALS['lang']];}$temp = $temp."\n".self::og('title',$title);
			$description = null;if(!empty($array['og']['description'][$GLOBALS['lang']])){$description = $array['og']['description'][$GLOBALS['lang']];}$temp = $temp."\n".self::og('description',$description);
			$type = null;if(!empty($array['og']['type'])){$type = $array['og']['type'];}$temp = $temp."\n".self::og('type',$type);
			$ln = $GLOBALS['app']['constants']['defaults']['language'];
			$tr = strtoupper($GLOBALS['app']['languages'][$ln]['territory']);
			$temp = $temp."\n".self::og('locale',$ln."-".$tr);
			foreach($GLOBALS['app']['languages'] as $key=>$value){if($key != $ln){$temp = $temp."\n".self::og('locale:alternate',$key."-".strtoupper($value['territory']));}}
			$image = null;if(!empty($array['og']['image'])){$image = $array['og']['image'];}$temp = $temp."\n".self::og('image',$image);
		}
		if(!empty($array['twitter']))
		{
			$site = null;if(!empty($array['twitter']['site'])){$site = $array['twitter']['site'];}$temp = $temp."\n".self::og('site',$site,'twitter');
			$title = null;if(!empty($array['twitter']['title'][$GLOBALS['lang']])){$title = $array['twitter']['title'][$GLOBALS['lang']];}$temp = $temp."\n".self::og('title',$title,'twitter');
			$description = null;if(!empty($array['twitter']['description'][$GLOBALS['lang']])){$description = $array['twitter']['description'][$GLOBALS['lang']];}$temp = $temp."\n".self::og('description',$description,'twitter');
			$creator = null;if(!empty($array['twitter']['creator'])){$creator = $array['twitter']['creator'];}$temp = $temp."\n".self::og('creator',$creator,'twitter');
			$card = null;if(!empty($array['twitter']['card'])){$card = $array['twitter']['card'];}$temp = $temp."\n".self::og('card',$card,'twitter');
			$image = null;if(!empty($array['twitter']['image'])){$image = $array['twitter']['image'];}$temp = $temp."\n".self::og('image',$image,'twitter');
		}
		if(empty($array['clipboard'])){$array['clipboard'] = false;}if($array['clipboard']){$temp = $temp."\n".self::script($clipboard);}
		if(empty($array['chart'])){$array['chart'] = false;}if($array['chart']){$temp = $temp."\n".self::script($chart);}
		if($array['dry-js']){$temp = $temp."\n".self::script($scripts.'dry.js');}
		return $temp."\n</head>\n";
	}
	static function doctype(){return "<!DOCTYPE html>\n";}
	static function html(){return "<html dir='".$GLOBALS['dirc']."' lang='".$GLOBALS['lang']."'>\n";}
	static function body($load=null,$resize=null){return "<body onload='".$load."' onresize='".$resize."'>\n";}
	static function closing($tag='html'){return "</".$tag.">\n";}
	
}
class number
{
	public $numeric;public $value;public $sign;public $color;public $absolute;public $decimal;public $negative;public $display;function __construct($number=0,$decimal=2,$negative=null){if(is_numeric($number)){$this->numeric = true;$this->value = $number;$this->negative = $negative;$this->absolute = abs($number);if($number >= 0){$this->sign = "+";$this->color = "green";$this->display = number_format($number,$decimal);}else{$this->sign = "-";$this->color = "red";if(empty($negative)){$this->display = number_format($number,$decimal);}else{if(strpos($negative,'(') != -1){$this->display = $GLOBALS['app'][$GLOBALS['dirc']]['open-parenthesis'].number_format(abs($number),$decimal).$GLOBALS['app'][$GLOBALS['dirc']]['close-parenthesis'];}$this->display = number_format($number,$decimal);}}}}
}
class mailer
{
	use format;public $type;public $to;public $subject;public $message;public $display;public $from;public $cc;public $bcc;public $replyto;private $tos;private $headers;
	public function send(){if(empty($this->display)){$this->display = "";}if(empty($this->from)){$this->from = "";}$this->from = self::email($this->from,$this->display);$this->tos  = self::emails($this->to);echo $this->tos."<br>";$this->subject = '=?UTF-8?B?'.base64_encode($this->subject).'?=';$head   = array();$head[] = "MIME-Version: 1.0";if($this->type=="html"){$head[] = "Content-type: text/html; charset=UTF-8";}else{$head[] = "Content-type: text/plain; charset=UTF-8";}if(empty($this->from)){$head[] = "From: Private";}else{$head[] = "From: ".$this->from;}if(!empty($this->cc)){$head[] = "Cc: ".self::emails($this->cc);}if(!empty($this->bcc)){$head[] = "Bcc: ".self::emails($this->bcc);}if(!empty($this->replyto)){$headers[] = "Reply-To: ".self::emails($this->replyto);}$head[] = "X-Mailer: PHP/".phpversion();$this->headers = implode("\r\n",$head);if(mail($this->tos,$this->subject,$this->message,$this->headers)){return true;}else{return false;}}
}
class db
{
	use output;
	use format;
	public $connection;
	public $query;
	public $result;
	public $count;
	static function connect($server,$user,$password,$database){$temp = new mysqli($server,$user,$password,$database);if($temp -> connect_errno){throw new Exception('unable to connect to database');return;}mysqli_query($temp,"SET NAMES UTF8");mysqli_query($temp,"SET CHARACTER SET UTF8");return $temp;}
	static function query($connection,$query){$temp = $connection -> query($query);if(empty($temp)){throw new Exception('unable to handle query');return;}return $temp;}
	static function counting($result){$temp = mysqli_num_rows($result);return mysqli_num_rows($result);if(empty($temp)){throw new Exception('unable to count rows');return;}return $temp;}
	static function fetch($result){return $result -> fetch_array(MYSQLI_ASSOC);}
	static function free($result){$result -> free_result();}
	static function drop($connection,$table,$level=1){$query = "drop table ".$table;if($connection -> query($query)){self::heading("OK...'".$table."' table has been dropped.",$level);}else{self::heading("Failed to drop the '".$table."' table (".mysqli_error($connection).").",$level);}}
	static function exists($connection,$table,$check,$filter=null,$order=null){$temp = array();$temp["EXISTS"] = false;if($filter != ""){$filter = " and(".$filter.")";}if($order != ""){$order = " order by ".$order;}$query = "select * from ".$table." where ".$check.$filter.$order;$result = $connection -> query($query);while($row = $result -> fetch_array(MYSQLI_ASSOC)){$temp["EXISTS"] = true;$temp["COLUMNS"] = $row;return $temp;}return $temp;}
	static function truncate($connection,$table,$level=1){$query = "truncate table ".$table;if($connection -> query($query)){self::heading("OK...'".$table."' table has been truncated.",$level);}else{self::heading("Failed to truncate the '".$table."' table (".mysqli_error($connection).").",$level);}}
	static function formula($connection,$table,$formula,$filter=null){if($filter != ""){$filter = " where(".$filter.")";}$query = "select ".$formula."  as number from ".$table.$filter;$result = $connection -> query($query);while($row = $result -> fetch_array(MYSQLI_ASSOC)){return $row["number"];}}
	static function rows($connection,$table,$filter=null){if($filter != ""){$filter = " where(".$filter.")";}$query = "select count(*)  as number from ".$table.$filter;$result = $connection -> query($query);while($row = $result -> fetch_array(MYSQLI_ASSOC)){return $row["number"];}}
	static function find($connection,$table){$exist = false;$result = $connection -> query("show tables like '".$table."'");if(mysqli_num_rows($result) > 0){$exist = true;}return $exist;}
	static function create($connection,$table,$columns,$level=1){$query = "create table ".$table."(".implode(",", $columns).")";if($connection -> query($query)){self::heading("OK...'".$table."' table has been created.",$level);}else{self::heading("Failed to create the '".$table."' table (".mysqli_error($connection).").",$level);unset($columns);}}
	static function grant($connection,$table,$USERNAME,$username,$PASSWORD,$password,$md5=false){$temp = false;if($md5){$username=md5($username);$password=md5($password);}$query = "select ".$PASSWORD." from ".$table." where ".$USERNAME."="."'".$username."'";$result = $connection -> query($query);while($row = $result -> fetch_array(MYSQLI_ASSOC)){if($row[$PASSWORD] == $password){$temp = true;}}return $temp;}
	static function now($connection){$query = "select now()  as number";$result = $connection -> query($query);while($row = $result -> fetch_array(MYSQLI_ASSOC)){return $row["number"];}}
	static function tables($connection){$temp = array();$query = "show tables";$result = $connection -> query($query);while($row = $result -> fetch_array(MYSQLI_ASSOC)){foreach($row as $x => $x_value){$temp[] = $x_value;}}return $temp;}
	static function insert($table,$fieldst){$Sqlfiel = "";$Sqlvalu = "";$Field = array();$Value = array();$Dtime = array();foreach ($fieldst as $Item){if($Sqlfiel != null){$Sqlfiel = $Sqlfiel.",".$Item[1];}else{$Sqlfiel = $Item[1];}if($Sqlvalu != null){if($Item[0] == 0){$Sqlvalu = $Sqlvalu.","."'".$Item[2]."'";}elseif($Item[0] == 1){$Sqlvalu = $Sqlvalu.","."now()";}elseif($Item[0] == 2){$Sqlvalu = $Sqlvalu.","."null";}}else{if($Item[0] == 0){$Sqlvalu = "'".$Item[2]."'";}elseif($Item[0] == 1){$Sqlvalu = "now()";}elseif($Item[0] == 2){$Sqlvalu = "null";}}}return "insert into ".$table."(".$Sqlfiel.")"." values(".$Sqlvalu.")";}
	static function start()
	{
		if(($GLOBALS['app'] == null) || ($GLOBALS['app'] == null)){return;}
		$temp = null;
		$indexes_contents = explode(',',$GLOBALS['app']['indexes']['contents']);for($i=0;$i<count($indexes_contents);$i++){$GLOBALS['app']['contents'][$indexes_contents[$i]] = null;}
		$indexes_functions = explode(',',$GLOBALS['app']['indexes']['functions']);for($i=0;$i<count($indexes_functions);$i++){$GLOBALS['app']['functions'][$indexes_functions[$i]] = null;}
		$indexes_uis = explode(',',$GLOBALS['app']['indexes']['uis']);for($i=0;$i<count($indexes_uis);$i++){$GLOBALS['app']['uis'][$indexes_uis[$i]] = null;}
		if(isset($GLOBALS["app"]["host"]["server"]) && isset($GLOBALS["app"]["host"]["user"]) && isset($GLOBALS["app"]["host"]["password"]) && isset($GLOBALS["app"]["host"]["database"])){try{$temp = self::connect($GLOBALS["app"]["host"]["server"],$GLOBALS["app"]["host"]["user"],$GLOBALS["app"]["host"]["password"],$GLOBALS["app"]["host"]["database"]);}catch(Exception $e){self::type($e->getMessage(),$GLOBALS['app']['constants']['error-color']);goto datafiles;}}else{goto datafiles;}
		if(!empty($temp))
		{
			$missing = 0;
			$tables = self::tables($temp);
			$tablesno = 0;
			foreach ($GLOBALS["app"]["tables"]["static"] as $x => $x_value){$tablesno += 1;if(!in_array($x_value['name'],$tables)){$missing += 1;}}
			if($missing > 0){self::type("Operation stopped working, there are ".$missing." database tables missing of ".$tablesno.".",$GLOBALS['app']['constants']['warning-color']);goto datafiles;}
			if(!empty($GLOBALS['app']['tables']['static']['contents']['name']))
			{
				if(in_array($GLOBALS['app']['tables']['static']['contents']['name'],$tables))
				{
					$query = "select * from ".$GLOBALS['app']['tables']['static']['contents']['name']." order by CNID asc";
					$result = $temp -> query($query);
					while($row = $result -> fetch_array(MYSQLI_ASSOC)){$GLOBALS['app']['contents'][$row["CNID"]] = json_decode($row["CNLN"],true)[$GLOBALS["lang"]];}
				}
			}
			if(!empty($GLOBALS['app']['tables']['static']['functions']['name']))
			{
				if(!in_array($GLOBALS['app']['tables']['static']['functions']['name'],$tables))
				{
					$query = "select * from ".$GLOBALS['app']['tables']['static']['functions']['name']." order by FNNM asc";
					$result = $temp -> query($query);
					while($row = $result -> fetch_array(MYSQLI_ASSOC)){$GLOBALS['app']['functions'][$row["FNNM"]] = json_decode($row["FNLN"],true)["name"][$GLOBALS["lang"]];}
				}
			}
			if(!empty($GLOBALS['app']['tables']['static']['uis']['name']))
			{
				if(!in_array($GLOBALS['app']['tables']['static']['uis']['name'],$tables))
				{
					$query = "select * from ".$GLOBALS['app']['tables']['static']['uis']['name']." order by USID asc";
					$result = $temp -> query($query);
					while($row = $result -> fetch_array(MYSQLI_ASSOC)){$GLOBALS['app']['uis'][$row["USID"]] = json_decode($row["USLN"],true)["name"][$GLOBALS["lang"]];}
				}
			}
			if(!empty($_SESSION['stid'])){$query = "select * from ".$GLOBALS['app']['tables']['static']['staff']['name']." where STID='".$_SESSION['stid']."'";$result = $temp -> query($query);while($row = $result -> fetch_array(MYSQLI_ASSOC)){$GLOBALS['app']['profile']['first-name'] = $row["STFN"];$GLOBALS['app']['profile']['last-name'] = $row["STLN"];$GLOBALS['app']['profile']['full-name'] = self::fullname($GLOBALS['app']['profile']['first-name'],$GLOBALS['app']['profile']['last-name']);$GLOBALS['app']['profile']['functions'] = json_decode($row["STFS"],true);$GLOBALS['app']['profile']['uis'] = json_decode($row["STUS"],true);$GLOBALS['app']['profile']['settings'] = json_decode($row["STGS"],true);$query_theme = "select * from ".$GLOBALS['app']['tables']['static']['themes']['name']." where THID='".$row["STTH"]."'";$result_theme = $temp -> query($query_theme);while($row_theme = $result_theme -> fetch_array(MYSQLI_ASSOC)){$GLOBALS['app']['profile']['theme'] = $row_theme;}}}return $temp;
		}
		datafiles:
		foreach($GLOBALS['data']['contents'] as $key=>$value){$GLOBALS['app']['contents'][$key] = $value;}
		return;
	}
	static function page($connection,$pagename=null){if(!empty($pagename)){$query = "select * from ".$GLOBALS['app']['tables']['pages']['name'];$result = $connection -> query($query);while($row = $result -> fetch_array(MYSQLI_ASSOC)){return $row;}}return;}
}
trait supers
{
	static function posted(){if($_SERVER["REQUEST_METHOD"] == "POST"){return true;}return;}
	static function self(){return htmlspecialchars($_SERVER['PHP_SELF']);}
}
class validate
{
	static function secure($data=null){$data = trim($data);$data = stripslashes($data);$data = htmlspecialchars($data);return $data;}
	static function email($email=null){return filter_var($email,FILTER_VALIDATE_EMAIL);}
	static function url($url=null){return filter_var($url,FILTER_VALIDATE_URL);}
	static function datetime($string=null){return strtotime($string);}
	static function numbers($string=null){return preg_match("/^[0-9]*$/",$string);}
	static function numeric($number=null,$zero=true){if(!$zero && $number == 0){return;}if(is_numeric($number)){return true;}return false;}
	static function goid($name,$connection,$table,$column,$removed,$filter=null,$md5=true,$mandatory=true){$error = null;if($mandatory){if(empty($_POST[$name])){$error = $GLOBALS['app']['contents']['ALERT_MANDATORY'];goto error;}}$GLOBALS[$name] = self::secure($_POST[$name]);if($md5){$check = md5($_POST[$name]);}else{$check = $GLOBALS[$name];}if(!empty($filter)){$filter = " and".$filter;}$query = "select * from ".$table." where ".$column."='".$check."'".$filter;$result = $connection -> query($query);while($row = $result -> fetch_array(MYSQLI_ASSOC)){if($row[$removed] == 2){$error = $GLOBALS['app']['contents']['ALERT_REMOVED'];goto error;}return true;}$error = $GLOBALS['app']['contents']['ALERT_DOES_NOT_EXIST'];error:$GLOBALS[$name.'er'] = $error;$GLOBALS['redy'] = false;return false;}
	static function goin($username,$password,$connection,$table='staff',$coluser='STUN',$colpass='STPW',$colrflg='STRF',$filter=null,$md5=true,$mandatory=true){$error = array("username"=>null,"password"=>null);$GLOBALS[$username] = self::secure($_POST[$username]);$GLOBALS[$password] = self::secure($_POST[$password]);if($mandatory){if(empty($_POST[$username]) && empty($_POST[$password])){$error["username"] = $error["password"] = $GLOBALS['app']['contents']['ALERT_MANDATORY'];goto error;}else{if(empty($_POST[$username])){$error["username"] = $GLOBALS['app']['contents']['ALERT_MANDATORY'];goto error;}if(empty($_POST[$password])){$error["password"] = $GLOBALS['app']['contents']['ALERT_MANDATORY'];goto error;}}}if($md5){$user = md5($_POST[$username]);$pass = md5($_POST[$password]);}else{$user = $_POST[$username];$pass = $_POST[$password];}if(!empty($filter)){$filter = " and".$filter;}$query = "select * from ".$table." where ".$coluser."='".$user."'".$filter;$result = $connection -> query($query);while($row = $result -> fetch_array(MYSQLI_ASSOC)){if($row[$colrflg] == 2){$error["username"] = $GLOBALS['app']['contents']['ALERT_REMOVED'];goto error;}if($row[$colpass] != $pass){$error["password"] = $GLOBALS['app']['contents']['ALERT_PASSWORD_WRONG'];goto error;}return true;}$error["username"] = $GLOBALS['app']['contents']['ALERT_DOES_NOT_EXIST'];error:$GLOBALS[$username.'er'] = $error["username"];$GLOBALS[$password.'er'] = $error["password"];$GLOBALS['redy'] = false;return false;}
	static function gocheckbox($name,$mandatory=true){$error = null;if($mandatory){if(empty($_POST[$name])){$error = $GLOBALS['app']['contents']['ALERT_MANDATORY'];goto error;}}$GLOBALS[$name] = $_POST[$name];return true;error:$GLOBALS[$name.'er'] = $error;$GLOBALS['redy'] = false;return false;}
	static function gocolor($name,$mandatory=true){$error = null;if($mandatory){if(empty($_POST[$name])){$error = $GLOBALS['app']['contents']['ALERT_MANDATORY'];goto error;}}$GLOBALS[$name] = $_POST[$name];return true;error:$GLOBALS[$name.'er'] = $error;$GLOBALS['redy'] = false;return false;}
	static function godate($name,$min=null,$max=null,$mandatory=true){$error = null;if($mandatory){if(empty($_POST[$name])){$error = $GLOBALS['app']['contents']['ALERT_MANDATORY'];goto error;}}$GLOBALS[$name] = self::secure($_POST[$name]);$namedate = strtotime($_POST[$name]);if($namedate){if(!empty($min) && ($namedate < strtotime($min))){$error = $GLOBALS['app']['contents']['ALERT_WRONG_VALUE_MUST_BE']." ".$GLOBALS['app'][$GLOBALS['dirc']]['greater-than'].$min;goto error;}if(!empty($max) && ($namedate > strtotime($max))){$error = $GLOBALS['app']['contents']['ALERT_WRONG_VALUE_MUST_BE']." ".$GLOBALS['app'][$GLOBALS['dirc']]['less-than'].$max;goto error;}return true;}else{return true;}error:$GLOBALS[$name.'er'] = $error;$GLOBALS['redy'] = false;return false;}
	static function goemail($name,$mandatory=true){$error = null;if($mandatory){if(empty($_POST[$name])){$error = $GLOBALS['app']['contents']['ALERT_MANDATORY'];goto error;}}$GLOBALS[$name] = self::secure($_POST[$name]);if(!filter_var($GLOBALS[$name],FILTER_VALIDATE_EMAIL)){$error = $GLOBALS['app']['contents']['ALERT_MANDATORY'];goto error;}return true;error:$GLOBALS[$name.'er'] = $error;$GLOBALS['redy'] = false;return false;}
	static function gofile($name,$extension=null,$size=null,$mandatory=true){$error = null;if($mandatory){if(empty($_FILES[$name]['tmp_name'])){$error = $GLOBALS['app']['contents']['ALERT_MANDATORY'];goto error;}}if(!empty($extension)){if(!in_array(pathinfo($_FILES[$name]['name'],PATHINFO_EXTENSION),$extension)){$error = $GLOBALS['app']['contents']['ALERT_INVALID_EXTENSION_ALLOWED_ARE'].implode(',',$extension);goto error;}}if(!empty($size)){if($_FILES[$name]['size'] > $size){$error = $GLOBALS['app']['contents']['ALERT_SIZE_EXCEEDED']." ".$size;goto error;}}return true;error:$GLOBALS[$name.'er'] = $error;$GLOBALS['redy'] = false;return false;}
	static function gohidden($name){if(!empty($_POST[$name])){return self::secure($_POST[$name]);}return;}
	static function gonumber($name,$zero=true,$min=null,$max=null,$mandatory=true){$error = null;if($mandatory){if(!$zero){if(empty($_POST[$name])){$error = $GLOBALS['app']['contents']['ALERT_MANDATORY'];goto error;}}}$GLOBALS[$name] = self::secure($_POST[$name]);if(!$zero && ($_POST[$name] === 0)){$error = $GLOBALS['app']['contents']['ALERT_ZERO_IS_NOT_ALLOWED'];goto error;}if(is_numeric($_POST[$name])){if(!empty($min) && ($_POST[$name] < $min)){$error = $GLOBALS['app']['contents']['ALERT_WRONG_VALUE_MUST_BE']." ".$GLOBALS['app'][$GLOBALS['dirc']]['greater-than'].$min;goto error;}if(!empty($max) && ($_POST[$name] > $max)){$error = $GLOBALS['app']['contents']['ALERT_WRONG_VALUE_MUST_BE']." ".$GLOBALS['app'][$GLOBALS['dirc']]['less-than'].$max;goto error;}return true;}return true;error:$GLOBALS[$name.'er'] = $error;$GLOBALS['redy'] = false;return false;}
	static function gopassword($pass,$check,$min=null,$max=null,$pattern=null,$mandatory=true){$error = array("pass"=>null,"check"=>null);$GLOBALS[$pass] = self::secure($_POST[$pass]);$GLOBALS[$check] = self::secure($_POST[$check]);if($mandatory){if(empty($_POST[$pass]) && empty($_POST[$check])){$error["pass"] = $error["check"] = $GLOBALS['app']['contents']['ALERT_MANDATORY'];goto error;}else{if(empty($_POST[$pass])){$error["pass"] = $GLOBALS['app']['contents']['ALERT_MANDATORY'];goto error;}if(empty($_POST[$check])){$error["check"] = $GLOBALS['app']['contents']['ALERT_MANDATORY'];goto error;}}}if($GLOBALS[$pass] != $GLOBALS[$check]){$error["check"] = $GLOBALS['app']['contents']['ALERT_PASSWORDS_MISMATCH'];goto error;}if(!empty($min)){if(strlen($GLOBALS[$pass]) < $min){$error["pass"] = $error["check"] = $GLOBALS['app']['contents']['ALERT_CHARACTERS_LENGTH_MUST_BE']." ".$GLOBALS['app'][$GLOBALS['dirc']]['greater-than'].$min;goto error;}}if(!empty($max)){if(strlen($GLOBALS[$pass]) > $max){$error["pass"] = $error["check"] = $GLOBALS['app']['contents']['ALERT_CHARACTERS_LENGTH_MUST_BE']." ".$GLOBALS['app'][$GLOBALS['dirc']]['less-than'].$max;goto error;}}/*pattern check*/return true;error:$GLOBALS[$pass.'er'] = $error["pass"];$GLOBALS[$check.'er'] = $error["check"];$GLOBALS['redy'] = false;return false;}/* radio */
	static function goradio($name,$mandatory=true){$error = null;if($mandatory){if(empty($_POST[$name])){$error = $GLOBALS['app']['contents']['ALERT_MANDATORY'];goto error;}}$GLOBALS[$name] = $_POST[$name];return true;error:$GLOBALS[$name.'er'] = $error;$GLOBALS['redy'] = false;return false;}
	static function gotel($name,$mandatory=true){$error = null;if($mandatory){if(empty($_POST[$name])){$error = $GLOBALS['app']['contents']['ALERT_MANDATORY'];goto error;}}$GLOBALS[$name] = self::secure($_POST[$name]);if(!preg_match("/^[0-9]*$/",$GLOBALS[$name])){$error = $GLOBALS['app']['contents']['ALERT_ONLY_NUMBERS'];goto error;}return true;error:$GLOBALS[$name.'er'] = $error;$GLOBALS['redy'] = false;return false;}/* text */
	static function gotext($name,$mandatory=true){$error = null;if($mandatory){if(empty($_POST[$name])){$error = $GLOBALS['app']['contents']['ALERT_MANDATORY'];goto error;}}$GLOBALS[$name] = addslashes(self::secure($_POST[$name]));return true;error:$GLOBALS[$name.'er'] = $error;$GLOBALS['redy'] = false;return false;}/* time */
	static function gourl($name,$mandatory=true){$error = null;if($mandatory){if(empty($_POST[$name])){$error = $GLOBALS['app']['contents']['ALERT_MANDATORY'];goto error;}}$GLOBALS[$name] = self::secure($_POST[$name]);if(!filter_var($GLOBALS[$name],FILTER_VALIDATE_URL)){$error = $GLOBALS['app']['contents']['ALERT_MANDATORY'];goto error;}return true;error:$GLOBALS[$name.'er'] = $error;$GLOBALS['redy'] = false;return false;}
}
class files
{
	static function paths($dir=null,$extension=null,&$results = array()){if($dir == ""){$dir = getcwd();}$list = true;$files = scandir($dir);foreach ($files as $key => $value){$path = realpath($dir . DIRECTORY_SEPARATOR . $value);if (!is_dir($path)){if(!empty($extension)){$file_extension = pathinfo($path,PATHINFO_EXTENSION);if($file_extension != $extension){$list = false;}}if($list){$results[] = $path;}}else if($value != "." && $value != ".."){self::paths($path,$extension,$results);if(!empty($extension)){$file_extension = pathinfo($path,PATHINFO_EXTENSION);if($file_extension != $extension){$list = false;}}if($list){$results[] = $path;}}}return $results;}
	static function path($Input){return $_FILES[$Input]['name'];}
	static function temporary($Input){return $_FILES[$Input]['tmp_name'];}
	static function error($Input){return $_FILES[$Input]['error'];}
	static function type($name,$input=false)     {if($input){return $_FILES[$Input]['type'];}else{return mime_content_type($name);}}
	static function size($name,$input=false)     {if($input){return $_FILES[$Input]['size'];}else{return filesize($name);}}
	static function folder($name,$input=false)   {if($input){$path = $_FILES[$name]['name'];}else{$path = $name;}return pathinfo($path,PATHINFO_DIRNAME);}
	static function base($name,$input=false)     {if($input){$path = $_FILES[$name]['name'];}else{$path = $name;}return pathinfo($path,PATHINFO_BASENAME);}
	static function extension($name,$input=false){if($input){$path = $_FILES[$name]['name'];}else{$path = $name;}return pathinfo($path,PATHINFO_EXTENSION);}
	static function move($name,$folder,$new,$input=false){if($folder != ""){$folder=$folder."//";}if($input){return move_uploaded_file($_FILES[$name]["tmp_name"],$folder.$new);}else{return rename($name,$folder.$new);}}
	static function relevance($url=null,$caller=null){if(empty($caller)){return $url;}
	if($caller == 'child'){return "../".$url;}
	}
}
class images
{
	static function resize($source,$percent=0.5,$prefix='thumb'){$naked = files::base($source);$folder = files::folder($source);$extension = files::extension($source);if(!empty($prefix)){$prefix = $prefix."-";}if($extension == "jpg"){header('Content-Type:image/jpeg');}if($extension == "png"){header('Content-Type:image/png');}list($width,$height) = getimagesize($source);$newwidth = $width * $percent;$newheight = $height * $percent;$resizedimg = imagecreatetruecolor($newwidth,$newheight);if($extension == "jpg"){$source = imagecreatefromjpeg($source);}if($extension == "png"){$source = imagecreatefrompng($source);self::transparency($resizedimg,$source);}imagecopyresized($resizedimg,$source,0,0,0,0,$newwidth,$newheight,$width,$height);if($extension == "jpg"){return imagejpeg($resizedimg,$folder."/".$prefix.$naked);}if($extension == "png"){return imagepng($resizedimg,$folder."/".$prefix.$naked);}}
	static function transparency($new_image,$image_source){$transparencyIndex = imagecolortransparent($image_source);$transparencyColor = array('red' => 255, 'green' => 255, 'blue' => 255);if($transparencyIndex >= 0){$transparencyColor = imagecolorsforindex($image_source,$transparencyIndex);}$transparencyIndex = imagecolorallocate($new_image,$transparencyColor['red'],$transparencyColor['green'],$transparencyColor['blue']);imagefill($new_image,0,0,$transparencyIndex);imagecolortransparent($new_image,$transparencyIndex);}
}
?>
