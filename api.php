<?php

$db = getDB();
$result = false;

switch($_SERVER["REQUEST_METHOD"])
{
	case "GET":
		$requestUri = $_SERVER["REQUEST_URI"];
		$key = "";
		$redirect = false;
		
		if(strpos($requestUri, "/api") === 0)
			$key = explode("api/", $requestUri);
		else
		{
			$key = explode("/", $requestUri);
			$redirect = true;
		}

		if(isset($key[1]) && !empty($key[1]))
			$result = getURL($db, $key[1]);
			
			if($redirect){
				header("location: $result");
			}
			
		break;
	case "POST":
		$url = explode("api/", $_SERVER["REQUEST_URI"]);
		if(isset($url[1]) && !empty($url[1]))
			$result = saveUrl($db, $url[1]);
		break;
	default:
		header("HTTP/1.1 405 Method Not Allowed");
}

echo $result;

$db->close();

//Funktionen-------------------------------------------
function getDB(){
	$host = "localhost";
	$username = "exampleUser";
	$passwd = "12345678";
	$dbname = "cut_url";
	return @new mysqli($host, $username, $passwd, $dbname);
}

function getURL($db, $cut){
	$sql = "SELECT url FROM url WHERE generated = ? LIMIT 1";
	if($stmt = $db->prepare($sql)){
		$stmt->bind_param("s", $cut);
		$stmt->execute();
		$stmt->bind_result($url);
		$stmt->bind_result($url);
		$stmt->fetch();
		if($url)
			return addHttp($url);
		return false;
	}
	return false;
}

function getRandomString(){
	$symbols = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
	return substr(substr($symbols, mt_rand(0, 61), 1).substr(md5(time()), 1), 0, 4);
}

function getFreeRandomString($db){
	$key = 0;
	do{
		$key = getRandomString($db);
	}while(!checkUseOfRandomString($db, $key));
	return $key;
}

function checkUseOfRandomString($db, $key){
	$sql = "SELECT generated FROM url WHERE generated = ? LIMIT 1";
	
	if($stmt = $db->prepare($sql)){
		$stmt->bind_param("s", $key);
		$stmt->execute();
		$stmt->bind_result($generated);
		$stmt->fetch();
		return empty($generated);
	}
	return false;
}

function saveUrl($db, $url){
	if($key = getKeyOfUrl($db, $url))
		return $key;

	$key = getFreeRandomString($db);
	$sql = "INSERT INTO url (url, generated) VALUES (?, ?)";
	
	if($stmt = $db->prepare($sql)){
		$stmt->bind_param("ss", $url, $key);
		$stmt->execute();
		if($stmt->affected_rows == 1)
			return $key;
	}
	return false;
}

function getKeyOfUrl($db, $url){
	$sql = "SELECT generated FROM url WHERE url = ? LIMIT 1";
	
	if($stmt = $db->prepare($sql)){
		$stmt->bind_param("s", $url);
		$stmt->execute();
		$stmt->bind_result($generated);
		$stmt->fetch();
		return $generated;
	}
	return false;
}

function addHttp($url){
	if (!preg_match("~^(?:f|ht)tps?://~i", $url))
		$url = "http://" . $url;
	return $url;
}
?>