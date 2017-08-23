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
         * @param null $header
         * @return array|mixed|null
         * @internal param string $nameHeader
         */
        public function getHeaders($header = null){
            if(empty($header)){
                return $this->getallheaders();
            }else{
                if(!isset($this->getallheaders()[$header])){
                    return null;
                }
                return $this->getallheaders()[$header];
            }
        }

		/**
		 * @param $header
         */
		public function setHeader($header){
			header($header);
		}

        /**
         * @return array|false
         */
        private function getallheaders(){
            if (!function_exists('getallheaders'))
            {
                function getallheaders()
                {
                    $headers = array ();
                    foreach ($_SERVER as $name => $value)
                    {
                        if (substr($name, 0, 5) == 'HTTP_')
                        {
                            $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                        }
                    }
                    return $headers;
                }
            }
            return getallheaders();
        }
	}