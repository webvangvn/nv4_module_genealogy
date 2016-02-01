<?php

/**
 * @Project NUKEVIET 4.x
 * @Author webvang (hoang.nguyen@webvang.vn)
 * @Copyright (C) 2015 Webvang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 11/10/2015 00:00
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
	'name' => 'Genealogy', // Tieu de module
	'modfuncs' => 'main,viewfam,location,groups,detail,search,content,tag,rss,manager,users,shows,creategenealogy', // Cac function co block
	'change_alias' => 'groups,content,rss',
	'submenu' => 'content,rss,search',
	'is_sysmod' => 0, // 1:0 => Co phai la module he thong hay khong
	'virtual' => 1, // 1:0 => Co cho phep ao hao module hay khong
	'version' => '4.0.0', // Phien ban cua modle
	'date' => 'Sun, 11 Oct 2015 00:00:00 GMT', // Ngay phat hanh phien ban
	'author' => 'Webvang (hoang.nguyen@webvang.vn)', // Tac gia
	'note' => '', // Ghi chu
	'uploads_dir' => array( $module_upload, $module_upload . '/temp_pic' ),
	'files_dir' => array()
);