<?php

namespace Fuel\Migrations;

class Create_users_roles
{
	public function up()
	{
		\DBUtil::create_table('users_roles', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'name' => array('constraint' => 255, 'type' => 'varchar')
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('users_roles');
	}
}