<?php

/**
 * @Project NUKEVIET 4.x
 * @Author webvang (hoang.nguyen@webvang.vn)
 * @Copyright (C) 2015 Webvang. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 11/10/2015 00:00
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$timecheckstatus = $module_config[$module_name]['timecheckstatus'];
if( $timecheckstatus > 0 and $timecheckstatus < NV_CURRENTTIME )
{
	nv_set_status_module();
}

/**
 * nv_set_status_module()
 *
 * @return
 */
function nv_set_status_module()
{
	global $db, $module_name, $module_data, $global_config;

	$check_run_cronjobs = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/data_logs/cronjobs_' . md5( $module_data . 'nv_set_status_module' . $global_config['sitekey'] ) . '.txt';
	$p = NV_CURRENTTIME - 300;
	if( file_exists( $check_run_cronjobs ) and @filemtime( $check_run_cronjobs ) > $p )
	{
		return;
	}
	file_put_contents( $check_run_cronjobs, '' );

	//status_0 = "Cho duyet";
	//status_1 = "Xuat ban";
	//status_2 = "Hen gio dang";
	//status_3= "Het han";

	// Dang cai bai cho kich hoat theo thoi gian
	$query = $db->query( 'SELECT id, listfid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy WHERE status=2 AND publtime < ' . NV_CURRENTTIME . ' ORDER BY publtime ASC' );
	while( list( $id, $listfid ) = $query->fetch( 3 ) )
	{
		$array_fid = explode( ',', $listfid );
		foreach( $array_fid as $fid_i )
		{
			$fid_i = intval( $fid_i );
			if( $fid_i > 0 )
			{
				$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $fid_i . ' SET status=1 WHERE id=' . $id );
			}
		}
		$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy SET status=1 WHERE id=' . $id );
	}

	// Ngung hieu luc cac bai da het han
	$query = $db->query( 'SELECT id, listfid, archive FROM ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy WHERE status=1 AND exptime > 0 AND exptime <= ' . NV_CURRENTTIME . ' ORDER BY exptime ASC' );
	while( list( $id, $listfid, $archive ) = $query->fetch( 3 ) )
	{
		if( intval( $archive ) == 0 )
		{
			nv_del_content_module( $id );
		}
		else
		{
			nv_archive_content_module( $id, $listfid );
		}
	}

	// Tim kiem thoi gian chay lan ke tiep
	$time_publtime = $db->query( 'SELECT min(publtime) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy WHERE status=2 AND publtime > ' . NV_CURRENTTIME )->fetchColumn();
	$time_exptime = $db->query( 'SELECT min(exptime) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy WHERE status=1 AND exptime > ' . NV_CURRENTTIME )->fetchColumn();

	$timecheckstatus = min( $time_publtime, $time_exptime );
	if( ! $timecheckstatus ) $timecheckstatus = max( $time_publtime, $time_exptime );

	$sth = $db->prepare( "UPDATE " . NV_CONFIG_GLOBALTABLE . " SET config_value = :config_value WHERE lang = '" . NV_LANG_DATA . "' AND module = :module_name AND config_name = 'timecheckstatus'" );
	$sth->bindValue( ':module_name', $module_name, PDO::PARAM_STR );
	$sth->bindValue( ':config_value', intval( $timecheckstatus ), PDO::PARAM_STR );
	$sth->execute();

	nv_del_moduleCache( 'settings' );
	nv_del_moduleCache( $module_name );

	unlink( $check_run_cronjobs );
	clearstatcache();
}

/**
 * nv_del_content_module()
 *
 * @param mixed $id
 * @return
 */
function nv_del_content_module( $id )
{
	global $db, $module_name, $module_data, $title, $lang_module;
	$content_del = 'NO_' . $id;
	$title = '';
	list( $id, $listfid, $title, $homeimgfile ) = $db->query( 'SELECT id, listfid, title, homeimgfile FROM ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy WHERE id=' . intval( $id ) )->fetch( 3 );
	if( $id > 0 )
	{
		$number_no_del = 0;
		$array_fid = explode( ',', $listfid );
		foreach( $array_fid as $fid_i )
		{
			$fid_i = intval( $fid_i );
			if( $fid_i > 0 )
			{
				$_sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_' . $fid_i . ' WHERE id=' . $id;
				if( ! $db->exec( $_sql ) )
				{
					++$number_no_del;
				}
			}
		}
		
		$_sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy WHERE id=' . $id;
		if( ! $db->exec( $_sql ) )
		{
			++$number_no_del;
		}

		$_sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_bodyhtml_' . ceil( $id / 2000 ) . ' WHERE id = ' . $id;
		if( ! $db->exec( $_sql ) )
		{
			++$number_no_del;
		}
		
		$_sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_bodytext WHERE id = ' . $id;
		if( ! $db->exec( $_sql ) )
		{
			++$number_no_del;
		}
		
		$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_comment WHERE module=' . $db->quote( $module_name ) . ' AND id = ' . $id );
		
		$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_tags SET numnews = numnews-1 WHERE tid IN (SELECT tid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id=' . $id . ')' );
		$db->query( 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_tags_id WHERE id = ' . $id );
		
		if( $number_no_del == 0 )
		{
			$content_del = 'OK_' . $id .'_' . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true );
		}
		else
		{
			$content_del = 'ERR_' . $lang_module['error_del_content'];
		}
	}
	return $content_del;
}

/**
 * nv_archive_content_module()
 *
 * @param mixed $id
 * @param mixed $listfid
 * @return
 */
function nv_archive_content_module( $id, $listfid )
{
	global $db, $module_data;
	$array_fid = explode( ',', $listfid );
	foreach( $array_fid as $fid_i )
	{
		$fid_i = intval( $fid_i );
		if( $fid_i > 0 )
		{
			$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_' . $fid_i . ' SET status=3 WHERE id=' . $id );
		}
	}
	$db->query( 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_genealogy SET status=3 WHERE id=' . $id );
}

/**
 * nv_link_edit_page()
 *
 * @param mixed $id
 * @return
 */
function nv_link_edit_page( $id )
{
	global $lang_global, $lang_module, $module_name;
	$link = "<a  href=\"" . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=manager&amp;id=" . $id . "\"><em class=\"fa fa-edit margin-right\"></em> " . $lang_module['manager'] . "</a>";
	return $link;
}

/**
 * nv_link_delete_page()
 *
 * @param mixed $id
 * @return
 */
function nv_link_delete_page( $id, $detail = 0)
{
	global $lang_global, $module_name;
	$link = "<a class=\"btn btn-danger btn-xs\" href=\"javascript:void(0);\" onclick=\"nv_del_genealogy(" . $id . ", '" . md5( $id . session_id() ) . "','" . NV_BASE_ADMINURL . "', " . $detail . ")\"><em class=\"fa fa-trash-o margin-right\"></em> " . $lang_global['delete'] . "</a>";
	return $link;
}

/**
 * nv_genealogy_get_bodytext()
 *
 * @param mixed $bodytext
 * @return
 */
function nv_genealogy_get_bodytext( $bodytext )
{
	// Get image tags
	if( preg_match_all( "/\<img[^\>]*src=\"([^\"]*)\"[^\>]*\>/is", $bodytext, $match ) )
	{
		foreach( $match[0] as $key => $_m )
		{
			$textimg = '';
			if( strpos( $match[1][$key], 'data:image/png;base64' ) === false )
			{
				$textimg = " " . $match[1][$key];
			}
			if( preg_match_all( "/\<img[^\>]*alt=\"([^\"]+)\"[^\>]*\>/is", $_m, $m_alt ) )
			{
				$textimg .= " " . $m_alt[1][0];
			}
			$bodytext = str_replace( $_m, $textimg, $bodytext );
		}
	}
	// Get link tags
	if( preg_match_all( "/\<a[^\>]*href=\"([^\"]+)\"[^\>]*\>(.*)\<\/a\>/isU", $bodytext, $match ) )
	{
		foreach( $match[0] as $key => $_m )
		{
			$bodytext = str_replace( $_m, $match[1][$key] . " " . $match[2][$key], $bodytext );
		}
	}

	$bodytext = str_replace( '&nbsp;', ' ', strip_tags( $bodytext ) );
	return preg_replace( '/[ ]+/', ' ', $bodytext );
}

if( ! nv_function_exists( 'INT' ) )
{
	function INT($d) {
		return floor($d);
	}
}
if( ! nv_function_exists( 'jdFromDate' ) )
{
	function jdFromDate($dd, $mm, $yy) {
		$a = INT((14 - $mm) / 12);
		$y = $yy + 4800 - $a;
		$m = $mm + 12 * $a - 3;
		$jd = $dd + INT((153 * $m + 2) / 5) + 365 * $y + INT($y / 4) - INT($y / 100) + INT($y / 400) - 32045;
		if ($jd < 2299161) {
			$jd = $dd + INT((153* $m + 2)/5) + 365 * $y + INT($y / 4) - 32083;
		}
		return $jd;
	}
}
if( ! nv_function_exists( 'jdToDate' ) )
{
	function jdToDate($jd) {
		if ($jd > 2299160) { // After 5/10/1582, Gregorian calendar
			$a = $jd + 32044;
			$b = INT((4*$a+3)/146097);
			$c = $a - INT(($b*146097)/4);
		} else {
			$b = 0;
			$c = $jd + 32082;
		}
		$d = INT((4*$c+3)/1461);
		$e = $c - INT((1461*$d)/4);
		$m = INT((5*$e+2)/153);
		$day = $e - INT((153*$m+2)/5) + 1;
		$month = $m + 3 - 12*INT($m/10);
		$year = $b*100 + $d - 4800 + INT($m/10);
		//echo "day = $day, month = $month, year = $year\n";
		return array($day, $month, $year);
	}
}
if( ! nv_function_exists( 'getNewMoonDay' ) )
{
	function getNewMoonDay($k, $timeZone) {
		$T = $k/1236.85; // Time in Julian centuries from 1900 January 0.5
		$T2 = $T * $T;
		$T3 = $T2 * $T;
		$dr = M_PI/180;
		$Jd1 = 2415020.75933 + 29.53058868*$k + 0.0001178*$T2 - 0.000000155*$T3;
		$Jd1 = $Jd1 + 0.00033*sin((166.56 + 132.87*$T - 0.009173*$T2)*$dr); // Mean new moon
		$M = 359.2242 + 29.10535608*$k - 0.0000333*$T2 - 0.00000347*$T3; // Sun's mean anomaly
		$Mpr = 306.0253 + 385.81691806*$k + 0.0107306*$T2 + 0.00001236*$T3; // Moon's mean anomaly
		$F = 21.2964 + 390.67050646*$k - 0.0016528*$T2 - 0.00000239*$T3; // Moon's argument of latitude
		$C1=(0.1734 - 0.000393*$T)*sin($M*$dr) + 0.0021*sin(2*$dr*$M);
		$C1 = $C1 - 0.4068*sin($Mpr*$dr) + 0.0161*sin($dr*2*$Mpr);
		$C1 = $C1 - 0.0004*sin($dr*3*$Mpr);
		$C1 = $C1 + 0.0104*sin($dr*2*$F) - 0.0051*sin($dr*($M+$Mpr));
		$C1 = $C1 - 0.4068*sin($Mpr*$dr) + 0.0161*sin($dr*2*$Mpr);
		$C1 = $C1 - 0.0004*sin($dr*3*$Mpr);
		$C1 = $C1 + 0.0104*sin($dr*2*$F) - 0.0051*sin($dr*($M+$Mpr));
		$C1 = $C1 - 0.0074*sin($dr*($M-$Mpr)) + 0.0004*sin($dr*(2*$F+$M));
		$C1 = $C1 - 0.0004*sin($dr*(2*$F-$M)) - 0.0006*sin($dr*(2*$F+$Mpr));
		$C1 = $C1 + 0.0010*sin($dr*(2*$F-$Mpr)) + 0.0005*sin($dr*(2*$Mpr+$M));
		if ($T < -11) {
			$deltat= 0.001 + 0.000839*$T + 0.0002261*$T2 - 0.00000845*$T3 - 0.000000081*$T*$T3;
		} else {
			$deltat= -0.000278 + 0.000265*$T + 0.000262*$T2;
		};
		$JdNew = $Jd1 + $C1 - $deltat;
		//echo "JdNew = $JdNew\n";
		return INT($JdNew + 0.5 + $timeZone/24);
	}
}
if( ! nv_function_exists( 'getSunLongitude' ) )
{
	function getSunLongitude($jdn, $timeZone) {
		$T = ($jdn - 2451545.5 - $timeZone/24) / 36525; // Time in Julian centuries from 2000-01-01 12:00:00 GMT
		$T2 = $T * $T;
		$dr = M_PI/180; // degree to radian
		$M = 357.52910 + 35999.05030*$T - 0.0001559*$T2 - 0.00000048*$T*$T2; // mean anomaly, degree
		$L0 = 280.46645 + 36000.76983*$T + 0.0003032*$T2; // mean longitude, degree
		$DL = (1.914600 - 0.004817*$T - 0.000014*$T2)*sin($dr*$M);
		$DL = $DL + (0.019993 - 0.000101*$T)*sin($dr*2*$M) + 0.000290*sin($dr*3*$M);
		$L = $L0 + $DL; // true longitude, degree
		//echo "\ndr = $dr, M = $M, T = $T, DL = $DL, L = $L, L0 = $L0\n";
		// obtain apparent longitude by correcting for nutation and aberration
		$omega = 125.04 - 1934.136 * $T;
		$L = $L - 0.00569 - 0.00478 * sin($omega * $dr);
		$L = $L*$dr;
		$L = $L - M_PI*2*(INT($L/(M_PI*2))); // Normalize to (0, 2*PI)
		return INT($L/M_PI*6);
	}
}
if( ! nv_function_exists( 'getLunarMonth11' ) )
{
	function getLunarMonth11($yy, $timeZone) {
		$off = jdFromDate(31, 12, $yy) - 2415021;
		$k = INT($off / 29.530588853);
		$nm = getNewMoonDay($k, $timeZone);
		$sunLong = getSunLongitude($nm, $timeZone); // sun longitude at local midnight
		if ($sunLong >= 9) {
			$nm = getNewMoonDay($k-1, $timeZone);
		}
		return $nm;
	}
}
if( ! nv_function_exists( 'getLeapMonthOffset' ) )
{
	function getLeapMonthOffset($a11, $timeZone) {
		$k = INT(($a11 - 2415021.076998695) / 29.530588853 + 0.5);
		$last = 0;
		$i = 1; // We start with the month following lunar month 11
		$arc = getSunLongitude(getNewMoonDay($k + $i, $timeZone), $timeZone);
		do {
			$last = $arc;
			$i = $i + 1;
			$arc = getSunLongitude(getNewMoonDay($k + $i, $timeZone), $timeZone);
		} while ($arc != $last && $i < 14);
		return $i - 1;
	}
}
if( ! nv_function_exists( 'convertSolar2Lunar' ) )
{
	/* Comvert solar date dd/mm/yyyy to the corresponding lunar date */
	function convertSolar2Lunar($dd, $mm, $yy, $timeZone) {
		$dayNumber = jdFromDate($dd, $mm, $yy);
		$k = INT(($dayNumber - 2415021.076998695) / 29.530588853);
		$monthStart = getNewMoonDay($k+1, $timeZone);
		if ($monthStart > $dayNumber) {
			$monthStart = getNewMoonDay($k, $timeZone);
		}
		$a11 = getLunarMonth11($yy, $timeZone);
		$b11 = $a11;
		if ($a11 >= $monthStart) {
			$lunarYear = $yy;
			$a11 = getLunarMonth11($yy-1, $timeZone);
		} else {
			$lunarYear = $yy+1;
			$b11 = getLunarMonth11($yy+1, $timeZone);
		}
		$lunarDay = $dayNumber - $monthStart + 1;
		$diff = INT(($monthStart - $a11)/29);
		$lunarLeap = 0;
		$lunarMonth = $diff + 11;
		if ($b11 - $a11 > 365) {
			$leapMonthDiff = getLeapMonthOffset($a11, $timeZone);
			if ($diff >= $leapMonthDiff) {
				$lunarMonth = $diff + 10;
				if ($diff == $leapMonthDiff) {
					$lunarLeap = 1;
				}
			}
		}
		if ($lunarMonth > 12) {
			$lunarMonth = $lunarMonth - 12;
		}
		if ($lunarMonth >= 11 && $diff < 4) {
			$lunarYear -= 1;
		}
		return array($lunarDay, $lunarMonth, $lunarYear, $lunarLeap);
	}
}
if( ! nv_function_exists( 'convertLunar2Solar' ) )
{
	/* Convert a lunar date to the corresponding solar date */
	function convertLunar2Solar($lunarDay, $lunarMonth, $lunarYear, $lunarLeap, $timeZone) {
		if ($lunarMonth < 11) {
			$a11 = getLunarMonth11($lunarYear-1, $timeZone);
			$b11 = getLunarMonth11($lunarYear, $timeZone);
		} else {
			$a11 = getLunarMonth11($lunarYear, $timeZone);
			$b11 = getLunarMonth11($lunarYear+1, $timeZone);
		}
		$k = INT(0.5 + ($a11 - 2415021.076998695) / 29.530588853);
		$off = $lunarMonth - 11;
		if ($off < 0) {
			$off += 12;
		}
		if ($b11 - $a11 > 365) {
			$leapOff = getLeapMonthOffset($a11, $timeZone);
			$leapMonth = $leapOff - 2;
			if ($leapMonth < 0) {
				$leapMonth += 12;
			}
			if ($lunarLeap != 0 && $lunarMonth != $leapMonth) {
				return array(0, 0, 0);
			} else if ($lunarLeap != 0 || $off >= $leapOff) {
				$off += 1;
			}
		}
		$monthStart = getNewMoonDay($k + $off, $timeZone);
		return jdToDate($monthStart + $lunarDay - 1);
	}
}