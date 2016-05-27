<?php
	namespace Molecular\Http;
	class Response{
		private $responseContent;


		public function __construct() {
		    $this->responseContent = '';
		}

		/**
		 * @return string
         */
		public function getResponseContent(){
			return $this->responseContent;
		}

		/**
		 * @param $context
		 * @param bool $subscribe
         */
		public function setResponseContent($context , $subscribe = false){
			if($subscribe){
				$this->responseContent = $context;
			}else{
				$this->responseContent .= $context;
			}
		}

		/**
		 * @param string $nameHeader
		 * @return array|mixed|null
         */
		public function getHeader($nameHeader = ''){
			$headers = [];
			foreach (headers_list() as $value) {
				$temp = '';
				preg_match('/^(\X.*):(\X.*)$/', $value ,$temp);
				$headers[$temp[1]] = $temp[2];
			}
			if(!empty($nameHeader)){
				if(!isset($headers[$nameHeader]))
					return null;
				return $headers[$nameHeader];
			}
			return $headers;
		}

		/**
		 * @param $header
         */
		public function setHeader($header){
			header($header);
		}
		
	}