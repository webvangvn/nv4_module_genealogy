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
if (defined('NV_IS_USER'))
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
    

    $post = array();
    $birthday_hour = $birthday_min = $dieday_hour = $dieday_min = 0;

    $post['id'] = $nv_Request->get_int('id', 'post,get', 0);
    if (!empty($post['id']))
    {
        $post_old = $db->query("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $post['id'])->fetch();
        $post['gid'] = $post_old['gid'];
    }
    else
    {
        $post['gid'] = $nv_Request->get_int('gid', 'post,get', 0);
    }
	
    $post_gid = $db->query("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_genealogy WHERE id=" . $post['gid'])->fetch();
	//die($user_info['userid'].'/'.$post_gid['admin_id']);
    if (!defined( 'NV_IS_ADMIN' ) or empty($post_gid)  or $post_gid['admin_id'] != $user_info['userid'])
    {
        $redirect = "<meta http-equiv=\"Refresh\" content=\"3;URL=" . nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name, true) . "\" />";
        nv_info_die($lang_module['error_who_view_title'], $lang_module['error_who_view_title'], $lang_module['error_who_view_content'] . $redirect);
    }

    $post['parentid'] = $nv_Request->get_int('parentid', 'post,get', 0);
    $post['parentid2'] = $nv_Request->get_int('parentid2', 'post,get', 0);

    $post['relationships'] = $nv_Request->get_int('relationships', 'post,get', 1);
    $post['opanniversary'] = $nv_Request->get_int('opanniversary', 'post,get', 0);

    $post['weight'] = $nv_Request->get_int('weight', 'post,get', 1);
    $post['gender'] = $nv_Request->get_int('gender', 'post,get', 1);
    $post['status'] = $nv_Request->get_int('status', 'post,get', 2);

    $post['full_name'] = $nv_Request->get_string('full_name', 'post', '');

    $post['code'] = $nv_Request->get_string('code', 'post', '');
    $post['name1'] = $nv_Request->get_string('name1', 'post', '');
    $post['name2'] = $nv_Request->get_string('name2', 'post', '');

    $content = $nv_Request->get_string('content', 'post', '');
    $post['content'] = defined('NV_EDITOR') ? nv_nl2br($content, '') : nv_nl2br(nv_htmlspecialchars(strip_tags($content)), '<br />');

    $post['life'] = $nv_Request->get_int('life', 'post,get', 0);
    $post['burial'] = $nv_Request->get_string('burial', 'post', '');

    $post['image'] = "";

    $birthday_date = $nv_Request->get_string('birthday_date', 'post', '');
    $dieday_date = $nv_Request->get_string('dieday_date', 'post', '');
    if (!empty($birthday_date) and preg_match("/^([0-9]{1,2})\\/([0-9]{1,2})\/([0-9]{4})$/", $birthday_date, $m))
    {
        $birthday_hour = $nv_Request->get_int('birthday_hour', 'post', 0);
        $birthday_min = $nv_Request->get_int('birthday_min', 'post', 0);
        $life_birthday = $m[3];

        $post['birthday_data'] = $m[3] . "-" . $m[2] . "-" . $m[1] . " " . $birthday_hour . ":" . $birthday_min . ":00";
        $post['birthday_date'] = $birthday_date;
    }
    else
    {
        $post['birthday_data'] = "0000-00-00 00:00:00";
    }

    if (!empty($dieday_date) and preg_match("/^([0-9]{1,2})\\/([0-9]{1,2})\/([0-9]{4})$/", $dieday_date, $m))
    {
        $dieday_hour = $nv_Request->get_int('dieday_hour', 'post', 0);
        $dieday_min = $nv_Request->get_int('dieday_min', 'post', 0);
        $life_dieday = $m[3];

        $post['dieday_data'] = $m[3] . "-" . $m[2] . "-" . $m[1] . " " . $dieday_hour . ":" . $dieday_min . ":00";
        $post['dieday_date'] = $dieday_date;
    }
    else
    {
        $post['dieday_data'] = "0000-00-00 00:00:00";
    }

    if ($nv_Request->get_int('save', 'post') == 1 and !empty($post['full_name']) and $post['life'] < 200)
    {
        $post['userid'] = $user_info['userid'];
        if (empty($post['id']))
        {
            if ($post['parentid'])
            {
                list($lev) = $db->query("SELECT lev FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $post['parentid'])->fetch(3);
                $post['lev'] = intval($lev) + 1;
            }
            else
            {
                $post['lev'] = 1;
                $post['weight'] = 1;
            }
            $post['actanniversary'] = ($post['status'] == 0 and $post['dieday_data'] != '0000-00-00 00:00:00') ? 1 : 0;
			$_sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "
						( gid, parentid, parentid2, weight, lev, relationships, gender, status, anniversary_day, anniversary_mont, actanniversary, 
				alias,full_name, code, name1, name2, 
				birthday, dieday, life, burial, content, image, userid, add_time, edit_time) VALUES
						 (" .  intval($post['gid'])  . ",
						 " .  intval($post['parentid'])  . ",
						 " .  intval($post['parentid2'])  . ",
						 " .  intval($post['weight'])  . ",
						 " .  intval($post['lev'])  . ",
						 " .  intval($post['relationships'])  . ",
						 " .  intval($post['gender'])  . ",
						 " .  intval($post['status'])  . ",
						 " . $db->quote($post['anniversary_day'])  . ",
						 " . $db->quote($post['anniversary_mont'])  . ",
						 " . $db->quote($post['actanniversary'])  . ",
						 '',
						 " . $db->quote($post['full_name'])  . ",
						 " . $db->quote($post['code'])  . ",
						 " . $db->quote($post['name1'])  . ",
						 " . $db->quote($post['name2'])  . ",
						 " . $db->quote($post['birthday_data'])  . ",
						 " . $db->quote($post['dieday_data'])  . ",
						 " .  intval($post['life'])  . ",
						 " . $db->quote($post['burial'])  . ",
						 " . $db->quote($post['content'])  . ",
						 " . $db->quote($post['image'])  . ",
						 " .  $db->quote($post['userid'])  . ",
						 " . NV_CURRENTTIME . ",
						 " . NV_CURRENTTIME . ")";
				//die( $_sql);
				$post['id'] = $db->insert_id( $_sql, 'id' );
            
           if ($post['id'])
            {	
				
                $alias = change_alias($post['full_name']);
                    $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET alias=".  $db->quote($alias . "-" . $post['id']) . " WHERE id =" . $post['id'] . "";
                    $db->query($query);
                $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_genealogy SET number=number+1 WHERE id =" . $post['gid'] . "";
                $db->query($query);
				$query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_". $post_gid['fid'] ." SET number=number+1 WHERE id =" . $post['gid'] . "";
				//die($query);
                $db->query($query);
                nv_fix_genealogy_user($post['parentid']);
                nv_del_moduleCache($module_name);
				$alias_family_tree=change_alias($lang_module['family_tree']);
				$base_url_rewrite=nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$post_gid['fid']]['alias'] . '/' . $post_gid['alias'] . '/Manager' . $global_config['rewrite_exturl'], true );
                echo '<script type="text/javascript">
					parent.location="' . $base_url_rewrite . '";
    				</script>';
                die();
            }
        }
        else
        {
            $post['actanniversary'] = ($post['status'] == $post_old['status'] and $post['status'] == 0) ? 0 : $post_old['actanniversary'];
            //$post['anniversary'] = ($post['dieday_data'] == '0000-00-00 00:00:00') ? $post['anniversary'] : '';
            $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET parentid2=" . $post['parentid2'] . ", weight=" . $post['weight'] . ", relationships =  " . $post['relationships'] . ", gender=" . $post['gender'] . ", status= " . $post['status'] . ", actanniversary= " . $db->quote($post['actanniversary']) . ", 
			full_name=" . $db->quote($post['full_name']) . ", code=" . $db->quote($post['code']) . ", name1=" . $db->quote($post['name1']) . ", name2=" . $db->quote($post['name2']) . ", 
			birthday='" . $post['birthday_data'] . "', dieday='" . $post['dieday_data'] . "', life='" . $post['life'] . "', burial=" . $db->quote($post['burial']) . ", content=" . $db->quote($post['content']) . ",
			edit_time=UNIX_TIMESTAMP( ) WHERE id =" . $post['id'] . "";
            if( $db->exec( $query ) )
			{
                $alias = change_alias($post['full_name']);
                if ($post_old['alias'] != $alias)
                {
                        $query = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET alias=" . $db->quote($alias . "-" . $post['id']) . " WHERE id =" . $post['id'] . "";
                        $db->query($query);
                }
                nv_fix_genealogy_user($post['parentid']);
                nv_del_moduleCache($module_name);

                $op2 = ($post['opanniversary']) ? "anniversary" : "shows";

                $alias_family_tree=change_alias($lang_module['family_tree']);
				$base_url_rewrite=nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$post_gid['fid']]['alias'] . '/' . $post_gid['alias'] . '/Manager' . $global_config['rewrite_exturl'], true );
                echo '<script type="text/javascript">
					parent.location="' . $base_url_rewrite . '";
    				</script>';
                die();
            }
        }
    }
    elseif ($post['id'])
    {
		
        $post = $db->query("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $post['id'] . "")->fetch();

        preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $post['birthday'], $datetime);
        if ($post['birthday'] != '0000-00-00 00:00:00')
        {
            $post['birthday_date'] = $datetime[3] . "/" . $datetime[2] . "/" . $datetime[1];
        }
        else
        {
            $post['birthday_date'] = "";
        }
		
        $birthday_hour = intval($datetime[4]);
        $birthday_min = intval($datetime[5]);

        preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})/", $post['dieday'], $datetime);
        if ($post['dieday'] != '0000-00-00 00:00:00')
        {
            $post['dieday_date'] = $datetime[3] . "/" . $datetime[2] . "/" . $datetime[1];

        }
        else
        {
            $post['dieday_date'] = "";
        }
        $post['opanniversary'] = $nv_Request->get_int('opanniversary', 'post,get', 0);
        $dieday_hour = intval($datetime[4]);
        $dieday_min = intval($datetime[5]);
    }
    elseif ($post['parentid'] == 0)
    {
        $post['gender'] = 1;
        $post['status'] = 2;
    }
    elseif ($post['parentid'] > 0)
    {

        list($maxweight) = $db->query("SELECT max(weight) FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE parentid=" . $post['parentid'] . " AND relationships=" . $post['relationships'])->fetch(3);
        $post['weight'] = intval($maxweight) + 1;

    }

    if (!empty($post['content']))
        $post['content'] = nv_htmlspecialchars($post['content']);

    if (!empty($post['image']) and file_exists(NV_UPLOADS_REAL_DIR . "/" . $module_name . "/" . $post['image']))
    {
        $post['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $post['image'];
    }

    if ($post['relationships'] == 2)
    {
        $post['gender'] = 2;
    }

    $page_title = ($post['id'] == 0) ? $lang_module['u_add'] : $lang_module['u_edit'];

    $lang_module['burial_address'] = ($post['status'] == 0) ? $lang_module['u_burial'] : $lang_module['u_address'];

    $my_head .= "<link rel=\"stylesheet\" href=\"" . NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/css/tab_info.css\" type=\"text/css\" />";
    $my_head .= "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.css\" rel=\"stylesheet\" />\n";
    $my_head .= "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.theme.css\" rel=\"stylesheet\" />\n";
    $my_head .= "<link type=\"text/css\" href=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.datepicker.css\" rel=\"stylesheet\" />\n";

    $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/jquery/jquery.autocomplete.js\"></script>\n";
    $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.core.min.js\"></script>\n";
    $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/ui/jquery.ui.datepicker.min.js\"></script>\n";
    $my_head .= "<script type=\"text/javascript\" src=\"" . NV_BASE_SITEURL . "js/language/jquery.ui.datepicker-" . NV_LANG_INTERFACE . ".js\"></script>\n";

    $xtpl = new XTemplate("users.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file);

    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('OP', $op);

    $xtpl->assign('NV_SITE_COPYRIGHT', "" . $global_config['site_name'] . " [" . $global_config['site_email'] . "] ");
    $xtpl->assign('NV_SITE_NAME', $global_config['site_name']);
    $xtpl->assign('NV_SITE_TITLE', "" . $global_config['site_name'] . " " . NV_TITLEBAR_DEFIS . " " . $lang_global['admin_page'] . " " . NV_TITLEBAR_DEFIS . " " . $module_info['custom_title'] . "");
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_ADMINDIR', NV_ADMINDIR);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('MODULE_FILE', $module_file);
    $xtpl->assign('TEMPLATE', $module_info['template']);

    $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('NV_SITE_TIMEZONE_OFFSET', round(NV_SITE_TIMEZONE_OFFSET / 3600));
    $xtpl->assign('NV_CURRENTTIME', nv_date("T", NV_CURRENTTIME));
    $xtpl->assign('NV_COOKIE_PREFIX', $global_config['cookie_prefix']);

    if ($post['parentid'] > 0 and $post['relationships'] == 1)
    {
        $sql = "SELECT id,full_name FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE parentid=" . $post['parentid'] . " AND id!=" . $post['id'] . " AND relationships=2 ORDER BY weight";
        $result = $db->query($sql);
        while ($row = $result->fetch())
        {
            $row['selected'] = ($row['id'] == $post['parentid2']) ? ' selected="selected"' : '';
            $xtpl->assign('PARENTID2', $row);
            $xtpl->parse('main.parentid2');
        }
    }

    $array_relationships = array(1 => $lang_module['u_relationships_1'], 2 => $lang_module['u_relationships_2'], 3 => $lang_module['u_relationships_3']);
    foreach ($array_relationships as $value => $title)
    {
        $arrayName = array('value' => $value, 'title' => $title, 'checked' => ($value == $post['relationships']) ? ' checked="checked"' : '');
        $xtpl->assign('RELATIONSHIPS', $arrayName);

        $xtpl->parse('main.root.relationships');
    }
    for ($i = 1; $i <= 100; $i++)
    {
        $arrayName = array('value' => $i, 'title' => $i, 'selected' => ($i == $post['weight']) ? ' selected="selected"' : '');
        $xtpl->assign('WEIGHT', $arrayName);

        $xtpl->parse('main.root.weight');
    }

    $array_gender = array(0 => $lang_module['u_gender_0'], 1 => $lang_module['u_gender_1'], 2 => $lang_module['u_gender_2']);
    foreach ($array_gender as $value => $title)
    {
        $temp = array('value' => $value, 'title' => $title, 'checked' => ($value == $post['gender']) ? ' checked="checked"' : '');
        $xtpl->assign('GENDER', $temp);
        $xtpl->parse('main.gender');
    }

    $array_status = array(0 => $lang_module['u_status_0'], 1 => $lang_module['u_status_1'], 2 => $lang_module['u_status_2']);
    foreach ($array_status as $value => $title)
    {
        $arrayName = array('value' => $value, 'title' => $title, 'checked' => ($value == $post['status']) ? ' checked="checked"' : '');
        $xtpl->assign('STATUS', $arrayName);
        $xtpl->parse('main.status');
    }

    for ($i = 0; $i <= 120; $i++)
    {
        $arrayName = array('value' => $i, 'title' => $i, 'checked' => ($i == $post['life']) ? ' selected="selected"' : '');
        $xtpl->assign('LIFE', $arrayName);

        $xtpl->parse('main.life');
    }
	if( defined( 'NV_EDITOR' ) and nv_function_exists( 'nv_aleditor' ) )
	{
		$htmlbodyhtml = nv_aleditor( 'content', '100%', '300px', $post['content'], 'Basic' );
	}
	else
	{
		$htmlbodyhtml .= "<textarea class=\"textareaform\" name=\"content\" id=\"content\" cols=\"60\" rows=\"15\">" . $post['content']  . "</textarea>";
	}
	$xtpl->assign( 'HTMLBODYTEXT', $htmlbodyhtml );

    $post['birthday_hour'] = $post['birthday_min'] = '';
    for ($i = 0; $i <= 23; $i++)
    {
        $post['birthday_hour'] .= "<option value=\"" . $i . "\"" . (($i == $birthday_hour) ? " selected=\"selected\"" : "") . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
    }
    for ($i = 0; $i < 60; $i++)
    {
        $post['birthday_min'] .= "<option value=\"" . $i . "\"" . (($i == $birthday_min) ? " selected=\"selected\"" : "") . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
    }

    $post['dieday_hour'] = $post['dieday_min'] = '';
    for ($i = 0; $i <= 23; $i++)
    {
        $post['dieday_hour'] .= "<option value=\"" . $i . "\"" . (($i == $dieday_hour) ? " selected=\"selected\"" : "") . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
    }
    for ($i = 0; $i < 60; $i++)
    {
        $post['dieday_min'] .= "<option value=\"" . $i . "\"" . (($i == $dieday_min) ? " selected=\"selected\"" : "") . ">" . str_pad($i, 2, "0", STR_PAD_LEFT) . "</option>\n";
    }
    $xtpl->assign('DATA', $post);
    $xtpl->assign('UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_name);

    if ($post['parentid'] > 0)
    {
        $xtpl->parse('main.root');
        list($full_name_parentid) = $db->query("SELECT full_name FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $post['parentid'])->fetch();
        $page_title .= ": " . $full_name_parentid . " ---> " . $post['full_name'];
    }

    if (!empty($my_head))
    {
        $xtpl->assign('NV_ADD_MY_HEAD', $my_head);
        $xtpl->parse('main.nv_add_my_head');
    }

    if (!empty($page_title))
    {
        $xtpl->assign('PAGE_TITLE', $page_title);
        $xtpl->parse('main.empty_page_title');
    }

    if (NV_LANG_INTERFACE == 'vi' and NV_LANG_DATA == 'vi')
    {
        $xtpl->parse('main.nv_if_mudim');
    }
    $xtpl->assign('NV_GENPASS', nv_genpass());
    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include (NV_ROOTDIR . "/includes/header.php");
    echo $contents;
    include (NV_ROOTDIR . "/includes/footer.php");
}
else
{
    $redirect = "<meta http-equiv=\"Refresh\" content=\"2;URL=" . nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=login&nv_redirect=" . nv_base64_encode($client_info['selfurl']), true) . "\" />";
    nv_info_die($lang_module['error_login_title'], $lang_module['error_login_title'], $lang_module['error_login_content'] . $redirect);
}
