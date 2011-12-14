<?php

namespace Fuel\Migrations;

class Create_users_rights
{
	public function up()
	{
		\DBUtil::create_table('users_rights', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true),
			'location' => array('constraint' => 255, 'type' => 'varchar'),
			'rights' => array('type' => 'text')
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('users_rights');
	}
}