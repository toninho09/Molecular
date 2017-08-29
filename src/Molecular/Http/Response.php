<?php
	namespace Molecular\Http;
	class Response{
		private $responseContent;
		private $header;


		public function __construct() {
		    $this->responseContent = '';
		    $this->header = new Headers();
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
        public function getHeaders($header = null,$default = null){
            $this->header->getHeader($header,$default);
        }

        /**
         * @return array|false
         */
        private function getallheaders(){
            $this->header->getAllHeader();
        }

		/**
		 * @param $header
         */
		public function setHeader($header){
		    $this->header->setHeader($header);
		}
	}