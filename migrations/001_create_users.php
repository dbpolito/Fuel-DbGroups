<?php

namespace Fuel\Migrations;

class Create_users
{
	public function up()
	{
		\DBUtil::create_table('users', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'username' => array('constraint' => 50, 'type' => 'varchar'),
			'password' => array('constraint' => 255, 'type' => 'varchar'),
			'group' => array('constraint' => 11, 'type' => 'int'),
			'email' => array('constraint' => 255, 'type' => 'varchar'),
			'last_login' => array('constraint' => 11, 'type' => 'int'),
			'login_hash' => array('constraint' => 255, 'type' => 'varchar'),
			'profile_fields' => array('type' => 'text'),
			'created_at' => array('constraint' => 11, 'type' => 'int'),
			'updated_at' => array('constraint' => 11, 'type' => 'int'),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('users');
	}
}
