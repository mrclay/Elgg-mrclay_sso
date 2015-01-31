<?php

namespace MrClay\ElggSso;

use UserlandSession\Session;

/**
 * Provides access to authentication data from a system. E.g. if doing SSO from Elgg to another system,
 * you'll create a SharedData object with system name "elgg", populate it with user info, and the other system
 * will read it. If SSO must go both ways, you'll be using two SharedData objects to send data back and forth.
 */
class SharedData {

	/**
	 * @var Session
	 */
	protected $session;

	/**
	 * @var string
	 */
	protected $prefix;

	/**
	 * Constructor
	 *
	 * @param Session $session
	 * @param string $system_name E.g. "elgg"
	 */
	public function __construct(Session $session, $system_name) {
		$this->session = $session;
		$this->prefix = "{$system_name}:";
	}

	/**
	 * Is the data fresh?
	 *
	 * @param int $max_age
	 * @return bool
	 * @throws \UserlandSession\Exception
	 */
	function isFresh($max_age = 30) {
		$this->session->start();
		$time = $this->session->get($this->prefix . 'time', 0);
		return (time() - $time) < $max_age;
	}

	/**
	 * Get the username the user is logged into in ths system
	 *
	 * @return string|null
	 * @throws \UserlandSession\Exception
	 */
	function getUsername() {
		$this->session->start();
		return $this->session->get($this->prefix . 'username');
	}

	/**
	 * Set the username the user is logged into in ths system
	 *
	 * @param string|null $username Null to remove the username
	 * @throws \UserlandSession\Exception
	 */
	function setUsername($username = null) {
		$this->session->start();
		if ($username === null) {
			unset($this->session->data[$this->prefix . 'username']);
		} else {
			$this->session->set($this->prefix . 'username', $username);
		}
		$this->markFresh();
	}

	/**
	 * Get any miscellaneous data in the session
	 *
	 * @return mixed
	 * @throws \UserlandSession\Exception
	 */
	function getData() {
		$this->session->start();
		return $this->session->get($this->prefix . 'data');
	}

	/**
	 * Set some miscellaneous data in the session
	 *
	 * @param mixed $data Null to remove the data
	 * @throws \UserlandSession\Exception
	 */
	function setData($data = null) {
		$this->session->start();
		if ($data === null) {
			unset($this->session->data[$this->prefix . 'data']);
		} else {
			$this->session->set($this->prefix . 'data', $data);
		}
		$this->markFresh();
	}

	protected function markFresh($time = null) {
		if ($time === null) {
			$time = time();
		}
		$this->session->set($this->prefix . 'time', $time);
	}
}
