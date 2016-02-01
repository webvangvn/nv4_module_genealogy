<?php

/**
 * @Project NUKEVIET 4.x
 * @Author webvang (hoang.nguyen@webvang.vn)
 * @Copyright (C) 2015 Webvang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 11/10/2015 00:00
 */

if( ! defined( 'NV_IS_MOD_GENEALOGY' ) ) die( 'Stop!!!' );


function view_location( $array_fampage, $fid, $page, $generate_page )
{
	global $module_name, $module_file, $module_upload, $lang_module, $module_config, $module_info, $global_array_fam, $global_array_location_city;

	$xtpl = new XTemplate( 'view_location.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$i=1;
	foreach( $array_fampage as $city_i =>  $rowscity ){
		
		
		$xtpl->assign( 'DATA',  $rowscity);
		if($rowscity ['number']>0){
			$xtpl->parse( 'main.looptr.looptd.number' );
		}
		if($i>=4){
			$xtpl->parse( 'main.looptr.looptd.break' );
			$i=0;
		}
		$i++;
		$xtpl->parse( 'main.looptr.looptd' );
		
	}
	$xtpl->parse( 'main.looptr' );
	

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function viewfam_list_new( $array_fampage, $fid, $page, $generate_page )
{
	global $module_name, $module_file, $module_upload, $lang_module, $module_config, $module_info, $global_array_fam;

	$xtpl = new XTemplate( 'viewfam_list.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'IMGWIDTH1', $module_config[$module_name]['homewidth'] );
	$xtpl->assign( 'TOOLTIP_POSITION', $module_config[$module_name]['showtooltip'] ? $module_config[$module_name]['tooltip_position'] : '' );

	if( ( $global_array_fam[$fid]['viewdescription'] and $page == 0 ) or $global_array_fam[$fid]['viewdescription'] == 2 )
	{
		$xtpl->assign( 'CONTENT', $global_array_fam[$fid] );
		if( $global_array_fam[$fid]['image'] )
		{
			$xtpl->assign( 'HOMEIMG1', NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $global_array_fam[$fid]['image'] );
			$xtpl->parse( 'main.viewdescription.image' );
		}
		$xtpl->parse( 'main.viewdescription' );
	}

	$a = $page;
	foreach( $array_fampage as $array_row_i )
	{
		$newday = $array_row_i['publtime'] + ( 86400 * $array_row_i['newday'] );
		$array_row_i['publtime'] = nv_date( 'd/m/Y h:i:s A', $array_row_i['publtime'] );
		$array_row_i['hometext'] = nv_clean60( $array_row_i['hometext'], $module_config[$module_name]['tooltip_length'], true );
		$xtpl->clear_autoreset();
		$xtpl->assign( 'NUMBER', ++$a );
		$xtpl->assign( 'CONTENT', $array_row_i );

		if( defined( 'NV_IS_MODADMIN' ) )
		{
			$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $array_row_i['id'] ) . " " . nv_link_delete_page( $array_row_i['id'] ) );
			$xtpl->parse( 'main.viewfamloop.adminlink' );
		}

		if( $array_row_i['imghome'] != '' )
		{
			$xtpl->assign( 'HOMEIMG1', $array_row_i['imghome'] );
			$xtpl->assign( 'HOMEIMGALT1', ! empty( $array_row_i['homeimgalt'] ) ? $array_row_i['homeimgalt'] : $array_row_i['title'] );
			$xtpl->parse( 'main.viewfamloop.image' );
		}

		if( $newday >= NV_CURRENTTIME )
		{
			$xtpl->parse( 'main.viewfamloop.newday' );
		}

		$xtpl->set_autoreset();
		$xtpl->parse( 'main.viewfamloop' );
	}
	if( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function viewfam_page_new( $array_fampage, $array_fam_other, $generate_page )
{
	global $global_array_fam, $module_name, $module_file, $module_upload, $lang_module, $module_config, $module_info, $global_array_fam, $fid, $page;

	$xtpl = new XTemplate( 'viewfam_page.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'IMGWIDTH1', $module_config[$module_name]['homewidth'] );

	if( ( $global_array_fam[$fid]['viewdescription'] and $page == 1 ) or $global_array_fam[$fid]['viewdescription'] == 2 )
	{
		$xtpl->assign( 'CONTENT', $global_array_fam[$fid] );
		if( $global_array_fam[$fid]['image'] )
		{
			$xtpl->assign( 'HOMEIMG1', NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $global_array_fam[$fid]['image'] );
			$xtpl->parse( 'main.viewdescription.image' );
		}
		$xtpl->parse( 'main.viewdescription' );
	}

	$a = 0;
	foreach( $array_fampage as $array_row_i )
	{
		$newday = $array_row_i['publtime'] + ( 86400 * $array_row_i['newday'] );
		$array_row_i['publtime'] = nv_date( 'd/m/Y h:i:s A', $array_row_i['publtime'] );
		$array_row_i['listfid'] = explode( ',', $array_row_i['listfid'] );
		$num_fam = sizeof( $array_row_i['listfid'] );

		$n = 1;
		foreach( $array_row_i['listfid'] as $listfid )
		{
			$listfam = array( 'title' => $global_array_fam[$listfid]['title'], "link" => $global_array_fam[$listfid]['link'] );
			$xtpl->assign( 'fam', $listfam );
			( ( $n < $num_fam ) ? $xtpl->parse( 'main.viewfamloop.fam.comma' ) : '' );
			$xtpl->parse( 'main.viewfamloop.fam' );
			++$n;
		}
		if( $a == 0 )
		{
			$xtpl->clear_autoreset();
			$xtpl->assign( 'CONTENT', $array_row_i );

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $array_row_i['id'] ) . " " . nv_link_delete_page( $array_row_i['id'] ) );
				$xtpl->parse( 'main.viewfamloop.featured.adminlink' );
			}

			if( $array_row_i['imghome'] != '' )
			{
				$xtpl->assign( 'HOMEIMG1', $array_row_i['imghome'] );
				$xtpl->assign( 'HOMEIMGALT1', ! empty( $array_row_i['homeimgalt'] ) ? $array_row_i['homeimgalt'] : $array_row_i['title'] );
				$xtpl->parse( 'main.viewfamloop.featured.image' );
			}

			if( $newday >= NV_CURRENTTIME )
			{
				$xtpl->parse( 'main.viewfamloop.featured.newday' );
			}

			$xtpl->parse( 'main.viewfamloop.featured' );
		}
		else
		{
			$xtpl->clear_autoreset();
			$xtpl->assign( 'CONTENT', $array_row_i );

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $array_row_i['id'] ) . " " . nv_link_delete_page( $array_row_i['id'] ) );
				$xtpl->parse( 'main.viewfamloop.news.adminlink' );
			}

			if( $array_row_i['imghome'] != '' )
			{
				$xtpl->assign( 'HOMEIMG1', $array_row_i['imghome'] );
				$xtpl->assign( 'HOMEIMGALT1', ! empty( $array_row_i['homeimgalt'] ) ? $array_row_i['homeimgalt'] : $array_row_i['title'] );
				$xtpl->parse( 'main.viewfamloop.news.image' );
			}

			if( $newday >= NV_CURRENTTIME )
			{
				$xtpl->parse( 'main.viewfamloop.news.newday' );
			}

			$xtpl->set_autoreset();
			$xtpl->parse( 'main.viewfamloop.news' );
		}
		++$a;
	}
	$xtpl->parse( 'main.viewfamloop' );

	if( ! empty( $array_fam_other ) )
	{
		$xtpl->assign( 'ORTHERNEWS', $lang_module['other'] );

		foreach( $array_fam_other as $array_row_i )
		{
			$newday = $array_row_i['publtime'] + ( 86400 * $array_row_i['newday'] );
			$array_row_i['publtime'] = nv_date( "d/m/Y", $array_row_i['publtime'] );
			$xtpl->assign( 'RELATED', $array_row_i );
			if( $newday >= NV_CURRENTTIME )
			{
				$xtpl->parse( 'main.related.loop.newday' );
			}
			$xtpl->parse( 'main.related.loop' );
		}

		$xtpl->parse( 'main.related' );
	}

	if( ! empty( $generate_page ) )
	{
		$xtpl->assign( 'GENERATE_PAGE', $generate_page );
		$xtpl->parse( 'main.generate_page' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}


function viewsubfam_main( $viewfam, $array_fam )
{
	global $module_name, $module_file, $global_array_fam, $lang_module, $module_config, $module_info;

	$xtpl = new XTemplate( $viewfam . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TOOLTIP_POSITION', $module_config[$module_name]['showtooltip'] ? $module_config[$module_name]['tooltip_position'] : '' );

	// Hien thi cac chu de con
	foreach( $array_fam as $key => $array_row_i )
	{
		if( isset( $array_fam[$key]['content'] ) )
		{
			$array_row_i['rss'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $module_info['alias']['rss'] . "/" . $array_row_i['alias'];
			$xtpl->assign( 'FAM', $array_row_i );
			$fid = intval( $array_row_i['fid'] );

			if( $array_row_i['subfid'] != '' )
			{
				$_arr_subfam = explode( ',', $array_row_i['subfid'] );
				foreach( $_arr_subfam as $fid_i )
				{
					if( $global_array_fam[$fid_i]['inhome'] == 1 )
					{
						$xtpl->assign( 'SUBFAM', $global_array_fam[$fid_i] );
						$xtpl->parse( 'main.listfam.subfamloop' );
					}
				}
			}

			$a = 0;
			$xtpl->assign( 'IMGWIDTH', $module_config[$module_name]['homewidth'] );

			foreach( $array_fam[$key]['content'] as $array_row_i )
			{
				$newday = $array_row_i['publtime'] + ( 86400 * $array_row_i['newday'] );
				$array_row_i['publtime'] = nv_date( 'd/m/Y H:i', $array_row_i['publtime'] );
				++$a;

				if( $a == 1 )
				{
					if( $newday >= NV_CURRENTTIME )
					{
						$xtpl->parse( 'main.listfam.newday' );
					}
					$xtpl->assign( 'CONTENT', $array_row_i );

					if( $array_row_i['imghome'] != "" )
					{
						$xtpl->assign( 'HOMEIMG', $array_row_i['imghome'] );
						$xtpl->assign( 'HOMEIMGALT', ! empty( $array_row_i['homeimgalt'] ) ? $array_row_i['homeimgalt'] : $array_row_i['title'] );
						$xtpl->parse( 'main.listfam.image' );
					}

					if( defined( 'NV_IS_MODADMIN' ) )
					{
						$xtpl->assign( 'ADMINLINK', nv_link_edit_page( $array_row_i['id'] ) . " " . nv_link_delete_page( $array_row_i['id'] ) );
						$xtpl->parse( 'main.listfam.adminlink' );
					}
				}
				else
				{
					if( $newday >= NV_CURRENTTIME )
					{
						$xtpl->assign( 'CLASS', 'icon_new_small' );
					}
					else
					{
						$xtpl->assign( 'CLASS', 'icon_list' );
					}
					$array_row_i['hometext'] = nv_clean60( $array_row_i['hometext'], $module_config[$module_name]['tooltip_length'], true );
					$xtpl->assign( 'OTHER', $array_row_i );
					$xtpl->parse( 'main.listfam.related.loop' );
				}

				if( $a > 1 )
				{
					$xtpl->assign( 'WCT', 'col-md-16 ' );
				}
				else
				{
					$xtpl->assign( 'WCT', '' );
				}

				$xtpl->set_autoreset();
			}

			if( $a > 1 )
			{
				$xtpl->parse( 'main.listfam.related' );
			}

			$xtpl->parse( 'main.listfam' );
		}
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}


function detail_theme( $news_contents, $array_keyword, $content_comment )
{
	global $global_config, $module_info, $lang_module, $module_name, $module_file, $module_config, $lang_global, $user_info, $admin_info, $client_info, $global_array_fam;

	$xtpl = new XTemplate( 'detail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG_GLOBAL', $lang_global );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'TEMPLATE', $global_config['module_theme'] );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'TOOLTIP_POSITION', $module_config[$module_name]['showtooltip'] ? $module_config[$module_name]['tooltip_position'] : '' );

	$news_contents['addtime'] = nv_date( 'd/m/Y h:i:s', $news_contents['addtime'] );

	$xtpl->assign( 'NEWSID', $news_contents['id'] );
	$xtpl->assign( 'NEWSCHECKSS', $news_contents['newscheckss'] );
	$xtpl->assign( 'DETAIL', $news_contents );
	$xtpl->assign( 'SELFURL', $client_info['selfurl'] );

	if( $news_contents['allowed_send'] == 1 )
	{
		$xtpl->assign( 'URL_SENDMAIL', $news_contents['url_sendmail'] );
		$xtpl->parse( 'main.allowed_send' );
	}

	if( $news_contents['allowed_print'] == 1 )
	{
		$xtpl->assign( 'URL_PRINT', $news_contents['url_print'] );
		$xtpl->parse( 'main.allowed_print' );
	}

	if( $news_contents['allowed_save'] == 1 )
	{
		$xtpl->assign( 'URL_SAVEFILE', $news_contents['url_savefile'] );
		$xtpl->parse( 'main.allowed_save' );
	}

	if( $news_contents['allowed_rating'] == 1 )
	{
		$xtpl->assign( 'LANGSTAR', $news_contents['langstar'] );
		$xtpl->assign( 'STRINGRATING', $news_contents['stringrating'] );
		$xtpl->assign( 'NUMBERRATING', $news_contents['numberrating'] );

		if( $news_contents['disablerating'] == 1 )
		{
			$xtpl->parse( 'main.allowed_rating.disablerating' );
		}

		if( $news_contents['numberrating'] >= $module_config[$module_name]['allowed_rating_point'] )
		{
			$xtpl->parse( 'main.allowed_rating.data_rating' );
		}

		$xtpl->parse( 'main.allowed_rating' );
	}

	if( $news_contents['showhometext'] )
	{
		if( ! empty( $news_contents['image']['src'] ) )
		{
			if( $news_contents['image']['position'] == 1 )
			{
				if( ! empty( $news_contents['image']['note'] ) )
				{
					$xtpl->parse( 'main.showhometext.imgthumb.note' );
				}
				else
				{
					$xtpl->parse( 'main.showhometext.imgthumb.empty' );
				}
				$xtpl->parse( 'main.showhometext.imgthumb' );
			}
			elseif( $news_contents['image']['position'] == 2 )
			{
				if( ! empty( $news_contents['image']['note'] ) )
				{
					$xtpl->parse( 'main.showhometext.imgfull.note' );
				}
				$xtpl->parse( 'main.showhometext.imgfull' );
			}
		}

		$xtpl->parse( 'main.showhometext' );
	}
	if( ! empty( $news_contents['post_name'] ) )
	{
		$xtpl->parse( 'main.post_name' );
	}

	if( ! empty( $news_contents['author'] ) or ! empty( $news_contents['source'] ) )
	{
		if( ! empty( $news_contents['author'] ) )
		{
			$xtpl->parse( 'main.author.name' );
		}

		if( ! empty( $news_contents['source'] ) )
		{
			$xtpl->parse( 'main.author.source' );
		}

		$xtpl->parse( 'main.author' );
	}
	if( $news_contents['copyright'] == 1 )
	{
		if( ! empty( $module_config[$module_name]['copyright'] ) )
		{
			$xtpl->assign( 'COPYRIGHT', $module_config[$module_name]['copyright'] );
			$xtpl->parse( 'main.copyright' );
		}
	}

	if( ! empty( $array_keyword ) )
	{
		$t = sizeof( $array_keyword ) - 1;
		foreach( $array_keyword as $i => $value )
		{
			$xtpl->assign( 'KEYWORD', $value['keyword'] );
			$xtpl->assign( 'LINK_KEYWORDS', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=tag/' . urlencode( $value['alias'] ) );
			$xtpl->assign( 'SLASH', ( $t == $i ) ? '' : ', ' );
			$xtpl->parse( 'main.keywords.loop' );
		}
		$xtpl->parse( 'main.keywords' );
	}

	if( defined( 'NV_IS_GENEALOGY_MANAGER' ) )
	{
		$link_manager=nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] . '/Manager' . $global_config['rewrite_exturl'], true );
		$xtpl->assign( 'ADMINLINK', "<a  href=\"" . $link_manager . "\"><em class=\"fa fa-edit margin-right\"></em> " . $lang_module['manager'] . "</a>");
		$xtpl->parse( 'main.adminlink' );
	}

	if( $module_config[$module_name]['socialbutton'] )
	{
		global $meta_property;

		if( ! empty( $module_config[$module_name]['facebookappid'] ) )
		{
			$meta_property['fb:app_id'] = $module_config[$module_name]['facebookappid'];
			$meta_property['og:locale'] = ( NV_LANG_DATA == 'vi' ) ? 'vi_VN' : 'en_US';
		}
		$xtpl->parse( 'main.socialbutton' );
	}


	if( ! empty( $content_comment ) )
	{
		$xtpl->assign( 'CONTENT_COMMENT', $content_comment );
		$xtpl->parse( 'main.comment' );
	}

	if( $news_contents['status'] != 1 )
	{
		$xtpl->parse( 'main.no_public' );
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function no_permission()
{
	global $module_info, $module_file, $lang_module;

	$xtpl = new XTemplate( 'detail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );

	$xtpl->assign( 'NO_PERMISSION', $lang_module['no_permission'] );
	$xtpl->parse( 'no_permission' );
	return $xtpl->text( 'no_permission' );
}

function viewfam_location( $genealogy_array, $page_title )
{
	global $lang_module, $module_info, $module_name, $module_file, $topicalias, $module_config;

	$xtpl = new XTemplate( 'location.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LOCAL_TITLE', $page_title );
	$xtpl->assign( 'IMGWIDTH1', $module_config[$module_name]['homewidth'] );
	if( ! empty(  $genealogy_array ) )
	{
		foreach(  $genealogy_array as  $genealogy_array_i )
		{
			$xtpl->assign( 'DATA', $genealogy_array_i );
			$xtpl->assign( 'TIME', date( 'H:i',  $genealogy_array_i['publtime'] ) );
			$xtpl->assign( 'DATE', date( 'd/m/Y',  $genealogy_array_i['publtime'] ) );

			if( ! empty(  $genealogy_array_i['src'] ) )
			{
				$xtpl->parse( 'main.loop.homethumb' );
			}

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$xtpl->assign( 'ADMINLINK', nv_link_edit_page(  $genealogy_array_i['id'] ) . ' ' . nv_link_delete_page(  $genealogy_array_i['id'] ) );
				$xtpl->parse( 'main.loop.adminlink' );
			}

			$xtpl->parse( 'main.loop' );
		}
	}

	

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function viewfam_user_manager( $genealogy_array, $page_title )
{
	global $lang_module, $module_info, $module_name, $module_file, $topicalias, $module_config;

	$xtpl = new XTemplate( 'manager.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LOCAL_TITLE', $page_title );
	$xtpl->assign( 'IMGWIDTH1', $module_config[$module_name]['homewidth'] );
	if( ! empty(  $genealogy_array ) )
	{
		foreach(  $genealogy_array as  $genealogy_array_i )
		{
			$xtpl->assign( 'DATA', $genealogy_array_i );
			$xtpl->assign( 'TIME', date( 'H:i',  $genealogy_array_i['publtime'] ) );
			$xtpl->assign( 'DATE', date( 'd/m/Y',  $genealogy_array_i['publtime'] ) );

			if( ! empty(  $genealogy_array_i['src'] ) )
			{
				$xtpl->parse( 'main.loop.homethumb' );
			}

			if( defined( 'NV_IS_MODADMIN' ) )
			{
				$xtpl->assign( 'ADMINLINK', nv_link_edit_page(  $genealogy_array_i['id'] ) . ' ' . nv_link_delete_page(  $genealogy_array_i['id'] ) );
				$xtpl->parse( 'main.loop.adminlink' );
			}

			$xtpl->parse( 'main.loop' );
		}
	}

	

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function view_detail( $viewdetail , $news_contents, $array_keyword, $content_comment )
{
	global $lang_module, $module_info, $module_name, $module_file, $topicalias, $module_config, $global_array_fam;
	$xtpl = new XTemplate( $viewdetail . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'DATA', $news_contents );
	$xtpl->assign( 'ACTIVE', 'ui-genealogys-selected' );
	if( defined( 'NV_IS_GENEALOGY_MANAGER' ) )
	{
		$link_manager=nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] . '/Manager' . $global_config['rewrite_exturl'], true );
		$xtpl->assign( 'ADMINLINK', "<a  href=\"" . $link_manager . "\"><em class=\"fa fa-edit margin-right\"></em> " . $lang_module['manager'] . "</a>");
		$xtpl->parse( 'main.adminlink' );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}
function nv_manager_viewdirtree_genealogy($parentid = 0, $tpl)
{
    global $list_users, $global_config, $module_file, $module_info;

    $_dirlist = $list_users[$parentid];
    $content = "";
    foreach ($_dirlist as $_dir)
    {
        if ($_dir['relationships'] == 1)
        {
            switch ($_dir['gender'] )
            {
                case 1 :
                    $_dir['class'] = 'class="male"';
                    break;
                case 2 :
                    $_dir['class'] = 'class="female"';
                    break;
                default :
                    $_dir['class'] = 'class="default"';
                    break;
            }

            $xtpl = new XTemplate("" . $tpl . ".tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
            if (isset($list_users[$_dir['id']]))
            {
                $_dirlist_wife = $list_users[$_dir['id']];
                foreach ($_dirlist_wife as $_dir_wife)
                {
                    if ($_dir_wife['relationships'] != 1)
                    {
                        switch ($_dir_wife['gender'] )
                        {
                            case 1 :
                                $_dir_wife['class'] = 'class="male noadd"';
                                break;
                            case 2 :
                                $_dir_wife['class'] = 'class="female noadd"';
                                break;
                            default :
                                $_dir_wife['class'] = 'class="default noadd"';
                                break;
                        }

                        $xtpl->assign("WIFE", $_dir_wife);
                        $xtpl->parse('tree.wife');
                    }
                }

                $content2 = nv_manager_viewdirtree_genealogy($_dir['id'], $tpl );
                if (!empty($content2))
                {
                    $xtpl->assign("TREE_CONTENT", $content2);
                    $xtpl->parse('tree.tree_content.loop');
                }
                $xtpl->parse('tree.tree_content');
            }
            $xtpl->assign("DIRTREE", $_dir);
            $xtpl->parse('tree');
            $content .= $xtpl->text('tree');
        }
    }
    return $content;
}
function view_family( $news_contents, $list_users, $array_keyword, $content_comment, $OrgChart )
{
	global $lang_module, $module_info, $module_name, $module_file, $topicalias, $module_config, $global_array_fam, $global_config;
	$xtpl = new XTemplate( 'genealogy-show.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'ACTIVE', 'ui-genealogys-selected' );
	$xtpl->assign( 'DATA', $news_contents );
	if( defined( 'NV_IS_GENEALOGY_MANAGER' ) )
	{
		$link_manager=nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] . '/Manager' . $global_config['rewrite_exturl'], true );
		$xtpl->assign( 'ADMINLINK', "<a  href=\"" . $link_manager . "\"><em class=\"fa fa-edit margin-right\"></em> " . $lang_module['manager'] . "</a>");
		$xtpl->parse( 'main.adminlink' );
	}
	 if( ! empty( $OrgChart ) )
		{
			$xtpl->assign( 'DATACHARTROWS', count( $OrgChart ) );
			foreach( $OrgChart as $item )
			{
				if( $item['id'] == $list_users['id'] )
				{
					$item['full_name'] = '<span style="color:red; font-weight: 700">' . $item['full_name'] . '</span>';
				}
				$xtpl->assign( 'DATACHART', $item );
				if( $item['number'] > 0 )
				{
					$xtpl->parse( 'main.orgchart.looporgchart.looporgchart2' );
				}
				$xtpl->parse( 'main.orgchart.looporgchart' );
			}
			$xtpl->parse( 'main.orgchart' );
		}
	
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function sendmail_themme( $sendmail )
{
	global $module_info, $module_file, $global_config, $lang_module, $lang_global;

	$xtpl = new XTemplate( 'sendmail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'SENDMAIL', $sendmail );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'GFX_NUM', NV_GFX_NUM );

	if( $global_config['gfx_chk'] > 0 )
	{
		$xtpl->assign( 'CAPTCHA_REFRESH', $lang_global['captcharefresh'] );
		$xtpl->assign( 'CAPTCHA_REFR_SRC', NV_BASE_SITEURL . NV_FILES_DIR . '/images/refresh.png' );
		$xtpl->assign( 'N_CAPTCHA', $lang_global['securitycode'] );
		$xtpl->assign( 'GFX_WIDTH', NV_GFX_WIDTH );
		$xtpl->assign( 'GFX_HEIGHT', NV_GFX_HEIGHT );
		$xtpl->parse( 'main.content.captcha' );
	}

	$xtpl->parse( 'main.content' );

	if( ! empty( $sendmail['result'] ) )
	{
		$xtpl->assign( 'RESULT', $sendmail['result'] );
		$xtpl->parse( 'main.result' );

		if( $sendmail['result']['check'] == true )
		{
			$xtpl->parse( 'main.close' );
		}
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function news_print( $result )
{
	global $module_info, $module_file, $lang_module;

	$xtpl = new XTemplate( 'print.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'CONTENT', $result );
	$xtpl->assign( 'LANG', $lang_module );

	if( ! empty( $result['image']['width'] ) )
	{
		if( $result['image']['position'] == 1 )
		{
			if( ! empty( $result['image']['note'] ) )
			{
				$xtpl->parse( 'main.image.note' );
			}

			$xtpl->parse( 'main.image' );
		}
		elseif( $result['image']['position'] == 2 )
		{
			if( $result['image']['note'] > 0 )
			{
				$xtpl->parse( 'main.imagefull.note' );
			}

			$xtpl->parse( 'main.imagefull' );
		}
	}

	if( $result['copyright'] == 1 )
	{
		$xtpl->parse( 'main.copyright' );
	}

	if( ! empty( $result['author'] ) or ! empty( $result['source'] ) )
	{
		if( ! empty( $result['author'] ) )
		{
			$xtpl->parse( 'main.author.name' );
		}

		if( ! empty( $result['source'] ) )
		{
			$xtpl->parse( 'main.author.source' );
		}

		$xtpl->parse( 'main.author' );
	}

	if( $result['status'] != 1 )
	{
		$xtpl->parse( 'main.no_public' );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

// Search
function search_theme( $key, $check_num, $date_array, $array_fam_search )
{
	global $module_name, $module_info, $module_file, $lang_module, $module_name;

	$xtpl = new XTemplate( 'search.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'BASE_URL_SITE', NV_BASE_SITEURL . 'index.php' );
	$xtpl->assign( 'TO_DATE', $date_array['to_date'] );
	$xtpl->assign( 'FROM_DATE', $date_array['from_date'] );
	$xtpl->assign( 'KEY', $key );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'OP_NAME', 'search' );

	foreach( $array_fam_search as $search_fam )
	{
		$xtpl->assign( 'SEARCH_FAM', $search_fam );
		$xtpl->parse( 'main.search_fam' );
	}

	for( $i = 0; $i <= 3; ++$i )
	{
		if( $check_num == $i )
		{
			$xtpl->assign( 'CHECK' . $i, 'selected=\'selected\'' );
		}
		else
		{
			$xtpl->assign( 'CHECK' . $i, '' );
		}
	}

	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}

function search_result_theme( $key, $numRecord, $per_pages, $page, $array_content, $fid )
{
	global $module_file, $module_info, $lang_module, $module_name, $global_array_fam, $module_config, $global_config;

	$xtpl = new XTemplate( 'search.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'KEY', $key );
	$xtpl->assign( 'IMG_WIDTH', $module_config[$module_name]['homewidth'] );
	$xtpl->assign( 'TITLE_MOD', $lang_module['search_modul_title'] );

	if( ! empty( $array_content ) )
	{
		foreach( $array_content as $value )
		{
			$fid_i = $value['fid'];

			$xtpl->assign( 'LINK', $global_array_fam[$fid_i]['link'] . '/' . $value['alias'] . "-" . $value['id'] . $global_config['rewrite_exturl'] );
			$xtpl->assign( 'TITLEROW', strip_tags( BoldKeywordInStr( $value['title'], $key ) ) );
			$xtpl->assign( 'CONTENT', BoldKeywordInStr( $value['hometext'], $key ) . "..." );
			$xtpl->assign( 'TIME', date( 'd/m/Y h:i:s A', $value['publtime'] ) );
			$xtpl->assign( 'AUTHOR', BoldKeywordInStr( $value['author'], $key ) );
			$xtpl->assign( 'SOURCE', BoldKeywordInStr( GetSourceNews( $value['sourceid'] ), $key ) );

			if( ! empty( $value['homeimgfile'] ) )
			{
				$xtpl->assign( 'IMG_SRC', $value['homeimgfile'] );
				$xtpl->parse( 'results.result.result_img' );
			}

			$xtpl->parse( 'results.result' );
		}
	}

	if( $numRecord == 0 )
	{
		$xtpl->assign( 'KEY', $key );
		$xtpl->assign( 'INMOD', $lang_module['search_modul_title'] );
		$xtpl->parse( 'results.noneresult' );
	}

	if( $numRecord > $per_pages ) // show pages
	{
		$url_link = $_SERVER['REQUEST_URI'];
		if( strpos( $url_link, '&page=' ) > 0 )
		{
			$url_link = substr( $url_link, 0, strpos( $url_link, '&page=' ) );
		}
		elseif( strpos( $url_link, '?page=' ) > 0 )
		{
			$url_link = substr( $url_link, 0, strpos( $url_link, '?page=' ) );
		}
		$_array_url = array( 'link' => $url_link, 'amp' => '&page=' );
		$generate_page = nv_generate_page( $_array_url, $numRecord, $per_pages, $page );

		$xtpl->assign( 'VIEW_PAGES', $generate_page );
		$xtpl->parse( 'results.pages_result' );
	}

	$xtpl->assign( 'NUMRECORD', $numRecord );
	$xtpl->assign( 'MY_DOMAIN', NV_MY_DOMAIN );

	$xtpl->parse( 'results' );
	return $xtpl->text( 'results' );
}


function nv_theme_genealogy_detail( $row_genealogy, $row_detail, $array_parentid, $OrgChart )
{
	global $global_config, $module_name, $module_file, $lang_module, $my_head, $module_info, $op;

	if( ! defined( 'SHADOWBOX' ) )
	{
		$my_head .= "<link rel=\"Stylesheet\" href=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.css\" />\n";
		$my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/shadowbox/shadowbox.js\"></script>\n";
		$my_head .= "<script type=\"text/javascript\">Shadowbox.init({ handleOversize: \"drag\" });</script>";
		define( 'SHADOWBOX', true );
	}
	$xtpl = new XTemplate( "gdetail.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GENEALOGY', $row_genealogy );
	$xtpl->assign( 'ACTIVE', 'ui-genealogys-selected' );
	if(int(count($row_detail['id']))!=0){
		$array_key = array(
			'full_name',
			'gender',
			'status',
			'code',
			'name1',
			'name2',
			'birthday',
			'dieday',
			'life',
			'burial' );
		$i = 0;

		$lang_module['u_life'] = ( $row_detail['life'] >= 50 ) ? $lang_module['u_life_ht'] : $lang_module['u_life_hd'];

		foreach( $array_key as $key )
		{
			if( $row_detail[$key] != "" )
			{
				$i++;
				$dataloop = array(
					'class' => ( $i % 2 == 0 ) ? 'class="second"' : '',
					'lang' => $lang_module['u_' . $key],
					'value' => $row_detail[$key] );
				$xtpl->assign( 'DATALOOP', $dataloop );
				$xtpl->parse( 'main.info.loop' );
			}
		}
		$xtpl->assign( 'DATA', $row_detail );
		
		foreach( $array_parentid as $array_parentid_i )
		{
			$xtpl->assign( 'PARENTIDCAPTION', $array_parentid_i['caption'] );
			if (isset($array_parentid_i['items']))
			{
				$items = $array_parentid_i['items'];
				$number = 1;
				foreach( $items as $item )
				{
					$item['number'] = $number++;
					$item['class'] = ( $number % 2 == 0 ) ? 'class="second"' : '';

					$xtpl->assign( 'DATALOOP', $item );
					$xtpl->parse( 'main.info.parentid.loop2' );
				}
			}
			$xtpl->parse( 'main.info.parentid' );
		}
		if( ! empty( $row_detail['content'] ) )
		{
			$xtpl->parse( 'main.info.content' );
		}

		if( ! empty( $OrgChart ) )
		{
			$xtpl->assign( 'DATACHARTROWS', count( $OrgChart ) );
			foreach( $OrgChart as $item )
			{
				if( $item['id'] == $row_detail['id'] )
				{
					$item['full_name'] = '<span style="color:red; font-weight: 700">' . $item['full_name'] . '</span>';
				}
				$xtpl->assign( 'DATACHART', $item );
				if( $item['number'] > 0 )
				{
					$xtpl->parse( 'main.info.orgchart.looporgchart.looporgchart2' );
				}
				$xtpl->parse( 'main.info.orgchart.looporgchart' );
			}
			$xtpl->parse( 'main.info.orgchart' );
		}
		$xtpl->parse( 'main.info' );
	}else{
		$xtpl->parse( 'main.not_info' );
	}
	$xtpl->parse( 'main' );
	return $xtpl->text( 'main' );
}


function manager_theme($news_contents, $list_users, $array_keyword, $content_comment)
{
		global $global_config, $module_name, $module_file, $lang_module, $my_head, $module_info, $op, $global_array_fam, $global_array_location_city, $global_array_location_district, $global_array_location_ward;
	    $xtpl = new XTemplate("manager-genealogy.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NV_ACTION_FILE', nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/Manager'  . $global_config['rewrite_exturl'], true ));
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('TEMPLATE', $module_info['template']);
	
    $xtpl->assign('OP', $op);


    foreach($global_array_fam as $global_array_fam_i)
	{
        $global_array_fam_i['selected'] = ($global_array_fam_i['fid'] == $news_contents['fid']) ? ' selected="selected"' : '';
        $xtpl->assign('FAMILY', $global_array_fam_i);
        $xtpl->parse('main.family');
    }

    foreach ($global_array_location_city as $global_array_city_i)
	{
        $global_array_city_i['selected'] = ($global_array_city_i['city_id'] == $news_contents['cityid']) ? ' selected="selected"' : '';
        $xtpl->assign('CITY', $global_array_city_i);
        $xtpl->parse('main.city');
    }
	foreach ($global_array_location_district as $global_array_district_i)
	{
        $global_array_district_i['selected'] = ($global_array_district_i['district_id'] == $news_contents['districtid']) ? ' selected="selected"' : '';
        $xtpl->assign('DISTRICT', $global_array_district_i);
        $xtpl->parse('main.district');
    }
	foreach ($global_array_location_ward as $global_array_ward_i)
	{
        $global_array_ward_i['selected'] = ($global_array_ward_i['ward_id'] == $news_contents['wardid']) ? ' selected="selected"' : '';
        $xtpl->assign('WARD', $global_array_ward_i);
        $xtpl->parse('main.ward');
    }

 /*   
    $post['status_checked'] = ($post['status']) ? ' checked="checked"' : '';
*/
    $array_who_view = array(0 => $lang_module['who_view0'], 1 => $lang_module['who_view1'], 2 => $lang_module['who_view2']);
    foreach ($array_who_view as $key => $value)
    {
        $row = array('id' => $key, 'title' => $value, 'selected' =>  '');

        $xtpl->assign('WHO_VIEW', $row);
        $xtpl->parse('main.who_view');
    }
    


	if (nv_function_exists('nv_aleditor'))
    {
        $news_contents['bodytext'] = nv_aleditor('bodytext', '100%', '200px', $news_contents['bodytext']);
        $news_contents['rule'] = nv_aleditor('rule', '100%', '200px', $news_contents['rule']);
        $news_contents['content'] = nv_aleditor('content', '100%', '200px', $news_contents['content']);
    }
    else
    {
        $news_contents['bodytext'] = "<textarea style=\"width: 100%\" name=\"bodytext\" cols=\"20\" rows=\"15\">" . $news_contents['bodytext'] . "</textarea>";
        $news_contents['rule'] = "<textarea style=\"width: 100%\" name=\"rule\" cols=\"20\" rows=\"15\">" . $news_contents['rulet'] . "</textarea>";
        $news_contents['content'] = "<textarea style=\"width: 100%\" name=\"content\" cols=\"20\" rows=\"15\">" . $news_contents['content'] . "</textarea>";
    }
	$xtpl->assign('DATA', $news_contents);
	
	 if (!empty($list_users))
    {
		
        $xtpl->assign('DATATREE', nv_manager_viewdirtree_genealogy(0,'manager-genealogy'));
        $xtpl->parse('main.foldertree');
		if( defined( 'NV_IS_GENEALOGY_MANAGER' )){
			$xtpl->parse('main.contextMenu');
		}
    }
    else
    {
		if( defined( 'NV_IS_GENEALOGY_MANAGER' )){
			$xtpl->parse('main.create_users');
		}else{
			$xtpl->parse('main.no_list');
		}
    }
    $xtpl->parse('main');
    return $xtpl->text('main');

}
function create_genealogy($news_contents, $list_users, $array_keyword, $content_comment)
{
		global $global_config, $module_name, $module_file, $lang_module, $my_head, $module_info, $op, $global_array_fam, $global_array_location_city, $global_array_location_district, $global_array_location_ward;
	    $xtpl = new XTemplate("create-genealogy.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('NV_ACTION_FILE', nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/Manager'  . $global_config['rewrite_exturl'], true ));
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('TEMPLATE', $module_info['template']);
	
    $xtpl->assign('OP', $op);


    foreach($global_array_fam as $global_array_fam_i)
	{
        $global_array_fam_i['selected'] = ($global_array_fam_i['fid'] == $news_contents['fid']) ? ' selected="selected"' : '';
        $xtpl->assign('FAMILY', $global_array_fam_i);
        $xtpl->parse('main.family');
    }

    foreach ($global_array_location_city as $global_array_city_i)
	{
        $global_array_city_i['selected'] = ($global_array_city_i['city_id'] == $news_contents['cityid']) ? ' selected="selected"' : '';
        $xtpl->assign('CITY', $global_array_city_i);
        $xtpl->parse('main.city');
    }
	foreach ($global_array_location_district as $global_array_district_i)
	{
        $global_array_district_i['selected'] = ($global_array_district_i['district_id'] == $news_contents['districtid']) ? ' selected="selected"' : '';
        $xtpl->assign('DISTRICT', $global_array_district_i);
        $xtpl->parse('main.district');
    }
	foreach ($global_array_location_ward as $global_array_ward_i)
	{
        $global_array_ward_i['selected'] = ($global_array_ward_i['ward_id'] == $news_contents['wardid']) ? ' selected="selected"' : '';
        $xtpl->assign('WARD', $global_array_ward_i);
        $xtpl->parse('main.ward');
    }

 /*   
    $post['status_checked'] = ($post['status']) ? ' checked="checked"' : '';
*/
    $array_who_view = array(0 => $lang_module['who_view0'], 1 => $lang_module['who_view1'], 2 => $lang_module['who_view2']);
    foreach ($array_who_view as $key => $value)
    {
        $row = array('id' => $key, 'title' => $value, 'selected' =>  '');

        $xtpl->assign('WHO_VIEW', $row);
        $xtpl->parse('main.who_view');
    }
    


	if (nv_function_exists('nv_aleditor'))
    {
        $news_contents['bodytext'] = nv_aleditor('bodytext', '100%', '200px', $news_contents['bodytext']);
        $news_contents['rule'] = nv_aleditor('rule', '100%', '200px', $news_contents['rule']);
        $news_contents['content'] = nv_aleditor('content', '100%', '200px', $news_contents['content']);
    }
    else
    {
        $news_contents['bodytext'] = "<textarea style=\"width: 100%\" name=\"bodytext\" cols=\"20\" rows=\"15\">" . $news_contents['bodytext'] . "</textarea>";
        $news_contents['rule'] = "<textarea style=\"width: 100%\" name=\"rule\" cols=\"20\" rows=\"15\">" . $news_contents['rulet'] . "</textarea>";
        $news_contents['content'] = "<textarea style=\"width: 100%\" name=\"content\" cols=\"20\" rows=\"15\">" . $news_contents['content'] . "</textarea>";
    }
	$xtpl->assign('DATA', $news_contents);
	
	 if (!empty($list_users))
    {
		
        $xtpl->assign('DATATREE', nv_manager_viewdirtree_genealogy(0,'manager-genealogy'));
        $xtpl->parse('main.foldertree');
		if( defined( 'NV_IS_GENEALOGY_MANAGER' )){
			$xtpl->parse('main.contextMenu');
		}
    }
    else
    {
		if( defined( 'NV_IS_GENEALOGY_MANAGER' )){
			$xtpl->parse('main.create_users');
		}else{
			$xtpl->parse('main.no_list');
		}
    }
    $xtpl->parse('main');
    return $xtpl->text('main');

}
