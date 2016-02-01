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

$contents = '';
$publtime = 0;
$alias_made_up=change_alias($lang_module['made_up']);
$alias_convention=change_alias($lang_module['convention']);
$alias_collapse=change_alias($lang_module['collapse']);
$alias_anniversary=change_alias($lang_module['anniversary']);
$alias_family_tree=change_alias($lang_module['family_tree']);
$array_relationships = array(1 => $lang_module['u_relationships_1'], 2 => $lang_module['u_relationships_2'], 3 => $lang_module['u_relationships_3']);
$array_gender = array(0 => $lang_module['u_gender_0'], 1 => $lang_module['u_gender_1'], 2 => $lang_module['u_gender_2']);
$array_status = array(0 => $lang_module['u_status_0'], 1 => $lang_module['u_status_1'], 2 => $lang_module['u_status_2']);

if( ! defined( 'NV_IS_USER' ) )
{
	$url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login';
	$nv_redirect = nv_get_redirect();
	if( ! empty( $nv_redirect ) ) $url .= '&nv_redirect=' . $nv_redirect;
	Header( 'Location: ' . nv_url_rewrite( $url, true ) );
	exit();
}
else
{	

	if ($nv_Request->get_string('submit', 'post') != "")
	{
		$post['title'] = $nv_Request->get_string('title', 'post', '', 1);
		$post['fid'] = $nv_Request->get_int('fid', 'post', 0);
		$post['cityid'] = $nv_Request->get_int('cityid', 'post', 0);
		$post['districtid'] = $nv_Request->get_int('districtid', 'post', 0);
		$post['wardid'] = $nv_Request->get_int('wardid', 'post', 0);
		$post['bodytext'] = $nv_Request->get_string('bodytext', 'post', '');
		$post['bodytext'] = defined('NV_EDITOR') ? nv_nl2br($post['bodytext'], '') : nv_nl2br(nv_htmlspecialchars(strip_tags($post['bodytext'])), '<br />');
		$post['bodyhtml'] = $post['bodytext'];
		$post['listfid'] =  $post['fid'];
		$post['rule'] = $nv_Request->get_string('rule', 'post', '');
		$post['rule'] = defined('NV_EDITOR') ? nv_nl2br($post['rule'], '') : nv_nl2br(nv_htmlspecialchars(strip_tags($post['rule'])), '<br />');
		$post['ruletext'] = $post['rule'];
		$post['content'] = $nv_Request->get_string('content', 'post', '');
		$post['content'] = defined('NV_EDITOR') ? nv_nl2br($post['content'], '') : nv_nl2br(nv_htmlspecialchars(strip_tags($post['content'])), '<br />');
		$post['contenttext'] = $post['content'];
		$post['years'] = $nv_Request->get_string('years', 'post', '', 1);
		$post['author'] = $nv_Request->get_string('author', 'post', '', 1);
		$post['patriarch'] = $nv_Request->get_string('patriarch', 'post', '', 1);
		$post['full_name'] = $nv_Request->get_string('full_name', 'post', '', 1);
		$post['telephone'] = $nv_Request->get_string('telephone', 'post', '', 1);
		$post['email'] = $nv_Request->get_string('email', 'post', '', 1);
		
		if (!empty($post['title']) and $post['cityid'] > 0)
		{
			
			$post['alias'] = change_alias($post['title']);
			if( $module_config[$module_name]['alias_lower'] ) $post['alias'] = strtolower( $post['alias'] );
			
			$post['userid'] = $user_info['userid'];
			

			if( $post['status'] == 1 and $post['publtime'] > NV_CURRENTTIME )
			{
				$post['status'] = 2;
			}
			$sql =  "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_genealogy SET
					 fid=" . intval( $post['fid'] ) . ",
					 listfid=" . $db->quote(  $post['listfid']) . ",
					 author=" . $db->quote(  $post['author']) . ",
					 patriarch=" . $db->quote(  $post['patriarch']) . ",
					 status=" . intval( $post['status'] ) . ",
					 publtime=" . intval( $post['publtime'] ) . ",
					 exptime=" . intval( $post['exptime'] ) . ",
					 title=" . $db->quote(  $post['title']) . ",
					 alias=" . $db->quote(  $post['alias']) . ",
					 cityid=" . intval( $post['cityid'] ) . ",
					 districtid=" . intval( $post['districtid'] ) . ",
					 wardid=" . intval( $post['wardid'] ) . ",
					 years=" . $db->quote(  $post['years']) . ",
					 full_name=" . $db->quote(  $post['full_name']) . ",
					 telephone=" . $db->quote(  $post['telephone']) . ",
					 email=" . $db->quote(  $post['email']) . ",
					 edittime=" . NV_CURRENTTIME . "
				WHERE id =" . $news_contents['id'];
			
			if( $db->exec( $sql ) )
			{
				if(defined( 'NV_IS_ADMIN' ))
				{
					$user_post= $admin_info['userid'];
				}else{
					$user_post= $user_info['userid'];
				}
				$sql =  "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_" . $post['fid']. " SET
					 fid=" . intval( $post['fid'] ) . ",
					 listfid=" . $db->quote(  $post['listfid']) . ",
					 author=" . $db->quote(  $post['author']) . ",
					 patriarch=" . $db->quote(  $post['patriarch']) . ",
					 status=" . intval( $post['status'] ) . ",
					 publtime=" . intval( $post['publtime'] ) . ",
					 exptime=" . intval( $post['exptime'] ) . ",
					 title=" . $db->quote(  $post['title']) . ",
					 alias=" . $db->quote(  $post['alias']) . ",
					 cityid=" . intval( $post['cityid'] ) . ",
					 districtid=" . intval( $post['districtid'] ) . ",
					 wardid=" . intval( $post['wardid'] ) . ",
					 years=" . $db->quote(  $post['years']) . ",
					 full_name=" . $db->quote(  $post['full_name']) . ",
					 telephone=" . $db->quote(  $post['telephone']) . ",
					 email=" . $db->quote(  $post['email']) . ",
					 edittime=" . NV_CURRENTTIME . "
				WHERE id =" . $news_contents['id'];
				$db->exec( $sql );
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['genealogy_edit'], $post['title'], $user_post );
				
				
				$sql =  'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_bodyhtml_' . ceil( $news_contents['id'] / 2000 ) . ' SET
					bodyhtml=' . $db->quote(  $post['bodyhtml'] ) . ',
					rule=' . $db->quote(  $post['rule'] ) . ',
					content=' . $db->quote(  $post['content'] ) . ',
					copyright=' . intval( $post['copyright'] ) . ',
					gid=' . intval( $post['gid'] ) . '
				WHERE id =' . $news_contents['id'] ;

				
				
				$db->exec( $sql );
				$array_fam_old = $post_old['listfid'] ;
				$array_fam_new =  $post['listfid'] ;

				$array_fam_diff = array_diff( $array_fam_old, $array_fam_new );
				foreach( $array_fam_diff as $fid )
				{
					$ct_query[] = $db->exec( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $fid . ' WHERE id = ' . $news_contents['id'] );
				}
				foreach( $array_fam_new as $fid )
				{
					$db->exec( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $fid . ' WHERE id = ' . $news_contents['id'] );
					$ct_query[] = $db->exec( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $fid . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy WHERE id=' . $news_contents['id'] );
				}

				$sql =  'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_bodytext SET bodytext=' . $db->quote( $post['bodytext'] ) . ', ruletext=' . $db->quote(  $post['ruletext']) . ', contenttext=' . $db->quote(  $post['contenttext'] ) . ' WHERE id =' . $news_contents['id'] ;
				
				
				
				$db->exec( $sql );
				
					Header("Location: " . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] . '/Manager' . $global_config['rewrite_exturl'], true ));
					exit();
				
			}
			else
			{
				Header("Location: " . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] . '/Manager' . $global_config['rewrite_exturl'], true ));
				exit();
			}
			
		}
	}
	else
	{
		if( defined( 'NV_EDITOR' ) )
		{
			require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
		}
		elseif( ! nv_function_exists( 'nv_aleditor' ) and file_exists( NV_ROOTDIR . '/' . NV_EDITORSDIR . '/ckeditor/ckeditor.js' ) )
		{
			define( 'NV_EDITOR', true );
			define( 'NV_IS_CKEDITOR', true );
			$my_head .= '<script type="text/javascript" src="' . NV_BASE_SITEURL . NV_EDITORSDIR . '/ckeditor/ckeditor.js"></script>';

			function nv_aleditor( $textareaname, $width = '100%', $height = '450px', $val = '', $customtoolbar = '' )
			{
				global  $module_data;
				$return = '<textarea style="width: ' . $width . '; height:' . $height . ';" id="' . $module_data . '_' . $textareaname . '" name="' . $textareaname . '">' . $val . '</textarea>';
				$return .= "<script type=\"text/javascript\">
				CKEDITOR.replace( '" . $module_data . "_" . $textareaname . "', {" . ( ! empty( $customtoolbar ) ? 'toolbar : "' . $customtoolbar . '",' : '' ) . " width: '" . $width . "',height: '" . $height . "',});
				</script>";
				return $return;
			}
		}
		$show_no_image = $module_config[$module_name]['show_no_image'];

		
		
		$base_url_rewrite = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] . '/Manager' . $global_config['rewrite_exturl'], true );
		
	}


	$array_mod_title[] = array(
			'title' => $lang_module['manager'],
			'link' => $base_url_rewrite
		);
	$contents = create_genealogy( $news_contents, $list_users, $array_keyword, $content_comment );
}


include NV_ROOTDIR . '/includes/header.php';
echo  $contents ;
include NV_ROOTDIR . '/includes/footer.php';
