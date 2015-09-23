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
	
	
$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$contents = '';
$cache_file = '';

$base_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;
$base_url_rewrite = nv_url_rewrite( $base_url, true );
$page_url_rewrite = $page ? nv_url_rewrite( $base_url . '/page-' . $page, true ) : $base_url_rewrite;
$request_uri = $_SERVER['REQUEST_URI'];
if( ! ( $home OR $request_uri == $base_url_rewrite OR $request_uri == $page_url_rewrite OR NV_MAIN_DOMAIN . $request_uri == $base_url_rewrite OR NV_MAIN_DOMAIN . $request_uri == $page_url_rewrite ) )
{
	$redirect = '<meta http-equiv="Refresh" content="3;URL=' . $base_url_rewrite . '" />';
	nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect );
}
if( ! defined( 'NV_IS_MODADMIN' ) and $page < 5 )
{
	$cache_file = NV_LANG_DATA . '_' . $module_info['template'] . '-' . $op . '-' . $page . '-' . NV_CACHE_PREFIX . '.cache';
	if( ( $cache = nv_get_cache( $module_name, $cache_file ) ) != false )
	{
		$contents = $cache;
	}
}

if( empty( $contents ) )
{
	$viewfam = $module_config[$module_name]['indexfile'];
	$show_no_image = $module_config[$module_name]['show_no_image'];
	$array_fampage = array();
	$array_fam_other = array();

	if(  $viewfam == 'viewfam_none' )
	{
		$contents = '';
	}
	elseif( $viewfam == 'viewfam_page_new' or $viewfam == 'viewfam_page_old' )
	{
		$order_by = ( $viewfam == 'viewfam_page_new' ) ? 'publtime DESC' : 'publtime ASC';
		$db->sqlreset()
			->select( 'COUNT(*)' )
			->from( NV_PREFIXLANG . '_' . $module_data . '_genealogy' )
			->where( 'status= 1 AND inhome=1' );

		$num_items = $db->query( $db->sql() )->fetchColumn();

		$db->select( 'id, fid, listfid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, hitstotal, hitscm, total_rating, click_rating' )
			->order( $order_by )
			->limit( $per_page )
			->offset( ( $page - 1 ) * $per_page );

		$end_publtime = 0;

		$result = $db->query( $db->sql() );
		while( $item = $result->fetch() )
		{
			if( $item['homeimgthumb'] == 1 ) //image thumb
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
			}
			elseif( $item['homeimgthumb'] == 2 ) //image file
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
			}
			elseif( $item['homeimgthumb'] == 3 ) //image url
			{
				$item['imghome'] = $item['homeimgfile'];
			}
			elseif( ! empty( $show_no_image ) ) //no image
			{
				$item['imghome'] = NV_BASE_SITEURL . $show_no_image;
			}
			else
			{
				$item['imghome'] = '';
			}

			$item['newday'] = $global_array_fam[$item['fid']]['newday'];
			$item['link'] = $global_array_fam[$item['fid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
			$array_fampage[] = $item;
			$end_publtime = $item['publtime'];
		}

		if( $st_links > 0)
		{
			$db->sqlreset()
				->select('id, fid, addtime, edittime, publtime, title, alias, hitstotal')
				->from( NV_PREFIXLANG . '_' . $module_data . '_genealogy' );

			if( $viewfam == 'viewfam_page_new' )
			{
				$db->where( 'status= 1 AND inhome=1 AND publtime < ' . $end_publtime );
			}
			else
			{
				$db->where( 'status= 1 AND inhome=1 AND publtime > ' . $end_publtime );
			}
			$db->order( $order_by )->limit( $st_links );

			$result = $db->query( $db->sql() );
			while( $item = $result->fetch() )
			{
				$item['newday'] = $global_array_fam[$item['fid']]['newday'];
				$item['link'] = $global_array_fam[$item['fid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
				$array_fam_other[] = $item;
			}
		}

		$viewfam = 'viewfam_page_new';
		$generate_page = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );
		$contents = call_user_func( $viewfam, $array_fampage, $array_fam_other, $generate_page );
	}
	elseif( $viewfam == 'viewfam_list_new' or $viewfam == 'viewfam_list_old' ) // Xem theo tieu de
	{
		$order_by = ( $viewfam == 'viewfam_list_new' ) ? 'publtime DESC' : 'publtime ASC';

		$db->sqlreset()
			->select( 'COUNT(*) ')
			->from( NV_PREFIXLANG . '_' . $module_data . '_genealogy' )
			->where( 'status= 1 AND inhome=1' );

		$num_items = $db->query( $db->sql() )->fetchColumn();

		$db->select( 'id, fid, topicid, admin_id, author, sourceid, addtime, edittime, publtime, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, allowed_rating, hitstotal, hitscm, total_rating, click_rating' )
			->order( $order_by )
			->limit($per_page )
			->offset( ( $page - 1 ) * $per_page );

		$result = $db->query( $db->sql() );
		while( $item = $result->fetch() )
		{
			if( $item['homeimgthumb'] == 1 ) //image thumb
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
			}
			elseif( $item['homeimgthumb'] == 2 ) //image file
			{
				$item['imghome'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $item['homeimgfile'];
			}
			elseif( $item['homeimgthumb'] == 3 ) //image url
			{
				$item['imghome'] = $item['homeimgfile'];
			}
			elseif( ! empty( $show_no_image ) ) //no image
			{
				$item['imghome'] = NV_BASE_SITEURL . $show_no_image;
			}
			else
			{
				$item['imghome'] = '';
			}

			$item['newday'] = $global_array_fam[$item['fid']]['newday'];
			$item['link'] = $global_array_fam[$item['fid']]['link'] . '/' . $item['alias'] . '-' . $item['id'] . $global_config['rewrite_exturl'];
			$array_fampage[] = $item;
		}

		$viewfam = 'viewfam_list_new';
		$generate_page = nv_alias_page( $page_title, $base_url, $num_items, $per_page, $page );
		$contents = call_user_func( $viewfam, $array_fampage, 0, ( $page - 1 ) * $per_page, $generate_page );
	}
	elseif( $viewfam == 'view_location' ) // Xem theo địa điểm
	{
		foreach( $global_array_location_city as $city_i =>  $rowscity ){
			$db->sqlreset()
			->select( 'COUNT(*) ')
			->from( NV_PREFIXLANG . '_' . $module_data . '_genealogy' )
			->where( 'status= 1 AND inhome=1 AND cityid='. $rowscity["city_id"].'');
			$num_items = $db->query( $db->sql() )->fetchColumn();
			$array_fampage[$city_i]['link']=NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['location'] . '/' . $rowscity['alias'];
			$array_fampage[$city_i]['title']=$rowscity['title'];
			$array_fampage[$city_i]['number']=$num_items;
		}
	
		
		
		$contents = call_user_func( $viewfam, $array_fampage, 0 , 0, '' );
	}

	if( ! defined( 'NV_IS_MODADMIN' ) and $contents != '' and $cache_file != '' )
	{
		nv_set_cache( $module_name, $cache_file, $contents );
	}
}

if( $page > 1 )
{
	$page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
