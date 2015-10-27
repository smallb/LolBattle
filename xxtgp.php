<?php
/**
* 
*/
class XxTgp
{
	private $cookie    = "";
	private $qquin     = "";
	private $area_id   = "";
	private $name      = "";
	private $host      = array();
	private $Available = false;

	function __construct()
	{
		parent::getSql();
		$this->cookie = "Cookie: pgv_pvi=3279384576; pgv_pvid=5401866420; pgv_info=ssid=s7344199186; chkNew=865293057%3B1095988640%3B1050289176; puin=1095988640; paid=-1; pkey=00015627B67B0070B71C1617F8C96152F7E1BE7C295A12779A632D72C5F0CD89DE31654605C726A5246EE23E254CE6652D5B062F298D2B4C3D4E49D50D970D86A5AAB673F4AD4440EC56585E65B443AA6F03AE5F9305157DD076DE7DD4B66183A655F67DD4CC823E0D6D080CC5AD3C2EE01F762B13A63242";

		//模拟来源ip
		$ip = sprintf("%d.%d.%d.%d",rand(1,254),rand(1,254),rand(1,254),rand(1,254));
        $this->host = array("X-FORWARDED-FOR:{$ip}", "CLIENT-IP:{$ip}");
	}

	//--------------------------------------------------------------------------------
	//							curl 模拟发包
	//--------------------------------------------------------------------------------


	// //根据游戏id搜索玩家信息 只能获取当前登陆账号，所以暂时屏蔽掉
	// private function searchAllPlayerByQQ($qq)
	// {
	// 	if (empty($qq)) {
	// 		return "";
	// 	}
		
	// 	$timestamp = time();
	// 	$host      = $this->host;
	// 	$url       = "http://api.pallas.tgp.qq.com/core/search_player?callback=jQuery172016812353694737858_1439874190643&key=".$qq."&puin=".$qq."&key_type=2&_=".$timestamp;
	// 	$cookie    = $this->cookie;
	// 	$ch        = curl_init($url);
	// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	// 	curl_setopt($ch, CURLOPT_HTTPHEADER,$host);
	// 	curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)");
	// 	curl_setopt($ch, CURLOPT_REFERER,"http://api.tgp.qq.com/profile/v1405/history.shtml?vuin=123456789&vaid=1");
	// 	curl_setopt($ch, CURLOPT_COOKIE,$cookie);
	// 	$str = curl_exec($ch);
	// 	curl_close($ch);	
		
	// 	$json = $this->formatTgpJson($str);
	// 	if (empty($json)) {
	// 		return "";
	// 	}

	// 	return $json;
	// }

	//根据游戏id搜索玩家信息
	private function searchPlayer($name)
	{
		if (empty($name)) {
			return "";
		}
		
		$timestamp = time();
		$host      = $this->host;
		$url       = "http://api.pallas.tgp.qq.com/core/search_player_with_ob?callback=jQuery17208404020658480955_1439544008767&key=".$name."&_=".$timestamp;
		$cookie    = $this->cookie;
		$ch        = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$host);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)");
		curl_setopt($ch, CURLOPT_REFERER,"http://api.tgp.qq.com/profile/v1405/history.shtml?vuin=123456789&vaid=1");
		curl_setopt($ch, CURLOPT_COOKIE,$cookie);
		$str = curl_exec($ch);
		curl_close($ch);	
		//echo $str;		
		$json = $this->formatTgpJson($str);
		if (empty($json)) {
			return "";
		}

		return $json;
	}

	//获取玩家信息
	private function getPlayerDataByUid($area_id, $qquin)
	{
		if (empty($qquin) || empty($area_id)) {
			return "";
		}

		$timestamp = time();
		$host      = $this->host;
		$url       = "http://api.pallas.tgp.qq.com/core/get_user_hot_info?callback=getUserDataCallBack&dtag=profile&area_id=".$area_id."&qquin=".$qquin."&t=".$timestamp;
		$cookie    = $this->cookie;
		$ch        = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$host);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)");
		curl_setopt($ch, CURLOPT_REFERER,"http://api.tgp.qq.com/profile/v1405/history.shtml?vuin=123456789&vaid=1");
		curl_setopt($ch, CURLOPT_COOKIE,$cookie);
		$str = curl_exec($ch);
		curl_close($ch);	

		$json = $this->formatTgpJson($str);
		if (empty($json)) {
			return "";
		}

		return $json;
	}

	//获取某个英雄最近几场的游戏记录，当champon_id为0的时候，是获取玩家最近几场记录
	private function getPlayerGameListForChampionId($area_id, $qquin, $champion_id, $limit = 8)
	{
		if (empty($area_id) ||
			empty($qquin)) {
			return "";
		}

		$url       = 'http://api.pallas.tgp.qq.com/core/tcall?callback=jQuery17205320705477876868_1439544549052&p=[[3,{"qquin":"'.$qquin.'","area_id":"'.$area_id.'","bt_num":"0","bt_list":[],"champion_id":'.$champion_id.',"offset":0,"limit":'.$limit.'}]]';
		$cookie    = $this->cookie;
		$host      = $this->host;
		$ch        = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$host);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)");
		curl_setopt($ch, CURLOPT_REFERER,"http://api.tgp.qq.com/profile/v1405/history.shtml?vuin=123456789&vaid=1");
		curl_setopt($ch, CURLOPT_COOKIE,$cookie);
		$str = curl_exec($ch);
		curl_close($ch);	

		$json = $this->formatTgpJson($str);
		if (empty($json)) {
			return "";
		}

		return $json;
	}

	//获取游戏详细信息
	private function getGameInfo($area_id, $game_id)
	{
		if (empty($area_id) || 
			empty($game_id)) {
			return "";
		}

		$timestamp = time();
		$host      = $this->host;
		$url       = 'http://api.pallas.tgp.qq.com/core/tcall?callback=getGameDetailCallback&dtag=profile&p=[[4,{"area_id":"'.$area_id.'","game_id":"'.$game_id.'"}]]&t='.$timestamp;
		$cookie    = $this->cookie;
		$ch        = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$host);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)");
		curl_setopt($ch, CURLOPT_REFERER,"http://api.tgp.qq.com/profile/v1405/history.shtml?vuin=123456789&vaid=1");
		curl_setopt($ch, CURLOPT_COOKIE,$cookie);
		$str = curl_exec($ch);
		curl_close($ch);	

		$json = $this->formatTgpJson($str);
		if (empty($json)) {
			return "";
		}

		return $json;
	}

	//获取玩家英雄列表
	private function getPlayerHeroByUid($area_id, $qquin)
	{
		if (empty($area_id) || 
			empty($qquin)) {
			return "";
		}

		$timestamp = time();
		$host      = $this->host;
		$url       = 'http://api.pallas.tgp.qq.com/core/tcall?callback=jQuery17205320705477876868_1439544549050&p=[[63,{"items":[{"qquin":"'.$qquin.'","area_id":"'.$area_id.'"}]}],[50,{"qquin":"'.$qquin.'","area_id":"'.$area_id.'"}],[36,{"qquin":"'.$qquin.'","area_id":"'.$area_id.'"}],[35,{"champion_id":0,"qquin":"'.$qquin.'","area_id":"'.$area_id.'"}]]&_cache_time=300';
		$cookie    = $this->cookie;
		$ch        = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$host);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)");
		curl_setopt($ch, CURLOPT_REFERER,"http://api.tgp.qq.com/profile/v1405/history.shtml?vuin=123456789&vaid=1");
		curl_setopt($ch, CURLOPT_COOKIE,$cookie);
		$str = curl_exec($ch);
		curl_close($ch);	

		$json = $this->formatTgpJson($str);
		if (empty($json)) {
			return "";
		}

		return $json;
	}

	//获取玩家某个英雄最近10盘比赛简要信息
	private function getPlayerHeroWinsByUid($area_id, $qquin, $champion_id)
	{
		if (empty($area_id) || 
			empty($qquin) || 
			empty($champion_id)) {
			return "";
		}

		$timestamp = time();
		$host      = $this->host;
		$cookie    = $this->cookie;
		$url       = 'http://api.pallas.tgp.qq.com/core/tcall?callback=jQuery17205320705477876868_1439544549056&p=[[42,{"qquin":"'.$qquin.'","area_id":"'.$area_id.'","champion_id":'.$champion_id.'}]]';
		$ch        = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$host);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)");
		curl_setopt($ch, CURLOPT_REFERER,"http://api.tgp.qq.com/profile/v1405/history.shtml?vuin=123456789&vaid=1");
		curl_setopt($ch, CURLOPT_COOKIE,$cookie);
		$str = curl_exec($ch);
		curl_close($ch);	

		$json = $this->formatTgpJson($str);
		if (empty($json)) {
			return "";
		}

		return $json;
	}

	//获取玩家最近使用的5个英雄
	private function getPlayerRecentHeroByUid($area_id, $qquin)
	{
		if (empty($area_id) || 
			empty($qquin)) {
			return "";
		}

		$timestamp = time();
		$host      = $this->host;
		$cookie    = $this->cookie;
		$url       = 'http://api.pallas.tgp.qq.com/core/tcall?callback=jQuery17205320705477876868_1439544549055&p=[[17,{"player_list":[{"qquin":"'.$qquin.'","area_id":"'.$area_id.'"}]}]]&_cache_time=300';
		$ch        = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$host);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)");
		curl_setopt($ch, CURLOPT_REFERER,"http://api.tgp.qq.com/profile/v1405/history.shtml?vuin=123456789&vaid=1");
		curl_setopt($ch, CURLOPT_COOKIE,$cookie);
		$str = curl_exec($ch);
		curl_close($ch);	

		$json = $this->formatTgpJson($str);
		if (empty($json)) {
			return "";
		}

		return $json;
	}

	//获取玩家最近使用的英雄的胜率信息
	private function getPlayerHeroWinsByJson($json)
	{
		if (empty($json)) {
			return "";
		}

		$timestamp = time();
		$host      = $this->host;
		$cookie    = $this->cookie;
		$url       = 'http://api.pallas.tgp.qq.com/core/tcall?callback=jQuery17205320705477876868_1439544549053&p=[[21,'.$json.']]';
		$ch        = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$host);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)");
		curl_setopt($ch, CURLOPT_REFERER,"http://api.tgp.qq.com/profile/v1405/history.shtml?vuin=123456789&vaid=1");
		curl_setopt($ch, CURLOPT_COOKIE,$cookie);
		$str = curl_exec($ch);
		curl_close($ch);	

		$json = $this->formatTgpJson($str);
		if (empty($json)) {
			return "";
		}

		return $json;
	}

	//获取玩家详细信息
	private function getPlayerDetailedInfoByUid($area_id, $qquin)
	{
		if (empty($area_id) || 
			empty($qquin)) {
			return "";
		}

		$timestamp = time();
		$host      = $this->host;
		$cookie    = $this->cookie;
		$url       = 'http://api.pallas.tgp.qq.com/core/tcall?callback=jQuery17205320705477876868_1439544549051&p=[[14,{"battle_type":1,"qquin":"'.$qquin.'","area_id":"'.$area_id.'"}],[14,{"battle_type":6,"qquin":"'.$qquin.'","area_id":"'.$area_id.'"}],[44,{"sid":3,"qquin":"'.$qquin.'","area_id":"'.$area_id.'"}],[44,{"sid":2,"qquin":"'.$qquin.'","area_id":"'.$area_id.'"}]]&_cache_time=300 ';
		$ch        = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$host);
		curl_setopt($ch, CURLOPT_USERAGENT,"Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)");
		curl_setopt($ch, CURLOPT_REFERER,"http://api.tgp.qq.com/profile/v1405/history.shtml?vuin=123456789&vaid=1");
		curl_setopt($ch, CURLOPT_COOKIE,$cookie);
		$str = curl_exec($ch);
		curl_close($ch);	

		$json = $this->formatTgpJson($str);
		if (empty($json)) {
			return "";
		}

		return $json;
	}

	//--------------------------------------------------------------------------------

	//--------------------------------------------------------------------------------
	//							私有函数
	//--------------------------------------------------------------------------------
	
	private function getCookie()
	{

	}

	//将tgp的json转换成标准的json
	private function formatTgpJson($tgpjson)
	{
		if (empty($tgpjson)) {
			return "";
		}

		$strStartPos = strpos($tgpjson, "({");
		if ($strStartPos === false) {
			return "";
		}

		$strEndPos = strrpos($tgpjson, ")}");
		if ($strEndPos === false) {
			return "";
		}

		$json = substr($tgpjson, $strStartPos+1, $strEndPos-$strStartPos-1);
		if (empty($json)) {
			return "";
		}

		$jsonArray = json_decode($json,true);
		if (empty($jsonArray)) {
			return "";
		}

		if (!isset($jsonArray["data"])) {
			return "msg:".$jsonArray["msg"];
		}

		$json = json_encode($jsonArray["data"]);
		if (empty($json)) {
			return "";
		}

		if (substr($json, 0, 1) == "[" && substr($json, -1, 1) == "]") {
			$json = substr($json, 1, -1);
		}
		
		return "{\"data\":[".$json."]}";
	}

	//格式化创建数据库表的sql
	private function formatJsonToCreateSql($json, $tableName)
	{
		if (empty($tableName) || empty($json)) {
			return "";
		}

		$jsonArray = json_decode($json, true);
		if (empty($jsonArray)) {
			return "";
		}

		$sql = "";
		foreach ($jsonArray as $key => $value) {
			$tableOption = "varchar(255)";
			if (is_array($value)) {
				continue;
			}elseif (is_int($value)) {
				$tableOption = "int(10)";
			}elseif (is_float($value)) {
				$tableOption = "double(16,2)";
			}
			$sql .= "`".$key."`"." ".$tableOption.",";
		}

		if (empty($sql)) {
			return "";
		}

		$sql .= "`times` timestamp) DEFAULT CHARSET=utf8 AUTO_INCREMENT= 1;";
		return "CREATE TABLE IF NOT EXISTS `$tableName` ( `id` int(10) not null auto_increment primary key, ".$sql;
	}

	private function formatJsonArrayToInsert($jsonArray, $tableName)
	{
		if (empty($tableName) || empty($jsonArray)) {
			return "";
		}

		$sql = "";
		foreach ($jsonArray as $key => $value) {
			$tableValue = 0;
			if (is_array($value)) {
				continue;
			}elseif (is_int($value)) {
				$tableValue = $value;
			}elseif (is_double($value)) {
				$tableValue = $value;
			}elseif (is_string($value)) {
				$tableValue = "\"".$value."\"";
			}
			$sql .= "`".$key."`"."=".$tableValue.",";
		}

		if (empty($sql)) {
			return "";
		}

		$sql = rtrim($sql, ",");
		return "INSERT INTO `$tableName` SET ".$sql;
	}

	private function insertIntoBattleTable($json)
	{

	}

	//通过游戏id获取玩家的uid，并且保存一份缓存。
	private function getPlayerUidByName($area_id, $name)
	{		
		if (empty($name) || empty($area_id)) {
			return "";
		}

		if ("" != $this->qquin) {
			return $this->qquin;
		}

		$playerJson = $this->searchPlayer($name);
		if (empty($playerJson)) {
			return "";
		}

		$jsonArray = json_decode($playerJson, true);
		foreach ($jsonArray["data"] as $keyJson => $valueJson) {
			foreach ($valueJson as $key => $value) {
				if ($value == $area_id) {
					return $this->qquin = $valueJson["qquin"];
				}
			}	
		}
	
		return "";
	}

	//--------------------------------------------------------------------------------
	//根据名字搜索所有服务器，获取玩家列表
	public function GetSearchPlayerList($name)
	{
		if (empty($name)) {
			return "";
		}

		$json = $this->searchPlayer($name);
		if (empty($json)) {
			return "";
		}

		$jsonArray = json_decode($json, true);
		if (empty($jsonArray)) {
			return "";
		}

		$json = "";
		foreach ($jsonArray["data"] as $keyObj => $valueObj) {
			$json .=  "{";
			$json .= "\"area_id\":".$valueObj["area_id"].",";
			$json .= "\"name\":\"".$valueObj["name"]."\"";
			$json .=  "},";
		}

		if (empty($json)) {
			return "";
		}

		$json = rtrim($json, ",");
		return "{\"data\":[".$json."]}";
	}

	//设置玩家结构
	public function SetPlayerInfo($area_id, $name)
	{
		if (empty($name) || empty($area_id)) {
			return false;
		}

		$qquin = $this->getPlayerUidByName($area_id, $name);
		if (empty($qquin)) {
			return false;
		}

		$this->qquin     = $qquin;
		$this->name      = $name;
		$this->area_id   = $area_id;
		$this->Available = true;

		return true;
	}

	//获取玩家信息
	public function GetPlayerData()
	{
		if (false == $this->Available) {
			return "";
		}
		$area_id = $this->area_id;
		$qquin   = $this->qquin;
		return $this->getPlayerDataByUid($area_id, $qquin);
	}

	//通过玩家uid获取玩家最后一盘的game id
	public function GetPlayerLastGameId()
	{
		if (false == $this->Available) {
			return "";
		}

		$area_id = $this->area_id;
		$qquin   = $this->qquin;
		
		$json = $this->GetPlayerLastGameBriefInfo();
		if (empty($json)) {
			return "";
		}

		$jsonObj = json_decode($json);
		if (!is_object($jsonObj)) {
			return "";
		}

		return '{"game_id":'.$jsonObj->data[0]->battle_list[0]->game_id.'}';
	}

	//获取limit盘玩家的简要对战信息列表
	public function GetPlayerGameList($limit)
	{
		if (false == $this->Available) {
			return "";
		}

		if (empty($limit)) {
			return "";
		}

		$area_id = $this->area_id;
		$qquin   = $this->qquin;

		return $this->getPlayerGameListForChampionId($area_id, $qquin, 0, $limit);
	}

	//获取玩家最后一盘对战的详细信息
	public function GetPlayerLastGameDetailedInfo()
	{
		if (false == $this->Available) {
			return "";
		}

		$area_id = $this->area_id;
		$qquin   = $this->qquin;

		$game_id = $this->GetPlayerLastGameId();
		if (empty($game_id)) {
			return "";
		}

		$jsonObj = json_decode($game_id);
		if (!is_object($jsonObj)) {
			return "";
		}

		$json = $this->getGameInfo($area_id, $jsonObj->game_id);
		if (empty($json)) {
			return "";
		}

		return $json; //不需要写入数据库的话，直接关闭这个注释就好

		$this->insertIntoBattleTable($json);

		return $json;
	}

	//获取玩家最后一盘对战的简要信息
	public function GetPlayerLastGameBriefInfo()
	{
		if (false == $this->Available) {
			return "";
		}

		$area_id = $this->area_id;
		$qquin   = $this->qquin;

		return $this->getPlayerGameListForChampionId($area_id, $qquin, 0, 1);
	}

	//获取玩家的英雄
	public function GetPlayerHero()
	{
		if (false == $this->Available) {
			return "";
		}

		$area_id = $this->area_id;
		$qquin   = $this->qquin;

		return $this->getPlayerHeroByUid($area_id, $qquin);
	}

	//获取玩家某个英雄最近10盘（假如有10盘）比赛的简要信息
	public function GetPlayerHeroBattleList($champion_id)
	{
		if (empty($champion_id)) {
			return "";
		}

		if (false == $this->Available) {
			return "";
		}

		$area_id = $this->area_id;
		$qquin   = $this->qquin;

		return $this->getPlayerHeroWinsByUid($area_id, $qquin, $champion_id);
	}

	//获取玩家英雄最近使用的5个英雄
	public function GetPlayerRecentHero()
	{
		if (false == $this->Available) {
			return "";
		}

		$area_id = $this->area_id;
		$qquin   = $this->qquin;

		return $this->getPlayerRecentHeroByUid($area_id, $qquin);
	}

	//获取玩家英雄使用的英雄的胜率
	public function GetPlayerHeroWins($champion_id_array = array())
	{
		if (empty($champion_id_array)) {
			return "";
		}

		if (false == $this->Available) {
			return "";
		}

		$area_id = $this->area_id;
		$qquin   = $this->qquin;	

		$json = "";
		foreach ($champion_id_array as $key => $value) {
			$json .= "{";
			$json .= "\"qquin\":"."\"".$qquin."\"".",";
			$json .= "\"area_id\":"."\"".$area_id."\"".",";
			$json .= "\"champion_id\":".$value;
			$json .= "},";
		}

		if (empty($json)) {
			return "";
		}

		$json = rtrim($json,",");
		$json = '{"items":['.$json."]}";
		return $this->getPlayerHeroWinsByJson($json);
	}

	//获取玩家的详细信息
	public function GetPlayerDetailedInfo()
	{
		if (false == $this->Available) {
			return "";
		}

		$area_id = $this->area_id;
		$qquin   = $this->qquin;

		return $this->getPlayerDetailedInfoByUid($area_id, $qquin);
	}

	// //根据游戏id搜索玩家信息 只能获取当前登陆账号，所以暂时屏蔽掉
	// public function GetAllPlayerByQQ($qq)
	// {
	// 	if (empty($qq)) {
	// 		return "";
	// 	}
	// 	return $this->searchAllPlayerByQQ($qq);
	// }
}

function Distrubution($cmd, $jsonUser)
{
	$user_area_id = $jsonUser->user_area_id;
	$user_name    = $jsonUser->user_name;
	$user_uid     = $jsonUser->user_uid;
	$parameter    = $jsonUser->parameter;

	$content  = "";
	$XxTgpObj = new XxTgp();

	//这是搜索玩家
	if (100 == $cmd) {
		$content = $XxTgpObj->GetSearchPlayerList($user_name);
		return ($content);
	}

	if (false == $XxTgpObj->SetPlayerInfo($user_area_id, $user_name)) {
		return $content;
	}

	switch ($cmd) {
		case 101:
			$content = $XxTgpObj->GetPlayerData();	
			break;
		case 102:
			$content = $XxTgpObj->GetPlayerHero();
			break;
		case 103:
			$content = $XxTgpObj->GetPlayerDetailedInfo();
			break;
		case 104:
			$content = $XxTgpObj->GetPlayerLastGameId();
			break;
		case 105:
			$content = $XxTgpObj->GetPlayerLastGameBriefInfo();
			break;
		case 106:
			$content = $XxTgpObj->GetPlayerLastGameDetailedInfo();
			break;
		case 107:
			$content = $XxTgpObj->GetPlayerRecentHero();
			break;
		case 108:
			$content = $XxTgpObj->GetPlayerGameList($parameter);
			break;
		case 109:
			$content = $XxTgpObj->GetPlayerHeroBattleList($parameter);
			break;
		case 110:
			$content = $XxTgpObj->GetPlayerHeroWins(array($parameter));
			break;
		default:
			$content = "";
			break;
	}
	return urldecode($content);
}

function xx_Distrubution($content)
{
	$json = json_decode($content);
	if (!is_object($json)) {
		return "json格式错误!";
	}

	$cmd      = $json->cmd;
	$item_num = $json->data->item_num;
	$items    = $json->data->items;

	$json     = "";
	$retNum   = 0;
	$retArray = array();
	for ($i=0; $i < $item_num; $i++) { 

		if (1 == $item_num) {
			$json .= substr(Distrubution($cmd, $items), strlen('"data":[{'), 0 - strlen(']}'));	
		}else{
			$json .= substr(Distrubution($cmd, $items[$i]), strlen('"data":[{'), 0 - strlen(']}'));
		}

		if (empty($json)) {
			continue;
		}
		$json .= ",";
		$retNum += 1;
	}

	$json = rtrim($json, ",");
	$json = '{"cmd":'.$cmd.',"data":[{"item_num":'.$retNum.',"items":['.$json.']}],"times":'.time().'}';
	return $json;
}

$content = "";
$TestMode = 3;
if (1 == $TestMode) {

	//通过post方式来获取
	$content = file_get_contents("php://input");
	if (empty($content)) {
		return "";
	}
	$content = mb_convert_encoding($content,'UTF-8','GBK');//gbk转utf8

}elseif (2 == $TestMode) {
	//通过get方式来获取

	$area 	= 0;
	$name 	= "";
	$cmd 	= "";

	if (!isset($_GET["area"]) || 
		!isset($_GET["name"]) || 
		!isset($_GET["cmd"])) {
		echo "参数有误!";
		return;
	}

	$area 	= $_GET["area"];
	$name 	= $_GET["name"];
	$cmd 	= $_GET["cmd"];

	$content = "{
					\"cmd\":$cmd,
					\"data\":
					{
						\"item_num\":1,
						\"items\":[
						{
							\"user_area_id\":$area,
							\"user_name\":\"$name\",
							\"user_uid\":\"yyyy\",
							\"parameter\":111
						}]
					},
					\"times\":11111111
				}";
}else{
	$content = '
	{
		"cmd":106,
		"data":
		{
			"item_num":1,
			"items":
			{
				"user_area_id":1,
				"user_name":"丶羡小b",
				"user_uid":"yyyy",
				"parameter":0
			}
		},
		"times":11111111
	}';
}

//in
// {
// 	"cmd":103,
// 	"data":
// 	{
// 		"item_num":2,
// 		"items":[
// 		{
// 			"user_area_id":1,
// 			"user_name":"12345",
// 			"user_uid":"yyyy",
//			"parameter":111
// 		},
// 		{
// 			"user_area_id":1,
// 			"user_name":"丶羡小b",
// 			"user_uid":"yyyy"
// 		}]
// 	},
// 	"times":11111111
// }

// out
// {
//     "cmd": 104,
//     "data": [
//         {
//             "item_num": 2,
//             "items": [
//                 {
//                     "game_id": 1513895372
//                 },
//                 {
//                     "game_id": 1493112240
//                 }
//             ],
//             "times": 1440062343
//         }
//     ]
// }

//$content = RsaDecrypt($content);
$content = xx_Distrubution($content);
//$content = RsaEncrypt($content);
$content = mb_convert_encoding($content,'GBK','UTF-8');
echo $content;
?>