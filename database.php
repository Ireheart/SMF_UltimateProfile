<?php
// Handle running this file by using SSI.php
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF')) 
	require_once(dirname(__FILE__) . '/SSI.php');
// Hmm... no SSI.php and no SMF?
elseif (!defined('SMF'))
	die('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');


global $db_prefix, $smcFunc;
	
// Make sure that we have the package database functions.
if (!array_key_exists('db_add_column', $smcFunc))
	db_extend('Packages');
	
$columns = array(
	array(
		'name' => 'id_comment',
		'type' => 'int',
		'size' => '11',
		'auto' => true,
	),
	array(
		'name' => 'id_member',
		'type' => 'mediumint',
		'size' => '8',
		'default' => 0,
	),
	array(
		'name' => 'comment',
		'type' => 'text',
	),
	array(
		'name' => 'time',
		'type' => 'int',
		'size' => '11',
	),
	array(
		'name' => 'comment_member_id',
		'type' => 'mediumint',
		'size' => '8',
	),
);
$indexes = array(
	array(
		'type' => 'primary',
		'columns' => array('id_comment'),
	),
);
$smcFunc['db_create_table']('{db_prefix}profile_comments', $columns, $indexes, array(), 'ignore');

$columns = array(
	array(
		'name' => 'id_picture',
		'type' => 'int',
		'size' => '11',
		'auto' => true,
	),
	array(
		'name' => 'id_member',
		'type' => 'mediumint',
		'size' => '8',
		'default' => 0,
	),
	array(
		'name' => 'time',
		'type' => 'int',
		'size' => '11',
	),
	array(
		'name' => 'title',
		'type' => 'text',
	),
	array(
		'name' => 'description',
		'type' => 'text',
	),
	array(
		'name' => 'filename',
		'type' => 'tinytext',
	),
	array(
		'name' => 'id_album',
		'type' => 'int',
		'size' => '11',
	),
);
$indexes = array(
	array(
		'type' => 'primary',
		'columns' => array('id_picture'),
	),
);
$smcFunc['db_create_table']('{db_prefix}profile_pictures', $columns, $indexes, array(), 'ignore');

$columns = array(
	array(
		'name' => 'id_comment',
		'type' => 'int',
		'size' => '11',
		'auto' => true,
	),
	array(
		'name' => 'id_member',
		'type' => 'mediumint',
		'size' => '8',
		'default' => 0,
	),
	array(
		'name' => 'comment',
		'type' => 'text',
	),
	array(
		'name' => 'time',
		'type' => 'int',
		'size' => '11',
	),
	array(
		'name' => 'comment_picture_id',
		'type' => 'mediumint',
		'size' => '8',
	),
);
$indexes = array(
	array(
		'type' => 'primary',
		'columns' => array('id_comment'),
	),
);
$smcFunc['db_create_table']('{db_prefix}picture_comments', $columns, $indexes, array(), 'ignore');

$columns = array(
	array(
		'name' => 'id_album',
		'type' => 'int',
		'size' => '11',
		'auto' => true,
	),
	array(
		'name' => 'id_member',
		'type' => 'mediumint',
		'size' => '8',
		'default' => 0,
	),
	array(
		'name' => 'title',
		'type' => 'text',
	),
	array(
		'name' => 'pictures',
		'type' => 'mediumint',
		'size' => '8',
	),
	array(
		'name' => 'parent_id',
		'type' => 'int',
		'size' => '11',
	),
);
$indexes = array(
	array(
		'type' => 'primary',
		'columns' => array('id_album'),
	),
);
$smcFunc['db_create_table']('{db_prefix}profile_albums', $columns, $indexes, array(), 'ignore');

$columns = array(
	array(
		'name' => 'id_member',
		'type' => 'mediumint',
		'size' => '8',
		'default' => 0,
	),
	array(
		'name' => 'buddy_id',
		'type' => 'mediumint',
		'size' => '8',
		'default' => 0,
	),
	array(
		'name' => 'approved',
		'type' => 'smallint',
		'size' => '1',
		'default' => 0,
	),
	array(
		'name' => 'position',
		'type' => 'tinyint',
		'size' => '4',
		'default' => 0,
	),
	array(
		'name' => 'time_updated',
		'type' => 'int',
		'size' => '11',
		'default' => 0,
	),
	array(
		'name' => 'requested',
		'type' => 'mediumint',
		'size' => '8',
		'default' => 0,
	),
);
$smcFunc['db_create_table']('{db_prefix}buddies', $columns, array(), array(), 'ignore');


// Let's insert default settings.
$mod_settings = array(
	'profile_pictures_path' => $boarddir . '/profile_pictures',
	'profile_pictures_url' => $boardurl . '/profile_pictures',
	'profile_pictures_number' => '12',
	'profile_pictures_width' => '640',
	'profile_enable_all' => '0',
	'profile_enable_pictures' => '1',
	'profile_allow_customize' => '1',
	'profile_allow_mediabox' => '1',
	'profile_buddies_shown' => '6',
);
$replaceArray = array();

foreach ($mod_settings as $variable => $value)
	$replaceArray[] = array($variable, $value);


$smcFunc['db_insert']('replace',
	'{db_prefix}settings',
	array('variable' => 'string-255', 'value' => 'string-65534'),
	$replaceArray,
	array('variable')
);

?>