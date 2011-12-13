#DbRoutes

Store all of the application routes in a database table.

###Configuration

Create the dbroutes database table

    CREATE TABLE IF NOT EXISTS `dbroutes` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `route` text NOT NULL,
        `translation` text NOT NULL,
        PRIMARY KEY (`id`)
    );

###Installation

    Add `http://github.com/Phil-F` to your packages config and run `php oil install DbRoutes`.


    *** Edit the packages/dbroutes/config/config.php to suit you


    *** In app/config/congig.php add dbroutes to the always load packages

    'always_load'	=> array(
		'packages'	=> array(
            'dbroutes',
		),


	*** Replace app/config/routes.php with the the packages/dbroutes/config/routes.php or alter yours
	    so it similar to the following:

	<?php
    return array_merge(array(
    '_root_' => 'welcome/index', // The default route
    '_404_' => 'welcome/404', // The main 404 route
    ), \DbRoutes::load());


###Administration

	In an admin form allow for three inputs:

	* `$url_route` Suggested field input type textarea
	* `$named_route` to allow for the support of named routes
	* `$translation` the actual real route. Suggested field input type textarea

	Basic example processing below:

	// The data below would come from form input

	$url_route = 'logout';
	$named_route = 'logout';
	$translation = 'user/user/logout';

	// Process the data and allow for named routes
	if ( ! empty($named_route))
	{
	    $route = array('name' => $named_route, $translation);
	}
	else
	{
	    $route = $translation;
	}

	$data = array(
	    'route' => $url_route,
	    'translation' => serialize($route)
	);


	// Example update

	DB::insert('dbroutes')->set($data)->execute();

	// Then re-cache the routes

	DbRoutes::refresh();