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

$q = $nv_Request->get_title( 'term', 'get', '', 1 );
if( empty( $q ) ) return;

$db->sqlreset()
	->select('keywords')
	->from( NV_PREFIXLANG . '_' . $module_data . '_tags')
	->where( 'alias LIKE :alias OR keywords LIKE :keywords' )
	->order( 'alias ASC' )
	->limit( 50 );

$sth = $db->prepare( $db->sql() );
$sth->bindValue( ':alias','%' . $q . '%', PDO::PARAM_STR );
$sth->bindValue( ':keywords','%' . $q . '%', PDO::PARAM_STR );
$sth->execute();

$array_data = array();
while( list( $keywords ) = $sth->fetch( 3 ) )
{
	$keywords = explode( ',', $keywords );
	foreach ( $keywords as $_keyword )
	{
		$array_data[] = str_replace('-', ' ', $_keyword) ;
	}
}

header( 'Cache-Control: no-cache, must-revalidate' );
header( 'Content-type: application/json' );

ob_start( 'ob_gzhandler' );
echo json_encode( $array_data );
exit();