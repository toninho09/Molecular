<?php
	namespace Molecular\Http;
	class Request{
		public function getRequestURI(){
			return $_SERVER['REQUEST_URI'];
		}
		public function getMethod(){
			return $_SERVER['REQUEST_METHOD'];
		}
		public function getPort(){
			return $_SERVER['SERVER_PORT'];
		}
		public function getServerName(){
			return $_SERVER['SERVER_NAME'];
		}
		public function getContentType(){
			return $_SERVER['CONTENT_TYPE'];
		}
		public function getContentLength(){
			return $_SERVER['CONTENT_LENGTH'];
		}
		public function getAuthUser(){
			return $_SERVER['AUTH_USER'];
		}
		public function getAuthPassword(){
			return $_SERVER['AUTH_PASSWORD'];
		}
		public function getRequestTime(){
			return $_SERVER['REQUEST_TIME'];
		}
		public function getAccept(){
			return $_SERVER['HTTP_ACCEPT'];
		}
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
	