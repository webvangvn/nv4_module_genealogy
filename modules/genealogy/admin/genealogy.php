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
if( defined( 'NV_EDITOR' ) )
{
	require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$username_alias = change_alias( $admin_info['username'] );
$array_structure_image = array();
$array_structure_image[''] = $module_upload;
$array_structure_image['username'] = $module_upload . '/' . $username_alias;


$structure_upload = isset( $module_config[$module_name]['structure_upload'] ) ? $module_config[$module_name]['structure_upload'] : 'username';
$currentpath = isset( $array_structure_image[$structure_upload] ) ? $array_structure_image[$structure_upload] : '';

if( file_exists( NV_UPLOADS_REAL_DIR . '/' . $currentpath ) )
{
	$upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $currentpath;
}
else
{
	$upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $module_upload;
	$e = explode( '/', $currentpath );
	if( ! empty( $e ) )
	{
		$cp = '';
		foreach( $e as $p )
		{
			if( ! empty( $p ) and ! is_dir( NV_UPLOADS_REAL_DIR . '/' . $cp . $p ) )
			{
				$mk = nv_mkdir( NV_UPLOADS_REAL_DIR . '/' . $cp, $p );
				if( $mk[0] > 0 )
				{
					$upload_real_dir_page = $mk[2];
					$db->query( "INSERT INTO " . NV_UPLOAD_GLOBALTABLE . "_dir (dirname, time) VALUES ('" . NV_UPLOADS_DIR . "/" . $cp . $p . "', 0)" );
				}
			}
			elseif( ! empty( $p ) )
			{
				$upload_real_dir_page = NV_UPLOADS_REAL_DIR . '/' . $cp . $p;
			}
			$cp .= $p . '/';
		}
	}
	$upload_real_dir_page = str_replace( '\\', '/', $upload_real_dir_page );
}

$currentpath = str_replace( NV_ROOTDIR . '/', '', $upload_real_dir_page );
$uploads_dir_user = NV_UPLOADS_DIR . '/' . $module_upload;
if( ! defined( 'NV_IS_SPADMIN' ) and strpos( $structure_upload, 'username' ) !== false )
{
	$array_currentpath = explode( '/', $currentpath );
	if( $array_currentpath[2] == $username_alias )
	{
		$uploads_dir_user = NV_UPLOADS_DIR . '/' . $module_upload . '/' . $username_alias;
	}
}


$fid = $nv_Request->get_int( 'fid', 'get', 0 );
$parentid = $nv_Request->get_int( 'parentid', 'get', 0 );
$array_imgposition = array(
	0 => $lang_module['imgposition_0'],
	1 => $lang_module['imgposition_1'],
	2 => $lang_module['imgposition_2'] );

$rowcontent = array(
	'id' => '',
	'fid' => $fid,
	'listfid' => $fid . ',' . $parentid,
	'admin_id' => $admin_id,
	'author' => '',
	'patriarch' => '',
	'addtime' => NV_CURRENTTIME,
	'edittime' => NV_CURRENTTIME,
	'status' => 0,
	'publtime' => NV_CURRENTTIME,
	'exptime' => 0,
	'archive' => 1,
	'title' => '',
	'alias' => '',
	'hometext' => '',
	'homeimgfile' => '',
	'homeimgalt' => '',
	'homeimgthumb' => '',
	'imgposition' => isset( $module_config[$module_name]['imgposition']) ? $module_config[$module_name]['imgposition'] : 1,
	'bodyhtml' => '',
	'rule' => '',
	'content' => '',
	'copyright' => 0,
	'gid' => 0,
	'inhome' => 1,
	'allowed_comm' => $module_config[$module_name]['setcomm'],
	'allowed_rating' => 1,
	'allowed_send' => 1,
	'allowed_print' => 1,
	'allowed_save' => 1,
	'hitstotal' => 0,
	'hitscm' => 0,
	'total_rating' => 0,
	'click_rating' => 0,
	'keywords' => '',
	'keywords_old' => '',
	'mode' => 'add',
	'cityid' => 0,
	'districtid' => 0,
	'wardid' => 0,
	'years' => '',
	'full_name' => '',
	'telephone' => '',
	'email' => '',
);

$page_title = $lang_module['genealogy_add'];
$error = array();
$groups_list = nv_groups_list();
$array_keywords_old = array();

$rowcontent['id'] = $nv_Request->get_int( 'id', 'get,post', 0 );
if( $rowcontent['id'] > 0 )
{
	$check_permission = false;
	$rowcontent = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy where id=' . $rowcontent['id'] )->fetch();
	if( ! empty( $rowcontent['id'] ) )
	{
		$rowcontent['mode'] = 'edit';
		$arr_fid = explode( ',', $rowcontent['listfid'] );
		if( defined( 'NV_IS_ADMIN_MODULE' ) )
		{
			$check_permission = true;
		}
		else
		{
			$check_edit = 0;
			$status = $rowcontent['status'];
			foreach( $arr_fid as $fid_i )
			{
				if( isset( $array_fam_admin[$admin_id][$fid_i] ) )
				{
					if( $array_fam_admin[$admin_id][$fid_i]['admin'] == 1 )
					{
						++$check_edit;
					}
					else
					{
						if( $array_fam_admin[$admin_id][$fid_i]['edit_content'] == 1 )
						{
							++$check_edit;
						}
						elseif( $array_fam_admin[$admin_id][$fid_i]['pub_content'] == 1 and ( $status == 0 or $status = 2 ) )
						{
							++$check_edit;
						}
						elseif( ( $status == 0 or $status == 4 or $status == 5 ) and $rowcontent['admin_id'] == $admin_id )
						{
							++$check_edit;
						}
					}
				}
			}
			if( $check_edit == sizeof( $arr_fid ) )
			{
				$check_permission = true;
			}
		}
	}

	if( ! $check_permission )
	{
		Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name );
		die();
	}

	$page_title = $lang_module['genealogy_edit'];

	$body_contents = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_bodyhtml_' . ceil( $rowcontent['id'] / 2000 ) . ' where id=' . $rowcontent['id'] )->fetch();
	$rowcontent = array_merge( $rowcontent, $body_contents );
	unset( $body_contents );

	$_query = $db->query( 'SELECT tid, keyword FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id=' . $rowcontent['id'] . ' ORDER BY keyword ASC' );
	while( $row = $_query->fetch() )
	{
		$array_keywords_old[$row['tid']] = $row['keyword'];
	}
	$rowcontent['keywords'] = implode( ', ', $array_keywords_old );
	$rowcontent['keywords_old'] = $rowcontent['keywords'];

}

$array_fam_add_content = $array_fam_pub_content = $array_fam_edit_content = $array_censor_content = array();
foreach( $global_array_fam as $fid_i => $array_value )
{
	$check_add_content = $check_pub_content = $check_edit_content = $check_censor_content = false;
	if( defined( 'NV_IS_ADMIN_MODULE' ) )
	{
		$check_add_content = $check_pub_content = $check_edit_content = $check_censor_content = true;
	}
	elseif( isset( $array_fam_admin[$admin_id][$fid_i] ) )
	{
		if( $array_fam_admin[$admin_id][$fid_i]['admin'] == 1 )
		{
			$check_add_content = $check_pub_content = $check_edit_content = $check_censor_content = true;
		}
		else
		{
			if( $array_fam_admin[$admin_id][$fid_i]['add_content'] == 1 )
			{
				$check_add_content = true;
			}

			if( $array_fam_admin[$admin_id][$fid_i]['pub_content'] == 1 )
			{
				$check_pub_content = true;
			}

			if( $array_fam_admin[$admin_id][$fid_i]['app_content'] == 1 )
			{
				$check_censor_content = true;
			}

			if( $array_fam_admin[$admin_id][$fid_i]['edit_content'] == 1 )
			{
				$check_edit_content = true;
			}
		}
	}
	if( $check_add_content )
	{
		$array_fam_add_content[] = $fid_i;
	}

	if( $check_pub_content )
	{
		$array_fam_pub_content[] = $fid_i;
	}
	if( $check_censor_content ) //Nguoi kiem duyet
	{
		$array_censor_content[] = $fid_i;
	}

	if( $check_edit_content )
	{
		$array_fam_edit_content[] = $fid_i;
	}
}

if( $nv_Request->get_int( 'save', 'post' ) == 1 )
{
	$fids = array_unique( $nv_Request->get_typed_array( 'fids', 'post', 'int', array() ) );

	$rowcontent['fid'] = $nv_Request->get_int( 'fid', 'post', 0 );
	$rowcontent['cityid'] = $nv_Request->get_int( 'cityid', 'post', 0 );
	$rowcontent['districtid'] = $nv_Request->get_int( 'districtid', 'post', 0 );
	$rowcontent['wardid'] = $nv_Request->get_int( 'wardid', 'post', 0 );

	$rowcontent['listfid'] = implode( ',', $fids );

	if( $nv_Request->isset_request( 'status1', 'post' ) ) $rowcontent['status'] = 1; //dang tin
	elseif( $nv_Request->isset_request( 'status0', 'post' ) ) $rowcontent['status'] = 0; //cho tong bien tap duyet
	elseif( $nv_Request->isset_request( 'status4', 'post' ) ) $rowcontent['status'] = 4; //luu tam
	else  $rowcontent['status'] = 6; //gui, cho bien tap

	$message_error_show = $lang_module['permissions_pub_error'];
	if( $rowcontent['status'] == 1 )
	{
		$array_fam_check_content = $array_fam_pub_content;
	}
	elseif( $rowcontent['status'] == 1 and $rowcontent['publtime'] <= NV_CURRENTTIME )
	{
		$array_fam_check_content = $array_fam_edit_content;
	}
	elseif( $rowcontent['status'] == 0 )
	{
		$array_fam_check_content = $array_censor_content;
		$message_error_show = $lang_module['permissions_sendspadmin_error'];
	}
	else
	{
		$array_fam_check_content = $array_fam_add_content;
	}
	
	foreach( $fids as $fid_i )
	{
		if($fid_i > 0){
			if( ! in_array( $fid_i, $array_fam_check_content ) )
			{
				$error[] = sprintf( $message_error_show, $global_array_fam[$fid_i]['title'] );
			}
		}else{
			$error[] = sprintf( $lang_module['error_fam'] );
		}
	}

	
	$rowcontent['author'] = $nv_Request->get_title( 'author', 'post', '', 1 );
	$rowcontent['patriarch'] = $nv_Request->get_title( 'patriarch', 'post', '', 1 );
	$rowcontent['years'] = $nv_Request->get_title( 'years', 'post', '', 1 );
	$rowcontent['full_name'] = $nv_Request->get_title( 'full_name', 'post', '', 1 );
	$rowcontent['telephone'] = $nv_Request->get_title( 'telephone', 'post', '', 1 );
	$rowcontent['email'] = $nv_Request->get_title( 'email', 'post', '', 1 );
	
	$publ_date = $nv_Request->get_title( 'publ_date', 'post', '' );

	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $publ_date, $m ) )
	{
		$phour = $nv_Request->get_int( 'phour', 'post', 0 );
		$pmin = $nv_Request->get_int( 'pmin', 'post', 0 );
		$rowcontent['publtime'] = mktime( $phour, $pmin, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$rowcontent['publtime'] = NV_CURRENTTIME;
	}

	$exp_date = $nv_Request->get_title( 'exp_date', 'post', '' );
	if( preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $exp_date, $m ) )
	{
		$ehour = $nv_Request->get_int( 'ehour', 'post', 0 );
		$emin = $nv_Request->get_int( 'emin', 'post', 0 );
		$rowcontent['exptime'] = mktime( $ehour, $emin, 0, $m[2], $m[1], $m[3] );
	}
	else
	{
		$rowcontent['exptime'] = 0;
	}

	$rowcontent['archive'] = $nv_Request->get_int( 'archive', 'post', 0 );
	if( $rowcontent['archive'] > 0 )
	{
		$rowcontent['archive'] = ( $rowcontent['exptime'] > NV_CURRENTTIME ) ? 1 : 2;
	}
	$rowcontent['title'] = $nv_Request->get_title( 'title', 'post', '', 1 );

	// Xử lý liên kết tĩnh
	$alias = $nv_Request->get_title( 'alias', 'post', '' );
	if( empty( $alias ) )
	{
		$alias = change_alias( $rowcontent['title'] );
		if( $module_config[$module_name]['alias_lower'] ) $alias = strtolower( $alias );
	}
	else
	{
		$alias = change_alias( $alias );
	}

	if( empty( $alias ) or ! preg_match( "/^([a-zA-Z0-9\_\-]+)$/", $alias ) )
	{
		if( empty( $rowcontent['alias'] ) )
		{
			$rowcontent['alias'] = 'post';
		}
	}
	else
	{
		$rowcontent['alias'] = $alias;
	}

	$rowcontent['hometext'] = $nv_Request->get_textarea( 'hometext', '', 'br', 1 );

	$rowcontent['homeimgfile'] = $nv_Request->get_title( 'homeimg', 'post', '' );
	$rowcontent['homeimgalt'] = $nv_Request->get_title( 'homeimgalt', 'post', '', 1 );
	$rowcontent['imgposition'] = $nv_Request->get_int( 'imgposition', 'post', 0 );
	if( ! array_key_exists( $rowcontent['imgposition'], $array_imgposition ) )
	{
		$rowcontent['imgposition'] = 1;
	}
	$rowcontent['bodyhtml'] = $nv_Request->get_editor( 'bodyhtml', '', NV_ALLOWED_HTML_TAGS );
	$rowcontent['rule'] = $nv_Request->get_editor( 'rule', '', NV_ALLOWED_HTML_TAGS );
	$rowcontent['content'] = $nv_Request->get_editor( 'content', '', NV_ALLOWED_HTML_TAGS );

	$rowcontent['copyright'] = ( int )$nv_Request->get_bool( 'copyright', 'post' );
	$rowcontent['inhome'] = ( int )$nv_Request->get_bool( 'inhome', 'post' );

	$_groups_post = $nv_Request->get_array( 'allowed_comm', 'post', array() );
	$rowcontent['allowed_comm'] = ! empty( $_groups_post ) ? implode( ',', nv_groups_post( array_intersect( $_groups_post, array_keys( $groups_list ) ) ) ) : '';

	$rowcontent['allowed_rating'] = ( int )$nv_Request->get_bool( 'allowed_rating', 'post' );
	$rowcontent['allowed_send'] = ( int )$nv_Request->get_bool( 'allowed_send', 'post' );
	$rowcontent['allowed_print'] = ( int )$nv_Request->get_bool( 'allowed_print', 'post' );
	$rowcontent['allowed_save'] = ( int )$nv_Request->get_bool( 'allowed_save', 'post' );
	$rowcontent['gid'] = $nv_Request->get_int( 'gid', 'post', 0 );

	$rowcontent['keywords'] = $nv_Request->get_array( 'keywords', 'post', '' );
    $rowcontent['keywords'] = implode(', ', $rowcontent['keywords'] );

	// Tu dong xac dinh keywords
	if( $rowcontent['keywords'] == '' and ! empty( $module_config[$module_name]['auto_tags'] ) )
	{
		$keywords = ( $rowcontent['hometext'] != '' ) ? $rowcontent['hometext'] : $rowcontent['bodyhtml'];
		$keywords = nv_get_keywords( $keywords, 100 );
		$keywords = explode( ',', $keywords );

		// Ưu tiên lọc từ khóa theo các từ khóa đã có trong tags thay vì đọc từ từ điển
		$keywords_return = array();
		foreach ( $keywords as $keyword_i )
		{
			$sth = $db->prepare( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id where keyword = :keyword' );
			$sth->bindParam( ':keyword', $keyword_i, PDO::PARAM_STR );
			$sth->execute();
			if( $sth->fetchColumn() )
			{
				$keywords_return[] = $keyword_i;
				if( sizeof( $keywords_return ) > 20 )
				{
					break;
				}
			}
		}

		if( sizeof( $keywords_return ) < 20 )
		{
			foreach ( $keywords as $keyword_i )
			{
				if( ! in_array( $keyword_i, $keywords_return ) )
				{
					$keywords_return[] = $keyword_i;
					if( sizeof( $keywords_return ) > 20 )
					{
						break;
					}
				}
			}
		}
		$rowcontent['keywords'] = implode( ',', $keywords_return );
	}

	if( empty( $rowcontent['title'] ) )
	{
		$error[] = $lang_module['error_title'];
	}
	elseif( empty( $rowcontent['listfid'] ) )
	{
		$error[] = $lang_module['error_fam'];
	}elseif( empty( $rowcontent['cityid'] ) )
	{
		$error[] = $lang_module['error_location_city'];
	}elseif( empty( $rowcontent['districtid'] ) )
	{
		$error[] = $lang_module['error_location_city'];
	}elseif( empty( $rowcontent['wardid'] ) )
	{
		$error[] = $lang_module['error_location_city'];
	}

	if( empty( $error ) )
	{
		$rowcontent['fid'] = in_array( $rowcontent['fid'], $fids ) ? $rowcontent['fid'] : $fids[0];
		$rowcontent['bodytext'] = nv_genealogy_get_bodytext( $rowcontent['bodyhtml'] );
		$rowcontent['ruletext'] = nv_genealogy_get_bodytext( $rowcontent['rule'] );
		$rowcontent['contenttext'] = nv_genealogy_get_bodytext( $rowcontent['content'] );

		

		

		// Xu ly anh minh hoa
		$rowcontent['homeimgthumb'] = 0;
		if( ! nv_is_url( $rowcontent['homeimgfile'] ) and is_file( NV_DOCUMENT_ROOT . $rowcontent['homeimgfile'] ) )
		{
			$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' );
			$rowcontent['homeimgfile'] = substr( $rowcontent['homeimgfile'], $lu );
			if( file_exists( NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $module_upload . '/' . $rowcontent['homeimgfile'] ) )
			{
				$rowcontent['homeimgthumb'] = 1;
			}
			else
			{
				$rowcontent['homeimgthumb'] = 2;
			}
		}
		elseif( nv_is_url( $rowcontent['homeimgfile'] ) )
		{
			$rowcontent['homeimgthumb'] = 3;
		}
		else
		{
			$rowcontent['homeimgfile'] = '';
		}

		if( $rowcontent['id'] == 0 )
		{
			if( ! defined( 'NV_IS_SPADMIN' ) and intval( $rowcontent['publtime'] ) < NV_CURRENTTIME )
			{
				$rowcontent['publtime'] = NV_CURRENTTIME;
			}
			if( $rowcontent['status'] == 1 and $rowcontent['publtime'] > NV_CURRENTTIME )
			{
				$rowcontent['status'] = 2;
			}
			$sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy
				(fid, listfid, admin_id, author, patriarch, addtime, edittime, status, publtime, exptime, archive, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, inhome, allowed_comm, allowed_rating, hitstotal, hitscm, total_rating, click_rating, cityid, districtid, wardid, years, full_name, telephone, email) VALUES
				 (' . intval( $rowcontent['fid'] ) . ',
				 :listfid,
				 ' . intval( $rowcontent['admin_id'] ) . ',
				 :author,
				 :patriarch,
				 ' . intval( $rowcontent['addtime'] ) . ',
				 ' . intval( $rowcontent['edittime'] ) . ',
				 ' . intval( $rowcontent['status'] ) . ',
				 ' . intval( $rowcontent['publtime'] ) . ',
				 ' . intval( $rowcontent['exptime'] ) . ',
				 ' . intval( $rowcontent['archive'] ) . ',
				 :title,
				 :alias,
				 :hometext,
				 :homeimgfile,
				 :homeimgalt,
				 :homeimgthumb,
				 ' . intval( $rowcontent['inhome'] ) . ',
				 :allowed_comm,
				 ' . intval( $rowcontent['allowed_rating'] ) . ',
				 ' . intval( $rowcontent['hitstotal'] ) . ',
				 ' . intval( $rowcontent['hitscm'] ) . ',
				 ' . intval( $rowcontent['total_rating'] ) . ',
				 ' . intval( $rowcontent['click_rating'] ) . ',
				 ' . intval( $rowcontent['cityid'] ) . ',
				 ' . intval( $rowcontent['districtid'] ) . ',
				 ' . intval( $rowcontent['wardid'] ) . ',
				 :years,
				 :full_name,
				 :telephone,
				 :email
				 
				 )';

			$data_insert = array();
			$data_insert['listfid'] = $rowcontent['listfid'];
			$data_insert['author'] = $rowcontent['author'];
			$data_insert['patriarch'] = $rowcontent['patriarch'];
			$data_insert['title'] = $rowcontent['title'];
			$data_insert['alias'] = $rowcontent['alias'];
			$data_insert['hometext'] = $rowcontent['hometext'];
			$data_insert['homeimgfile'] = $rowcontent['homeimgfile'];
			$data_insert['homeimgalt'] = $rowcontent['homeimgalt'];
			$data_insert['homeimgthumb'] = $rowcontent['homeimgthumb'];
			$data_insert['allowed_comm'] = $rowcontent['allowed_comm'];
			$data_insert['years'] = $rowcontent['years'];
			$data_insert['full_name'] = $rowcontent['full_name'];
			$data_insert['telephone'] = $rowcontent['telephone'];
			$data_insert['email'] = $rowcontent['email'];
			$rowcontent['id'] = $db->insert_id( $sql, 'id', $data_insert );
			if( $rowcontent['id'] > 0 )
			{
				
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['genealogy_add'], $rowcontent['title'], $admin_info['userid'] );
				$ct_query = array();
				$tbhtml = NV_PREFIXLANG . '_' . $module_data . '_bodyhtml_' . ceil( $rowcontent['id'] / 2000 );
				$db->query( "CREATE TABLE IF NOT EXISTS " . $tbhtml . " (id int(11) unsigned NOT NULL, bodyhtml longtext NOT NULL, rule longtext NOT NULL, content longtext NOT NULL, imgposition tinyint(1) NOT NULL default '1', copyright tinyint(1) NOT NULL default '0', allowed_send tinyint(1) NOT NULL default '0', allowed_print tinyint(1) NOT NULL default '0', allowed_save tinyint(1) NOT NULL default '0', gid mediumint(9) NOT NULL DEFAULT '0', PRIMARY KEY (id)) ENGINE=MyISAM" );

				$stmt = $db->prepare( 'INSERT INTO ' . $tbhtml . ' VALUES
					(' . $rowcontent['id'] . ',
					 :bodyhtml,
					 :rule,
					 :content,
					 ' . $rowcontent['imgposition'] . ',
					 ' . $rowcontent['copyright'] . ',
					 ' . $rowcontent['allowed_send'] . ',
					 ' . $rowcontent['allowed_print'] . ',
					 ' . $rowcontent['allowed_save'] . ',
					 ' . $rowcontent['gid'] . '
					 )' );
				$stmt->bindParam( ':bodyhtml', $rowcontent['bodyhtml'], PDO::PARAM_STR, strlen( $rowcontent['bodyhtml'] ) );
				$stmt->bindParam( ':rule', $rowcontent['rule'], PDO::PARAM_STR, strlen( $rowcontent['rule'] ) );
				$stmt->bindParam( ':content', $rowcontent['content'], PDO::PARAM_STR, strlen( $rowcontent['content'] ) );
				$ct_query[] = ( int )$stmt->execute();
		
				foreach( $fids as $fid )
				{
					$ct_query[] = ( int )$db->exec( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $fid . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy WHERE id=' . $rowcontent['id'] );
				}

				$stmt = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_bodytext VALUES (' . $rowcontent['id'] . ', :bodytext, :ruletext, :contenttext )' );
				$stmt->bindParam( ':bodytext', $rowcontent['bodytext'], PDO::PARAM_STR, strlen( $rowcontent['bodytext'] ) );
				$stmt->bindParam( ':ruletext', $rowcontent['ruletext'], PDO::PARAM_STR, strlen( $rowcontent['ruletext'] ) );
				$stmt->bindParam( ':contenttext', $rowcontent['contenttext'], PDO::PARAM_STR, strlen( $rowcontent['contenttext'] ) );
				$ct_query[] = ( int )$stmt->execute();
		
				if( array_sum( $ct_query ) != sizeof( $ct_query ) )
				{
					$error[] = $lang_module['errorsave'];
				}
				unset( $ct_query );
			}
			else
			{
				$error[] = $lang_module['errorsave'];
			}
		}
		else
		{
			$rowcontent_old = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy where id=' . $rowcontent['id'] )->fetch();
			if( $rowcontent_old['status'] == 1 )
			{
				$rowcontent['status'] = 1;
			}
			if( ! defined( 'NV_IS_SPADMIN' ) and intval( $rowcontent['publtime'] ) < intval( $rowcontent_old['addtime'] ) )
			{
				$rowcontent['publtime'] = $rowcontent_old['addtime'];
			}

			if( $rowcontent['status'] == 1 and $rowcontent['publtime'] > NV_CURRENTTIME )
			{
				$rowcontent['status'] = 2;
			}
			$sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy SET
					 fid=' . intval( $rowcontent['fid'] ) . ',
					 listfid=:listfid,
					 author=:author,
					 patriarch=:patriarch,
					 status=' . intval( $rowcontent['status'] ) . ',
					 publtime=' . intval( $rowcontent['publtime'] ) . ',
					 exptime=' . intval( $rowcontent['exptime'] ) . ',
					 archive=' . intval( $rowcontent['archive'] ) . ',
					 title=:title,
					 alias=:alias,
					 hometext=:hometext,
					 homeimgfile=:homeimgfile,
					 homeimgalt=:homeimgalt,
					 homeimgthumb=:homeimgthumb,
					 inhome=' . intval( $rowcontent['inhome'] ) . ',
					 allowed_comm=:allowed_comm,
					 allowed_rating=' . intval( $rowcontent['allowed_rating'] ) . ',
					 cityid=' . intval( $rowcontent['cityid'] ) . ',
					 districtid=' . intval( $rowcontent['districtid'] ) . ',
					 wardid=' . intval( $rowcontent['wardid'] ) . ',
					 years=:years,
					 full_name=:full_name,
					 telephone=:telephone,
					 email=:email,
					 edittime=' . NV_CURRENTTIME . '
				WHERE id =' . $rowcontent['id'] );

			$sth->bindParam( ':listfid', $rowcontent['listfid'], PDO::PARAM_STR );
			$sth->bindParam( ':author', $rowcontent['author'], PDO::PARAM_STR );
			$sth->bindParam( ':patriarch', $rowcontent['patriarch'], PDO::PARAM_STR );
			$sth->bindParam( ':title', $rowcontent['title'], PDO::PARAM_STR );
			$sth->bindParam( ':alias', $rowcontent['alias'], PDO::PARAM_STR );
			$sth->bindParam( ':hometext', $rowcontent['hometext'], PDO::PARAM_STR, strlen( $rowcontent['hometext'] ) );
			$sth->bindParam( ':homeimgfile', $rowcontent['homeimgfile'], PDO::PARAM_STR );
			$sth->bindParam( ':homeimgalt', $rowcontent['homeimgalt'], PDO::PARAM_STR );
			$sth->bindParam( ':homeimgthumb', $rowcontent['homeimgthumb'], PDO::PARAM_STR );
			$sth->bindParam( ':allowed_comm', $rowcontent['allowed_comm'], PDO::PARAM_STR );
			$sth->bindParam( ':years', $rowcontent['years'], PDO::PARAM_STR );
			$sth->bindParam( ':full_name', $rowcontent['full_name'], PDO::PARAM_STR );
			$sth->bindParam( ':telephone', $rowcontent['telephone'], PDO::PARAM_STR );
			$sth->bindParam( ':email', $rowcontent['email'], PDO::PARAM_STR );
			if( $sth->execute() )
			{
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['genealogy_edit'], $rowcontent['title'], $admin_info['userid'] );

				$ct_query = array();
				$sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_bodyhtml_' . ceil( $rowcontent['id'] / 2000 ) . ' SET
					bodyhtml=:bodyhtml,
					rule=:rule,
					content=:content,
					imgposition=' . intval( $rowcontent['imgposition'] ) . ',
					copyright=' . intval( $rowcontent['copyright'] ) . ',
					allowed_send=' . intval( $rowcontent['allowed_send'] ) . ',
					allowed_print=' . intval( $rowcontent['allowed_print'] ) . ',
					allowed_save=' . intval( $rowcontent['allowed_save'] ) . ',
					gid=' . intval( $rowcontent['gid'] ) . '
				WHERE id =' . $rowcontent['id'] );

				$sth->bindParam( ':bodyhtml', $rowcontent['bodyhtml'], PDO::PARAM_STR, strlen( $rowcontent['bodyhtml'] ) );
				$sth->bindParam( ':rule', $rowcontent['rule'], PDO::PARAM_STR, strlen( $rowcontent['rule'] ) );
				$sth->bindParam( ':content', $rowcontent['content'], PDO::PARAM_STR, strlen( $rowcontent['content'] ) );

				$ct_query[] = ( int )$sth->execute();

				$array_fam_old = explode( ',', $rowcontent_old['listfid'] );
				$array_fam_new = explode( ',', $rowcontent['listfid'] );

				$array_fam_diff = array_diff( $array_fam_old, $array_fam_new );
				foreach( $array_fam_diff as $fid )
				{
					$ct_query[] = $db->exec( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $fid . ' WHERE id = ' . $rowcontent['id'] );
				}
				foreach( $array_fam_new as $fid )
				{
					$db->exec( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $fid . ' WHERE id = ' . $rowcontent['id'] );
					$ct_query[] = $db->exec( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_' . $fid . ' SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy WHERE id=' . $rowcontent['id'] );
				}

				$sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_bodytext SET bodytext=:bodytext, ruletext=:ruletext, contenttext=:contenttext WHERE id =' . $rowcontent['id'] );
				$sth->bindParam( ':bodytext', $rowcontent['bodytext'], PDO::PARAM_STR, strlen( $rowcontent['bodytext'] ) );
				$sth->bindParam( ':ruletext', $rowcontent['ruletext'], PDO::PARAM_STR, strlen( $rowcontent['ruletext'] ) );
				$sth->bindParam( ':contenttext', $rowcontent['contenttext'], PDO::PARAM_STR, strlen( $rowcontent['contenttext'] ) );
				$ct_query[] = ( int )$sth->execute();

				if( array_sum( $ct_query ) != sizeof( $ct_query ) )
				{
					$error[] = $lang_module['errorsave'];
				}
			}
			else
			{
				$error[] = $lang_module['errorsave'];
			}
		}

		nv_set_status_module();
		if( empty( $error ) )
		{

			if( $rowcontent['keywords'] != $rowcontent['keywords_old'] )
			{
				$keywords = explode( ',', $rowcontent['keywords'] );
				$keywords = array_map( 'strip_punctuation', $keywords );
				$keywords = array_map( 'trim', $keywords );
				$keywords = array_diff( $keywords, array( '' ) );
				$keywords = array_unique( $keywords );

				foreach( $keywords as $keyword )
				{
					if( ! in_array( $keyword, $array_keywords_old ) )
					{
						$alias_i = ( $module_config[$module_name]['tags_alias'] ) ? change_alias( $keyword ) : str_replace( ' ', '-', $keyword );
						$alias_i = nv_strtolower( $alias_i );
						$sth = $db->prepare( 'SELECT tid, alias, description, keywords FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags where alias= :alias OR FIND_IN_SET(:keyword, keywords)>0' );
						$sth->bindParam( ':alias', $alias_i, PDO::PARAM_STR );
						$sth->bindParam( ':keyword', $keyword, PDO::PARAM_STR );
						$sth->execute();

						list( $tid, $alias, $keywords_i ) = $sth->fetch( 3 );
						if( empty( $tid ) )
						{
							$array_insert = array();
							$array_insert['alias'] = $alias_i;
							$array_insert['keyword'] = $keyword;

							$tid = $db->insert_id( "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_tags (numnews, alias, description, image, keywords) VALUES (1, :alias, '', '', :keyword)", "tid", $array_insert );
						}
						else
						{
							if( $alias != $alias_i )
							{
								if( ! empty( $keywords_i ) )
								{
									$keyword_arr = explode( ',', $keywords_i );
									$keyword_arr[] = $keyword;
									$keywords_i2 = implode( ',', array_unique( $keyword_arr ) );
								}
								else
								{
									$keywords_i2 = $keyword;
								}
								if( $keywords_i != $keywords_i2 )
								{
									$sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET keywords= :keywords WHERE tid =' . $tid );
									$sth->bindParam( ':keywords', $keywords_i2, PDO::PARAM_STR );
									$sth->execute();
								}
							}
							$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET numnews = numnews+1 WHERE tid = ' . $tid );
						}

						// insert keyword for table _tags_id
						try
						{
							$sth = $db->prepare( 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id (id, tid, keyword) VALUES (' . $rowcontent['id'] . ', ' . intval( $tid ) . ', :keyword)' );
							$sth->bindParam( ':keyword', $keyword, PDO::PARAM_STR );
							$sth->execute();
						}
						catch( PDOException $e )
						{
							$sth = $db->prepare( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id SET keyword = :keyword WHERE id = ' . $rowcontent['id'] . ' AND tid=' . intval( $tid ) );
							$sth->bindParam( ':keyword', $keyword, PDO::PARAM_STR );
							$sth->execute();
						}
						unset( $array_keywords_old[$tid] );
					}
				}

				foreach( $array_keywords_old as $tid => $keyword )
				{
					if( ! in_array( $keyword, $keywords ) )
					{
						$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET numnews = numnews-1 WHERE tid = ' . $tid );
						$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id = ' . $rowcontent['id'] . ' AND tid=' . $tid );
					}
				}
			}

			if( isset( $module_config['seotools']['prcservice'] ) and ! empty( $module_config['seotools']['prcservice'] ) and $rowcontent['status'] == 1 and $rowcontent['publtime'] < NV_CURRENTTIME + 1 and ( $rowcontent['exptime'] == 0 or $rowcontent['exptime'] > NV_CURRENTTIME + 1 ) )
			{
				Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=rpc&id=' . $rowcontent['id'] . '&rand=' . nv_genpass() );
				die();
			}
			else
			{
				$url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
				$msg1 = $lang_module['content_saveok'];
				$msg2 = $lang_module['content_main'] . ' ' . $module_info['custom_title'];
				redriect( $msg1, $msg2, $url, $module_data . '_bodyhtml' );
			}
		}
	}
	else
	{
		$url = 'javascript: history.go(-1)';
		$msg1 = implode( '<br />', $error );
		$msg2 = $lang_module['content_back'];
		redriect( $msg1, $msg2, $url, $module_data . '_bodyhtml', 'back' );
	}
}

$rowcontent['hometext'] = nv_htmlspecialchars( nv_br2nl( $rowcontent['hometext'] ) );
$rowcontent['bodyhtml'] = htmlspecialchars( nv_editor_br2nl( $rowcontent['bodyhtml'] ) );
$rowcontent['rule'] = htmlspecialchars( nv_editor_br2nl( $rowcontent['rule'] ) );
$rowcontent['content'] = htmlspecialchars( nv_editor_br2nl( $rowcontent['content'] ) );

if( ! empty( $rowcontent['homeimgfile'] ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $rowcontent['homeimgfile'] ) )
{
	$rowcontent['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $rowcontent['homeimgfile'];
}

$array_fid_in_row = explode( ',', $rowcontent['listfid'] );


$tdate = date( 'H|i', $rowcontent['publtime'] );
$publ_date = date( 'd/m/Y', $rowcontent['publtime'] );
list( $phour, $pmin ) = explode( '|', $tdate );
if( $rowcontent['exptime'] == 0 )
{
	$emin = $ehour = 0;
	$exp_date = '';
}
else
{
	$exp_date = date( 'd/m/Y', $rowcontent['exptime'] );
	$tdate = date( 'H|i', $rowcontent['exptime'] );
	list( $ehour, $emin ) = explode( '|', $tdate );
}

if( $rowcontent['status'] == 1 and $rowcontent['publtime'] > NV_CURRENTTIME )
{
	$array_fam_check_content = $array_fam_pub_content;
}
elseif( $rowcontent['status'] == 1 )
{
	$array_fam_check_content = $array_fam_edit_content;
}
else
{
	$array_fam_check_content = $array_fam_add_content;
}

if( empty( $array_fam_check_content ) )
{
	$redirect = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=family';

	$contents = '<p class="note_fam">' . $lang_module['note_fam'] . '</p>';
	$contents .= "<meta http-equiv=\"refresh\" content=\"3;URL=" . $redirect . "\" />";
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	die();
}
$contents = '';

$lang_global['title_suggest_max'] = sprintf( $lang_global['length_suggest_max'], 65 );
$lang_global['description_suggest_max'] = sprintf( $lang_global['length_suggest_max'], 160 );

$xtpl = new XTemplate( 'genealogy.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'rowcontent', $rowcontent );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );

$xtpl->assign( 'module_name', $module_name );

foreach( $global_array_fam as $fid_i => $array_value )
{
	if( defined( 'NV_IS_ADMIN_MODULE' ) )
	{
		$check_show = 1;
	}
	else
	{
		$array_fam = GetfidInParent( $fid_i );
		$check_show = array_intersect( $array_fam, $array_fam_check_content );
	}
	if( ! empty( $check_show ) )
	{
		$space = intval( $array_value['lev'] ) * 30;
		$fiddisplay = ( sizeof( $array_fid_in_row ) > 1 and ( in_array( $fid_i, $array_fid_in_row ) ) ) ? '' : ' display: none;';
		$temp = array(
			'fid' => $fid_i,
			'space' => $space,
			'title' => $array_value['title'],
			'disabled' => ( ! in_array( $fid_i, $array_fam_check_content ) ) ? ' disabled="disabled"' : '',
			'selected' => ( in_array( $fid_i, $array_fid_in_row ) ) ? ' selected="selected"' : '',
			'fidchecked' => ( $fid_i == $rowcontent['fid'] ) ? ' checked="checked"' : '',
			'fiddisplay' => $fiddisplay );
		$xtpl->assign( 'FAMS', $temp );
		$xtpl->parse( 'main.fid' );
	}
}

// list city
foreach( $global_array_location_city as $city_i =>  $rowscity )
{
	$rowscity['selected'] = ($city_i == $rowcontent['cityid']) ? ' selected="selected"' : '';

	$xtpl->assign( 'CITY', $rowscity );
	$xtpl->parse( 'main.city' );
}

// Copyright
$checkcop = ( $rowcontent['copyright'] ) ? ' checked="checked"' : '';
$xtpl->assign( 'checkcop', $checkcop );


// position images
while( list( $id_imgposition, $title_imgposition ) = each( $array_imgposition ) )
{
	$sl = ( $id_imgposition == $rowcontent['imgposition'] ) ? ' selected="selected"' : '';
	$xtpl->assign( 'id_imgposition', $id_imgposition );
	$xtpl->assign( 'title_imgposition', $title_imgposition );
	$xtpl->assign( 'posl', $sl );
	$xtpl->parse( 'main.looppos' );
}

// time update
$xtpl->assign( 'publ_date', $publ_date );
$select = '';
for( $i = 0; $i <= 23; ++$i )
{
	$select .= "<option value=\"" . $i . "\"" . ( ( $i == $phour ) ? ' selected="selected"' : '' ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'phour', $select );
$select = '';
for( $i = 0; $i < 60; ++$i )
{
	$select .= "<option value=\"" . $i . "\"" . ( ( $i == $pmin ) ? ' selected="selected"' : '' ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'pmin', $select );

// time exp
$xtpl->assign( 'exp_date', $exp_date );
$select = '';
for( $i = 0; $i <= 23; ++$i )
{
	$select .= "<option value=\"" . $i . "\"" . ( ( $i == $ehour ) ? ' selected="selected"' : '' ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'ehour', $select );
$select = '';
for( $i = 0; $i < 60; ++$i )
{
	$select .= "<option value=\"" . $i . "\"" . ( ( $i == $emin ) ? ' selected="selected"' : '' ) . ">" . str_pad( $i, 2, "0", STR_PAD_LEFT ) . "</option>\n";
}
$xtpl->assign( 'emin', $select );

// allowed comm
$allowed_comm = explode( ',', $rowcontent['allowed_comm'] );
foreach( $groups_list as $_group_id => $_title )
{
	$xtpl->assign( 'ALLOWED_COMM', array(
		'value' => $_group_id,
		'checked' => in_array( $_group_id, $allowed_comm ) ? ' checked="checked"' : '',
		'title' => $_title
	) );
	$xtpl->parse( 'main.allowed_comm' );
}
if( $module_config[$module_name]['allowed_comm'] != '-1' )
{
	$xtpl->parse( 'main.content_note_comm' );
}


if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
{
	$edits = nv_aleditor( 'bodyhtml', '100%', '400px', $rowcontent['bodyhtml'], '', $uploads_dir_user, $currentpath );
	$edit_rule = nv_aleditor( 'rule', '100%', '400px', $rowcontent['rule'], '', $uploads_dir_user, $currentpath );
	$edit_content = nv_aleditor( 'content', '100%', '400px', $rowcontent['content'], '', $uploads_dir_user, $currentpath );
}
else
{
	$edits = "<textarea style=\"width: 100%\" name=\"bodyhtml\" id=\"bodyhtml\" cols=\"20\" rows=\"15\">" . $rowcontent['bodyhtml'] . "</textarea>";
	$edit_rule = "<textarea style=\"width: 100%\" name=\"rule\" id=\"rule\" cols=\"20\" rows=\"15\">" . $rowcontent['rule'] . "</textarea>";
	$edit_content = "<textarea style=\"width: 100%\" name=\"content\" id=\"content\" cols=\"20\" rows=\"15\">" . $rowcontent['content'] . "</textarea>";
}

$shtm = '';

if( ! empty( $rowcontent['keywords'] ) )
{
	$keywords_array = explode( ',', $rowcontent['keywords'] );
	foreach( $keywords_array as $keywords )
	{
		$xtpl->assign( 'KEYWORDS', $keywords );
		$xtpl->parse( 'main.keywords' );
	}
}
$archive_checked = ( $rowcontent['archive'] ) ? ' checked="checked"' : '';
$xtpl->assign( 'archive_checked', $archive_checked );
$inhome_checked = ( $rowcontent['inhome'] ) ? ' checked="checked"' : '';
$xtpl->assign( 'inhome_checked', $inhome_checked );
$allowed_rating_checked = ( $rowcontent['allowed_rating'] ) ? ' checked="checked"' : '';
$xtpl->assign( 'allowed_rating_checked', $allowed_rating_checked );
$allowed_send_checked = ( $rowcontent['allowed_send'] ) ? ' checked="checked"' : '';
$xtpl->assign( 'allowed_send_checked', $allowed_send_checked );
$allowed_print_checked = ( $rowcontent['allowed_print'] ) ? ' checked="checked"' : '';
$xtpl->assign( 'allowed_print_checked', $allowed_print_checked );
$allowed_save_checked = ( $rowcontent['allowed_save'] ) ? ' checked="checked"' : '';
$xtpl->assign( 'allowed_save_checked', $allowed_save_checked );

$xtpl->assign( 'edit_bodytext', $edits );
$xtpl->assign( 'edit_rule', $edit_rule );
$xtpl->assign( 'edit_content', $edit_content );


if( ! empty( $error ) )
{
	$xtpl->assign( 'error', implode( '<br />', $error ) );
	$xtpl->parse( 'main.error' );
}

if( defined( 'NV_IS_ADMIN_MODULE' ) || ! empty( $array_pub_content ) ) //toan quyen module
{
	if( $rowcontent['status'] == 1 and $rowcontent['id'] > 0 )
	{
		$xtpl->parse( 'main.status' );
	}
	else
	{
		$xtpl->parse( 'main.status0' );
	}
}
else
{
	//gioi hoan quyen
	if( $rowcontent['status'] == 1 and $rowcontent['id'] > 0 )
	{
		$xtpl->parse( 'main.status' );
	}
	elseif( ! empty( $array_fam_pub_content ) ) // neu co quyen dang bai
	{
		$xtpl->parse( 'main.status0' );
	}
	else
	{
		if( ! empty( $array_censor_content ) ) // neu co quyen duyet bai thi
		{
			$xtpl->parse( 'main.status1.status0' );
		}
		$xtpl->parse( 'main.status1' );
	}
}
if( empty( $rowcontent['alias'] ) )
{
	$xtpl->parse( 'main.getalias' );
}
$xtpl->assign( 'UPLOADS_DIR_USER', $uploads_dir_user );
$xtpl->assign( 'UPLOAD_CURRENT', $currentpath );

$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_googleplus ORDER BY weight ASC';
$_array = $db->query( $sql )->fetchAll();
if( sizeof( $_array ) )
{
	$array_googleplus = array();
	$array_googleplus[] = array( 'gid' => -1, 'title' => $lang_module['googleplus_1'] );
	$array_googleplus[] = array( 'gid' => 0, 'title' => $lang_module['googleplus_0'] );
	foreach( $_array as $row )
	{
		$array_googleplus[] = $row;
	}
	foreach( $array_googleplus as $grow )
	{
		$grow['selected'] = ( $rowcontent['gid'] == $grow['gid'] ) ? ' selected="selected"' : '';
		$xtpl->assign( 'GOOGLEPLUS', $grow );
		$xtpl->parse( 'main.googleplus.gid' );
	}
	$xtpl->parse( 'main.googleplus' );
}

if( $module_config[$module_name]['auto_tags'] )
{
	$xtpl->parse( 'main.auto_tags' );
}

$xtpl->parse( 'main' );
$contents .= $xtpl->text( 'main' );

if( $rowcontent['id'] > 0 )
{
	$op = '';
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';