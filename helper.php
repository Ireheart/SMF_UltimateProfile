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

// Let's check for the UTF-8 support
// Sorry, this is only for MySQL; I don't know the other way :(
global $db_type, $db_prefix;
if ($db_type == 'mysql') {
	$request = mysql_query('
		SELECT variable, value 
		FROM ' . $db_prefix . 'settings 
		WHERE variable = "global_character_set" 
			AND value="UTF-8"');
	
	if (mysql_num_rows($request) > 0) {
		mysql_free_result($request);
		
		mysql_query('ALTER TABLE ' . $db_prefix . 'profile_comments DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci');
		mysql_query('ALTER TABLE ' . $db_prefix . 'profile_comments CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');
		
		mysql_query('ALTER TABLE ' . $db_prefix . 'profile_pictures DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci');
		mysql_query('ALTER TABLE ' . $db_prefix . 'profile_pictures CHANGE `title` `title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');
		mysql_query('ALTER TABLE ' . $db_prefix . 'profile_pictures CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');
		mysql_query('ALTER TABLE ' . $db_prefix . 'profile_pictures CHANGE `filename` `filename` TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');
		
		mysql_query('ALTER TABLE ' . $db_prefix . 'picture_comments DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci');
		mysql_query('ALTER TABLE ' . $db_prefix . 'picture_comments CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');
		
		mysql_query('ALTER TABLE ' . $db_prefix . 'profile_albums DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci');
		mysql_query('ALTER TABLE ' . $db_prefix . 'profile_albums CHANGE `title` `title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL');
	}
}



// Shoud we update older (SMF 1.1.x) tables?
$columns = $smcFunc['db_list_columns']('{db_prefix}profile_comments');
if ($columns[0] == 'ID_COMMENT') {
	// The array holding all the changes.
	$nameChanges = array(
		'profile_comments' => array(
			'ID_COMMENT' => '`ID_COMMENT` `id_comment` int(11) NOT NULL auto_increment',
			'ID_MEMBER' => '`ID_MEMBER` `id_member` mediumint(8) unsigned NOT NULL default \'0\'',
			'COMMENT_MEMBER_ID' => '`COMMENT_MEMBER_ID` `comment_member_id` mediumint(8) unsigned NOT NULL default \'0\'',
		),
		'profile_pictures' => array(
			'ID_PICTURE' => '`ID_PICTURE` `id_picture` int(11) NOT NULL auto_increment',
			'ID_MEMBER' => '`ID_MEMBER` `id_member` mediumint(8) NOT NULL default \'0\'',
			'ID_ALBUM' => '`ID_ALBUM` `id_album` int(11) NOT NULL default \'0\'',
		),
		'picture_comments' => array(
			'ID_COMMENT' => '`ID_COMMENT` `id_comment` int(11) NOT NULL auto_increment',
			'ID_MEMBER' => '`ID_MEMBER` `id_member` mediumint(8) NOT NULL default \'0\'',
			'COMMENT_PICTURE_ID' => '`COMMENT_PICTURE_ID` `comment_picture_id` mediumint(8) NOT NULL default \'0\'',
		),
		'profile_albums' => array(
			'ID_ALBUM' => '`ID_ALBUM` `id_album` int(11) NOT NULL auto_increment',
			'ID_MEMBER' => '`ID_MEMBER` `id_member` mediumint(8) NOT NULL default \'0\'',
			'PARENT_ID' => '`PARENT_ID` `parent_id` int(11) NOT NULL default \'0\'',
		),
		'buddies' => array(
			'ID_MEMBER' => '`ID_MEMBER` `id_member` mediumint(8) NOT NULL default \'0\'',
			'BUDDY_ID' => '`BUDDY_ID` `buddy_id` mediumint(8) NOT NULL default \'0\'',
		),
	);

	$changes = array();
	$changes[] = '
		<h1>Updating tables:</h1>
		<ul>';
	foreach ($nameChanges as $table_name => $table) {
		$table_name = $db_prefix . $table_name;
		$changes[] = '
			<li>Updating table: ' .$table_name . '
				<ul>';

		foreach ($table as $colname => $coldef) {
			$request = $smcFunc['db_query']('', '
				ALTER TABLE ' . $table_name . '
				CHANGE COLUMN ' . $coldef);
			$changes[] = '
					<li>' . $colname . ' updating: ' . ($request ? 'Done.' : 'Error! ' . mysql_error() . '') . '</li>';
		}
		
		$changes[] = '
				</ul>';
		$changes[] = '
			</li>';
	}
	$changes[] = '
		</ul>';
		echo '
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head><title>Ultimate Profile</title></head>
		<body>';
		foreach ($changes as $change)
			echo $change;
		echo '
		</body>
	</html>';
} else {
	return;
}

?>