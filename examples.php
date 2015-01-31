<?php

/**
 * E.g. log in a user that has recently logged into a site "external".
 */
function example_login_from_external() {
	if (elgg_is_logged_in()) {
		return false;
	}
	$data = \MrClay\ElggSso\get_shared_data('external');
	if (!$data->isFresh(3600)) {
		// maybe register an error?
		return false;
	}
	$username = $data->getUsername();
	if (!$username) {
		// maybe register an error?
		return false;
	}

	// in this scenario, usernames must match between the systems
	$user = get_user_by_username($username);
	if (!$user) {
		// maybe register an error?
		return false;
	}

	login($user);
	return true;
}

/**
 * E.g. Use this as the (login, user) event handler to share some Elgg data with another site
 */
function example_handle_login($event, $type, \ElggUser $user) {
	$data = \MrClay\ElggSso\get_shared_data('elgg');
	$data->setUsername($user->username);
	$data->setData(array(
		'guid' => $user->guid,
		'name' => $user->name,
		'email' => $user->email,
	));
}

/**
 * E.g. Use this as the (logout:after, user) event handler to erase the user info
 */
function example_handle_logout($event, $type, \ElggUser $user) {
	$data = \MrClay\ElggSso\get_shared_data('elgg');
	$data->setUsername();
	$data->setData();
}
