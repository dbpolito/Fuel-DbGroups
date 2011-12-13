#DbGroups

Store ACL permission in the Database

###Configuration

Create the dbgroups tables


    CREATE TABLE IF NOT EXISTS `users_groups` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
    );

    CREATE TABLE IF NOT EXISTS `users_groups_roles` (
        `group_id` int(11) unsigned NOT NULL,
        `role_id` int(11) unsigned NOT NULL
    );

    CREATE TABLE IF NOT EXISTS `users_rights` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `location` varchar(255) NOT NULL,
        `rights` text NOT NULL,
        PRIMARY KEY (`id`)
    );

    CREATE TABLE IF NOT EXISTS `users_roles` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        PRIMARY KEY (`id`)
    );

    CREATE TABLE IF NOT EXISTS `users_roles_rights` (
        `role_id` int(11) unsigned NOT NULL,
        `right_id` int(11) unsigned NOT NULL,
        PRIMARY KEY (`role_id`, `right_id`)
    );


###Installation

    Add `http://github.com/dbpolito` to your packages config and run `php oil cells install DbGroups`.


    *** Edit the packages/dbgroups/config/dbgroups.php as you need


    *** In app/config/congig.php add dbroutes to the always load packages

    'always_load'	=> array(
		'packages'	=> array(
            'dbgroups',
		),


	*** Replace app/config/simpleauth.php with the the packages/dbgroups/config/simpleauth.php or alter yours
	    so it similar to the following:

	'groups' => \DbGroups::get('groups'),
    'roles' => \DbGroups::get('roles'),


###Administration

	Coming Soon