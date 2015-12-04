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

function nv_viewdirtree_genealogy_delete($parentid = 0)
{
    global $db, $array_data_delete, $module_data;

    if (isset($array_data_delete[$parentid]))
    {
        $_dirlist = $array_data_delete[$parentid];
        foreach ($_dirlist as $_dir)
        {
            nv_viewdirtree_genealogy_delete($_dir['id']);
        }
    }
    $db->query("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $parentid);
}

if (defined('NV_IS_USER'))
{

    $page_title = $module_info['custom_title'];
    $key_words = $module_info['keywords'];

    $gid = $nv_Request->get_int('gid', 'post,get', 0);
    $deleteid = $nv_Request->get_int('deleteid', 'post,get', 0);
    if ($deleteid > 0)
    {
        $array_data_delete = array();
        $query = "SELECT id, parentid FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE gid=" . $gid . " ORDER BY parentid, weight ASC";
        $result = $db->query($query);
        while ($row = $result->fetch())
        {
            $array_data_delete[$row['parentid']][$row['id']] = $row;
        }
        $db->sqlreset();

        nv_viewdirtree_genealogy_delete($deleteid);

        list($number) = $db->query("SELECT  count(*) FROM " . NV_PREFIXLANG . "_" . $module_data . " where gid=" . $gid)->fetch(3);
        $db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_genealogy SET number=" . $number . " WHERE id =" . $gid);
		$post_gid = $db->query("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_genealogy WHERE id=" . $gid)->fetch();
		$db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_" . $post_gid['fid'] . " SET number=" . $number . " WHERE id =" . $gid);
        nv_del_moduleCache($module_name);
		$post_gid = $db->query("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_genealogy WHERE id=" . $gid)->fetch();
		$alias_family_tree=change_alias($lang_module['family_tree']);
		$base_url_rewrite=nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $global_array_fam[$post_gid['fid']]['alias'] . '/' . $post_gid['alias'] . '/'. $alias_family_tree . $global_config['rewrite_exturl'], true );
		echo '<script type="text/javascript">
			parent.location="' . $base_url_rewrite . '";
			</script>';
		die();
    }

}
else
{
    $redirect = "<meta http-equiv=\"Refresh\" content=\"2;URL=" . nv_url_rewrite(NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=users&" . NV_OP_VARIABLE . "=login&nv_redirect=" . nv_base64_encode($client_info['selfurl']), true) . "\" />";
    nv_info_die($lang_module['error_login_title'], $lang_module['error_login_title'], $lang_module['error_login_content'] . $redirect);
}
?>