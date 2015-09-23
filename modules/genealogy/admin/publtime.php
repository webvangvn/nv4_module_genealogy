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

if( $nv_Request->isset_request( 'checkss', 'get' ) and $nv_Request->get_string( 'checkss', 'get' ) == md5( $global_config['sitekey'] . session_id() ) )
{
	$listid = $nv_Request->get_string( 'listid', 'get' );
	$id_array = array_map( 'intval', explode( ',', $listid ) );

	$publ_array = array();

	$sql = 'SELECT id, listfid, status, publtime, exptime FROM ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy WHERE id in (' . implode( ',', $id_array ) . ')';
	$result = $db->query( $sql );
	while( list( $id, $listfid, $status, $publtime, $exptime ) = $result->fetch( 3 ) )
	{
		$arr_fid = explode( ',', $listfid );

		$check_permission = false;
		if( defined( 'NV_IS_ADMIN_MODULE' ) )
		{
			$check_permission = true;
		}
		else
		{
			$check_edit = 0;
			foreach( $arr_fid as $fid_i )
			{
				if( isset( $array_fam_admin[$admin_id][$fid_i] ) )
				{
					if( $array_fam_admin[$admin_id][$fid_i]['admin'] == 1 )
					{
						++$check_edit;
					}
				}
			}
			if( $check_edit == sizeof( $arr_fid ) )
			{
				$check_permission = true;
			}
		}

		if( $check_permission > 0 )
		{
			$data_save = array();
			if( $exptime > 0 and $exptime < NV_CURRENTTIME )
			{
				$data_save['exptime'] = 0;
			}
			if( $publtime > NV_CURRENTTIME )
			{
				$data_save['publtime'] = NV_CURRENTTIME;
			}
			if( $status != 1 )
			{
				$data_save['status'] = 1;
			}

			if( ! empty( $data_save ) )
			{
				$s_ud = '';
				foreach( $data_save as $key => $value )
				{
					$s_ud .= $key . " = '" . $value . "', ";
				}
				$s_ud .= "edittime = '" . NV_CURRENTTIME . "'";
				$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy SET ' . $s_ud . ' WHERE id =' . $id );
				foreach( $arr_fid as $fid_i )
				{
					$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $fid_i . ' SET ' . $s_ud . ' WHERE id =' . $id );
				}
				$publ_array[] = $id;
			}
		}
	}
	if( ! empty( $publ_array ) )
	{
		nv_insert_logs( NV_LANG_DATA, $module_name, 'log_publ_content', 'listid: ' . implode( ', ', $publ_array ), $admin_info['userid'] );
	}
	nv_set_status_module();
}

Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
die();

?>