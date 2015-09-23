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
$city = $nv_Request->get_int( 'cityid', 'get', '' );
$district = $nv_Request->get_int( 'districtid', 'get', '' );
	// district
$sql = 'SELECT district_id, city_id, title,type FROM ' . $db_config['prefix'] . '_' . NV_LANG_DATA . '_location_district WHERE status=1 AND city_id = '.$city.' ORDER BY weight ASC';
$global_array_location_city_district = nv_db_cache( $sql, 'district_id', 'location' );
include NV_ROOTDIR . '/includes/header.php';
foreach( $global_array_location_city_district as $district_i =>  $rowsdistrict ){
	$rowsdistrict['selected'] = ($district_i == $district) ? ' selected="selected"' : '';
	echo '<option value="'.$rowsdistrict['district_id'].'" '.$rowsdistrict['selected'].'>'.$rowsdistrict['type']. ' '. $rowsdistrict['title'].'</option>';
}
include NV_ROOTDIR . '/includes/footer.php';
