<?php

namespace DbGroups;
/**
 * Handles all the loading, caching and re-caching of routes from a
 * database table.
 */
class DbGroups
{

	public static $t_groups = null;
	public static $t_groups_roles = null;
	public static $t_rights = null;
	public static $t_roles = null;
	public static $t_roles_rights = null;
	
	protected static $cache_id = null;

	protected static $groups = array();
	protected static $roles = array();

	/**
	 * @static
	 * @return void
	 */
	public static function _init()
	{
		\Config::load('dbgroups', true);

		static::$t_groups = \Config::get('dbgroups.db.groups', 'users_groups');
		static::$t_groups_roles = \Config::get('dbgroups.db.groups_roles', 'users_groups_roles');
		static::$t_rights = \Config::get('dbgroups.db.rights', 'user_rights');
		static::$t_roles = \Config::get('dbgroups.db.roles', 'user_roles');
		static::$t_roles_rights = \Config::get('dbgroups.db.roles_rights', 'user_roles_rights');
		
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

			// Note: The real_route is serialized to support named routes
			$db_groups = \DB::select('*')->from(static::$t_groups)->execute()->as_array();
			if ($db_groups) {
				foreach ($db_groups as $group)
				{
					static::$groups[$group['id']] = array('name' => $group['name'], 'roles' => array());

					$db_roles = \DB::select(static::$t_roles.'.*')
							->from(static::$t_roles)
							->join(static::$t_groups_roles)
								->on(static::$t_groups_roles.'.role_id', '=', static::$t_roles.'.id')
								//->on(static::$groups_roles.'.group_id', '=', $group['id'])
							->where(static::$t_groups_roles.'.group_id', $group['id'])
							->execute()
							->as_array();

					if ($db_roles) {
						foreach ($db_roles as $role)
						{
							static::$groups[$group['id']]['roles'][] = $role['name'];
							static::$roles[$role['name']] = array();

							$db_rights = \DB::select(static::$t_rights.'.*')
									->from(static::$t_rights)
									->join(static::$t_roles_rights)
										->on(static::$t_roles_rights.'.right_id', '=', static::$t_rights.'.id')
									->where(static::$t_roles_rights.'.role_id', $role['id'])
									->execute()
									->as_array();
							
							if ($db_rights) {
								foreach ($db_rights as $rights)
								{
									static::$roles[$role['name']][] = array($rights['location'] => explode(',', $rights['rights']));
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