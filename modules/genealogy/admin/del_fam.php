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
$contents = "NO_" . $fid;

list( $fid, $parentid, $title ) = $db->query( "SELECT fid, parentid, title FROM " . NV_PREFIXLANG . "_" . $module_data . "_family WHERE fid=" . intval( $fid ) )->fetch( 3 );
if( $fid > 0 )
{
	if( ( defined( 'NV_IS_ADMIN_MODULE' ) or ( $parentid > 0 and isset( $array_fam_admin[$admin_id][$parentid] ) and $array_fam_admin[$admin_id][$parentid]['admin'] == 1 ) ) )
	{
		$delallcheckss = $nv_Request->get_string( 'delallcheckss', 'post', '' );
		$check_parentid = $db->query( "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_family WHERE parentid = '" . $fid . "'" )->fetchColumn();
		if( intval( $check_parentid ) > 0 )
		{
			$contents = "ERR_FAM_" . sprintf( $lang_module['delfam_msg_fam'], $check_parentid );
		}
		else
		{
			$check_rows = $db->query( "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_" . $fid )->fetchColumn();
			if( intval( $check_rows ) > 0 )
			{
				if( $delallcheckss == md5( $fid . session_id() . $global_config['sitekey'] ) )
				{
					$delfamandrows = $nv_Request->get_string( 'delfamandrows', 'post', '' );
					$movefam = $nv_Request->get_string( 'movefam', 'post', '' );
					$fidnews = $nv_Request->get_int( 'fidnews', 'post', 0 );
					if( empty( $delfamandrows ) and empty( $movefam ) )
					{
						$sql = "SELECT fid, title, lev FROM " . NV_PREFIXLANG . "_" . $module_data . "_family WHERE fid !='" . $fid . "' ORDER BY sort ASC";
						$result = $db->query( $sql );
						$array_fam_list = array();
						$array_fam_list[0] = "&nbsp;";
						while( list( $fid_i, $title_i, $lev_i ) = $result->fetch( 3 ) )
						{
							$xtitle_i = '';
							if( $lev_i > 0 )
							{
								$xtitle_i .= "&nbsp;&nbsp;&nbsp;|";
								for( $i = 1; $i <= $lev_i; ++$i )
								{
									$xtitle_i .= "---";
								}
								$xtitle_i .= ">&nbsp;";
							}
							$xtitle_i .= $title_i;
							$array_fam_list[$fid_i] = $xtitle_i;
						}

						$xtpl = new XTemplate( 'del_fam.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
						$xtpl->assign( 'LANG', $lang_module );
						$xtpl->assign( 'GLANG', $lang_global );
						$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
						$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
						$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
						$xtpl->assign( 'MODULE_NAME', $module_name );
						$xtpl->assign( 'OP', $op );
						$xtpl->assign( 'FID', $fid );
						$xtpl->assign( 'DELALLCHECKSS', $delallcheckss );

						$xtpl->assign( 'TITLE', sprintf( $lang_module['delfam_msg_rows_select'], $title, $check_rows ) );

						while( list( $fid_i, $title_i ) = each( $array_fam_list ) )
						{
							$xtpl->assign( 'FIDNEWS', array( 'key' => $fid_i, 'title' => $title_i ) );
							$xtpl->parse( 'main.fidnews' );
						}

						$xtpl->parse( 'main' );
						$contents = $xtpl->text( 'main' );
					}
					elseif( ! empty( $delfamandrows ) )
					{
						nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['delfamandrows'], $title, $admin_info['userid'] );

						$sql = $db->query( "SELECT id, fid, listfid FROM " . NV_PREFIXLANG . "_" . $module_data . "_" . $fid );
						while( $row = $sql->fetch() )
						{
							if( $row['fid'] == $row['listfid'] )
							{
								nv_del_content_module( $row['id'] );
							}
							else
							{
								$arr_fid_old = explode( ',', $row['listfid'] );
								$arr_fid_i = array( $fid );
								$arr_fid_news = array_diff( $arr_fid_old, $arr_fid_i );
								if( $fid == $row['fid'] )
								{
									$row['fid'] = $arr_fid_news[0];
								}
								foreach( $arr_fid_news as $fid_i )
								{
									if( isset($global_array_fam[$fid_i] ) )
									{
										$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_" . $fid_i . " SET fid=" . $row['fid'] . ", listfid = '" . implode( ',', $arr_fid_news ) . "' WHERE id =" . $row['id'] );
									}
								}
								$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_genealogy SET fid=" . $row['fid'] . ", listfid = '" . implode( ',', $arr_fid_news ) . "' WHERE id =" . $row['id'] );
							}
						}
						$db->query( "DROP TABLE " . NV_PREFIXLANG . "_" . $module_data . "_" . $fid );
						$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_family WHERE fid=" . $fid );
						$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_admins WHERE fid=" . $fid );

						nv_fix_fam_order();
						nv_del_moduleCache( $module_name );
						Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=family&parentid=' . $parentid );
						die();
					}
					elseif( ! empty( $movefam ) and $fidnews > 0 and $fidnews != $fid )
					{
						list( $fidnews, $newstitle ) = $db->query( "SELECT fid, title FROM " . NV_PREFIXLANG . "_" . $module_data . "_family WHERE fid =" . $fidnews )->fetch( 3 );
						if( $fidnews > 0 )
						{
							nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['move'], $title . ' --> ' . $newstitle, $admin_info['userid'] );

							$sql = $db->query( "SELECT id, fid, listfid FROM " . NV_PREFIXLANG . "_" . $module_data . "_" . $fid );
							while( $row = $sql->fetch() )
							{
								$arr_fid_old = explode( ',', $row['listfid'] );
								$arr_fid_i = array( $fid );
								$arr_fid_news = array_diff( $arr_fid_old, $arr_fid_i );
								if( ! in_array( $fidnews, $arr_fid_news ) )
								{
									try
									{
										$db->query( "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_" . $fidnews . " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_genealogy WHERE id=" . $row['id'] );
										$arr_fid_news[] = $fidnews;
									}
									catch( PDOException $e )
									{
										trigger_error( $e->getMessage() );
									}
								}
								if( $fid == $row['fid'] )
								{
									$row['fid'] = $fidnews;
								}
								foreach( $arr_fid_news as $fid_i )
								{
									if( isset($global_array_fam[$fid_i] ) )
									{
										$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_" . $fid_i . " SET fid=" . $row['fid'] . ", listfid = '" . implode( ',', $arr_fid_news ) . "' WHERE id =" . $row['id'] );
									}
								}
								$db->query( "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_genealogy SET fid=" . $row['fid'] . ", listfid = '" . implode( ',', $arr_fid_news ) . "' WHERE id =" . $row['id'] );
							}
							$db->query( "DROP TABLE " . NV_PREFIXLANG . "_" . $module_data . "_" . $fid );
							$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_family WHERE fid=" . $fid );
							$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_admins WHERE fid=" . $fid );

							nv_fix_fam_order();
							nv_del_moduleCache( $module_name );
							Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=family&parentid=' . $parentid );
							die();
						}
					}
				}
				else
				{
					$contents = "ERR_ROWS_" . $fid . "_" . md5( $fid . session_id() . $global_config['sitekey'] ) . "_" . sprintf( $lang_module['delfam_msg_rows'], $check_rows );
				}
			}
		}
		if( $contents == "NO_" . $fid )
		{
			if( $delallcheckss == md5( $fid . session_id() ) )
			{
				$sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_family WHERE fid=" . $fid;
				if( $db->exec( $sql ) )
				{
					nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['delfamandrows'], $title, $admin_info['userid'] );
					nv_fix_fam_order();
					$db->query( "DROP TABLE " . NV_PREFIXLANG . "_" . $module_data . "_" . $fid );
					$contents = "OK_" . $parentid;
				}
				$db->query( "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_admins WHERE fid=" . $fid );
				nv_del_moduleCache( $module_name );
			}
			else
			{
				$contents = "CONFIRM_" . $fid . "_" . md5( $fid . session_id() );
			}
		}
	}
	else
	{
		$contents = "ERR_FAM_" . $lang_module['delfam_msg_fam_permissions'];
	}
}

if( defined( 'NV_IS_AJAX' ) )
{
	include NV_ROOTDIR . '/includes/header.php';
	echo $contents;
	include NV_ROOTDIR . '/includes/footer.php';
}
else
{
	Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=family' );
	die();
}