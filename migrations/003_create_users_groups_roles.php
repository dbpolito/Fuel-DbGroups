<?php

namespace Fuel\Migrations;

class Create_users_groups_roles
{
	public function up()
	{
		\DBUtil::create_table('users_groups_roles', array(
			'group_id' => array('constraint' => 11, 'type' => 'int'),
			'role_id' => array('constraint' => 11, 'type' => 'int')
		), array('group_id', 'role_id'));
	}

	public function down()
	{
		\DBUtil::drop_table('users_groups_roles');
	}
}