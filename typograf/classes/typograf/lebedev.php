<?
/*
	artlebedevtypograf.php
	PHP-implementation of ArtLebedevStudio.RemoteTypograf class (web-service client)
	
	Copyright (c) Art. Lebedev Studio | http://www.artlebedev.ru/

	Typograf homepage: http://typograf.artlebedev.ru/
	Web-service address: http://typograf.artlebedev.ru/webservices/typograf.asmx
	WSDL-description: http://typograf.artlebedev.ru/webservices/typograf.asmx?WSDL
	
	Default charset: UTF-8

	Author: Maxim S (Big_Shark@mail.ru)

 */

class Typograf_Lebedev
{
	static public $HTMLEntities = 1;
	static public $xmlEntities = 2;
	static public $mixedEntities = 3;
	static public $noEntities = 4;

	protected $entity_type = 4;
	protected $use_br = 1;
	protected $use_p = 1;
	protected $max_nobr = 3;
	protected $encoding = 'UTF-8';
	protected $quot_a = 'laquo raquo';
	protected $quot_b = 'bdquo ldquo';

	function __construct ($encoding)
	{
		if ($encoding) $this->encoding = $encoding;
	}

	function entity_type($entities)
	{
		$this->entity_type = $entities;
	}

	function br ($value)
	{
		$this->use_br = $value ? 1 : 0;
	}
	
	function p ($value)
	{
		$this->use_p = $value ? 1 : 0;
	}
	
	function nobr ($value)
	{
		$this->max_nobr = $value ? $value : 0;
	}

	function quot_a ($value)
	{
		$this->quot_a = $value;
	}
	
	function quot_b ($value)
	{
		$this->quot_b = $value;
	}

	function process_text ($text)
	{
		$soap = "http://typograf.artlebedev.ru/webservices/typograf.asmx?WSDL";
		$client = new SoapClient($soap);
		$typograf_response = $client->ProcessText(array(
			"text"=>$text,
			"entityType"=>$this->entity_type,
			"useBr"=>$this->use_br,
			"useP"=>$this->use_p,
			"maxNobr"=>$this->max_nobr,
			"quotA"=>$this->quot_a,
			"quotB"=>$this->quot_b,
		));

		$typograf_response = $typograf_response->ProcessTextResult;

		return  $typograf_response;
	}
}

?>
