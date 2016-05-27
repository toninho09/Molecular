<?php
	namespace Molecular\Http;
	class Request{

        private $input;

        /**
         * Request constructor.
         */
        public function __construct()
        {
            $this->input = new Input();
        }

		/**
		 * @return Input
         */
		public function input(){
            return $this->input;
        }

		/**
		 * @return mixed
         */
		public function getRequestURI(){
			return $_SERVER['REQUEST_URI'];
		}

		/**
		 * @return mixed
         */
		public function getMethod(){
			return $_SERVER['REQUEST_METHOD'];
		}

		/**
		 * @return mixed
         */
		public function getPort(){
			return $_SERVER['SERVER_PORT'];
		}

		/**
		 * @return mixed
         */
		public function getServerName(){
			return $_SERVER['SERVER_NAME'];
		}

		/**
		 * @return mixed
         */
		public function getContentType(){
			return $_SERVER['CONTENT_TYPE'];
		}

		/**
		 * @return mixed
         */
		public function getContentLength(){
			return $_SERVER['CONTENT_LENGTH'];
		}

		/**
		 * @return mixed
         */
		public function getAuthUser(){
			return $_SERVER['AUTH_USER'];
		}

		/**
		 * @return mixed
         */
		public function getAuthPassword(){
			return $_SERVER['AUTH_PASSWORD'];
		}

		/**
		 * @return mixed
         */
		public function getRequestTime(){
			return $_SERVER['REQUEST_TIME'];
		}

		/**
		 * @return mixed
         */
		public function getAccept(){
			return $_SERVER['HTTP_ACCEPT'];
		}

		/**
		 * @param $header
		 * @return array|false|null
         */
		public function getHeaders($header){
			if(!empty($header)){
				return getallheaders();
			}else{
				if(!isset(getallheaders()[$header])){
					return null;
				}
				return getallheaders()[$header];
			}
		}
	}
	