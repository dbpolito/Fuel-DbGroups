<?php
return array(
	'db' => array(
		'groups' => array(
			'table' => 'users_groups',
			'column' => array(
				'name' => 'name',
			)
		),
		'groups_roles' => array(
			'table' => 'users_groups_roles',
			'column' => array(
				'group_id' => 'group_id',
				'role_id' => 'role_id'
			)
		),
		'rights' => array(
			'table' => 'users_rights',
			'column' => array(
				'location' => 'location',
				'rights' => 'rights',
			)
		),
		'roles' => array(
			'table' => 'users_roles',
			'column' => array(
				'name' => 'name',
			)
		),
		'roles_rights' => array(
			'table' => 'users_roles_rights',
			'column' => array(
				'role_id' => 'role_id',
				'right_id' => 'right_id'
			)
		),
	),
	'cache' => array(
		'cacheid' => 'users_groups',
	),
);