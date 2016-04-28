<?php
namespace OrientDBYii2Connector\restfull;

class ActionHelpers
{
	const REST_LIMIT = 100;
	
	/**
     *  getters
     */
    static public function getWith()
	{
		$with = self::getParam('with');
		if(empty($with))
			return false;
		
		$arrModels = self::filterParam($with); // check data
		$with = explode( ',', $arrModels );
		
		if(empty($with))
			$with = false;
		
		return $with;
	}
	
	static public function getLimit()
	{
		$limit = (int)self::getParam('limit', self::REST_LIMIT);
		
		if($limit > self::REST_LIMIT)
			$limit = self::REST_LIMIT;
		
		return $limit;
	}
	
	static public function getSort()
	{
		$sort = self::JsToJSON(self::getParam('sort', '[]'));
		$sort = json_decode($sort, true);
		
		if(empty($sort))
			return [];
		
		if(count($sort) != 1 || empty($sort[0]['property']))
			return [];
		
		$direction = SORT_ASC;
		switch($sort[0]['direction']) {
			case 'ASC':
				$direction = SORT_ASC;
				break;
			case 'DESC':
				$direction = SORT_DESC;
				break;
		}
		
		return [
			'defaultOrder' => [
				self::filterParam($sort[0]['property']) => $direction
			]
		];
	}
	
	static public function getFilter()
	{
		$filter = self::JsToJSON(self::getParam('filter', '[]'));
		$filter = json_decode($filter, true);
		
		if(empty($filter))
			return [];
		
		// return [['like', 'id', '101']];
		if( count($filter) < 1  || !is_array($filter) )
			return [];
		
		$result = [];
		foreach($filter as $filt) {
			if( empty($filt['property']) || empty($filt['value']) )
				continue;
			
			$operator = 'like'; // default
			if(!empty($filter[0]['operator'])) { // filter operator:
				switch( $filter[0]['operator'] ) {
					case 'in': 		$operator = 'in'; 		break;
					case 'not in': 	$operator = 'not in'; 	break;
					case 'like': 	$operator = 'like'; 	break;
					case '=': 		$operator = '='; 		break;
					case '!=': 		$operator = '!='; 		break;
					case '>': 		$operator = '>'; 		break;
					case '>=': 		$operator = '>='; 		break;
					case '<': 		$operator = '<'; 		break;
					case '<=': 		$operator = '<='; 		break;
				}
			}
			
			$result[] = [
				$operator,
				self::filterParam($filt['property']), // field
				$filt['value'] // value ru en zh_cn
			];
		}
		
		return $result;
	}
	
	/**
     *  helpers:
     */
	static public function getParam($name, $defaultValue=null) {
		return isset($_GET[$name]) ? $_GET[$name] : (isset($_POST[$name]) ? $_POST[$name] : $defaultValue);
	}
	
	static public function JsToJSON($data) {
		return str_replace('\'', '"', $data);
	}
	
	static public function filterParam($param) {
		return preg_replace( '/[^\,.A-Za-z0-9_-]/', '', $param);
	}
}
