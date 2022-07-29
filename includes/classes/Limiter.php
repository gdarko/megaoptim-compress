<?php
namespace MegaOptim\Compress;

/**
 * Class Limiter
 * Daily Ip Address Limits
 * - Utilizes transients ( no need to care about the data left, it will be removed automatically )
 *
 * @package MegaOptim\Compress
 */
class Limiter {

	const CACHE_TIME = 60 * 60 * 48;

	public $limit;

	/**
	 * Limiter constructor.
	 *
	 * @param $limit
	 */
	public function __construct($limit) {
		$this->limit = $limit;
	}

	/**
	 * Returns the request ip
	 * @return mixed
	 */
	public function get_ip() {
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		return $ip;
	}

	/**
	 * Create unique user identifier
	 * @return string
	 */
	public function get_identifier() {
		$ip    = $this->get_ip();
		$today = date( 'Y-m-d', time() );
		$key   = md5( $today . '-' . $ip );

		return $key;
	}


	/**
	 * Increments requests counter for specific user
	 * @return int
	 */
	public function get_count() {
		$key    = $this->get_identifier();
		$object = get_transient( $key );

		return isset( $object->requests ) ? (int) $object->requests : 0;
	}

	/**
	 * Increments requests counter for specific user
	 * @return int
	 */
	public function increment() {
		$key              = $this->get_identifier();
		$count            = $this->get_count();
		$object           = new \stdClass();
		$object->requests = $count + 1;
		set_transient( $key, $object, self::CACHE_TIME );

		return $object->requests;
	}

	/**
	 * Is limited?
	 * @return bool
	 */
	public function is_limit_exceeded() {
		return $this->get_count() > $this->limit;
	}

}