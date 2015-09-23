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

$fid = $nv_Request->get_int( 'fid', 'post', 0 );
$mod = $nv_Request->get_string( 'mod', 'post', '' );
$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );
$content = 'NO_' . $fid;

list( $fid, $parentid, $numsubfam ) = $db->query( 'SELECT fid, parentid, numsubfam FROM ' . NV_PREFIXLANG . '_' . $module_data . '_family WHERE fid=' . $fid  )->fetch( 3 );
if( $fid > 0 )
{
	if( $mod == 'weight' and $new_vid > 0 and ( defined( 'NV_IS_ADMIN_MODULE' ) or ( $parentid > 0 and isset( $array_fam_admin[$admin_id][$parentid] ) and $array_fam_admin[$admin_id][$parentid]['admin'] == 1 ) ) )
	{
		$sql = 'SELECT fid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_family WHERE fid!=' . $fid . ' AND parentid=' . $parentid . ' ORDER BY weight ASC';
		$result = $db->query( $sql );

		$weight = 0;
		while( $row = $result->fetch() )
		{
			++$weight;
			if( $weight == $new_vid ) ++$weight;
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_family SET weight=' . $weight . ' WHERE fid=' . $row['fid'];
			$db->query( $sql );
		}

		$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_family SET weight=' . $new_vid . ' WHERE fid=' . $fid ;
		$db->query( $sql );

		nv_fix_fam_order();
		$content = 'OK_' . $parentid;
	}
	elseif( defined( 'NV_IS_ADMIN_MODULE' ) or ( isset( $array_fam_admin[$admin_id][$fid] ) and $array_fam_admin[$admin_id][$fid]['add_content'] == 1 ) )
	{
		if( $mod == 'inhome' and ( $new_vid == 0 or $new_vid == 1 ) )
		{
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_family SET inhome=' . $new_vid . ' WHERE fid=' . $fid ;
			$db->query( $sql );
			$content = 'OK_' . $parentid;
		}
		elseif( $mod == 'numlinks' and $new_vid >= 0 and $new_vid <= 20 )
		{
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_family SET numlinks=' . $new_vid . ' WHERE fid=' . $fid ;
			$db->query( $sql );
			$content = 'OK_' . $parentid;
		}
		elseif( $mod == 'newday' and $new_vid >= 0 and $new_vid <= 10 )
		{
			$sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_family SET newday=' . $new_vid . ' WHERE fid=' . $fid ;
			$db->query( $sql );
			$content = 'OK_' . $parentid;
		}
		elseif( $mod == 'viewfam' and $nv_Request->isset_request( 'new_vid', 'post' ) )
		{
			$viewfam = $nv_Request->get_title( 'new_vid', 'post' );
			$array_viewfam = ( $numsubfam > 0 ) ? $array_viewfam_full : $array_viewfam_nosub;
			if( ! array_key_exists( $viewfam, $array_viewfam ) )
			{
				$viewfam = 'viewfam_page_new';
			}
			$stmt = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_family SET viewfam= :viewfam WHERE fid=' . $fid  );
			$stmt->bindParam( ':viewfam', $viewfam, PDO::PARAM_STR );
			$stmt->execute();
			$content = 'OK_' . $parentid;
		}
	}
	nv_del_moduleCache( $module_name );
}

include NV_ROOTDIR . '/includes/header.php';
echo $content;
include NV_ROOTDIR . '/includes/footer.php';