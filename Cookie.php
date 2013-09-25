<?php

class Cookie {
	

	/**
	 * Cookie name
	 * @var string
	 */
	public $name;


	/**
	 * Cookie value
	 * @var string
	 */
	public $value = "";


	/**
	 * Default expiration time as UNIX Timestamp
	 * @var int
	 */
	public $expires;


	/**
	 * Default path
	 * @var string
	 */
	public $path = "/";


	/**
	 * Default domain
	 * @var string
	 */
	public $domain = "";


	/**
	 * Default secure setting
	 * @var bool
	 */
	public $secure = false;


	/**
	 * Default http only setting
	 * @var bool
	 */
	public $http_only = false;


	/**
	 * Constructor
	 *
	 * @param string $name
	 * @param string $value
	 * @return Cookie
	 */
	public function __construct ($name, $value = null) {
		$this->name   = $name;
		$this->domain = "." . $_SERVER["SERVER_NAME"];
		
		// Default to secure cookies, if https is used
		if ($_SERVER["HTTPS"] && $_SERVER["HTTPS"] !== "off") {
			$this->secure = true;
		}
		
		if ($value !== null) {
			$this->value  = $value;
		}
	}


	/**
	 * Sets the cookie to expires in given time
	 *
	 * @todo Make use of strtotime()?
	 * @param int $time
	 * @param string $unit
	 * @return void
	 */
	public function expires_in ($time, $unit = "months") {
		if (!empty($time)) {
			switch ($unit) {
				case "months" : $time = $time * 60 * 60 * 24 * 31; break;
				case "days"   : $time = $time * 60 * 60 * 24; break;
				case "hours"  : $time = $time * 60 * 60; break;
				case "minutes": $time = $time * 60; break;
			}
		}

		$this->expires_at($time);
	}


	/**
	 * Sets the cookie to expires at a given time
	 *
	 * @param int $time
	 * @return void
	 */
	public function expires_at ($time) {
		if ($time === 0) {
			$time = null;
		}

		$this->expires = $time;
	}


	/**
	 * Sets the cookie
	 *
	 * @return bool setcookie successfull
	 */
	public function set () {
		return setcookie(
			$this->name,
			$this->value,
			$this->expires,
			$this->path,
			$this->domain,
			$this->secure,
			$this->http_only
		);
	}
	
	
	/**
	 * Gets the cookie value
	 *
	 * @return string
	 */
	public function get () {
		return $this->value === "" ? $_COOKIES[$this->name] : $this->value;
	}


	/**
	 * Deletes a cookie
	 *
	 * @return bool setcookie successfull
	 */
	public function delete () {
		$this->value	= "";
		$this->expires 	= time() - 3600;
		return $this->set();
	}
}