<?php

class Connection{
	public function connect(){
		$link = new PDO("mysql:host=localhost;dbname=evacfinder", "root", "");
		$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$link -> exec("set names utf8");
		return $link;
	}
}