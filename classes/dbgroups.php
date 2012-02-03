<?php

namespace DbGroups;
/**
 * Gets all ACL permission in the Database
 */
class DbGroups
{

	public static $t_groups = array(
		'table' => 'users_groups',
		'column' => array(
			'name' => 'name',
		)
	);

	public static $t_groups_roles = array(
		'table' => 'users_groups_roles',
		'column' => array(
			'group_id' => 'group_id',
			'role_id' => 'role_id'
		)
	);

	public static $t_rights = array(
		'table' => 'users_rights',
		'column' => array(
			'location' => 'location',
			'rights' => 'rights',
		)
	);

	public static $t_roles = array(
		'table' => 'users_roles',
		'column' => array(
			'name' => 'name',
		)
	);

	public static $t_roles_rights = array(
		'table' => 'users_roles_rights',
		'column' => array(
			'role_id' => 'role_id',
			'permission_id' => 'permission_id'
		)
	);
	
	protected static $cache_id = 'users_groups';

	protected static $groups = array();
	protected static $roles = array();

	/**
	 * @static
	 * @return void
	 */
	public static function _init()
	{
		\Config::load('dbgroups', 'dbgroups');
		
		static::$cache_id = \Config::get('dbgroups.cache.cacheid', 'users_groups');
	}

	/**
	 * Loads in the groups from the database or cache, and caches them if
	 * it is not already cached.
	 *
	 * @return  array   routes array
	 */
	protected static function load()
	{
		try
		{
			$cache = \Cache::get(static::$cache_id);

			static::$groups = $cache['groups'];
			static::$roles = $cache['roles'];
		}
		catch (\CacheNotFoundException $e)
		{
			static::$groups = array();
			static::$roles = array();

			$groups = \Config::get('dbgroups.db.groups', static::$t_groups);
			$groups_roles = \Config::get('dbgroups.db.groups_roles', static::$t_groups_roles);
			$rights = \Config::get('dbgroups.db.rights', static::$t_rights);
			$roles = \Config::get('dbgroups.db.roles', static::$t_roles);
			$roles_rights = \Config::get('dbgroups.db.roles_rights', static::$t_roles_rights);

			$db_groups = \DB::select('*')->from($groups['table'])->execute()->as_array();

			if ($db_groups) {
				foreach ($db_groups as $group)
				{
					static::$groups[$group['id']] = array('name' => $group[$groups['column']['name']], 'roles' => array());

					$db_roles = \DB::select($roles['table'].'.*')
							->from($roles['table'])
							->join($groups_roles['table'])
								->on($groups_roles['table'].'.'.$groups_roles['column']['role_id'], '=', $roles['table'].'.id')
							->where($groups_roles['table'].'.'.$groups_roles['column']['group_id'], $group['id'])
							->execute()
							->as_array();

					if ($db_roles) {
						foreach ($db_roles as $role)
						{
							static::$groups[$group['id']]['roles'][] = $role[$roles['column']['name']];
							static::$roles[$role[$roles['column']['name']]] = array();

							$db_rights = \DB::select($rights['table'].'.*')
									->from($rights['table'])
									->join($roles_rights['table'])
										->on($roles_rights['table'].'.'.$roles_rights['column']['right_id'], '=', $rights['table'].'.id')
									->where($roles_rights['table'].'.'.$roles_rights['column']['role_id'], $role['id'])
									->execute()
									->as_array();
							
							if ($db_rights) {
								foreach ($db_rights as $right)
								{
									static::$roles[$roles['column']['name']][] = array($right[$rights['column']['location']] => explode(',', $right[$rights['column']['rights']]));
								}
							}
						}
					}
				}
			}

			$cache = array('groups' => static::$groups, 'roles' => static::$roles);

			\Cache::set(static::$cache_id, $cache);
		}

		return true;
	}

	/**
	 * Get the array.
	 *
	 * @return  array
	 */
	public static function get($var)
	{
		if ( ! static::${$var})
		{
			static::load();
		}

		return static::${$var};
	}

	/**
	 * Refreshes the groups cache.
	 *
	 * @return  array   new cached routes array
	 */
	public static function refresh()
	{
		\Cache::delete(static::$cache_id);
		return DbRoutes::load();
	}
}