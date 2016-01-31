<?php

/**
 * @Project NUKEVIET 4.x
 * @Author webvang (hoang.nguyen@webvang.vn)
 * @Copyright (C) 2015 Webvang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 11/10/2015 00:00
 */

if( ! defined( 'NV_IS_MOD_GENEALOGY' ) ) die( 'Stop!!!' );
if( ! defined( 'NV_MODULE_LOCATION' ) ){
	$contents = '<p class="note_fam">' . $lang_module['note_location'] . '</p>';
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	die();
	
	
}
//die($module_info['alias']['location']);
$show_no_image = $module_config[$module_name]['show_no_image'];
if( isset( $array_op[1] ) )
{
	$alias = trim( $array_op[1] );
	$stmt = $db->prepare( 'SELECT city_id,title,alias FROM ' . $tablelocation . '_city WHERE alias= :alias' );
	$stmt->bindParam( ':alias', $alias, PDO::PARAM_STR );
	$stmt->execute();
	list( $cityid, $page_title, $alias ) = $stmt->fetch( 3 );
	if( $cityid > 0 )
	{
		$base_url_rewrite = $base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['location'] . '/' . $alias;
		$base_url_rewrite = nv_url_rewrite( $base_url_rewrite, true );
		if( $_SERVER['REQUEST_URI'] != $base_url_rewrite and NV_MAIN_DOMAIN . $_SERVER['REQUEST_URI'] != $base_url_rewrite )
		{
			Header( 'Location: ' . $base_url_rewrite );
			die();
		}
		$array_mod_title[] = array(
			'cityid' => 0,
			'title' => $page_title,
			'link' => $base_url
		);
		$item_array = array();
		$end_weight = 0;

		$db->sqlreset()
			->select( '*' )
			->from( NV_PREFIXLANG . '_' . $module_data . '_genealogy ' )
			->where( 'cityid= ' . $cityid . ' AND status= 1' );
		$num_items = $db->query( $db->sql() );
		$db->select( 'id, fid, admin_id, author, patriarch, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, hitstotal, hitscm, total_rating, click_rating, years, number' )
			->order( 'publtime ASC' );
		$result = $db->query( $db->sql() );
		while( $item = $result->fetch() )
		{
			$sqllistuser = $db->sqlreset()->query( 'SELECT max(lev) as maxlev FROM ' . NV_PREFIXLANG . '_'. $module_data .' WHERE gid = "' . $item['id'] . '" ORDER BY weight ASC' )->fetch();
			$item['maxlev']=$sqllistuser['maxlev'];
			//die($item['id']);	
			if( $item['homeimgthumb'] == 1 )//image thumb
			{
				$item['src'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
			}
			elseif( $item['homeimgthumb'] == 2 )//image file
			{
				$item['src'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
			}
			elseif( $item['homeimgthumb'] == 3 )//image url
			{
				$item['src'] = $item['homeimgfile'];
			}
			elseif( ! empty( $show_no_image ) )//no image
			{
				$item['src'] = NV_BASE_SITEURL . $show_no_image;
			}
			else
			{
				$item['src'] = '';
			}

			$item['alt'] = ! empty( $item['homeimgalt'] ) ? $item['homeimgalt'] : $item['title'];
			$item['width'] = $module_config[$module_name]['homewidth'];

			$end_weight++;
			$item['weight']=$end_weight;
			$item['link'] = $global_array_fam[$item['fid']]['link'] . '/' . $item['alias']  . $global_config['rewrite_exturl'];
			$item_array[] = $item;
		}
		$result->closeCursor();
		unset( $query, $row );
		$contents = viewfam_location( $item_array, $page_title );
	
	}
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
