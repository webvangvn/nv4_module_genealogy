<?php


/**
 * @Project NUKEVIET 4.x
 * @Author webvang (hoang.nguyen@webvang.vn)
 * @Copyright (C) 2015 Webvang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 11/10/2015 00:00
 */

if( ! defined( 'NV_MAINFILE' ) )	die( 'Stop!!!' );

$sql = 'SELECT listfid FROM ' . NV_PREFIXLANG . '_' . $mod_info['module_data'] . '_genealogy WHERE id=' . $row['id'];
list( $listfid ) = $db->query( $sql )->fetch( 3 );

// Cap nhat lai so luong comment duoc kich hoat
$array_catid = explode( ',', $listcatid );
$numf = $db->query( 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_comment where module= ' . $db->quote( $row['module'] ) . ' AND id= ' . $row['id'] . ' AND status=1' )->fetchColumn();

$query = 'UPDATE ' . NV_PREFIXLANG . '_' . $mod_info['module_data'] . '_genealogy SET hitscm=' . $numf . ' WHERE id=' . $row['id'];
$db->query( $query );
foreach( $array_catid as $catid_i )
{
	$query = 'UPDATE ' . NV_PREFIXLANG . '_' . $mod_info['module_data'] . '_' . $catid_i . ' SET hitscm=' . $numf . ' WHERE id=' . $row['id'];
	$db->query( $query );
}
// Het Cap nhat lai so luong comment duoc kich hoat