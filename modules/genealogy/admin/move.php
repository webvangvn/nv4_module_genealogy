<?php

/**
 * @Project NUKEVIET 4.x
 * @Author webvang (hoang.nguyen@webvang.vn)
 * @Copyright (C) 2015 Webvang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 11/10/2015 00:00
 */

if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_MODULE_LOCATION' ) ){
	
	$contents = '<p class="note_fam">' . $lang_module['note_location'] . '</p>';
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	die();
	
	
}

$page_title = $lang_module['move'];

$id_array = array();
$listid = $nv_Request->get_string( 'listid', 'get,post', '' );
$fids = array_unique( $nv_Request->get_typed_array( 'fids', 'post', 'int', array() ) );
$fid = $nv_Request->get_int( 'fid', 'get,post', 0 );

if( $nv_Request->isset_request( 'idcheck', 'post' ) )
{
	$id_array = array_unique( $nv_Request->get_typed_array( 'idcheck', 'post', 'int', array() ) );
	if( !empty( $id_array ) AND !empty( $fids ) )
	{
		$listfid = implode( ',', $fids );
		if( empty( $fid ) )
		{
			$fid = $fids[0];
		}

		$result = $db->query( 'SELECT id, listfid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy WHERE id IN (' . implode( ',', $id_array ) . ')' );
		while( list( $id, $listfid_old ) = $result->fetch( 3 ) )
		{
			$array_fid_old = explode( ',', $listfid_old );
			foreach( $array_fid_old as $fid_i )
			{
				$db->exec( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $fid_i . ' WHERE id=' . $id );
			}

			$db->exec( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy SET fid=' . $fid . ', listfid=' . $db->quote( $listfid ) . ' WHERE id=' . $id );

			foreach( $fids as $fid_i )
			{
				try
				{
					$db->exec( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $fid_i . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy WHERE id=' . $id );
				}
				catch( PDOException $e )
				{
					$db->exec( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $fid_i . ' WHERE id=' . $id );
					$db->exec( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $fid_i . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy WHERE id=' . $id );
				}
			}
		}

		nv_del_moduleCache( $module_name );
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		die();
	}
}
else
{
	$id_array = array_map( 'intval', explode( ',', $listid ) );
}

if( empty( $id_array ) )
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
	die();
}

$db->sqlreset()->select( 'id, title' )->from( NV_PREFIXLANG . '_' . $module_data . '_genealogy' )->where( 'id IN (' . implode( ',', $id_array ) . ')' )->order( 'id DESC' );
$result = $db->query( $db->sql() );

$xtpl = new XTemplate( $op . '.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

while( list( $id, $title ) = $result->fetch( 3 ) )
{
	$xtpl->assign( 'ROW', array(
		'id' => $id,
		'title' => $title,
		'checked' => in_array( $id, $id_array ) ? ' checked="checked"' : ''
	) );

	$xtpl->parse( 'main.loop' );
}

foreach( $global_array_fam as $fid_i => $array_value )
{
	$space = intval( $array_value['lev'] ) * 30;
	$fiddisplay = (sizeof( $fids ) > 1 and ( in_array( $fid_i, $fids ))) ? '' : ' display: none;';
	$temp = array(
		'fid' => $fid_i,
		'space' => $space,
		'title' => $array_value['title'],
		'checked' => ( in_array( $fid_i, $fids )) ? ' checked="checked"' : '',
		'fidchecked' => ($fid_i == $fid) ? ' checked="checked"' : '',
		'fiddisplay' => $fiddisplay
	);
	$xtpl->assign( 'FAMS', $temp );
	$xtpl->parse( 'main.fid' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
