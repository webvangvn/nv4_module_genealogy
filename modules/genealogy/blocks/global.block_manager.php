<?php

/**
 * @Project NUKEVIET 3
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES., JSC. All rights reserved
 * @Createdate 24/8/2011 23:25
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

if (!nv_function_exists('nv_gia_pha_block_manager'))
{
    function nv_gia_pha_block_manager($block_config, $mod_file)
    {
        global $module_info;

        $module = $block_config['module'];
        $block_config['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module . "&amp;" . NV_OP_VARIABLE . "=manager";

        if (file_exists(NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $mod_file . "/block_manager.tpl"))
        {
            $block_theme = $module_info['template'];
        }
        else
        {
            $block_theme = "default";
        }
        $xtpl = new XTemplate("block_manager.tpl", NV_ROOTDIR . "/themes/" . $block_theme . "/modules/" . $mod_file);
        $xtpl->assign('CONFIG', $block_config);
		 $xtpl->parse('main');
        return $xtpl->text('main');
    }

}

if (defined('NV_SYSTEM'))
{
    global $site_mods;
    $module = $block_config['module'];
    if (isset($site_mods[$module]))
    {
        $mod_file = $site_mods[$module]['module_file'];
        $content = nv_gia_pha_block_manager($block_config, $mod_file);
    }
}
