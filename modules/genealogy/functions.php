<?php


/**
 * @Project NUKEVIET 4.x
 * @Author webvang (hoang.nguyen@webvang.vn)
 * @Copyright (C) 2015 Webvang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 11/10/2015 00:00
 */
if( ! defined( 'NV_SYSTEM' ) ) die( 'Stop!!!' );
$tablelocation = NV_PREFIXLANG . '_location';
$result = $db->query( 'SHOW TABLE STATUS LIKE ' . $db->quote( $tablelocation . '_%' ) );
$checklocation=0;
while( $item = $result->fetch( ) )
{
	$checklocation++;
}

if ($checklocation > 0) {
	
	define( 'NV_MODULE_LOCATION', true );
	
	$sql = 'SELECT city_id, title, alias, type FROM ' . $tablelocation . '_city WHERE status=1 ORDER BY weight ASC';
	
	$global_array_location_city = nv_db_cache( $sql, 'city_id', 'location' );
	
	$sql = 'SELECT district_id, city_id, title, alias, type FROM ' . $tablelocation . '_district WHERE status=1 ORDER BY weight ASC';
	
	$global_array_location_district = nv_db_cache( $sql, 'district_id', 'location' );
	
	$sql = 'SELECT ward_id, district_id, city_id, title, alias, type FROM ' . $tablelocation . '_ward WHERE status=1 ORDER BY weight ASC';
	
	$global_array_location_ward = nv_db_cache( $sql, 'ward_id', 'location' );
}

if( ! in_array( $op, array( 'viewfam', 'detail' ) ) )
{
	define( 'NV_IS_MOD_GENEALOGY', true );
}
require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

global $global_array_fam;
$global_array_fam = array();
$link_i = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=Other';
$global_array_fam[0] = array(
	'fid' => 0,
	'parentid' => 0,
	'title' => 'Other',
	'titlesite' => '',
	'alias' => 'Other',
	'link' => $link_i,
	'viewfam' => 'view_location',
	'viewdescription' => '',
	'subfid' => 0,
	'numlinks' => 3,
	'description' => '',
	'inhome' => 0,
	'keywords' => '',
	'groups_view'=>0
);
$fid = 0;
$parentid = 0;
$alias_fam_url = isset( $array_op[0] ) ? $array_op[0] : '';
$array_mod_title = array();

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_family ORDER BY sort ASC';
$list = nv_db_cache( $sql, 'fid', $module_name );
foreach( $list as $l )
{
	$global_array_fam[$l['fid']] = $l;
	$global_array_fam[$l['fid']]['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $l['alias'];
	if( $alias_fam_url == $l['alias'] )
	{
		$fid = $l['fid'];
		$parentid = $l['parentid'];
	}
}




foreach( $global_array_fam as $fid_i => $array_fam_i )
{
	if( $fid_i > 0 and $array_fam_i['parentid'] == 0 )
	{
		$act = 0;
		$submenu = array();
		if( $fid_i == $fid or $fid_i == $parentid )
		{
			$act = 1;
			if( ! empty( $global_array_fam[$fid_i]['subfid'] ) )
			{
				$array_fid = explode( ',', $global_array_fam[$fid_i]['subfid'] );
				foreach( $array_fid as $sub_fid_i )
				{
					$array_sub_fam_i = $global_array_fam[$sub_fid_i];
					$sub_act = 0;
					if( $sub_fid_i == $fid )
					{
						$sub_act = 1;
					}
					$submenu[] = array( $array_sub_fam_i['title'], $array_sub_fam_i['link'], $sub_act );
				}
			}
		}
		$nv_vertical_menu[] = array( $array_fam_i['title'], $array_fam_i['link'], $act, 'submenu' => $submenu );
	}

	
}
unset( $result, $fid_i, $parentid_i, $title_i, $alias_i );

$module_info['submenu'] = 0;

$page = 1;
$per_page = $module_config[$module_name]['per_page'];
$st_links = $module_config[$module_name]['st_links'];
$count_op = sizeof( $array_op );
if( ! empty( $array_op ) and $op == 'main' )
{
	if( $fid == 0 )
	{
		$contents = $lang_module['nofampage'] . $array_op[0];
		if( isset( $array_op[0] ) and substr( $array_op[0], 0, 5 ) == 'page-' )
		{
			$page = intval( substr( $array_op[0], 5 ) );
		}
		elseif( ! empty( $alias_fam_url ) )
		{
			$redirect = '<meta http-equiv="Refresh" content="3;URL=' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true ) . '" />';
			nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] . $redirect );
		}
	}
	else
	{
		$op = 'main';
		if( $count_op == 1 or substr( $array_op[1], 0, 5 ) == 'page-' )
		{
			$op = 'viewfam';
			if( $count_op > 1 )
			{
				$page = intval( substr( $array_op[1], 5 ) );
			}
		}
		elseif( $count_op == 2 )
		{
			$alias_url = $array_op[1];
			
			if(  $alias_url != '') 
			{
				$op = 'detail';
			}
		}
		elseif( $count_op == 3 )
		{
			$alias_url = $array_op[1];
			
			if(  $alias_url != '' AND $array_op[2]!='') 
			{
				if($array_op[2]=='Manager'){
					$op = 'manager';
				}else{
					$op = 'detail';
				}
			}
		}
		elseif( $count_op == 4 )
		{
			$alias_url = $array_op[1];
			if(  $alias_url != '' AND $array_op[2]!='' AND $array_op[3]!='') 
			{
				$op = 'detail';
			}
		}

	}
}


$sql = "SELECT fid, title, alias FROM " . NV_PREFIXLANG . "_" . $module_data . "_family ORDER BY weight ASC";
$array_family = nv_db_cache( $sql, 'fid', $module_name );





function nv_menu_gia_pha( $row_genealogy )
{
	global $module_name, $family_alias;
	$row_genealogy['link_main'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $family_alias . "/" . $row_genealogy['alias'];
	$row_genealogy['link_pha_ky'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $family_alias . "/" . $row_genealogy['alias'] . "/pha-ky";
	$row_genealogy['link_pha_do'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $family_alias . "/" . $row_genealogy['alias'] . "/pha-do";

	$row_genealogy['link_toc_uoc'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $family_alias . "/" . $row_genealogy['alias'] . "/toc-uoc";
	$row_genealogy['link_huong_hoa'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $family_alias . "/" . $row_genealogy['alias'] . "/huong-hoa";
	$row_genealogy['link_ngay_gio'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $family_alias . "/" . $row_genealogy['alias'] . "/ngay-gio";

	return $row_genealogy;
}


function nv_fix_genealogy()
{
	global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
	$query = "SELECT gid FROM " . NV_PREFIXLANG . "_" . $module_data . "_genealogy ORDER BY weight ASC";
	$weight = 0;
	$result = $db->sql_query( $query );
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$weight++;
		$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_genealogy SET weight=" . $weight . " WHERE fid=" . intval( $row['gid'] );
		$db->sql_query( $sql );
	}
	
}

function nv_fix_genealogy_user( $parentid )
{
	global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data, $op;
	$query = "SELECT id, relationships  FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE parentid=" . $parentid . " ORDER BY weight ASC";
	$weight1 = $weight2 = 0;
	$result = $db->query( $query );
	while( $row = $result->fetch() )
	{
		if( $row['relationships'] == 2 )
		{
			$weight2++;
			$weight = $weight2;
		}
		else
		{
			$weight1++;
			$weight = $weight1;
		}
		$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET weight=" . $weight . " WHERE id=" . intval( $row['id'] );
		$db->query( $sql );
	}
	$db->sqlreset();
}


function nv_module_gia_pha_sql_phado( $gid, $list_lev )
{
	global $db, $module_data;
	$array_data = array();
	$a = 0;
	$query = "SELECT id, parentid, weight, lev, relationships, gender, status, alias, full_name FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE gid=" . $gid . " AND lev IN ( " . implode( ",", $list_lev ) . " ) ORDER BY parentid ASC, weight, id ASC";
	$result = $db->sql_query( $query );
    $parentid = array();
	while( $row = $db->sql_fetchrow( $result ) )
	{
		$parentid[$row['parentid']] = $row['parentid'];
		$array_data[$row['parentid']][$row['id']] = array(
			"id" => $row['id'],
			"parentid" => $row['parentid'],
			"weight" => $row['weight'],
			"lev" => $row['lev'],
			"relationships" => $row['relationships'],
			"gender" => $row['gender'],
			"status" => $row['status'],
			"alias" => $row['alias'],
			"full_name" => $row['full_name'] );
	}
	return array( "parentid" => isset( $parentid[0] ) ? array(0) : $parentid , "array_data" => $array_data );
}

function nv_giapha_export_pdf( $contents, $background, $row_genealogy )
{
	global $db, $db_config, $lang_module, $lang_global, $module_name, $module_data;

	require_once ( NV_ROOTDIR . '/includes/class/phpPdf/config/lang/eng.php' );
	require_once ( NV_ROOTDIR . '/includes/class/phpPdf/tcpdf.php' );

	// create new PDF document
	$pdf = new TCPDF( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );

	// set document information
	$pdf->SetCreator( PDF_CREATOR );
	$pdf->SetAuthor( 'Nicola Asuni' );
	$pdf->SetTitle( 'TCPDF Example 001' );
	$pdf->SetSubject( 'TCPDF Tutorial' );
	$pdf->SetKeywords( 'TCPDF, PDF, example, test, guide' );
    $pdf->SetFont( 'dejavusans', '', 14, '', true );
	// set default header data
	$pdf->SetHeaderData( PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $row_genealogy['title'], $row_genealogy['full_name'] . ' - ' . $row_genealogy['email'] );

	// set header and footer fonts
	$pdf->setHeaderFont( array( 'dejavusans', '', PDF_FONT_SIZE_MAIN ) );
	$pdf->setFooterFont( array( 'dejavusans', '', PDF_FONT_SIZE_DATA ) );

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont( PDF_FONT_MONOSPACED );

	//set margins
	$pdf->SetMargins( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );
	$pdf->SetHeaderMargin( PDF_MARGIN_HEADER );
	$pdf->SetFooterMargin( PDF_MARGIN_FOOTER );

	//set auto page breaks
	$pdf->SetAutoPageBreak( true, PDF_MARGIN_BOTTOM );

	//set image scale factor
	$pdf->setImageScale( PDF_IMAGE_SCALE_RATIO );

	//set some language-dependent strings
	$pdf->setLanguageArray( $l );

	// ---------------------------------------------------------

	// set default font subsetting mode
	$pdf->setFontSubsetting( true );

	// Set font
	// dejavusans is a UTF-8 Unicode font, if you only need to
	// print standard ASCII chars, you can use core fonts like
	// helvetica or times to reduce file size.

	// Add a page
	// This method has several options, check the source code documentation for more information.
	$pdf->AddPage();
	if( ! empty( $contents['biagp'] ) )
	{
		if( ! empty( $background ) && file_exists( $background ) )
		{
			// -- set new background ---
			// get the current page break margin
			$bMargin = $pdf->getBreakMargin();
			// get current auto-page-break mode
			$auto_page_break = $pdf->getAutoPageBreak();
			// disable auto-page-break
			$pdf->SetAutoPageBreak( false, 0 );
			// set bacground image
			$pdf->Image( $background, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0 );
			// restore auto-page-break status
			$pdf->SetAutoPageBreak( $auto_page_break, $bMargin );
			// set the starting point for the page content
			$pdf->setPageMark();
		}

		//============================================================+
		// END OF FILE
		//============================================================+
		// Persian and English content
		$pdf->WriteHTML( $contents['biagp'], true, 0, true, 0 );
	}
	if( $contents['phaky'] )
	{
		// output the HTML content
		$pdf->WriteHTML( "<h2><i style=\"color:#990000;text-decoration: underline;\">Phả ký</i></h2>", true, 0, true, 0 );
		$pdf->Ln( 2 );
		$pdf->WriteHTML( "<span style=\"font-size:35px;\">" . $contents['phaky'] . "</span>", true, 0, true, 0 );
	}
	if( $contents['phado'] )
	{
		// output the HTML content
		$pdf->WriteHTML( "<h2><i style=\"color:#990000;text-decoration: underline;\">Phả đồ</i></h2>", true, 0, true, 0 );
		$pdf->Ln( 2 );

		foreach( $contents['phado'] as $phado )
		{
			$pdf->writeHTML( $phado, true, false, true, false, '' );
		}
	}
	if( $contents['tocuoc'] )
	{
		// output the HTML content
		$pdf->WriteHTML( "<h2><i style=\"color:#990000;text-decoration: underline;\">Tộc ước</i></h2>", true, 0, true, 0 );
		$pdf->Ln( 2 );
		$pdf->WriteHTML( $contents['tocuoc'], true, 0, true, 0 );
	}
	if( $contents['huonghoa'] )
	{
		// output the HTML content
		$pdf->WriteHTML( "<h2><i style=\"color:#990000;text-decoration: underline;\">Hương hỏa</i></h2>", true, 0, true, 0 );
		$pdf->Ln( 2 );
		$pdf->WriteHTML( $contents['huonghoa'], true, 0, true, 0 );
	}
	if( $contents['ngaygio'] )
	{
		// output the HTML content
		$pdf->WriteHTML( "<h2><i style=\"color:#990000;text-decoration: underline;\">Ngày giỗ</i></h2>", true, 0, true, 0 );
		$pdf->Ln( 2 );
		$pdf->SetFillColor( 221, 238, 255 );
		if( empty( $array_data ) && empty( $array_anniversary ) )
		{
			$pdf->Cell( 0, 10, 'Không có dữ liệu', 1, 1, 'C', true, '', 0, false, 'T', 'M' );
		}
		else
		{
			$array_data = $contents['ngaygio']['genealogy'];
			$array_anniversary = $contents['ngaygio']['anniversary'];
			// print font name
			$pdf->Cell( 0, 10, 'Ngày giỗ:' . $array_data['title'], 1, 1, 'C', true, '', 0, false, 'T', 'M' );

			$pdf->SetFillColor( 168, 164, 204 );
			$pdf->Cell( 20, 12, 'STT', 1, 0, 'C', true, '', 0, false, 'T', 'M' );
			$pdf->Cell( 110, 12, 'Họ tên', 1, 0, 'C', true, '', 0, false, 'T', 'M' );
			$pdf->Cell( 50, 12, 'Ngày giỗ', 1, 0, 'C', true, '', 0, false, 'T', 'M' );
			$pdf->Ln();
			// set font for chars
			$pdf->SetFont( $font, '', 16 );
			$i = 0;
			foreach( $array_anniversary as $row )
			{
				$i++;
				$row['number'] = $i;
				$pdf->Cell( 20, 12, $row['number'], 1, 0, 'C', false, '', 0, false, 'T', 'M' );
				$pdf->Cell( 110, 12, $row['full_name'], 1, 0, 'C', false, '', 0, false, 'T', 'M' );
				$pdf->Cell( 50, 12, $row['date'], 1, 0, 'C', false, '', 0, false, 'T', 'M' );
				$pdf->Ln();
			}
		}
		$pdf->Ln( 10 );
	}
	if( $contents['thanhvien'] )
	{
		//array allow
		$array_key = array(
			'image',
			'full_name',
			'gender',
			'status',
			'code',
			'name1',
			'name2',
			'birthday',
			'dieday',
			'life',
			'burial' );
		$row_detail = $contents['thanhvien']['detail'];
		$pdf->SetFillColor( 221, 238, 255 );
		// print font name
		$pdf->Cell( 0, 10, 'Trưởng họ', 1, 1, 'C', true, '', 0, false, 'T', 'M' );
		// set font for chars
		$pdf->SetFont( $font, '', 13 );
        $pdf->SetFontSize(13);
		foreach( $array_key as $key )
		{
			if( $row_detail[$key] != "" )
			{
				if( $key == "image" )
				{
					$pdf->Cell( 80, 40, $lang_module['u_' . $key], 1, 0, 'C', false, '', 0, false, 'T', 'M' );
                    $pdf->Image( $row_detail[$key], '', '', 40, 40, '', '', 'L', false, 150, '', false, false, 0, false, false, false );
					$pdf->Cell( 100, 40, '', 1, 0, 'R', false, '', 0, false, '', '' );
				}
				else
				{
					$pdf->Cell( 80, 12, $lang_module['u_' . $key], 1, 0, 'C', true, '', 0, false, 'T', 'M' );
					$pdf->Cell( 100, 12, $row_detail[$key], 1, 0, 'L', true, '', 1, false, 'T', 'M' );
				}
				$pdf->Ln();
			}
		}
		if( ! empty( $row_detail['content'] ) )
		{
			$pdf->Ln( 10 );
			$pdf->WriteHTML( "<h1><i style=\"color:#990000;\">" . $lang_module['u_content'] . "</i></h1>", true, 0, true, 0 );
			$pdf->Ln( 2 );
			$pdf->WriteHTML( $row_detail['content'] );
		}
		$array_parentid = $contents['thanhvien']['parentid'];
		foreach( $array_parentid as $array_parentid_i )
		{
			$pdf->Ln( 10 );
			$pdf->SetFillColor( 221, 238, 255 );
			$pdf->Cell( 180, 10, $array_parentid_i['caption'], 1, 1, 'C', true, '', 0, false, 'T', 'M' );
			$pdf->SetFillColor( 168, 164, 204 );
			$pdf->Cell( 10, 12, 'STT', 1, 0, 'C', true, '', 0, false, 'T', 'M' );
			$pdf->Cell( 78, 12, 'Họ tên', 1, 0, 'C', true, '', 0, false, 'T', 'M' );
			$pdf->Cell( 30, 12, 'Ngày sinh', 1, 0, 'C', true, '', 0, false, 'T', 'M' );
            $pdf->Cell( 12, 12, 'Ảnh', 1, 0, 'C', true, '', 0, false, 'T', 'M' );
            $pdf->Cell( 25, 12, 'Giới tính', 1, 0, 'C', true, '', 0, false, 'T', 'M' );
			$pdf->Cell( 25, 12, 'Trạng thái', 1, 0, 'C', true, '', 0, false, 'T', 'M' );
			$pdf->Ln();
			$items = $array_parentid_i['items'];
			$number = 1;
			foreach( $items as $item )
			{
				$item['number'] = $number++;
				$pdf->Cell( 10, 12, $item['number'], 1, 0, 'C', false, '', 0, false, 'T', 'M' );
				$pdf->Cell( 78, 12, $item['full_name'], 1, 0, 'C', false, '', 0, false, 'T', 'M' );
				$pdf->Cell( 30, 12, $item['birthday'], 1, 0, 'C', false, '', 0, false, 'T', 'M' );
                $pdf->Image( $item['image'], '', '', 12, 12, '', '', 'L', false, 150, '', false, false, 0, false, false, false );
                $pdf->Cell( 12, 12, '' , 1, 0, 'C', false, '', 0, false, 'T', 'M' );
                $pdf->Cell( 25, 12, $item['gender'], 1, 0, 'C', false, '', 0, false, 'T', 'M' );
				$pdf->Cell( 25, 12, $item['status'], 1, 0, 'C', false, '', 0, false, 'T', 'M' );
				$pdf->Ln();
			}
		}
	}
    if( $contents['chitiet'] )
	{
		// output the HTML content
		$pdf->WriteHTML( "<h3><i style=\"color:#990000;text-decoration: underline;\">Thông tin chi tiết</i></h3>", true, 0, true, 0 );
		$pdf->Ln( 2 );
		$pdf->WriteHTML( $contents['chitiet']['them'], true, 0, true, 0 );
        $array_parentid = $contents['chitiet']['array_parentid'];
        foreach( $array_parentid as $array_parentid_i )
		{
			$pdf->Ln( 10 );
			$pdf->SetFillColor( 221, 238, 255 );
			$pdf->Cell( 180, 10, $array_parentid_i['caption'], 1, 1, 'C', true, '', 0, false, 'T', 'M' );
			$pdf->SetFillColor( 168, 164, 204 );
			$pdf->Cell( 10, 12, 'STT', 1, 0, 'C', true, '', 0, false, 'T', 'M' );
			$pdf->Cell( 78, 12, 'Họ tên', 1, 0, 'C', true, '', 0, false, 'T', 'M' );
			$pdf->Cell( 30, 12, 'Ngày sinh', 1, 0, 'C', true, '', 0, false, 'T', 'M' );
            $pdf->Cell( 12, 12, 'Ảnh', 1, 0, 'C', true, '', 0, false, 'T', 'M' );
            $pdf->Cell( 25, 12, 'Giới tính', 1, 0, 'C', true, '', 0, false, 'T', 'M' );
			$pdf->Cell( 25, 12, 'Trạng thái', 1, 0, 'C', true, '', 0, false, 'T', 'M' );
			$pdf->Ln();
			$items = $array_parentid_i['items'];
			$number = 1;
			foreach( $items as $item )
			{
				$item['number'] = $number++;
				$pdf->Cell( 10, 12, $item['number'], 1, 0, 'C', false, '', 0, false, 'T', 'M' );
				$pdf->Cell( 78, 12, $item['full_name'], 1, 0, 'C', false, '', 0, false, 'T', 'M' );
				$pdf->Cell( 30, 12, $item['birthday'], 1, 0, 'C', false, '', 0, false, 'T', 'M' );
                $pdf->Image( $item['image'], '', '', 12, 12, '', '', 'L', false, 150, '', false, false, 0, false, false, false );
                $pdf->Cell( 12, 12, '' , 1, 0, 'C', false, '', 0, false, 'T', 'M' );
                $pdf->Cell( 25, 12, $item['gender'], 1, 0, 'C', false, '', 0, false, 'T', 'M' );
				$pdf->Cell( 25, 12, $item['status'], 1, 0, 'C', false, '', 0, false, 'T', 'M' );
				$pdf->Ln();
			}
		}
	}
	// set LTR direction for english translation
	$pdf->setRTL( false );

	$pdf->SetFontSize( 10 );

	// print newline
	$pdf->Ln();

	//Close and output PDF document
	$pdf->Output( $row_genealogy['alias'], 'I' );
}




$sql = "SELECT fid, title, alias FROM " . NV_PREFIXLANG . "_" . $module_data . "_family ORDER BY weight ASC";
$array_family = nv_db_cache( $sql, 'fid', $module_name );