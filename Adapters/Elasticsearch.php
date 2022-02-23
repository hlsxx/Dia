<?php

namespace Adapter {
  
	class Elasticsearch {

		public static array $config = [];
		public static Object $client;

		public function __construct() {
			global $config;
			
			self::$config = $config;
		}

		public static function init() {
			if (self::$config['web']['elasticsearch']) {
				self::$client = \Elasticsearch\ClientBuilder::create()
					->setHosts(['localhost:9200'])
					->build()
				;
			}
		}
	}

}