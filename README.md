**Note!** This directory must be named `mrclay_sso` in your mod directory.

## Simple Single Sign On API

This is an API for adding single sign on between PHP apps on the same host (or at least the same origin
with a shared data backend). All it does is set up a framework for passing authentication data between
two (or more!) systems.

### Getting Started

It's up to you to figure out how your authentication scheme must work and what the rules are. E.g.

* What parts of the sites can be accessed without logging in?
* Where is the single login page, if there is only one?
* What happens if a user's session expires in any of the sites?
* What happens if site A reads auth data from site B, but it's too old?

On each non-Elgg site you want to use
this, you must create a [`UserlandSession\Session`](https://github.com/mrclay/UserlandSession#userlandsession) object
with the name `"BRIDGE"` and the same "save handler" (storage backend) as the one used in this plugin. You can then
make `MrClay\ElggSso\SharedData` objects to access/write data for "elgg" and your other system(s).

If you want to customize the Session created by this plugin, you can replace the factory:

```php
// in your init()
elgg_unregister_plugin_hook_handler('getSession', 'mrclay_sso', 'MrClay\\ElggSso\\make_session');
elgg_register_plugin_hook_handler('getSession', 'mrclay_sso', 'myPlugin_make_session');

function myPlugin_make_session() {
	return SessionBuilder::instance()
    		->setName('MYSESS')
    		->setPdo($pdo_object)
    		->build();
}
```

### Example Code

`examples.php` might give you ideas of what you must do both within Elgg and in the other system(s).
