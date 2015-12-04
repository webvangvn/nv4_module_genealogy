<?php

/**
 * @Project NUKEVIET 4.x
 * @Author webvang (hoang.nguyen@webvang.vn)
 * @Copyright (C) 2015 Webvang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 11/10/2015 00:00
 */


if( ! function_exists('nv_genealogy_array_fam_admin') )
{
	/**
	 * nv_genealogy_array_cat_admin()
	 *
	 * @return
	 */
	function nv_genealogy_array_fam_admin( $module_data )
	{
		global $db;

		$array_fam_admin = array();
		$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_admins ORDER BY userid ASC';
		$result = $db->query( $sql );
if( ! defined( 'NV_ADMIN' ) ) die( 'Stop!!!' );

		while( $row = $result->fetch() )
		{
			$array_fam_admin[$row['userid']][$row['fid']] = $row;
		}

		return $array_fam_admin;
	}
}

$is_refresh = false;
$array_fam_admin = nv_genealogy_array_fam_admin( $module_data );

if( ! empty( $module_info['admins'] ) )
{
	$module_admin = explode( ',', $module_info['admins'] );
	foreach( $module_admin as $userid_i )
	{
		if( ! isset( $array_fam_admin[$userid_i] ) )
		{
			$db->query( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_admins (userid, fid, admin, add_content, pub_content, edit_content, del_content) VALUES (' . $userid_i . ', 0, 1, 1, 1, 1, 1)' );
			$is_refresh = true;
		}
	}
}
if( $is_refresh )
{
	$array_fam_admin = nv_genealogy_array_fam_admin( $module_data );
}

$admin_id = $admin_info['admin_id'];
$NV_IS_ADMIN_MODULE = false;
$NV_IS_ADMIN_FULL_MODULE = false;
if( defined( 'NV_IS_SPADMIN' ) )
{
	$NV_IS_ADMIN_MODULE = true;
	$NV_IS_ADMIN_FULL_MODULE = true;
}
else
{
	if( isset( $array_fam_admin[$admin_id][0] ) )
	{
		$NV_IS_ADMIN_MODULE = true;
		if( intval( $array_fam_admin[$admin_id][0]['admin'] ) == 2 )
		{
			$NV_IS_ADMIN_FULL_MODULE = true;
		}
	}
}

$allow_func = array( 'main', 'view', 'stop', 'publtime', 'waiting', 'declined', 're-published', 'genealogy', 'rpc', 'del_genealogy', 'alias', 'tagsajax', 'province' , 'ward' );

if( ! isset( $site_mods['cms'] ) )
{
	$submenu['genealogy'] = $lang_module['genealogy_add'];
}

if( $NV_IS_ADMIN_MODULE )
{
	$submenu['family'] = $lang_module['family'];
	$submenu['tags'] = $lang_module['tags'];
	$submenu['admins'] = $lang_module['admin'];
	$submenu['setting'] = $lang_module['setting'];

	$allow_func[] = 'family';
	$allow_func[] = 'change_fam';
	$allow_func[] = 'list_fam';
	$allow_func[] = 'del_fam';

	$allow_func[] = 'admins';


	$allow_func[] = 'tags';
	$allow_func[] = 'setting';
	$allow_func[] = 'move';
}

