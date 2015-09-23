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

$check_permission = false;
$rowcontent['id'] = $nv_Request->get_int( 'id', 'get,post', 0 );
if( $rowcontent['id'] > 0 )
{
	$rowcontent = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy where id=' . $rowcontent['id'] )->fetch();
	if( ! empty( $rowcontent['id'] ) )
	{
		$arr_fid = explode( ',', $rowcontent['listfid'] );
		if( defined( 'NV_IS_ADMIN_MODULE' ) )
		{
			$check_permission = true;
		}
		else
		{
			$check_comments = 0;
			$status = $rowcontent['status'];
			foreach( $arr_fid as $fid_i )
			{
				if( isset( $array_fam_admin[$admin_id][$fid_i] ) )
				{
					if( $array_fam_admin[$admin_id][$fid_i]['admin'] == 1 )
					{
						++$check_comments;
					}
					else
					{
						if( $array_fam_admin[$admin_id][$fid_i]['comments'] == 1 )
						{
							++$check_comments;
						}
					}
				}
			}
			if( $check_comments == sizeof( $arr_fid ) )
			{
				$check_permission = true;
			}
		}
	}
}
if( $check_permission )
{
	Header( 'Location: ' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $global_array_fam[$rowcontent['fid']]['alias'] . '/' . $rowcontent['alias'] . '-' . $rowcontent['id'] . $global_config['rewrite_exturl'], true ) );
	die();
}
else
{
	nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['admin_no_allow_func'] );
}