<?php

namespace MrClay\ElggSso;

use UserlandSession\Session;
use UserlandSession\SessionBuilder;

$autoload = __DIR__ . '/vendor/autoload.php';
if (!is_file($autoload)) {
	register_error("Composer is not set up: cd mod/mrclay_sso/ && composer install");
	return;
}
require $autoload;

/**
 * Access shared authentication data for a particular system
 *
 * @param string $system_name E.g. "elgg" or "external"
 * @return SharedData
 */
function get_shared_data($system_name) {
	static $session;
	if ($session === null) {
		$session = elgg_trigger_plugin_hook('getSession', 'mrclay_sso', null, null);
	}
	return new SharedData($session, $system_name);
}

/**
 * @return Session
 */
function make_session() {
	return SessionBuilder::instance()
		->setName('BRIDGE')
		->useSystemTmp()
		->build();
}

function init() {
	elgg_register_plugin_hook_handler('getSession', 'mrclay_sso', __NAMESPACE__ . '\\make_session');
}

elgg_register_event_handler('init', 'system', __NAMESPACE__ . '\\init');
