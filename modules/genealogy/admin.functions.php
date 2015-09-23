<?php

/**
 * @Project NUKEVIET 4.x
 * @Author webvang (hoang.nguyen@webvang.vn)
 * @Copyright (C) 2015 Webvang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 11/10/2015 00:00
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) or ! defined( 'NV_IS_MODADMIN' ) ) die( 'Stop!!!' );

$tablelocation = NV_PREFIXLANG . '_location';
$result = $db->query( 'SHOW TABLE STATUS LIKE ' . $db->quote( $tablelocation . '_%' ) );
$checklocation=0;
while( $item = $result->fetch( ) )
{
	$checklocation++;
	//City


	
}

if ($checklocation > 0) {
	define( 'NV_MODULE_LOCATION', true );
	$sql = 'SELECT city_id, title, type FROM ' . $tablelocation. '_city WHERE status=1 ORDER BY weight ASC';
	$global_array_location_city = nv_db_cache( $sql, 'city_id', 'location' );
	
}


if( $NV_IS_ADMIN_MODULE )
{
	define( 'NV_IS_ADMIN_MODULE', true );
}

if( $NV_IS_ADMIN_FULL_MODULE )
{
	define( 'NV_IS_ADMIN_FULL_MODULE', true );
}

$array_viewfam_full = array(
	'view_location' => $lang_module['view_location'],
	'viewfam_page_new' => $lang_module['viewfam_page_new'],
	'viewfam_page_old' => $lang_module['viewfam_page_old'],
	'viewfam_list_new' => $lang_module['viewfam_list_new'],
	'viewfam_list_old' => $lang_module['viewfam_list_old'],
	'viewfam_none' => $lang_module['viewfam_none']
);
$array_viewfam_nosub = array(
	'viewfam_page_new' => $lang_module['viewfam_page_new'],
	'viewfam_page_old' => $lang_module['viewfam_page_old'],
	'viewfam_list_new' => $lang_module['viewfam_list_new'],
	'viewfam_list_old' => $lang_module['viewfam_list_old'],
);

$array_allowed_comm = array(
	$lang_global['no'],
	$lang_global['level6'],
	$lang_global['level4']
);

define( 'NV_IS_FILE_ADMIN', true );
require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

global $global_array_fam;
$global_array_fam = array();
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_family ORDER BY sort ASC';
$result = $db->query( $sql );
while( $row = $result->fetch() )
{
	$global_array_fam[$row['fid']] = $row;
}

/**
 * nv_fix_fam_order()
 *
 * @param integer $parentid
 * @param integer $order
 * @param integer $lev
 * @return
 */
function nv_fix_fam_order( $parentid = 0, $order = 0, $lev = 0 )
{
	global $db, $module_data;

	$sql = 'SELECT fid, parentid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_family WHERE parentid=' . $parentid . ' ORDER BY weight ASC';
	$result = $db->query( $sql );
	$array_fam_order = array();
	while( $row = $result->fetch() )
	{
		$array_fam_order[] = $row['fid'];
	}
	$result->closeCursor();
	$weight = 0;
	if( $parentid > 0 )
	{
		++$lev;
	}
	else
	{
		$lev = 0;
	}
	foreach( $array_fam_order as $fid_i )
	{
		++$order;
		++$weight;
		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_family SET weight=' . $weight . ', sort=' . $order . ', lev=' . $lev . ' WHERE fid=' . intval( $fid_i );
		$db->query( $sql );
		$order = nv_fix_fam_order( $fid_i, $order, $lev );
	}
	$numsubfam = $weight;
	if( $parentid > 0 )
	{
		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_family SET numsubfam=' . $numsubfam;
		if( $numsubfam == 0 )
		{
			$sql .= ",subfid='', viewfam='viewfam_page_new'";
		}
		else
		{
			$sql .= ",subfid='" . implode( ',', $array_fam_order ) . "'";
		}
		$sql .= ' WHERE fid=' . intval( $parentid );
		$db->query( $sql );
	}
	return $order;
}


/**
 * nv_show_fam_list()
 *
 * @param integer $parentid
 * @return
 */
function nv_show_fam_list( $parentid = 0 )
{
	global $db, $lang_module, $lang_global, $module_name, $module_data, $array_viewfam_full, $array_viewfam_nosub, $array_fam_admin, $global_array_fam, $admin_id, $global_config, $module_file;

	$xtpl = new XTemplate( 'fam_list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );

	// Cac chu de co quyen han
	$array_fam_check_content = array();
	foreach( $global_array_fam as $fid_i => $array_value )
	{
		if( defined( 'NV_IS_ADMIN_MODULE' ) )
		{
			$array_fam_check_content[] = $fid_i;
		}
		elseif( isset( $array_fam_admin[$admin_id][$fid_i] ) )
		{
			if( $array_fam_admin[$admin_id][$fid_i]['admin'] == 1 )
			{
				$array_fam_check_content[] = $fid_i;
			}
			elseif( $array_fam_admin[$admin_id][$fid_i]['add_content'] == 1 )
			{
				$array_fam_check_content[] = $fid_i;
			}
			elseif( $array_fam_admin[$admin_id][$fid_i]['pub_content'] == 1 )
			{
				$array_fam_check_content[] = $fid_i;
			}
			elseif( $array_fam_admin[$admin_id][$fid_i]['edit_content'] == 1 )
			{
				$array_fam_check_content[] = $fid_i;
			}
		}
	}

	// Cac chu de co quyen han
	if( $parentid > 0 )
	{
		$parentid_i = $parentid;
		$array_fam_title = array();
		while( $parentid_i > 0 )
		{
			$array_fam_title[] = "<a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=family&amp;parentid=" . $parentid_i . "\"><strong>" . $global_array_fam[$parentid_i]['title'] . "</strong></a>";
			$parentid_i = $global_array_fam[$parentid_i]['parentid'];
		}
		sort( $array_fam_title, SORT_NUMERIC );

		$xtpl->assign( 'FAM_TITLE', implode( ' &raquo; ', $array_fam_title ) );
		$xtpl->parse( 'main.fam_title' );
	}

	$sql = 'SELECT fid, parentid, title, weight, viewfam, numsubfam, inhome, numlinks, newday FROM ' . NV_PREFIXLANG . '_' . $module_data . '_family WHERE parentid = ' . $parentid . ' ORDER BY weight ASC';
	$rowall = $db->query( $sql )->fetchAll( 3 );
	$num = sizeof( $rowall );
	$a = 1;
	$array_inhome = array(
		$lang_global['no'],
		$lang_global['yes']
	);

	foreach ($rowall as $row)
	{
		list( $fid, $parentid, $title, $weight, $viewfam, $numsubfam, $inhome, $numlinks, $newday ) = $row;
		if( defined( 'NV_IS_ADMIN_MODULE' ) )
		{
			$check_show = 1;
		}
		else
		{
			$array_fam = GetfidInParent( $fid );
			$check_show = array_intersect( $array_fam, $array_fam_check_content );
		}

		if( ! empty( $check_show ) )
		{
			$array_viewfam = ($numsubfam > 0) ? $array_viewfam_full : $array_viewfam_nosub;
			if( ! array_key_exists( $viewfam, $array_viewfam ) )
			{
				$viewfam = 'viewfam_page_new';
				$stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_family SET viewfam= :viewfam WHERE fid=' . intval( $fid ) );
				$stmt->bindParam( ':viewfam', $viewfam, PDO::PARAM_STR );
				$stmt->execute();
			}

			$admin_funcs = array();
			$weight_disabled = $func_fam_disabled = true;
			if( defined( 'NV_IS_ADMIN_MODULE' ) or (isset( $array_fam_admin[$admin_id][$fid] ) and $array_fam_admin[$admin_id][$fid]['add_content'] == 1) )
			{
				$func_fam_disabled = false;
				$admin_funcs[] = "<em class=\"fa fa-plus fa-lg\">&nbsp;</em> <a href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=genealogy&amp;fid=" . $fid . "&amp;parentid=" . $parentid . "\">" . $lang_module['genealogy_add'] . "</a>\n";
			}
			if( defined( 'NV_IS_ADMIN_MODULE' ) or ($parentid > 0 and isset( $array_fam_admin[$admin_id][$parentid] ) and $array_fam_admin[$admin_id][$parentid]['admin'] == 1) )
			{
				$func_fam_disabled = false;
				$admin_funcs[] = "<em class=\"fa fa-edit fa-lg\">&nbsp;</em> <a class=\"\" href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=family&amp;fid=" . $fid . "&amp;parentid=" . $parentid . "#edit\">" . $lang_global['edit'] . "</a>\n";
			}
			if( defined( 'NV_IS_ADMIN_MODULE' ) or ($parentid > 0 and isset( $array_fam_admin[$admin_id][$parentid] ) and $array_fam_admin[$admin_id][$parentid]['admin'] == 1) )
			{
				$weight_disabled = false;
				$admin_funcs[] = "<em class=\"fa fa-trash-o fa-lg\">&nbsp;</em> <a href=\"javascript:void(0);\" onclick=\"nv_del_fam(" . $fid . ")\">" . $lang_global['delete'] . "</a>";
			}

			$xtpl->assign( 'ROW', array(
				'fid' => $fid,
				'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=family&amp;parentid=' . $fid,
				'title' => $title,
				'adminfuncs' => implode( '&nbsp;-&nbsp;', $admin_funcs )
			) );

			if( $weight_disabled )
			{
				$xtpl->assign( 'STT', $a );
				$xtpl->parse( 'main.data.loop.stt' );
			}
			else
			{
				for( $i = 1; $i <= $num; ++$i )
				{
					$xtpl->assign( 'WEIGHT', array(
						'key' => $i,
						'title' => $i,
						'selected' => $i == $weight ? ' selected="selected"' : ''
					) );
					$xtpl->parse( 'main.data.loop.weight.loop' );
				}
				$xtpl->parse( 'main.data.loop.weight' );
			}

			if( $func_fam_disabled )
			{
				$xtpl->assign( 'INHOME', $array_inhome[$inhome] );
				$xtpl->parse( 'main.data.loop.disabled_inhome' );

				$xtpl->assign( 'VIEWFAM', $array_viewfam[$viewfam] );
				$xtpl->parse( 'main.data.loop.disabled_viewfam' );

				$xtpl->assign( 'NUMLINKS', $numlinks );
				$xtpl->parse( 'main.data.loop.title_numlinks' );

				$xtpl->assign( 'NEWDAY', $newday );
				$xtpl->parse( 'main.data.loop.title_newday' );
			}
			else
			{
				foreach( $array_inhome as $key => $val )
				{
					$xtpl->assign( 'INHOME', array(
						'key' => $key,
						'title' => $val,
						'selected' => $key == $inhome ? ' selected="selected"' : ''
					) );
					$xtpl->parse( 'main.data.loop.inhome.loop' );
				}
				$xtpl->parse( 'main.data.loop.inhome' );

				foreach( $array_viewfam as $key => $val )
				{
					$xtpl->assign( 'VIEWFAM', array(
						'key' => $key,
						'title' => $val,
						'selected' => $key == $viewfam ? ' selected="selected"' : ''
					) );
					$xtpl->parse( 'main.data.loop.viewfam.loop' );
				}
				$xtpl->parse( 'main.data.loop.viewfam' );

				for( $i = 0; $i <= 20; ++$i )
				{
					$xtpl->assign( 'NUMLINKS', array(
						'key' => $i,
						'title' => $i,
						'selected' => $i == $numlinks ? ' selected="selected"' : ''
					) );
					$xtpl->parse( 'main.data.loop.numlinks.loop' );
				}
				$xtpl->parse( 'main.data.loop.numlinks' );

				for( $i = 0; $i <= 10; ++$i )
				{
					$xtpl->assign( 'NEWDAY', array(
						'key' => $i,
						'title' => $i,
						'selected' => $i == $newday ? ' selected="selected"' : ''
					) );
					$xtpl->parse( 'main.data.loop.newday.loop' );
				}
				$xtpl->parse( 'main.data.loop.newday' );
			}

			if( $numsubfam )
			{
				$xtpl->assign( 'NUMSUBFAM', $numsubfam );
				$xtpl->parse( 'main.data.loop.numsubfam' );
			}

			$xtpl->parse( 'main.data.loop' );
			++$a;
		}
	}

	if( $num > 0 )
	{
		$xtpl->parse( 'main.data' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	return $contents;
}

/**
 * GetfidInParent()
 *
 * @param mixed $fid
 * @return
 */
function GetfidInParent( $fid )
{
	global $global_array_fam;
	$array_fam = array();
	$array_fam[] = $fid;
	$subfid = explode( ',', $global_array_fam[$fid]['subfid'] );
	if( ! empty( $subfid ) )
	{
		foreach( $subfid as $id )
		{
			if( $id > 0 )
			{
				if( $global_array_fam[$id]['numsubfam'] == 0 )
				{
					$array_fam[] = $id;
				}
				else
				{
					$array_fam_temp = GetfidInParent( $id );
					foreach( $array_fam_temp as $fid_i )
					{
						$array_fam[] = $fid_i;
					}
				}
			}
		}
	}
	return array_unique( $array_fam );
}

/**
 * redriect()
 *
 * @param string $msg1
 * @param string $msg2
 * @param mixed $nv_redirect
 * @return
 */
function redriect( $msg1 = '', $msg2 = '', $nv_redirect, $autoSaveKey = '', $go_back = '' )
{
	global $global_config, $module_file, $module_name;
	$xtpl = new XTemplate( 'redriect.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );

	if( empty( $nv_redirect ) )
	{
		$nv_redirect = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
	}
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_REDIRECT', $nv_redirect );
	$xtpl->assign( 'MSG1', $msg1 );
	$xtpl->assign( 'MSG2', $msg2 );

	if( ! empty( $autoSaveKey ) )
	{
		$xtpl->assign( 'AUTOSAVEKEY', $autoSaveKey );
		$xtpl->parse( 'main.removelocalstorage' );
	}

	if( $go_back )
	{
		$xtpl->parse( 'main.go_back' );
	}
	else
	{
		$xtpl->parse( 'main.meta_refresh' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );

	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}