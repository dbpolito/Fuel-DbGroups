<?php

namespace Fuel\Migrations;

class Create_users_roles_rights
{
	public function up()
	{
		\DBUtil::create_table('users_roles_rights', array(
			'role_id' => array('constraint' => 11, 'type' => 'int'),
			'right_id' => array('constraint' => 11, 'type' => 'int')
		), array('role_id', 'right_id'));
	}

	public function down()
	{
		\DBUtil::drop_table('users_roles_rights');
	}
}