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
if( nv_user_in_groups( $global_array_fam[$fid]['groups_view'] ) )
{

	$query = $db->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $fid . ' WHERE alias = "' . $alias_url.'"' );
	
	$news_contents = $query->fetch();
	if( $news_contents['id'] > 0 AND empty($array_op[2]) )
	{
		$sqllistuser = $db->sqlreset()->query( 'SELECT max(lev) as maxlev FROM ' . NV_PREFIXLANG . '_'. $module_data .' WHERE gid = "' . $news_contents['id'] . '" ORDER BY weight ASC' )->fetch();
		$news_contents['maxlev']=$sqllistuser['maxlev'];
		$show_no_image = $module_config[$module_name]['show_no_image'];

		if( defined( 'NV_IS_MODADMIN' ) or ( $news_contents['status'] == 1 and $news_contents['publtime'] < NV_CURRENTTIME and ( $news_contents['exptime'] == 0 or $news_contents['exptime'] > NV_CURRENTTIME ) ) )
		{
			if( defined( 'NV_IS_ADMIN' )){
				define( 'NV_IS_GENEALOGY_MANAGER', true);
			}elseif($user_info['userid'] == $news_contents['admin_id']){
				define( 'NV_IS_GENEALOGY_MANAGER', true);
			}
			$time_set = $nv_Request->get_int( $module_data . '_' . $op . '_' . $news_contents['id'], 'session' );
			if( empty( $time_set ) )
			{
				$nv_Request->set_Session( $module_data . '_' . $op . '_' . $news_contents['id'], NV_CURRENTTIME );
				$query = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy SET hitstotal=hitstotal+1 WHERE alias = "' . $alias_url.'"';
				$db->query( $query );

				$array_fid = explode( ',', $news_contents['listfid'] );
				foreach( $array_fid as $fid_i )
				{
					$query = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $fid_i . ' SET hitstotal=hitstotal+1 WHERE alias = "' . $alias_url.'"';
					$db->query( $query );
				}
			}
			$news_contents['showhometext'] = $module_config[$module_name]['showhometext'];
			if( ! empty( $news_contents['homeimgfile'] ) )
			{
				$src = $alt = $note = '';
				$width = $height = 0;
				if( $news_contents['homeimgthumb'] == 1 and $news_contents['imgposition'] == 1 )
				{
					$src = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_upload . '/' . $news_contents['homeimgfile'];
					$news_contents['homeimgfile'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $news_contents['homeimgfile'];
					$width = $module_config[$module_name]['homewidth'];
				}
				elseif( $news_contents['homeimgthumb'] == 3 )
				{
					$src = $news_contents['homeimgfile'];
					$width = ( $news_contents['imgposition'] == 1 ) ? $module_config[$module_name]['homewidth'] : $module_config[$module_name]['imagefull'];
				}
				elseif( file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $news_contents['homeimgfile'] ) )
				{
					$src = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $news_contents['homeimgfile'];
					if( $news_contents['imgposition'] == 1 )
					{
						$width = $module_config[$module_name]['homewidth'];
					}
					else
					{
						$imagesize = @getimagesize( NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $news_contents['homeimgfile'] );
						if( $imagesize[0] > 0 and $imagesize[0] > $module_config[$module_name]['imagefull'] )
						{
							$width = $module_config[$module_name]['imagefull'];
						}
						else
						{
							$width = $imagesize[0];
						}
					}
					$news_contents['homeimgfile'] = $src;
				}

				if( ! empty( $src ) )
				{
					$meta_property['og:image'] = ( preg_match( '/^(http|https|ftp|gopher)\:\/\//', $src ) ) ? $src : NV_MY_DOMAIN . $src;

					if( $news_contents['imgposition'] > 0 )
					{
						$news_contents['image'] = array(
							'src' => $src,
							'width' => $width,
							'alt' => ( empty( $news_contents['homeimgalt'] ) ) ? $news_contents['title'] : $news_contents['homeimgalt'],
							'note' => $news_contents['homeimgalt'],
							'position' => $news_contents['imgposition']
						);
					}
				}
				elseif( !empty( $show_no_image ) )
				{
					$meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . $show_no_image;
				}
			}
			elseif( ! empty( $show_no_image ) )
			{
				$meta_property['og:image'] = NV_MY_DOMAIN . NV_BASE_SITEURL . $show_no_image;
			}
			if( $alias_url == $news_contents['alias'] )
			{
				$publtime = intval( $news_contents['publtime'] );
			}

			$meta_property['og:type'] = 'article';
			$meta_property['article:published_time'] = date( 'Y-m-dTH:i:s', $news_contents['publtime'] );
			$meta_property['article:modified_time'] = date( 'Y-m-dTH:i:s', $news_contents['edittime'] );
			if( $news_contents['exptime'] )
			{
				$meta_property['article:expiration_time'] = date( 'Y-m-dTH:i:s', $news_contents['exptime'] );
			}
			$meta_property['article:section'] = $global_array_fam[$news_contents['fid']]['title'];
		}

		if( defined( 'NV_IS_MODADMIN' ) and $news_contents['status'] != 1 )
		{
			$alert = sprintf( $lang_module['status_alert'], $lang_module['status_' . $news_contents['status']] );
			$my_footer .= "<script type=\"text/javascript\">alert('". $alert ."')</script>";
			$news_contents['allowed_send'] = 0;
		}
		
		$base_url_rewrite = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] . $global_config['rewrite_exturl'], true );
	}elseif($news_contents['id'] > 0 AND $array_op[2]==$alias_made_up){
		$publtime =1;
		$base_url_rewrite = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_made_up . $global_config['rewrite_exturl'], true );
		
	}elseif($news_contents['id'] > 0 AND $array_op[2]==$alias_convention){
		$publtime =1;
		$base_url_rewrite = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_convention . $global_config['rewrite_exturl'], true );
				
	}elseif($news_contents['id'] > 0 AND $array_op[2]==$alias_collapse){
		$publtime =1;
		$base_url_rewrite = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_collapse . $global_config['rewrite_exturl'], true );
				
	}elseif($news_contents['id'] > 0 AND $array_op[2]==$alias_anniversary){
		$publtime =1;
		$base_url_rewrite = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_anniversary . $global_config['rewrite_exturl'], true );
				
	}elseif($news_contents['id'] > 0 AND $array_op[2]==$alias_family_tree AND $count_op == 4 AND $array_op[3]!='' ){
		$publtime =1;
		$base_url_rewrite = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_family_tree . '/' . $array_op[3] . $global_config['rewrite_exturl'], true );
		$info_users = $db->sqlreset()->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_'. $module_data .' WHERE gid = "' . $news_contents['id'] . '" AND alias="' . $array_op[3] . '" ORDER BY weight ASC' )->fetch();

		
		if(int(count($info_users['id']))!=0){
			
			if ($info_users['image'] != "" and file_exists(NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $info_users['image']))
			{
				$info_users['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $info_users['image'];
			}
			else
			{
				$info_users['image'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_info['module_file'] . '/no-images.jpg';
			}
			 $array_parentid = array();
			//Danh sách anh em
			if ($info_users['relationships'] == 1)
			{
				$query = "SELECT id, parentid, weight, relationships, gender, status, alias, full_name, birthday  FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE gid=" . $info_users['gid'] . " AND parentid=" . $info_users['parentid'] . " AND id!=" . $info_users['id'] . " AND relationships NOT IN(2,3) ORDER BY weight ASC";
				$result = $db->query($query);
				if (int(count($result))!=0)
				{
					$array_parentid[0]['caption'] = $lang_module['list_parentid_0'];
					while ($row = $result->fetch())
					{
						$row['link'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] . '/' . $alias_family_tree . '/' . $row['alias'] . $global_config['rewrite_exturl'], true );//$row_genealogy['link_main'] . '/' . $row['alias'];
						if ($row['birthday'] != '0000-00-00 00:00:00')
						{
							preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $row['birthday'], $datetime);
							$row['birthday'] = $datetime[3] . "/" . $datetime[2] . "/" . $datetime[1];
						}
						else
						{
							$row['birthday'] = "";
						}
						$row['status'] = $array_status[$row['status']];
						if($row['weight']<$info_users['weight']){
							if($row['gender']==1){
								$row['mogul']=$lang_module['brother_1'];
							}else{
								$row['mogul']=$lang_module['brother_2'];
							}
						}else{
							$row['mogul']=$lang_module['brother_3'];
						}
						$array_parentid[0]['items'][] = $row;
					}
				}
			}

			//Danh sách con cái
			$query = "SELECT id, parentid, weight, relationships, gender, status, alias, full_name, birthday FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE gid=" . $info_users['gid'] . " AND (parentid=" . $info_users['id']  . " OR parentid2 =" . $info_users['id']  . ") AND relationships=1 ORDER BY weight ASC";

			$result = $db->query($query);
			if (int(count($result))!=0)
			{
				
				$array_parentid[1]['caption'] = $lang_module['list_parentid_1'];
				while ($row = $result->fetch())
				{
					$row['link'] =  nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] . '/' . $alias_family_tree . '/' . $row['alias'] . $global_config['rewrite_exturl'], true );//$row_genealogy['link_main'] . '/' . $row['alias'];
					if ($row['birthday'] != '0000-00-00 00:00:00')
					{
						preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $row['birthday'], $datetime);
						$row['birthday'] = $datetime[3] . "/" . $datetime[2] . "/" . $datetime[1];
					}
					else
					{
						$row['birthday'] = "";
					}
					$row['status'] = $array_status[$row['status']];
					$array_parentid[1]['items'][] = $row;
				}
			}
			//die(int($info_users['gender']));
			//Danh sách vợ
			if ($info_users['gender'] == 1)
			{
				$query = "SELECT id, parentid, weight, relationships, gender, status, alias, full_name, birthday FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE gid=" . $info_users['gid'] . " AND parentid=" . $info_users['id'] . " AND relationships=2 ORDER BY weight ASC";
				$result = $db->query($query);
				if (int(count($result))!=0)
				{
					$array_parentid[2]['caption'] = $lang_module['list_parentid_2'];
					while ($row = $result->fetch())
					{
						$row['link'] =  nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] . '/' . $alias_family_tree . '/' . $row['alias'] . $global_config['rewrite_exturl'], true );//$row_genealogy['link_main'] . '/' . $row['alias'];
						if ($row['birthday'] != '0000-00-00 00:00:00')
						{
							preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $row['birthday'], $datetime);
							$row['birthday'] = $datetime[3] . "/" . $datetime[2] . "/" . $datetime[1];
						}
						else
						{
							$row['birthday'] = "";
						}
						$row['status'] = $array_status[$row['status']];
						$array_parentid[2]['items'][] = $row;
					}
				}
			}
			$info_users['relationships'] = $array_relationships[$info_users['relationships']];
			$info_users['gender'] = $array_gender[$info_users['gender']];
			$info_users['status'] = $array_status[$info_users['status']];
			if ($info_users['birthday'] != '0000-00-00 00:00:00')
			{
				preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $info_users['birthday'], $datetime);
				$info_users['birthday'] = $datetime[3] . "/" . $datetime[2] . "/" . $datetime[1];
			}
			else
			{
				$info_users['birthday'] = "";
			}

			if ($info_users['status'] == 0)
			{
				if ($info_users['dieday'] != '0000-00-00 00:00:00')
				{
					preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $info_users['dieday'], $datetime);
					$info_users['dieday'] = $datetime[3] . "/" . $datetime[2] . "/" . $datetime[1];
				}
				else
				{
					$info_users['dieday'] = "";
				}
			}
			else
			{
				$info_users['dieday'] = "";
				$info_users['life'] = "";
			}

			if ($info_users['life'] == 0)
			{
				$info_users['life'] = "";
			}
			$info_users['link']=nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_family_tree . '/' . $info_users['alias'] . $global_config['rewrite_exturl'], true );
			// Cay gia pha
			$OrgChart = array();
			$i = 0;
			// Xác định cha của người này
			if ($info_users['parentid'] > 0)
			{
				$info_parent = $db->query("SELECT full_name, alias FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $info_users['parentid'])->fetch();
				$info_parent['link']=nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_family_tree . '/' . $info_parent['alias'] . $global_config['rewrite_exturl'], true );
				$OrgChart[$i] = array('number' => $i, 'id' => $info_users['parentid'], 'parentid' => 0, 'full_name' => $info_parent['full_name'], 'link' =>  $info_parent['link']);

				// Thông tin của người này
				$i++;
				$OrgChart[$i] = array('number' => $i, 'id' => $info_users['id'], 'parentid' => $info_users['parentid'], 'full_name' => $info_users['full_name'], 'link' => $info_users['link']);
			}
			else
			{
				// Thông tin của người này
				$OrgChart[$i] = array('number' => $i, 'id' => $info_users['id'], 'parentid' => 0, 'full_name' => $info_users['full_name'], 'link' => $info_users['link']);
			}

			$array_in_parentid = array();
			$query = "SELECT id, parentid, parentid2, weight, relationships, gender, status, alias, full_name, birthday FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE gid=" . $info_users['gid'] . " AND parentid=" . $info_users['id'] . " ORDER BY relationships ASC, weight ASC";
			$result = $db->query($query);
			while ($row = $result->fetch())
			{
				$row['link'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_family_tree . '/' . $row['alias'] . $global_config['rewrite_exturl'], true );//$row_genealogy['link_main'] . '/' . $row['alias'];
				$array_in_parentid[$row['relationships']][] = $row;
			}

			// Xác định vợ của người này.
			if (isset($array_in_parentid[2]))
			{
				foreach ($array_in_parentid[2] as $key => $value)
				{
					$i++;
					$OrgChart[$i] = array('number' => $i, 'id' => $value['id'], 'parentid' => $value['parentid'], 'full_name' => $value['full_name'] . '<br><span style="color:red;">(' . $lang_module['u_relationships_2'] . ')</span>', 'link' => $value['link']);
				}
				if (isset($array_in_parentid[1]))
				{
					foreach ($array_in_parentid[1] as $key => $value)
					{
						$i++;
						$OrgChart[$i] = array('number' => $i, 'id' => $value['id'], 'parentid' => $value['parentid2'], 'full_name' => $value['full_name'], 'link' => $value['link']);
						$query = "SELECT id, parentid, parentid2, weight, relationships, gender, status, alias, full_name, birthday FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE gid=" . $info_users['gid'] . " AND parentid=" . $value['id'] . " ORDER BY relationships ASC, weight ASC";	
						//die($query);
						$result2 = $db->query($query);
						while ($row2 = $result2->fetch())
						{
							$row2['link'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_family_tree . '/' . $row2['alias'] . $global_config['rewrite_exturl'], true );//$row_genealogy['link_main'] . '/' . $row['alias'];
							$array_in_parentid_2[$row2['relationships']][] = $row2;
						}
						if (isset($array_in_parentid_2[2]))
						{
							foreach ($array_in_parentid_2[2] as $keys => $values)
							{
								$i++;
								$OrgChart[$i] = array('number' => $i, 'id' => $values['id'], 'parentid' => $values['parentid'], 'full_name' => $values['full_name'] . '<br>(' . $lang_module['u_relationships_2'] . ')', 'link' => $values['link']);
							}
						}
						if (isset($array_in_parentid_2[1]))
						{
							foreach ($array_in_parentid_2[1] as $keys => $values)
							{
								
								$i++;
								$numg = $db->query("SELECT COUNT(*) as numg FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE gid=" . $info_users['gid'] . " AND parentid=" . $values['id'] . " AND (gender = 1 OR gender = 3) ORDER BY relationships ASC, weight ASC")->fetch();
								if($values['parentid2']==0){$values['parentid2']=$values['parentid'];}
								$OrgChart[$i] = array('number' => $i, 'id' => $values['id'], 'parentid' => $values['parentid2'], 'full_name' => $values['full_name'].'<br><span style="color:red;">(' . $lang_module['u_relationships_4'] . ': ' . int($numg['numg']). ')</span>', 'link' => $values['link']);
							}
							
						}
						elseif (isset($array_in_parentid_2[3]))
						{
							foreach ($array_in_parentid_2[3] as $keys => $values)
							{
								$i++;
								$OrgChart[$i] = array('number' => $i, 'id' => $values['id'], 'parentid' => $values['parentid'], 'full_name' => $values['full_name'] . '<br><span style="color:red;">(' . $lang_module['u_relationships_3'] . ')</span>', 'link' => $values['link']);
							}
							if(isset($array_in_parentid_2[1])){
								foreach ($array_in_parentid_2[1] as $keys => $values)
								{
									$i++;
									$OrgChart[$i] = array('number' => $i, 'id' => $values['id'], 'parentid' => $values['parentid2'], 'full_name' => $values['full_name'].'<br><span style="color:red;">(' . $lang_module['u_relationships_1'] . ')</span>', 'link' => $values['link']);
								}
							}
						}
						elseif (isset($array_in_parentid_2[1]))
						{
							foreach ($array_in_parentid_2[1] as $keys => $values)
							{
								$i++;
								$numg = $db->query("SELECT COUNT(*) as numg FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE gid=" . $info_users['gid'] . " AND parentid=" . $values['id'] . " ORDER BY relationships ASC, weight ASC")->fetch();
								$OrgChart[$i] = array('number' => $i, 'id' => $value['id'], 'parentid' => $value['id'], 'full_name' => $values['full_name'].'<br><span style="color:red;">(' . $numg['numg'] . ')</span>', 'link' => $values['link']);
							}
							
						}
					}
				}
				// Xác định các con
				
			}
			elseif (isset($array_in_parentid[3]))
			{
				foreach ($array_in_parentid[3] as $key => $value)
				{
					$i++;
					$OrgChart[$i] = array('number' => $i, 'id' => $value['id'], 'parentid' => $value['parentid'], 'full_name' => $value['full_name'] . '<br><span style="color:red;">(' . $lang_module['u_relationships_3'] . ')</span>', 'link' => $value['link']);
				}
				foreach ($array_in_parentid[1] as $key => $value)
				{
					$i++;
					
					$OrgChart[$i] = array('number' => $i, 'id' => $value['id'], 'parentid' => $value['parentid2'], 'full_name' => $value['full_name'], 'link' => $value['link']);
				}
				
			}
			elseif (isset($array_in_parentid[1]))
			{
				foreach ($array_in_parentid[1] as $key => $value)
				{
					$i++;
					$OrgChart[$i] = array('number' => $i, 'id' => $value['id'], 'parentid' => $info_users['id'], 'full_name' => $value['full_name'], 'link' => $value['link']);
					$query = "SELECT id, parentid, parentid2, weight, relationships, gender, status, alias, full_name, birthday FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE gid=" . $info_users['gid'] . " AND parentid=" . $value['id'] . " ORDER BY relationships ASC, weight ASC";	
					//die($query);
					$result2 = $db->query($query);
					while ($row2 = $result2->fetch())
					{
						$row2['link'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_family_tree . '/' . $row2['alias'] . $global_config['rewrite_exturl'], true );//$row_genealogy['link_main'] . '/' . $row['alias'];
						$array_in_parentid_2[$row2['relationships']][] = $row2;
					}
					if (isset($array_in_parentid_2[1]))
					{
						foreach ($array_in_parentid_2[1] as $keys => $values)
						{
							$i++;
							$numg = $db->query("SELECT COUNT(*) as numg FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE gid=" . $info_users['gid'] . " AND parentid=" . $values['id'] . " AND (gender = 1 OR gender = 3) ORDER BY relationships ASC, weight ASC")->fetch();
							$OrgChart[$i] = array('number' => $i, 'id' => $values['id'], 'parentid' => $value['id'], 'full_name' => $values['full_name'].'<br><span style="color:red;">(' . $lang_module['u_relationships_4'] . ': ' . int($numg['numg']) . ')</span>', 'link' => $values['link']);
							
							
						}
					}
					
				}
			}
			// Cay gia pha
		}
	}elseif($news_contents['id'] > 0 AND $array_op[2]==$alias_family_tree){
		$publtime =1;
		$base_url_rewrite = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_family_tree . $global_config['rewrite_exturl'], true );
		$list_users = array();
		$sqllistuser = $db->sqlreset()->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_'. $module_data .' WHERE gid = "' . $news_contents['id'] . '" ORDER BY weight ASC' );

		while($listu = $sqllistuser->fetch())
		{
			$lu=array();
			$lu=$listu;
			$lu['link']=nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_family_tree . '/' . $lu['alias'] . $global_config['rewrite_exturl'], true );
			$list_users[$listu['parentid']][$listu['id']] = $lu;
					
		}
		$list_genealogy=$db->query("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_genealogy WHERE id=" . $news_contents['id'])->fetch();
		
		$info_users = $db->sqlreset()->query( 'SELECT * FROM ' . NV_PREFIXLANG . '_'. $module_data .' WHERE gid = "' . $news_contents['id'] . '" AND parentid=0 ORDER BY weight ASC' )->fetch();
		$info_users['link']=nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_family_tree . '/' . $info_users['alias'] . $global_config['rewrite_exturl'], true );
		//die('SELECT * FROM ' . NV_PREFIXLANG . '_'. $module_data .' WHERE gid = "' . $news_contents['id'] . '" AND parentid=0 ORDER BY weight ASC');
		if(int(count($info_users['id']))!=0){
			
			
			// Cay gia pha
			$OrgChart = array();
			$i = 0;
			// Xác định cha của người này
			if ($info_users['parentid'] > 0)
			{
				$info_parent = $db->query("SELECT full_name, alias FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $info_users['parentid'])->fetch();
				$info_parent['link']=nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_family_tree . '/' . $info_parent['alias'] . $global_config['rewrite_exturl'], true );
				$OrgChart[$i] = array('number' => $i, 'id' => $info_users['parentid'], 'parentid' => 0, 'full_name' => $info_parent['full_name'], 'link' =>  $info_parent['link']);

				// Thông tin của người này
				$i++;
				$OrgChart[$i] = array('number' => $i, 'id' => $info_users['id'], 'parentid' => $info_users['parentid'], 'full_name' => $info_users['full_name'], 'link' => $info_users['link']);
			}
			else
			{
				// Thông tin của người này
				$OrgChart[$i] = array('number' => $i, 'id' => $info_users['id'], 'parentid' => 0, 'full_name' => $info_users['full_name'], 'link' => $info_users['link']);
			}

			$array_in_parentid = array();
			$query = "SELECT id, parentid, parentid2, weight, relationships, gender, status, alias, full_name, birthday FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE gid=" . $info_users['gid'] . " AND parentid=" . $info_users['id'] . " ORDER BY relationships ASC, weight ASC";
			$result = $db->query($query);
			while ($row = $result->fetch())
			{
				$row['link'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_family_tree . '/' . $row['alias'] . $global_config['rewrite_exturl'], true );//$row_genealogy['link_main'] . '/' . $row['alias'];
				$array_in_parentid[$row['relationships']][] = $row;
				
			}

			// Xác định vợ của người này.
			if (isset($array_in_parentid[2]))
			{
				foreach ($array_in_parentid[2] as $key => $value)
				{
					$i++;
					$OrgChart[$i] = array('number' => $i, 'id' => $value['id'], 'parentid' => $value['parentid'], 'full_name' => $value['full_name'] . '<br><span style="color:red;">(' . $lang_module['u_relationships_2'] . ')</span>', 'link' => $value['link']);
					
				}
				if (isset($array_in_parentid[1]))
				{
					foreach ($array_in_parentid[1] as $key => $value)
					{
						$i++;
						$OrgChart[$i] = array('number' => $i, 'id' => $value['id'], 'parentid' => $value['parentid2'], 'full_name' => $value['full_name'], 'link' => $value['link']);
						$query = "SELECT id, parentid, parentid2, weight, relationships, gender, status, alias, full_name, birthday FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE gid=" . $info_users['gid'] . " AND parentid=" . $value['id'] . " ORDER BY relationships ASC, weight ASC";	
						//die($query);
						$result2 = $db->query($query);
						while ($row2 = $result2->fetch())
						{
							$row2['link'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_family_tree . '/' . $row2['alias'] . $global_config['rewrite_exturl'], true );//$row_genealogy['link_main'] . '/' . $row['alias'];
							$array_in_parentid_2[$row2['relationships']][] = $row2;
						}
						if (isset($array_in_parentid_2[2]))
						{
							foreach ($array_in_parentid_2[2] as $keys => $values)
							{
								$i++;
								$OrgChart[$i] = array('number' => $i, 'id' => $values['id'], 'parentid' => $values['parentid'], 'full_name' => $values['full_name'] . '<br>(' . $lang_module['u_relationships_2'] . ')', 'link' => $values['link']);
							}
						}
						if (isset($array_in_parentid[1]))
						{
							foreach ($array_in_parentid_2[1] as $keys => $values)
							{
								$i++;
								if($values['parentid2']==0){$values['parentid2']=$values['parentid'];}
								$numg = $db->query("SELECT COUNT(*) as numg FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE gid=" . $info_users['gid'] . " AND parentid=" . $values['id'] . " AND (gender = 1 OR gender = 3) ORDER BY relationships ASC, weight ASC")->fetch();
								$OrgChart[$i] = array('number' => $i, 'id' => $values['id'], 'parentid' => $values['parentid2'], 'full_name' => $values['full_name'].'<br><span style="color:red;">(' . $lang_module['u_relationships_4'] . ': ' . int($numg['numg']) . ')</span>', 'link' => $values['link']);
							}
						}
						elseif (isset($array_in_parentid_2[3]))
						{
							foreach ($array_in_parentid_2[3] as $keys => $values)
							{
								$i++;
								$OrgChart[$i] = array('number' => $i, 'id' => $values['id'], 'parentid' => $values['parentid'], 'full_name' => $values['full_name'] . '<br><span style="color:red;">(' . $lang_module['u_relationships_3'] . ')</span>', 'link' => $values['link']);
							}
							foreach ($array_in_parentid_2[1] as $keys => $values)
							{
								$i++;
								$OrgChart[$i] = array('number' => $i, 'id' => $values['id'], 'parentid' => $values['parentid2'], 'full_name' => $values['full_name'].'<br><span style="color:red;">(' . $lang_module['u_relationships_1'] . ')</span>', 'link' => $values['link']);
							}
						}
						elseif (isset($array_in_parentid_2[1]))
						{
							foreach ($array_in_parentid_2[1] as $keys => $values)
							{
								$i++;
								$OrgChart[$i] = array('number' => $i, 'id' => $value['id'], 'parentid' => $value['id'], 'full_name' => $values['full_name'].'<br><span style="color:red;">(' . $lang_module['u_relationships_1'] . ')</span>', 'link' => $values['link']);
							}
						}
						
					}
				}
				// Xác định các con
			}
			elseif (isset($array_in_parentid[3]))
			{
				foreach ($array_in_parentid[3] as $key => $value)
				{
					$i++;
					$OrgChart[$i] = array('number' => $i, 'id' => $value['id'], 'parentid' => $value['parentid'], 'full_name' => $value['full_name'] . '<br><span style="color:red;">(' . $lang_module['u_relationships_3'] . ')</span>', 'link' => $value['link']);
					$query = "SELECT id, parentid, parentid2, weight, relationships, gender, status, alias, full_name, birthday FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE gid=" . $info_users['gid'] . " AND parentid=" . $value['id'] . " ORDER BY relationships ASC, weight ASC";
					
					$result2 = $db->query($query);
					while ($row2 = $result2->fetch())
					{
						$array_in_parentid_2[$row2['relationships']][] = $row2;
					}
					foreach ($array_in_parentid_2[3] as $key => $value)
					{
						$i++;
						$OrgChart[$i] = array('number' => $i, 'id' => $value['id'], 'parentid' => $value['parentid'], 'full_name' => $value['full_name'] . '<br>(' . $lang_module['u_relationships_3'] . ')', 'link' => $value['link']);
					}
				}
				foreach ($array_in_parentid[1] as $key => $value)
				{
					$i++;
					$OrgChart[$i] = array('number' => $i, 'id' => $value['id'], 'parentid' => $value['parentid2'], 'full_name' => $value['full_name'], 'link' => $value['link']);
					$query = "SELECT id, parentid, parentid2, weight, relationships, gender, status, alias, full_name, birthday FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE gid=" . $info_users['gid'] . " AND parentid=" . $value['id'] . " ORDER BY relationships ASC, weight ASC";
					$result3 = $db->query($query);
					while ($row3 = $result3->fetch())
					{
						$array_in_parentid_3[$row3['relationships']][] = $row3;
					}
					foreach ($array_in_parentid_3[1] as $key => $value)
					{
						$i++;
						
						$OrgChart[$i] = array('number' => $i, 'id' => $value['id'], 'parentid' => $value['parentid'], 'full_name' => $value['full_name'] . '<br>(' . $lang_module['u_relationships_3'] . ')', 'link' => $value['link']);
					}
				}
			}
			elseif (isset($array_in_parentid[1]))
			{
				foreach ($array_in_parentid[1] as $key => $value)
				{
					$i++;
					//$numg = $db->query("SELECT COUNT(*) as numg FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE gid=" . $info_users['gid'] . " AND parentid=" . $value['id'] . " AND (gender = 1 OR gender = 3) ORDER BY relationships ASC, weight ASC")->fetch();
					$OrgChart[$i] = array('number' => $i, 'id' => $value['id'], 'parentid' => $info_users['id'], 'full_name' => $value['full_name'], 'link' => $value['link']);
					$query = "SELECT id, parentid, parentid2, weight, relationships, gender, status, alias, full_name, birthday FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE gid=" . $info_users['gid'] . " AND parentid=" . $value['id'] . " ORDER BY relationships ASC, weight ASC";	
					//die($query);
					$result2 = $db->query($query);
					while ($row2 = $result2->fetch())
					{
						$row2['link'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_family_tree . '/' . $row2['alias'] . $global_config['rewrite_exturl'], true );//$row_genealogy['link_main'] . '/' . $row['alias'];
						$array_in_parentid_2[$row2['relationships']][] = $row2;
					}
					if (isset($array_in_parentid_2[1]))
					{
						foreach ($array_in_parentid_2[1] as $keys => $values)
						{
							$i++;
							$numg = $db->query("SELECT COUNT(*) as numg FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE gid=" . $info_users['gid'] . " AND parentid=" . $values['id'] . " AND (gender = 1 OR gender = 3) ORDER BY relationships ASC, weight ASC")->fetch();
							$OrgChart[$i] = array('number' => $i, 'id' => $values['id'], 'parentid' => $value['id'], 'full_name' => $values['full_name'].'<br><span style="color:red;">(' . $lang_module['u_relationships_4'] . ': ' . int($numg['numg']) . ')</span>', 'link' => $values['link']);
						}
					}
				}
			}
			// Cay gia pha
		}
	//die("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_genealogy WHERE id=" . $list_genealogy['admin_id']);
	
		if( defined( 'NV_IS_ADMIN' )){
				define( 'NV_IS_GENEALOGY_MANAGER', true);
		}elseif($user_info['userid'] == $list_genealogy['admin_id']){
			define( 'NV_IS_GENEALOGY_MANAGER', true);
		}

	}
	
	


	if( $publtime == 0 )
	{
		$redirect = '<meta http-equiv="Refresh" content="3;URL=' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true ) . '" />';
		nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect );
	}
	
	
	
	if( $_SERVER['REQUEST_URI'] == $base_url_rewrite )
	{
		$canonicalUrl = NV_MAIN_DOMAIN . $base_url_rewrite;
	}
	elseif( NV_MAIN_DOMAIN . $_SERVER['REQUEST_URI'] != $base_url_rewrite )
	{
		Header( 'Location: ' . $base_url_rewrite );
		die();
	}
	else
	{
		$canonicalUrl = $base_url_rewrite;
	}

	$news_contents['url_sendmail'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=sendmail/' . $global_array_fam[$fid]['alias'] . '/' . $news_contents['alias'] . '-' . $news_contents['id'] . $global_config['rewrite_exturl'], true );
	$news_contents['url_print'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=print/' . $global_array_fam[$fid]['alias'] . '/' . $news_contents['alias'] . '-' . $news_contents['id'] . $global_config['rewrite_exturl'], true );
	$news_contents['url_savefile'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=savefile/' . $global_array_fam[$fid]['alias'] . '/' . $news_contents['alias'] . '-' . $news_contents['id'] . $global_config['rewrite_exturl'], true );

	

	$news_contents['newscheckss'] = md5( $news_contents['id'] . session_id() . $global_config['sitekey'] );
	$news_contents['publtime'] = nv_date( 'l - d/m/Y H:i', $news_contents['publtime'] );

	
	

	if( $news_contents['allowed_rating'] )
	{
		$time_set_rating = $nv_Request->get_int( $module_name . '_' . $op . '_' . $news_contents['id'], 'cookie', 0 );
		if( $time_set_rating > 0 )
		{
			$news_contents['disablerating'] = 1;
		}
		else
		{
			$news_contents['disablerating'] = 0;
		}
		$news_contents['stringrating'] = sprintf( $lang_module['stringrating'], $news_contents['total_rating'], $news_contents['click_rating'] );
		$news_contents['numberrating'] = ( $news_contents['click_rating'] > 0 ) ? round( $news_contents['total_rating'] / $news_contents['click_rating'], 1 ) : 0;
		$news_contents['langstar'] = array(
			'note' => $lang_module['star_note'],
			'verypoor' => $lang_module['star_verypoor'],
			'poor' => $lang_module['star_poor'],
			'ok' => $lang_module['star_ok'],
			'good' => $lang_module['star_good}'],
			'verygood' => $lang_module['star_verygood']
		);
	}

	list( $post_username, $post_first_name, $post_last_name ) = $db->query( 'SELECT username, first_name, last_name FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = ' . $news_contents['admin_id'] )->fetch( 3 );
	$news_contents['post_name'] = nv_show_name_user( $post_first_name, $post_last_name, $post_username );

	$array_keyword = array();
	$key_words = array();
	$_query = $db->query( 'SELECT a1.keyword, a2.alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id a1 INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_tags a2 ON a1.tid=a2.tid WHERE a1.id=' . $news_contents['id'] );
	while( $row = $_query->fetch() )
	{
		$array_keyword[] = $row;
		$key_words[] = $row['keyword'];
		$meta_property['article:tag'][] = $row['keyword'];
	}

	// comment
	if( isset( $site_mods['comment'] ) and isset( $module_config[$module_name]['activecomm'] ) )
	{
		define( 'NV_COMM_ID', $news_contents['id'] );//ID bài viết hoặc
	    define( 'NV_COMM_AREA', $module_info['funcs'][$op]['func_id'] );//để đáp ứng comment ở bất cứ đâu không cứ là bài viết
	    //check allow comemnt
	    $allowed = $module_config[$module_name]['allowed_comm'];//tuy vào module để lấy cấu hình. Nếu là module news thì có cấu hình theo bài viết
	    if( $allowed == '-1' )
	    {
	       $allowed = $news_contents['allowed_comm'];
	    }
	    define( 'NV_PER_PAGE_COMMENT', 5 ); //Số bản ghi hiển thị bình luận
	    require_once NV_ROOTDIR . '/modules/comment/comment.php';
	    $area = ( defined( 'NV_COMM_AREA' ) ) ? NV_COMM_AREA : 0;
	    $checkss = md5( $module_name . '-' . $area . '-' . NV_COMM_ID . '-' . $allowed . '-' . NV_CACHE_PREFIX );

	    $content_comment = nv_comment_module( $module_name, $checkss, $area, NV_COMM_ID, $allowed, 1 );
    }
	else
	{
		$content_comment = '';
	}
	$news_contents['link_made_up']=nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_made_up . $global_config['rewrite_exturl'], true );
	$news_contents['link_convention']=nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_convention . $global_config['rewrite_exturl'], true );
	$news_contents['link_collapse']=nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_collapse . $global_config['rewrite_exturl'], true );
	$news_contents['link_anniversary']=nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_anniversary . $global_config['rewrite_exturl'], true );
	$news_contents['link_family_tree']=nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_family_tree . $global_config['rewrite_exturl'], true );
	$news_contents['link_main']=nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] . $global_config['rewrite_exturl'], true );
	$news_contents['ftitle']=$global_array_fam[$fid]['title'];
	$array_mod_title[] = array(
				'title' => $news_contents['title'],
				'link' => $news_contents['link_main']
			);
	$body_contents = $db->query( 'SELECT bodyhtml as bodytext, rule, content, imgposition, copyright, allowed_send, allowed_print, allowed_save, gid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_bodyhtml_' . ceil( $news_contents['id'] / 2000 ) . ' where id=' . $news_contents['id'] )->fetch();
		$news_contents = array_merge( $news_contents, $body_contents );
		unset( $body_contents );
	
	if( $news_contents['id'] > 0 AND empty($array_op[2]) )
	{
		
		$contents = detail_theme( $news_contents, $array_keyword, $content_comment );
		
	}elseif($news_contents['id'] > 0 AND $array_op[2]==$alias_made_up){
		$array_mod_title[] = array(
				'title' => $lang_module['made_up'],
				'link' => $base_url_rewrite
			);
		$contents = view_detail('made_up', $news_contents, $array_keyword, $content_comment );
		
	}elseif($news_contents['id'] > 0 AND $array_op[2]==$alias_convention){
		$array_mod_title[] = array(
				'title' => $lang_module['convention'],
				'link' => $base_url_rewrite
			);
		$contents = view_detail('convention', $news_contents, $array_keyword, $content_comment );
		
	}elseif($news_contents['id'] > 0 AND $array_op[2]==$alias_collapse){
		$array_mod_title[] = array(
				'title' => $lang_module['collapse'],
				'link' => $base_url_rewrite
			);
		$contents = view_detail('collapse', $news_contents, $array_keyword, $content_comment );
		
	}elseif($news_contents['id'] > 0 AND $array_op[2]==$alias_anniversary){
		$array_mod_title[] = array(
				'title' => $lang_module['anniversary'],
				'link' => $base_url_rewrite
			);
		$contents = view_detail('anniversary', $news_contents, $array_keyword, $content_comment );
		
	}elseif($news_contents['id'] > 0 AND $array_op[2]==$alias_family_tree AND $count_op == 4 AND $array_op[3]!='' ){
			$link_alias_family_tree=nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$news_contents['fid']]['alias'] . '/' . $news_contents['alias'] .'/' . $alias_family_tree . $global_config['rewrite_exturl'], true );
			$array_mod_title[] = array(
				'title' => $lang_module['family_tree'],
				'link' => $link_alias_family_tree
			);
			$contents = nv_theme_genealogy_detail( $news_contents, $info_users, $array_parentid, $OrgChart );	
	}elseif($news_contents['id'] > 0 AND $array_op[2]==$alias_family_tree ){
			$array_mod_title[] = array(
				'title' => $lang_module['family_tree'],
				'link' => $base_url_rewrite
			);
			$contents = view_family( $news_contents, $info_users, $array_keyword, $content_comment, $OrgChart );	
	}
	$id_profile_googleplus = $news_contents['gid'];
	$page_title = $news_contents['title'];
	$key_words = implode( ', ', $key_words );
	$description = $news_contents['hometext'];
}
else
{
	$contents = no_permission( $global_array_fam[$fid]['groups_view'] );
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
