<?php
	namespace Molecular\Http;
	class Response{
		private $responseContent;


		public function __construct() {
		    $this->responseContent = '';
		}

		public function getResponseContent(){
			return $this->responseContent;
		}

		public function setResponseContent($context , $subscribe = false){
			if($subscribe){
				$this->responseContent = $context;
			}else{
				$this->responseContent .= $context;
			}
		}

		public function getHeader($nameHeader = ''){
			$headers = [];
			foreach (headers_list() as $key => $value) {
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

		public function setHeader($header){
			header($header);
		}
		
	}