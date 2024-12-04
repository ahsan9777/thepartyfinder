<?php
function user_logs($user_id, $llog_id, $ulog_ip, $ulog_page, $ulog_action, $ulog_parameters)
{
	mysqli_query($GLOBALS['conn'], "INSERT INTO user_logs (`user_id`, `llog_id`, `ulog_ip`, `ulog_page`, `ulog_action`, `ulog_parameters`) VALUES ('" . dbStr($user_id) . "','" . dbStr($llog_id) . "','" . dbStr($ulog_ip) . "','" . dbStr($ulog_page) . "','" . dbStr($ulog_action) . "','" . dbStr($ulog_parameters) . "')") or die(mysqli_error($GLOBALS['conn']));
}
function safe($value)
{
	return mysql_real_escape_string($value);
}

function rmPageFromURL($url)
{
	/* $parsed = parse_url($url);
	$query = $parsed['query']; */
	$query = $url;
	parse_str($query, $params);
	unset($params['page']);
	$str = http_build_query($params);
	return $str . "&";
}

function get_contents($url)
{
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$data = curl_exec($curl);
	print($data);
	curl_close($curl);
	return $data;
}

function retArray($qry)
{
	$return_array = array();
	$rs = mysqli_query($GLOBALS['conn'], $qry);
	if (mysqli_num_rows($rs) > 0) {
		while ($row = mysqli_fetch_row($rs)) {
			$return_array[] = $row[0];
		}
	} else {
		$return_array[] = 0;
	}
	return $return_array;
}

function genAppKey($mem_id, $app_id, $app_name)
{
	$str_gen = $mem_id . "_" . $app_id . "_" . $app_name;
	$appKey = base64_encode($str_gen);
	return $appKey;
}

function chkAppKey($apk_key)
{
	$app_id = 0;
	$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM mem_appkey WHERE apk_key='" . $apk_key . "'");
	if (mysqli_num_rows($rs) > 0) {
		$row = mysqli_fetch_object($rs);
		$app_id = $row->apk_id;
	}
	return $app_id;
}


/*function chkAppKey($apk_key, $app_id){
	$isValid = 0;
	$rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM mem_appkey WHERE apk_id='".$app_id."' AND apk_key='".$apk_key."'");
	if(mysqli_num_rows($rs)){
		$isValid = 0;
	}
	return $isValid;
}*/

function ret_com_set_op($val, $id, $action = 0)
{
	$strQry = '';
	if ($action > 0) {
		$strQry = '&action=' . $action;
	}
	$ret = '';
	switch ($val) {
		case 1:
			//$ret = '<span class="badge badge-green2">Accepté</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">YES &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnUnsets=1&id=' . $id . $strQry . '">NO</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
		default:
			//$ret = '<span class="badge badge-orange">Stand By</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown">NO &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnSets=1&id=' . $id . $strQry . '">YES</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
	}
	return $ret;
}
function ret_count_set_op($val, $id, $action, $qryStrURL)
{
	$strQry = '';
	if ($action > 0) {
		$strQry = '&action=' . $action;
	}
	$ret = '';
	switch ($val) {
		case 1:
			//$ret = '<span class="badge badge-green2">Accepté</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">YES &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btncountUnsets=1&id=' . $id . $strQry . '&' . $qryStrURL . '">NO</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
		default:
			//$ret = '<span class="badge badge-orange">Stand By</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown">NO &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btncountSets=1&id=' . $id . $strQry . '&' . $qryStrURL . '">YES</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
	}
	return $ret;
}
function peroject_status($val, $proj_id, $action = 0)
{
	$strQry = '';
	if ($action > 0) {
		$strQry = '&action=' . $action;
	}
	$ret = '';
	switch ($val) {
		case 1:
			//$ret = '<span class="badge badge-green2">Accepté</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">Current &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnPast=1&proj_id=' . $proj_id . $strQry . '">Past</a></li>';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnFuture=1&proj_id=' . $proj_id . $strQry . '">Future</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
		case 2:
			//$ret = '<span class="badge badge-green2">Accepté</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-warning dropdown-toggle" data-toggle="dropdown">Future &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnPast=1&proj_id=' . $proj_id . $strQry . '">Past</a></li>';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnCurrent=1&proj_id=' . $proj_id . $strQry . '">Current</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
		default:
			//$ret = '<span class="badge badge-green2">Accepté</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">Past &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnCurrent=1&proj_id=' . $proj_id . $strQry . '">Current</a></li>';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnFuture=1&proj_id=' . $proj_id . $strQry . '">Future</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
	}
	return $ret;
}
function set_permissions($val, $id, $action = 0)
{
	$strQry = '';
	if ($action > 0) {
		$strQry = '&action=' . $action;
	}
	$ret = '';
	switch ($val) {
		case 1:
			//$ret = '<span class="badge badge-green2">Accepté</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-success dropdown-toggle" data-toggle="dropdown">YES &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnUnsets=1&type_id=' . $id . $strQry . '">NO</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
		default:
			//$ret = '<span class="badge badge-orange">Stand By</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-danger dropdown-toggle" data-toggle="dropdown">NO &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnSets=1&type_id=' . $id . $strQry . '">YES</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
	}
	return $ret;
}

function ret_com_show_op($val, $id, $action = 0)
{
	$strQry = '';
	if ($action > 0) {
		$strQry = '&action=' . $action;
	}
	$ret = '';
	switch ($val) {
		case 1:
			//$ret = '<span class="badge badge-green2">Accepté</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-inverse dropdown-toggle" data-toggle="dropdown">SHOW &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnCNotShow=1&id=' . $id . $strQry . '">NOT SHOW</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
		default:
			//$ret = '<span class="badge badge-orange">Stand By</span>';
			$ret = '<div class="btn-group dropdown">';
			$ret .= '<button class="btn btn-xs btn-light-grey dropdown-toggle" data-toggle="dropdown">NOT SHOW &nbsp;<span class="caret"></span></button>';
			$ret .= '<ul class="dropdown-menu context">';
			$ret .= '<li><a href="' . $_SERVER['PHP_SELF'] . '?btnCShow=1&id=' . $id . $strQry . '">SHOW</a></li>';
			$ret .= '</ul>';
			$ret .= '</div>';
			break;
	}
	return $ret;
}

function getLnt($zip)
{
	$url = "http://maps.googleapis.com/maps/api/geocode/json?address=
	" . urlencode($zip) . "&sensor=false";
	$result_string = file_get_contents($url);
	$result = json_decode($result_string, true);
	$result1[] = $result['results'][0];
	$result2[] = $result1[0]['geometry'];
	$result3[] = $result2[0]['location'];
	return $result3[0];
}

function dbStr($str)
{
	$string = str_replace("'", "''", $str); // Converts ' to ' in database, but ' to '' in the static page
	//return stripslashes($string); // Removes any forward slashes from string
	//$string = $str;
	return $string;
}
function url_clean($string)
{
	$string = str_replace(" ", "-", strtolower(trim($string)));
	$string = str_replace(array(',', '’'), "", $string);
	return $string;
}
function cleanArray($array)
{
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			cleanArray($value);
		} else {
			$array[$key] = mysql_real_escape_string(urldecode($value));
		}
	}
	return $array;
}
function base_url($path = NULL)
{
	if ($path == NULL) {
		$dirArray = pathinfo($_SERVER['SCRIPT_NAME']);
	} else {
		$dirArray = pathinfo($path);
	}
	$_SERVER['HTTP_HOST'] = @str_replace("/", "", @$_SERVER['HTTP_HOST']);
	$serverAddress = "http://" . $_SERVER['HTTP_HOST'] . "/";
	$dirArray['dirname'] = @trim(@$dirArray['dirname']);
	if (!empty($dirArray['dirname']) && $dirArray['dirname'] != "/") {
		$ptn = "/^\//";  // Regex
		$rpltxt = "";  // Replacement string
		$dirArray['dirname'] = preg_replace($ptn, $rpltxt, $dirArray['dirname']);
		$serverAddress .= $dirArray['dirname'] . "/";
	}
	return $serverAddress;
}
function FillSelectedMul($Table, $IDField, $TextField, $active)
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table ORDER BY $IDField";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	$cou = 0;
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if (isset($active[$cou][$IDField]) && $row[0] == $active[$cou][$IDField]) {
				if ($row[0] == $active[$cou][$IDField]) {
					print("<option value=\"$row[0]\" selected>$row[1]</option>");
					$cou++;
				}
			} else {
				print("<option value=\"$row[0]\">$row[1]</option>");
			}
		}
	}
}

function bread_crum2($page, $cat_id)
{
	$bCrum = "";
	if (isset($_GET['property_id'])) {
		$Query = "SELECT pd.property_name As childs, pc.pcat_value AS parent FROM property_details AS pd LEFT OUTER JOIN property_cat AS pc ON pd.property_id=pc.pcat_id WHERE pd.property_id=" . $_GET['property_id'] . " LIMIT 1";
		$brdCrm1 = mysqli_query($GLOBALS['conn'], $Query);
		if (mysqli_num_rows($brdCrm1) > 0) {
			while ($row = mysqli_fetch_object($brdCrm1)) {
				$bCrum .= "
					<a href='$page.php?cat_id=$cat_id'>" . returnName("pcat_value", "property_cat", "pcat_id", $cat_id) . "</a> / 
					" . ucwords($row->childs) . "
				";
			}
		}
	} else {
		$bCrum .= returnName("pcat_value", "property_cat", "pcat_id", $cat_id);
	}
	return $bCrum;
}

function testimonials($section_id)
{
	$result = "";
	$Query = "SELECT t.* FROM testimonials AS t WHERE t.section_id='" . $section_id . "' AND t.status_id=1 ORDER BY RAND() LIMIT 1";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) >= 1) {
		while ($row = mysqli_fetch_object($rs)) {
			$result .= "
			<div class='featured'>
				<div class='title'>
					<h4>TESTIMONIALS</h4>
				</div>
				<blockquote>
					<p> 
						$row->tm_details
						<span>$row->tm_signature</span>
					</p>
				</blockquote>
			</div>";
		}
	}
	return $result;
}

function latest_news($section_id)
{
	$result = "";
	$Query = "SELECT n.* FROM news AS n WHERE n.section_id='" . $section_id . "' AND n.status_id=1 ORDER BY n.news_date DESC LIMIT 3";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) >= 1) {
		$result .= '
		<div class="featured">
			<div class="title">
				<h4>LATEST NEWS</h4>
			</div>
			<div class="recent_posts classic">';
		while ($row = mysqli_fetch_object($rs)) {
			$date = "";
			if ($row->news_date != "") {
				$arrdate = explode("-", $row->news_date);
				$arrdate2 = explode(" ", $arrdate[2]);
				$date = date("M j", mktime(0, 0, 0, $arrdate[1], $arrdate2[0], $arrdate[0]));
			}
			if ($row->news_date == "0000-00-00") {
				$date = "";
			}
			$arrdate3 = explode(" ", $date);
			$result .= "
				<ul>
					<li class='date'><span class='day'>$arrdate3[1]</span>$arrdate3[0]</li>
					<li>
						<span class='title'>
							<a href='#'>";
			$result .= limit_text($row->news_title, 50);
			$result .= "
							</a>
						</span>";
			$result .= limit_text($row->news_title, 75);
			$result .= "
					</li>
				</ul>";
		}
		$result .= '	
			</div>
		</div>';
	}
	return $result;
}

function left_navigation($section_id, $page_name)
{
	$result = "";
	$strQuery = "SELECT cat_id, cat_name FROM categories WHERE cat_parentid = 0 AND status_id=1 AND section_id='" . $section_id . "' ORDER BY cat_id";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			$strQry = "SELECT cat_id, cat_name FROM categories WHERE cat_parentid = $row[0] ORDER BY cat_id";
			$nRs = mysqli_query($GLOBALS['conn'], $strQry);
			if (mysqli_num_rows($nRs) >= 1) {
				$result .= '<ul class="sub-menu">';
				while ($row1 = mysqli_fetch_row($nRs)) {
					$result .= "<li><a href='" . $page_name . "?cid=$row1[0]'>$row1[1]</a>";
					$strQry3 = "SELECT cat_id, cat_name FROM categories WHERE cat_parentid = $row1[0] ORDER BY cat_id";
					$nRs3 = mysqli_query($GLOBALS['conn'], $strQry3);
					if (mysqli_num_rows($nRs3) >= 1) {
						$result .= "<ul class='sub-menu'>";
						while ($row3 = mysqli_fetch_row($nRs3)) {
							$result .= "<li class='current_page_item first'><a href='" . $page_name . "?cid=$row1[0]&sid=$row3[0]'>$row3[1]</a></li>";
						}
						$result .= "</ul>";
					}
					$result .= '</li>';
				}
				$result .= '</ul>';
			}
		}
	}
	return $result;
}

function right_navigation($section_id, $page_name)
{
	$result = "";
	$result .= '
	<div class="box three last">
		<div class="accordion">					
	';
	$strQuery = "SELECT cat_id, cat_name FROM categories WHERE cat_parentid=0 AND status_id=1 AND section_id='" . $section_id . "' ORDER BY cat_id";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			$strQry = "SELECT cat_id, cat_name FROM categories WHERE cat_parentid = $row[0] ORDER BY cat_id";
			$nRs = mysqli_query($GLOBALS['conn'], $strQry);
			if (mysqli_num_rows($nRs) >= 1) {
				while ($row1 = mysqli_fetch_row($nRs)) {
					$result .= "<div class='title'><span>$row1[1]</span></div>";
					$strQry3 = "SELECT cat_id, cat_name FROM categories WHERE cat_parentid = $row1[0] ORDER BY cat_id";
					$nRs3 = mysqli_query($GLOBALS['conn'], $strQry3);
					if (mysqli_num_rows($nRs3) >= 1) {
						$result .= "
					<div class='pane'>
						<ul class='check'>";
						while ($row3 = mysqli_fetch_row($nRs3)) {
							$result .= "<li><a href='$page_name?cid=$row1[0]&sid=$row3[0]'>$row3[1]</a></li>";
						}
						$result .= "
						</ul>
						<div class='clear'></div>
					</div>
					";
					}
				}
			}
		}
	}
	$result .= '
		</div>
	</div>                    
	';
	return $result;
}

function left_featured($section_id, $page_name)
{
	$result = "";
	$result .= '
  <h4>FEATURED</h4>
  <div class="ppy" id="ppy3">
	<ul class="ppy-imglist">';
	$Query = "SELECT DISTINCT l.list_id, list_name, i.img_title, i.img_file FROM listings AS l LEFT OUTER JOIN listing_images AS i ON l.list_id=i.list_id  WHERE l.is_featured=1 AND l.status_id=1 AND l.section_id='" . $section_id . "' AND i.img_default=1 ORDER BY RAND()";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	while ($row = mysqli_fetch_object($rs)) {
		$result .= "<li>
						<a href='images/listings/$row->img_file'>
							<img src='images/listings/th/$row->img_file'  alt='$row->img_title'/>
						</a>
						<div class='ppy-extcaption'>
							<h5>
								<a href='$page_name$row->list_id' title='$row->list_name'>$row->list_name</a>
							</h5>
						</div>
					</li>";
	}
	$result .= '</ul>
	<div class="ppy-outer">
	  <div class="ppy-stage">
		<div class="ppy-nav ">
		  <div class="nav-wrap"> <a class="ppy-prev" title="Previous image">Previous Post</a> <a class="ppy-switch-enlarge" title="Enlarge">Enlarge</a> <a class="ppy-switch-compact" title="Close">Close</a> <a class="ppy-next" title="Next image">Next Post</a> </div>
		</div>
		<div class="ppy-counter"> <strong class="ppy-current"></strong> / <strong class="ppy-total"></strong> </div>
	  </div>
	  <div class="ppy-caption"><span class="ppy-text"></span></div>
	</div>
  </div>
  <div class="clear"></div>';
	return $result;
}

function banner_news()
{
	$result = "";
	$rs_c = mysqli_query($GLOBALS['conn'], "SELECT COUNT(*) as cou_b from banners");
	$row_c = mysqli_fetch_object($rs_c);
	$Query = "SELECT pd.*, lc.location_value, pt.ptype_value, pc.pcat_value, ps.pstatus_value, ps.pstatus_value_fr, st.status_name, bd.bedrom_value, ad.ad_value, au.au_value FROM property_details AS pd LEFT OUTER JOIN locations AS lc ON pd.location_id=lc.location_id LEFT OUTER JOIN property_type AS pt ON pd.ptype_id=pt.ptype_id LEFT OUTER JOIN property_cat AS pc ON pd.pcat_id=pc.pcat_id LEFT OUTER JOIN property_status AS ps ON pd.pstatus_id=ps.pstatus_id LEFT OUTER JOIN status AS st ON pd.status_id=st.status_id LEFT OUTER JOIN bedrooms AS bd ON pd.bedrom_id=bd.bedrom_id LEFT OUTER JOIN area_digit AS ad ON pd.ad_id=ad.ad_id LEFT OUTER JOIN area_unit AS au ON pd.au_id=au.au_id LIMIT " . $row_c->cou_b;
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	while ($row = mysqli_fetch_object($rs)) {
		if (@$_SESSION['french'] == 1) {
			$pro_d = $row->property_short_detail_fr;
			$pro_n = $row->property_name_fr;
			$pro_s = $row->pstatus_value_fr;
		} else {
			$pro_d = $row->property_short_detail;
			$pro_n = $row->property_name;
			$pro_s = $row->pstatus_value;
		}
		$result .= "
			<li>
				<h3 class='desc_title'><a href='details.php?property_id=$row->property_id&cat_id=$row->pcat_id'>$pro_n</a></h3>
				<p class='desc_sub_title'><a href='details.php?property_id=$row->property_id&cat_id=$row->pcat_id'>$row->date</a></p>
				<p class='desc_status'>$row->ad_value $row->au_value | <strong>" . STATUS . ":</strong> $pro_s</p>
				<p>$pro_d</p>
				<h4 class='desc_price font_harabara'>" . ((@$_SESSION['french'] == 1) ? "$row->property_price_EU EURO" : "$row->property_price USD") . "</h4>
				<p>" . BEDROOM . " : $row->bedrom_value</p>
				<p class='" . ((@$_SESSION['french'] == 1) ? "desc_readmore_fr" : "desc_readmore") . "'><a href='details.php?property_id=$row->property_id&cat_id=$row->pcat_id' class='png_fix'><span class='hide_this'>" . READ_MORE . "</span></a></p>
			</li>";
	}
	return $result;
}

function blog_news()
{
	$result = "";
	$Query = "SELECT pd.property_id, pd.pcat_id, pd.property_name_fr, pd.property_name, ps.pstatus_value, ps.pstatus_value_fr, ad.ad_value, au.au_value, pk.pkind_value, pk.pkind_value_fr FROM property_details AS pd LEFT OUTER JOIN property_kind AS pk ON pd.pkind_id=pk.pkind_id LEFT OUTER JOIN property_status AS ps ON pd.pstatus_id=ps.pstatus_id LEFT OUTER JOIN area_digit AS ad ON pd.ad_id=ad.ad_id LEFT OUTER JOIN area_unit AS au ON pd.au_id=au.au_id ORDER BY pd.property_id DESC LIMIT 4";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	while ($row = mysqli_fetch_object($rs)) {
		if (@$_SESSION['french'] == 1) {
			$pro_n = $row->property_name_fr;
			$pro_k = $row->pkind_value_fr;
			$pro_s = $row->pstatus_value_fr;
		} else {
			$pro_n = $row->property_name;
			$pro_k = $row->pkind_value;
			$pro_s = $row->pstatus_value;
		}
		$result .= "
			<li>
				<span>" . RECENT . ": </span>
				<a href='details.php?property_id=$row->property_id&cat_id=$row->pcat_id'>$pro_n</a>
				<span> <strong>" . KIND . ":</strong> $pro_k</span>
				<span>$row->ad_value $row->au_value | <strong>" . STATUS . ":</strong> $pro_s</span>
			</li>";
	}
	return $result;
}

function top_slider($section_id)
{
	$res = "";
	$res .= "<div id='slider_area' class='cycle'>";
	$Query = "SELECT * FROM banners WHERE status_id=1 AND section_id='" . $section_id . "' ORDER BY ban_id";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	while ($row = mysqli_fetch_object($rs)) {
		$res .= "<div class='slide'>			
					<div class='desc'>
						<span class='title'>
							<a href='#' title='" . $row->ban_name . "'>$row->ban_name</a>
						</span>
					</div>
					<img src='images/banners/$row->ban_file' alt='" . $row->ban_title . "' />
				 </div>";
	}
	$res .= "</div>";
	return $res;
}

function top_slider1()
{
	$res = "";
	$Query = "SELECT * FROM banners ORDER BY ban_id";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	while ($row = mysqli_fetch_object($rs)) {
		$res .= "
					<li><img src='images/banners/lrg/$row->ban_file' alt='$row->ban_title' height='235' width='535' /></li>";
	}
	return $res;
}

function menuTarget($val)
{
	$strReturn = '';
	if ($val == '_self') {
		$strReturn .= '<option value="_self" selected="selected">Self</option>';
	} else {
		$strReturn .= '<option value="_self">Self</option>';
	}
	if ($val == '_blank') {
		$strReturn .= '<option value="_blank" selected="selected">Blank</option>';
	} else {
		$strReturn .= '<option value="_blank">Blank</option>';
	}
	return $strReturn;
}

function showStars1($memID, $proID)
{
	$retValue = "";
	$avgRate = 0;
	$voteVal = 1;
	$qry = mysqli_query($GLOBALS['conn'], "SELECT rev_rate FROM reviews WHERE mem_id=" . $memID . " AND pro_id=" . $proID . " LIMIT 1");
	$qryFetch = mysqli_fetch_object($qry);
	/*if(mysqli_num_rows($qry) > 0){
		$row = mysqli_fetch_object($qry);
		if($row->total_records > 0){
			$avgRate = round($row->total / $row->total_records, 1);
		}
	}*/
	$avgRate = round($qryFetch->rev_rate);
	for ($i = 0; $i < 5; $i++) {
		if ($i < $avgRate) {
			$retValue .= '<img src="images/star.png" alt="' . $avgRate . '" title="' . $avgRate . '" />';
		} else {
			$retValue .= '<img src="images/star_rol.png" alt="' . $avgRate . '" title="' . $avgRate . '" />';
		}
		$voteVal++;
	}
	return $retValue;
}

function showStars($proID)
{
	$retValue = "";
	$avgRate = 0;
	$avg = 0;
	$voteVal = 1;
	$avrgeReviews = 0;
	$rslt = 0;
	$qry = mysqli_query($GLOBALS['conn'], "SELECT * FROM reviews WHERE pro_id='" . $proID . "' AND status_id=1");

	/*if(mysqli_num_rows($qry) > 0){
		$row = mysqli_fetch_object($qry);
		if($row->total_records > 0){
			$avgRate = round($row->total / $row->total_records, 1);
		}
	}*/
	if (mysqli_num_rows($qry) > 0) {
		while ($row = mysqli_fetch_object($qry)) {
			$avg = $avg + $row->rev_rate;
			$avgRate++;
		}
		$avrgeReviews = $avg / $avgRate;
		$rslt = round($avrgeReviews);
	}
	for ($i = 0; $i < 5; $i++) {
		if ($i < $rslt) {
			$retValue .= '<img src="images/star.png" alt="' . $avgRate . '" title="' . $avgRate . '" />';
		} else {
			$retValue .= '<img src="images/star_rol.png" alt="' . $avgRate . '" title="' . $avgRate . '" />';
		}
		$voteVal++;
	}
	return $retValue;
}
function chkImage($imgType)
{
	$isallowed = 0;
	$typesAllowed = array('image/jpeg', 'image/gif', 'image/png');
	if (in_array($imgType, $typesAllowed)) {
		$isallowed = 1;
	}
	return $isallowed;
}
function deleteDir($dirPath)
{
	if (!is_dir($dirPath)) {
		throw new InvalidArgumentException("$dirPath must be a directory");
	}
	if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
		$dirPath .= '/';
	}
	$files = glob($dirPath . '*', GLOB_MARK);
	foreach ($files as $file) {
		if (is_dir($file)) {
			$this->deleteDir($file);
		} else {
			unlink($file);
		}
	}
	rmdir($dirPath);
}
function avgRating($secID, $objID)
{
	$retValue = "";
	$qry = mysqli_query($GLOBALS['conn'], "SELECT COUNT( 1 ) AS total_records, SUM( rate ) AS total FROM votes WHERE section_id=" . $secID . " AND pro_id=" . $objID);
	if (mysqli_num_rows($qry) > 0) {
		$row = mysqli_fetch_object($qry);
		if ($row->total_records == 0) {
			$retValue = '<span id="serverResponse3">Average: 0 of </span><span id="serverResponse4">0 vote(s)</span>';
		} else {
			$avgRate = $row->total / $row->total_records;
			$retValue = '<span id="serverResponse3">Average: ' . $avgRate . ' of </span><span id="serverResponse4">' . $row->total_records . ' vote(s)</span>';
		}
	} else {
		$retValue = '<span id="serverResponse3">Average: 0 of </span><span id="serverResponse4">0 vote(s)</span>';
	}
	return $retValue;
}

function avgRatingDet($secID, $objID)
{
	$retValue = "";
	$qry = mysqli_query($GLOBALS['conn'], "SELECT COUNT( 1 ) AS total_records, SUM( rate ) AS total FROM votes WHERE section_id=" . $secID . " AND pro_id=" . $objID);
	if (mysqli_num_rows($qry) > 0) {
		$row = mysqli_fetch_object($qry);
		if ($row->total_records == 0) {
			$retValue = '<a href="#" class="Votes"><span id="serverResponse1">0 Vote(s) </span></a><span id="serverResponse2">Average: 0</span>';
		} else {
			$avgRate = $row->total / $row->total_records;
			$retValue = '<a href="#" class="Votes"><span id="serverResponse1">' . $row->total_records . ' Vote(s) </span></a><span id="serverResponse2">Average: ' . $avgRate . '</span>';
		}
	} else {
		$retValue = '<span id="serverResponse3">Average: 0 of </span><span id="serverResponse4">0 vote(s)</span>';
	}
	return $retValue;
}

function fillTimeCombo($val)
{
	$strMin = 0;
	$strHr = 0;
	for ($i = 0; $i < 48; $i++) {
		$strTime = date("H:i", mktime($strHr, $strMin, 0, 1, 1, 2012));
		$strTimeComp = $strTime . ":00";
		if ($val == $strTimeComp) {
			print('<option value="' . $strTime . '" selected="selected">' . $strTime . '</option>');
		} else {
			print('<option value="' . $strTime . '">' . $strTime . '</option>');
		}
		if ($strMin == 0) {
			$strMin = 30;
		} else {
			$strMin = 0;
			$strHr++;
		}
	}
}

function rename_image($source)
{
	$ext = pathinfo($source);
	$image = substr(md5(rand(8, 999999999999999)), 0, 12) . "_repair." . $ext['extension'];
	return $image;
}

function returnAuthor($ID)
{
	$retRes = "";
	if ($ID == 0) {
		$retRes = "Site Admin";
	} else {
		$strQry = "SELECT mem_fname, mem_lname FROM members WHERE mem_id=" . $ID;
		$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
		if (mysqli_num_rows($nResult) >= 1) {
			$row = mysqli_fetch_row($nResult);
			$retRes = $row[0] . " " . $row[1];
		}
	}
	return $retRes;
}

function blogTags($ID)
{
	$cnt = 0;
	$strReturn = "";
	$strQuery = "SELECT t.tag_name FROM bl_post_tags AS p, bl_tags AS t WHERE t.tag_id=p.tag_id AND p.post_id=" . $ID;
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_object($nResult)) {
			if ($cnt > 0) {
				$strReturn .= ", ";
			}
			$strReturn .= $row->tag_name;
			$cnt++;
		}
	}
	return $strReturn;
}

function getMaxTags()
{
	$retResult = 0;
	$rsTag = mysqli_query($GLOBALS['conn'], "SELECT MAX(tag_total) AS MaxTag FROM bl_tags");
	if (mysqli_num_rows($rsTag) >= 1) {
		$rowTag = mysqli_fetch_object($rsTag);
		$retResult = $rowTag->MaxTag;
	}
	return $retResult;
}

function getMinTags()
{
	$retResult = 0;
	$rsTag = mysqli_query($GLOBALS['conn'], "SELECT MIN(tag_total) AS MinTag FROM bl_tags");
	if (mysqli_num_rows($rsTag) >= 1) {
		$rowTag = mysqli_fetch_object($rsTag);
		$retResult = $rowTag->MinTag;
	}
	return $retResult;
}

function limit_text($text, $limit)
{
	// figure out the total length of the string
	if (strlen($text) > $limit) {
		# cut the text
		$text = substr($text, 0, $limit);
		# lose any incomplete word at the end
		$text = substr($text, 0, - (strlen(strrchr($text, ' '))));
		$text .= " . . .";
	}
	// return the processed string
	return $text;
}

function insKeyGeneral($mmod_id)
{
	//$mk_general = chkExist("mk_api", "module_key", "WHERE mk_id=".$mk_id." AND mmod_id=".$mmod_id." AND mk_isext=0");
	$mk_general = chkExist("mk_api", "module_key", "WHERE mmod_id=" . $mmod_id . " AND mk_isext=0");
	if ($mk_general == '0') {
		$mk_id = getMaximum("module_key", "mk_id");
		$modName = returnName("mmod_name", "mem_modules", "mmod_id", $mmod_id);
		$str_gen = $mk_id . "_" . $mmod_id . "_" . $modName;
		$mk_general = base64_encode($str_gen);
		$mk_api = $mk_general;
		//$mk_api = $mk_general ."_". $mk_extension;
		$strQry = "INSERT INTO module_key (mk_id, mmod_id, mk_general, mk_extension, mk_api) VALUES(" . $mk_id . ", " . $mmod_id . ", '" . $mk_general . "', '" . $mk_extension . "', '" . $mk_api . "')";
		$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die(mysqli_error($GLOBALS['conn']));
	}
	return $mk_general;
}

/*function insKeyExtension($mk_id, $mmod_id, $mk_extension){
	$mk_id = getMaximum("module_key", "mk_id");
	$modName = returnName("mmod_name", "mem_modules", "mmod_id", $mmod_id);
	$str_gen = $mk_id."_".$mmod_id."_".$modName;
	$mk_general = base64_encode($str_gen);
	$mk_api = $mk_general ."_". $mk_extension;
	$strQry = "INSERT INTO module_key (mk_id, mmod_id, mk_general, mk_extension, mk_api) VALUES(".$mk_id.", ".$mmod_id.", '".$mk_general."', '".$mk_extension."', '".$mk_api."')";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die(mysqli_error($GLOBALS['conn']));
}*/

function returnSubsAmount($dur, $discount, $amount)
{
	$retAmount = round($amount, 2);
	if ($dur == 'y') {
		$savings = ($amount * $discount) / 100;
		$retAmount = round($amount - $savings, 2) * 12;
	}
	return $retAmount;
}

function dateDif($date1, $date2, $op)
{
	$retValue = "";
	$dt1 = explode("-", $date1);
	$dt2 = explode("-", $date2);
	$d1 = mktime(0, 0, 0, $dt1[1], $dt1[2], $dt1[0]);
	$d2 = mktime(0, 0, 0, $dt2[1], $dt2[2], $dt2[0]);
	switch ($op) {
		case 'hr':
			$retValue = floor(($d2 - $d1) / 3600);
			break;
		case 'min':
			$retValue = floor(($d2 - $d1) / 60);
			break;
		case 'sec':
			$retValue = ($d2 - $d1);
			break;
		case 'year':
			$retValue = floor(($d2 - $d1) / 31536000);
			break;
		case 'mon':
			$retValue = floor(($d2 - $d1) / 2628000);
			break;
		case 'day':
			$retValue = floor(($d2 - $d1) / 86400);
			break;
	}
	return $retValue;
}

function getEmbedCode($swfURL, $feedURL)
{
	$strEmbed = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="1000" height="600">
				<param name="movie" value="' . $swfURL . '" />
				<param name="quality" value="high" />
				<param name="menu" value="false" />
				<param name="bgcolor" value="#869ca7" />
				<param name="allowFullScreen" value="true" />
				<param name="allowscriptaccess" value="always" />
				<param name="flashvars" value="feedURL=' . $feedURL . '" />
				<embed src="' . $swfURL . '" menu="false" bgcolor="#869ca7" allowscriptaccess="always" allowFullScreen="true" flashvars="feedURL=' . $feedURL . '" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="1000" height="600"></embed>
			</object>';
	return $strEmbed;
}

function getProEmbedCode($swfURL, $feedURL, $proVars)
{
	$strEmbed = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="1000" height="600">
				<param name="movie" value="' . $swfURL . '" />
				<param name="quality" value="high" />
				<param name="menu" value="false" />
				<param name="bgcolor" value="#869ca7" />
				<param name="allowFullScreen" value="true" />
				<param name="allowscriptaccess" value="always" />
				<param name="flashvars" value="feedURL=' . $feedURL . $proVars . '" />
				<embed src="' . $swfURL . '" menu="false" bgcolor="#869ca7" allowscriptaccess="always" allowFullScreen="true" flashvars="feedURL=' . $feedURL . $proVars . '" quality="high" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="1000" height="600"></embed>
			</object>';
	return $strEmbed;
}

function chk_in_string($string, $find)
{
	$pos = strpos($string, $find);
	if ($pos === false) {
		$retVal = 0;
	} else {
		$retVal = 1;
	}
	return $retVal;
}

function fileRead($myFile)
{
	$fh = fopen($myFile, 'r');
	$fileData = fread($fh, filesize($myFile));
	fclose($fh);
	return $fileData;
}

function fileWrite($myFile, $strData)
{
	$fh = fopen($myFile, 'w');
	fwrite($fh, $strData);
	fclose($fh);
}

function returnFeatureVal($pfsID, $pfsName, $fnpQty, $fPrice, $fnpTenure, $fDir, $pakID, $pkfID, $pkfName, $dur, $discount)
{
	//$fVal = "";
	$fVal = '<input type="hidden" name="pfsID_' . $pkfID . '_' . $pakID . '" id="pfsID_' . $pkfID . '_' . $pakID . '" value="' . $pfsID . '" />';
	switch ($pfsID) {
		case 0:
			$fVal .= '<img src="' . $fDir . 'images/cross.png" width="24" height="24" alt="' . $pfsName . '" style="margin-top:8px;">';
			$fVal .= '<input type="hidden" name="qty_' . $pkfID . '_' . $pakID . '" id="qty_' . $pkfID . '_' . $pakID . '" value="0" />';
			break;
		case 1:
			$fVal .= '<img src="' . $fDir . 'images/tick.png" width="24" height="24" alt="' . $pfsName . '" style="margin-top:8px;">';
			$fVal .= '<input type="hidden" name="qty_' . $pkfID . '_' . $pakID . '" id="qty_' . $pkfID . '_' . $pakID . '" value="0" />';
			break;
		case 2:
			$fVal .= '<span style="line-height:38px;">Limited to <b>' . $fnpQty . '</b></span>';
			$fVal .= '<input type="hidden" name="qty_' . $pkfID . '_' . $pakID . '" id="qty_' . $pkfID . '_' . $pakID . '" value="' . $fnpQty . '" />';
			$fVal .= '<input type="hidden" name="proLimit" id="proLimit" value="' . $fnpQty . '" />';
			break;
		case 3:
			$fVal .= '<span style="line-height:38px;"><b>' . $pfsName . '</b></span>';
			$fVal .= '<input type="hidden" name="qty_' . $pkfID . '_' . $pakID . '" id="qty_' . $pkfID . '_' . $pakID . '" value="0" />';
			break;
		case 4:
			if ($fnpQty > 0) {
				$fVal .= '<input type="checkbox" name="chk_' . $pakID . '[]" id="chk_' . $pakID . '[]" value="' . $pkfID . '" /> Add USD ' . returnSubsAmount($dur, $discount, $fPrice) . '/' . $dur;
				$fVal .= '<br />Quantity: <input type="textbox" name="qty_' . $pkfID . '_' . $pakID . '" id="qty_' . $pkfID . '_' . $pakID . '" value="1" style="width:30px; text-align:right;" class="inputsmallBorder" onFocus="this.className=\'inputsmallBorder2\';" onBlur="this.className=\'inputsmallBorder\';" />';
			} else {
				$fVal .= '<span style="line-height:38px;"><input type="checkbox" name="chk_' . $pakID . '[]" id="chk_' . $pakID . '[]" value="' . $pkfID . '" /> Add USD ' . returnSubsAmount($dur, $discount, $fPrice) . '/' . $dur . '</span> <input type="hidden" name="qty_' . $pkfID . '_' . $pakID . '" id="qty_' . $pkfID . '_' . $pakID . '" value="0" />';
			}
			$fVal .= '<input type="hidden" name="price_' . $pkfID . '_' . $pakID . '" id="price_' . $pkfID . '_' . $pakID . '" value="' . returnSubsAmount($dur, $discount, $fPrice) . '" /><input type="hidden" name="pfName_' . $pkfID . '_' . $pakID . '" id="pfName_' . $pkfID . '_' . $pakID . '" value="' . $pkfName . '" />';
			break;
		case 5:
			$fVal = '<span style="line-height:34px;"><b><a href="#" title="Contact Us">Contact Us</a></b></span>';
			break;
	}
	return $fVal;
}

function showStatus($val)
{
	switch ($val) {
		case 0:
			$varStatus = "Pending";
			break;
		case 1:
			$varStatus = "Completed";
			break;
		case 2:
			$varStatus = "Failed";
			break;
		case 3:
			$varStatus = "Denied";
			break;
		case 4:
			$varStatus = "INVALID";
			break;
		case 5:
			$varStatus = "Cancelled";
			break;
		case 6:
			$varStatus = "Rejected";
			break;
	}
	return $varStatus;
}

function copyDir($dir, $dest)
{
	if ($handle = opendir($dir)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				$pos = strpos($file, ".");
				if ($pos > 0) {
					$strSource = $dir . "/" . $file;
					$strDest = $dest . "/" . $file;
					copy($strSource, $strDest);
				}
			}
		}
		closedir($handle);
	}
}

function showCardname($ID)
{
	$retRes = "";
	$strQry = "SELECT mcard_name FROM mem_cards WHERE mcard_id=$ID";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_row($nResult);
		$retRes = $row[0];
	} else {
		$retRes = "Card Removed";
	}
	return $retRes;
}

function FillSelected($Table, $IDField, $TextField, $ID, $Join = '')
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table $Join ORDER BY $IDField";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if ($row[0] == $ID) {
				print("<option value=\"$row[0]\" selected>$row[1]</option>");
			} else {
				print("<option value=\"$row[0]\">$row[1]</option>");
			}
		}
	}
}

function FillSelected22($Table, $IDField, $TextField, $ID)
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table ORDER BY $IDField";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if ($row[0] == $ID) {
				print("<option value=\"$row[0]\" selected>$row[1]</option>");
			} else {
				print("<option value=\"$row[0]\">$row[1]</option>");
			}
		}
	}
}

function FillSelectedJoin($Table, $IDField, $TextField, $ID, $Join = '')
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table $Join ORDER BY $IDField";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	$returnStr = "";
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if ($row[0] == $ID) {
				$returnStr .= "<option value=" . $row[0] . " selected>" . $row[1] . "</option>";
			} else {
				$returnStr .= "<option value=" . $row[0] . ">" . $row[1] . "</option>";
			}
		}
		return $returnStr;
	}
}

function FillSelectedJoinwhere($Table, $IDField, $TextField, $ID, $Join, $where)
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table $Join WHERE $where  ORDER BY $IDField";
	//print($strQuery);
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	$returnStr = "";
	if (mysqli_num_rows($nResult) > 0) {
		$returnStr .= "<option value='0'>N/A</option>";
		while ($row = mysqli_fetch_row($nResult)) {
			if ($row[0] == $ID) {
				$returnStr .= "<option value=" . $row[0] . " selected>" . $row[1] . "</option>";
			} else {
				$returnStr .= "<option value=" . $row[0] . ">" . $row[1] . "</option>";
			}
		}
	}  else{
		$returnStr = "<option value='0'>N/A</option>";
	}
	return print($returnStr);
}

// Display Just Parent Categories
function FillSelected2($Table, $IDField, $TextField, $ID, $WHERE)
{
	$strQuery = "SELECT DISTINCT $IDField, $TextField FROM $Table WHERE $WHERE ORDER BY $IDField ASC";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if ($row[0] == $ID) {
				print("<option value=\"$row[0]\" selected>$row[1]</option>");
			} else {
				print("<option value=\"$row[0]\">$row[1]</option>");
			}
		}
	}
}
function FillSelected2join($Table, $join, $IDField, $TextField, $ID, $WHERE, $orderby)
{
	$strQuery = "SELECT DISTINCT $IDField, $TextField FROM $Table LEFT OUTER JOIN $join  WHERE $WHERE ORDER BY $orderby ASC";

	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if ($row[0] == $ID) {
				print("<option value=\"$row[0]\" selected>$row[1]</option>");
			} else {
				print("<option value=\"$row[0]\">$row[1]</option>");
			}
		}
	}
}

function FillSelected2JS($Table, $IDField, $TextField, $ID, $WHERE)
{
	$strQuery = "SELECT DISTINCT $IDField, $TextField FROM $Table WHERE $WHERE ORDER BY $IDField ASC";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if ($row[0] == $ID) {
				print("<option value=\"$row[0]\" selected>" . str_replace(",", "\'", $row[1]) . "</option>");
			} else {
				print("<option value=\"$row[0]\">" . str_replace("'", "\'", $row[1]) . "</option>");
			}
		}
	}
}

function FillSelectedValue($Table, $IDField, $TextField, $ID)
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table ORDER BY $IDField";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if ($row[0] == $ID) {
				print("<option value=\"$row[1]\" selected>$row[1]</option>");
			} else {
				print("<option value=\"$row[1]\">$row[1]</option>");
			}
		}
	}
}

function FillMultiple($Table, $IDField, $TextField, $SelTbl, $Field1, $Field2, $SelID)
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			$strQuery1 = "SELECT * FROM $SelTbl WHERE $Field1=$row[0] AND $Field2=$SelID";
			$nResult1 = mysqli_query($GLOBALS['conn'], $strQuery1);
			if (mysqli_num_rows($nResult1) >= 1) {
				print("<option value=\"$row[0]\" selected>$row[1]</option>");
			} else {
				print("<option value=\"$row[0]\">$row[1]</option>");
			}
		}
	}
}

function FillMultiple2($Table, $IDField, $TextField, $WHERE, $per_type)
{
	$arr = explode(",", $per_type);
	$Query = "SELECT $IDField,$TextField from $Table WHERE $WHERE";
	$nResult = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($nResult) > 0) {
		while ($row = mysqli_fetch_row($nResult)) {
			if (in_array($row[0], $arr)) {
				print("<option value=\"$row[0]\" selected>$row[1]</option>");
			} else {
				print("<option value=\"$row[0]\">$row[1]</option>");
			}
		}
	}
}
function FillSelected_Parent($Table, $IDField, $TextField, $ID, $parentField)
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table WHERE $parentField = 0";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			$strQry = "SELECT $IDField, $TextField FROM $Table WHERE $parentField = $row[0]";
			$nRs = mysqli_query($GLOBALS['conn'], $strQry);
			if (mysqli_num_rows($nRs) >= 1) {
				print("<optgroup label=\"$row[1]\">");
				while ($row1 = mysqli_fetch_row($nRs)) {
					if ($row1[0] == $ID) {
						print("<option value=\"$row1[0]\" selected>$row1[1]</option>");
					} else {
						print("<option value=\"$row1[0]\">$row1[1]</option>");
					}
				}
				print("</optgroup>");
			} else {
				if ($row[0] == $ID) {
					print("<option value=\"$row[0]\" selected>$row[1]</option>");
				} else {
					print("<option value=\"$row[0]\">$row[1]</option>");
				}
			}
		}
	}
}
function FillSelected_ParentLang($Table, $IDField, $TextField, $ID, $parentField, $langID)
{
	$strQuery = "SELECT a." . $IDField . ", l." . $TextField . ", a." . $parentField . " FROM " . $Table . " AS a LEFT OUTER JOIN " . $Table . "_ln AS l ON l." . $IDField . "=a." . $IDField . " AND lang_id=" . $langID . " WHERE a." . $parentField . " = 0";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			//$strQry="SELECT $IDField, $TextField, $parentField FROM $Table WHERE $parentField = $row[0]";
			$strQry = "SELECT a." . $IDField . ", l." . $TextField . ", a." . $parentField . " FROM " . $Table . " AS a LEFT OUTER JOIN " . $Table . "_ln AS l ON l." . $IDField . "=a." . $IDField . " AND lang_id=" . $langID . " WHERE a.$parentField = $row[0]";
			$nRs = mysqli_query($GLOBALS['conn'], $strQry);
			if (mysqli_num_rows($nRs) >= 1) {
				print("<optgroup label=\"$row[1]\">");
				while ($row1 = mysqli_fetch_row($nRs)) {
					if ($row1[0] == $ID) {
						print("<option value=\"$row1[0]\" selected>$row1[1]</option>");
					} else {
						print("<option value=\"$row1[0]\">$row1[1]</option>");
					}
				}
				print("</optgroup>");
			} else {
				if ($row[0] == $ID) {
					print("<option value=\"$row[0]\" selected>$row[1]</option>");
				} else {
					print("<option value=\"$row[0]\">$row[1]</option>");
				}
			}
		}
	}
}
function FillSelected_AgentLead($Table, $IDField, $TextField, $ID)
{
	$strQuery = "SELECT a." . $IDField . ", l." . $TextField . " FROM " . $Table . " AS a LEFT OUTER JOIN leads AS l ON l." . $IDField . "=a." . $IDField . " where a.user_id=" . $_SESSION['UserID'];

	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {

			if ($row[0] == $ID) {
				print("<option value=\"$row[0]\" selected>$row[1]</option>");
			} else {
				print("<option value=\"$row[0]\">$row[1]</option>");
			}
		}
	}
}

function TotalRecords($Table, $condition)
{
	$strQuery = "SELECT * FROM $Table $condition";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	return mysqli_num_rows($nResult);
}
function TotalRecords2($Table, $condition, $ID)
{
	$strQuery = "SELECT * FROM $Table $condition Where " . $ID . "";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	return mysqli_num_rows($nResult);
}
function TotalRecords3($Table, $condition, $ID, $ID2)
{
	$strQuery = "SELECT * FROM $Table $condition Where " . $ID . " And " . $ID2 . "";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	return mysqli_num_rows($nResult);
}

function TotalRecords1($condition)
{
	$strQuery = $condition;
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	return mysqli_num_rows($nResult);
}

function checkAdminOldPass($UserID, $Pass)
{
	$retRes = 0;
	$strQry = "SELECT admin_user, admin_pass FROM admin WHERE admin_id=$UserID AND admin_pass='$Pass'";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		$retRes = 1;
	}
	return $retRes;
}

function checkAdminLogin($Login, $Pass)
{
	$retRes = 0;
	$strQry = "SELECT user_id FROM user WHERE user_name='$Login' AND user_password='$Pass'";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		$retRes = 1;
	}
	return $retRes;
}

function checkSAdminLogin($Login, $Pass)
{
	$retRes = 0;
	$strQry = "SELECT sadmin_user FROM sec_admin WHERE sadmin_user='$Login' AND sadmin_pass='$Pass'";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_object($nResult)) {
			if (@$row->sadmin_user)
				$retRes = 1;
		}
	}
	return $retRes;
}

function checkLogin($Login, $Pass)
{
	$retRes = 0;
	$strQry = "SELECT mem_login FROM members WHERE mem_login='$Login' AND mem_password='$Pass'";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_object($nResult)) {
			if (@$row->mem_login)
				$retRes = 1;
		}
	}
	return $retRes;
}

function checkLogin2($Login, $Pass)
{
	$retRes = 0;
	$strQry = "SELECT mem_login FROM members WHERE mem_login='$Login' AND mem_password='$Pass' AND mem_deleted = 1";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_object($nResult)) {
			if (@$row->mem_login)
				$retRes = 1;
		}
	}
	return $retRes;
}

function checkSubscription($mID)
{
	$retRes = 0;
	$strQry = "SELECT sinfo_enddate, paystatus_id FROM subscription_info WHERE mem_id=$mID";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_object($nResult);
		if ($row->paystatus_id > 1) {
			$retRes = 2;
		} elseif ($row->paystatus_id < 1) {
			$retRes = 3;
		} elseif ($row->sinfo_enddate < date("Y-m-d")) {
			$retRes = 1;
		} else {
			$retRes = 4;
		}
	}
	return $retRes;
}

function UpdateSignIn($MemberID, $MemberEmail)
{
	$MaxID = getMaximum("signin_counter", "signin_id");

	$strQry1 = "UPDATE members SET mem_last_login = NOW() WHERE mem_id=$MemberID";
	$nResult1 = mysqli_query($GLOBALS['conn'], $strQry1);

	$strQry2 = "INSERT INTO signin_counter(signin_id, mem_id, mem_email, signin_date) VALUES ($MaxID, $MemberID, '$MemberEmail', NOW())";
	$nResult2 = mysqli_query($GLOBALS['conn'], $strQry2);
}

function updateViews($cardID, $numViews)
{
	$totalViews = $numViews + 1;
	mysqli_query($GLOBALS['conn'], "UPDATE cards SET card_views=" . $totalViews . " WHERE card_id = " . $cardID) or die("Unable 2 Update Views");
}

function getRating($PhotoID)
{
	$Rating = 0;
	$strQry = "SELECT photo_totalrating, photo_rating FROM photos WHERE photo_id = $PhotoID";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_object($nResult)) {
			if ($row->photo_totalrating > 0 && $row->photo_rating > 0)
				$Rating = $row->photo_totalrating / $row->photo_rating;
			else
				$Rating = 0;
		}
	}
	return $Rating;
}

function getcount($Field, $Table, $IDField, $ID)
{
	$count = 0;
	$strQry = "SELECT COUNT(" . $Field . ") as field_count FROM ".$Table." WHERE  " . $IDField . "  = '".$ID."'";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) > 0) {
		$row = mysqli_fetch_object($nResult);
		$count = $row->field_count;
	} else{
		$count = 0;
	}
	return $count;
}
function getMaximumWhere($Table, $Field, $Where)
{
	$maxID = 0;
	$strQry = "SELECT MAX(" . $Field . ")+1 as CID FROM " . $Table . " " . $Where;
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_object($nResult)) {
			if (@$row->CID)
				$maxID = $row->CID;
			else
				$maxID = 1;
		}
	}
	return $maxID;
}

function getMaximum($Table, $Field)
{
	$maxID = 0;
	$strQry = "SELECT MAX(" . $Field . ")+1 as CID FROM " . $Table . " ";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_object($nResult)) {
			if (@$row->CID)
				$maxID = $row->CID;
			else
				$maxID = 1;
		}
	}
	return $maxID;
}

function getMaximumCatID($Table, $Field)
{
	$maxID = 0;
	$strQry = "SELECT MAX(" . $Field . ")+1 as CID FROM " . $Table . " ";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_object($nResult)) {
			if (@$row->CID)
				$maxID = $row->CID;
			else
				$maxID = 2;
		}
	}
	return $maxID;
}

function IsExist($Field, $Table, $TblField, $Value)
{
	$retRes = 0;
	$strQry = "SELECT $Field FROM $Table WHERE $TblField='$Value'";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die(mysqli_error($GLOBALS['conn']));
	if (mysqli_num_rows($nResult) >= 1) {
		$retRes = 1;
	}
	return $retRes;
}
function IsExistConfrm($Field, $Table, $TblField, $Value)
{
	$retRes = 0;
	$strQry = "SELECT $Field FROM $Table WHERE $TblField='$Value' AND confirm=0";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die(mysqli_error($GLOBALS['conn']));
	if (mysqli_num_rows($nResult) >= 1) {
		$retRes = 1;
	}
	return $retRes;
}

function chkExist($Field, $Table, $WHERE)
{
	$retRes = 0;
	$strQry = "SELECT $Field FROM $Table $WHERE";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_row($nResult);
		$retRes = $row[0];
		//$retRes=1;
	}
	return $retRes;
}

function returnMulCat($ID)
{
	$retRes = "";
	$numCnt = 0;
	$strQry = "SELECT c.cat_name FROM categories AS c, card_categories AS cc WHERE c.cat_id = cc.cat_id AND cc.card_id = $ID";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if ($numCnt == 0) {
				$retRes .= $row[0];
			} else {
				$retRes .= ", " . $row[0];
			}
			$numCnt++;
		}
	}
	return $retRes;
}

function returnName($Field, $Table, $IDField, $ID)
{
	$retRes = "";
	$strQry = "SELECT $Field FROM $Table WHERE $IDField= '".$ID."' LIMIT 1";
	//print($strQry);
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_row($nResult);
		$retRes = $row[0];
	}
	return $retRes;
}

function returnNameWhere($Field, $Table, $where){
	$retRes = "";
	$strQry = "SELECT $Field FROM $Table WHERE $where";
	//print($strQry); die();
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
	if (mysqli_num_rows($nResult) > 0) {
		$row = mysqli_fetch_row($nResult);
		$retRes = $row[0];
	} else{
		$retRes = "";
	}
	return $retRes;
}

function returnImage($Field, $Table, $IDField, $ID, $where_image = "")
{
	$retRes = "";
	$strQry = "SELECT $Field FROM $Table WHERE $IDField=$ID $where_image LIMIT 1";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_row($nResult);
		$retRes = $GLOBALS['siteURL'] . "files/no_img_1.jpg";
		if (!empty($row[0])) {
			$retRes = $row[0];
		}
		//$retRes = $row[0];
	}
	return $retRes;
}

function returnList($Fields, $Table, $Where)
{
	$strQry = "SELECT $Fields FROM $Table $Where LIMIT 1";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Return List");
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_row($nResult);
		return $row;
	} else {
		return false;
	}
}

function returnID($Field, $Table, $NameField, $Name)
{
	$retRes = "";
	$strQry = "SELECT $Field FROM $Table WHERE $NameField='$Name' LIMIT 1";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_row($nResult);
		$retRes = $row[0];
	}
	return $retRes;
}

function countCategories($Field, $qryText)
{
	$strQry = "SELECT CatID, CatName FROM Categories WHERE ParentID = $Field $qryText";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		$count = mysqli_num_rows($nResult);
	} else {
		$count = 0;
	}
	return $count;
}

function countSubCategories($Field)
{
	$strQry = "SELECT CatID, CatName FROM Categories WHERE ParentID = $Field";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		$count = mysqli_num_rows($nResult);
	} else {
		$count = 0;
	}
	return $count;
}

// Return Number of products in Category
function countProducts($CatID)
{
	$strQry = "SELECT C.CatID, C.ParentID, P.ProID, P.ItemNumber, P.ProName, P.Size, P.Price, P.ProPicture FROM Categories AS C, Products AS P, Products_Categories AS PC WHERE C.CatID = PC.CatID AND P.ProID = PC.ProID AND PC.CatID = $CatID";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		$count = mysqli_num_rows($nResult);
	} else {
		$count = 0;
	}
	return $count;
}

// Return Number of products in Category and its Sub Category
function countProducts1($CatID)
{
	//$strQry="SELECT C.CatID, C.ParentID, P.ProID, P.ItemNumber, P.ProName, P.Size, P.Price, P.ProPicture FROM Categories AS C, Products AS P, Products_Categories AS PC WHERE C.CatID = PC.CatID AND P.ProID = PC.ProID AND PC.CatID = $CatID AND C.ParentID = $CatID";
	$strQry = "SELECT C.CatID, C.ParentID, P.ProID, P.ItemNumber, P.ProName, P.Size, P.Price, P.ProPicture FROM Categories AS C, Products AS P, Products_Categories AS PC WHERE C.CatID = $CatID AND PC.CatID = C.CatID AND P.ProID = PC.ProID OR C.ParentID = $CatID AND PC.CatID = C.CatID AND P.ProID = PC.ProID";
	//print($strQry);
	$nResult = mysqli_query($GLOBALS['conn'], $strQry);
	if (mysqli_num_rows($nResult) >= 1) {
		$count = mysqli_num_rows($nResult);
	} else {
		$count = 0;
	}
	return $count;
}
// function for file deletion
function DeleteFile($Field, $Table, $IDField, $ID)
{
	$strQuery = "SELECT $Field FROM $Table WHERE $IDField=$ID";
	//	print($strQuery);
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_object($nResult);
		//print($row->$Field);
		$fPath = "../" . $row->$Field;
		@unlink($fPath);
	}
}
// function for file deletion
function DeleteFileWithThumb($Field, $Table, $IDField, $ID, $iPath, $tPath)
{
	$strQuery = "SELECT $Field FROM $Table WHERE $IDField=$ID";
	//	print($strQuery);
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_object($nResult);
		$fPath = $iPath . $row->$Field;
		@unlink($fPath);
		if ($tPath != "EMPTY") {
			$fPath = $tPath . $row->$Field;
			@unlink($fPath);
		}
	}
}
// function for file deletion
function DeleteFile2($Field, $Table, $IDField, $ID, $path)
{
	$strQuery = "SELECT $Field FROM $Table WHERE $IDField=$ID";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		$row = mysqli_fetch_object($nResult);
		$iPath = $path . $row->$Field;
		@unlink($iPath);
		$tPath = $path . "th/" . $row->$Field;
		@unlink($tPath);
	}
}
function Fill($Table, $IDField, $TextField, $chkSelected)
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table ORDER BY $IDField";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			if ($chkSelected == $row[0]) {
				print("<option value=\"$row[0]\" selected>$row[1]</option>");
			} else {
				print("<option value=\"$row[0]\">$row[1]</option>");
			}
		}
	}
}

function ImageSize($imagesource, $DisplayH, $DisplayW)
{
	list($width, $height, $type, $attr) = getimagesize($imagesource);
	$wid = $width;
	$hig = $height;

	if ($wid > $DisplayW || $hig > $DisplayH) {
		if ($wid <= $hig) {
			$img_ratio = $wid / $hig;
			$newHeight = $DisplayH;
			$temp = $newHeight * $img_ratio;
			$newWidth = round($temp);
		} else {
			$img_ratio = $hig / $wid;
			$newWidth = $DisplayW;
			$temp = $newWidth * $img_ratio;
			$newHeight = round($temp);
		}
	} else {
		$newHeight = $hig;
		$newWidth = $wid;
	}

	$showimage = "<img src=\"" . $imagesource . "\" height=\"" . $newHeight . "\" width=\"" . $newWidth . "\" class=\"img\">";
	return $showimage;
}

function IncreaseViews($Table, $CounterFeild, $IDField, $ID)
{
	$Query = "UPDATE $Table SET $CounterFeild = $CounterFeild+1 WHERE $IDField = $ID";
	$nRst = mysqli_query($GLOBALS['conn'], $Query) or die("Unable 2 Edit Record");
}

function GetViews($Field, $Table, $IDField, $ID)
{
	$strQry = "SELECT $Field FROM $Table WHERE $IDField=$ID";
	$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
	if (mysqli_num_rows($nResult) >= 1) {
		$rs = mysqli_fetch_object($nResult);
		print($rs->$Field);
	}
}

function SelectDate($emonth, $eday)
{
	print("<select name=\"month1\" class=\"inputsmallBorder\">");
	for ($i = 1; $i <= 12; $i++) {
		if ($emonth == $i) {
			print("<option value=\"$i\" selected>$i</option>");
		} else {
			print("<option value=\"$i\">$i</option>");
		}
	}
	print("</select>&nbsp;");

	print("<select name=\"day1\" class=\"inputsmallBorder\">");
	for ($i = 1; $i <= 31; $i++) {
		if ($eday == $i) {
			print("<option value=\"$i\" selected>$i</option>");
		} else {
			print("<option value=\"$i\">$i</option>");
		}
	}
	print("</select>");
}

function Display_Alphabets($char, $QryString)
{
	$count = 0;
	$linksHTML = "";
	$char_array = array();
	$char_array[0]	=	"A";
	$char_array[1]	=	"B";
	$char_array[2]	=	"C";
	$char_array[3]	=	"D";
	$char_array[4]	=	"E";
	$char_array[5]	=	"F";
	$char_array[6]	=	"G";
	$char_array[7]	=	"H";
	$char_array[8]	=	"I";
	$char_array[9]	=	"J";
	$char_array[10]	=	"K";
	$char_array[11]	=	"L";
	$char_array[12]	=	"M";
	$char_array[13]	=	"N";
	$char_array[14]	=	"O";
	$char_array[15]	=	"P";
	$char_array[16]	=	"Q";
	$char_array[17]	=	"R";
	$char_array[18]	=	"S";
	$char_array[19]	=	"T";
	$char_array[20]	=	"U";
	$char_array[21]	=	"V";
	$char_array[22]	=	"W";
	$char_array[23]	=	"X";
	$char_array[24]	=	"Y";
	$char_array[25]	=	"Z";

	$linksHTML = "<table width=\"98%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\"><tr>";
	while ($count < count($char_array)) {
		if ($char == $char_array[$count]) {
			$linksHTML .= "<td align=\"center\" class=\"charSelected\">" . $char_array[$count] . "</td>";
		} else {
			if ($QryString != "") {
				$linksHTML .= "<td align=\"center\" class=\"char\"><a href=\"" . $_SERVER['PHP_SELF'] . "?char=" . $char_array[$count] . "&" . $QryString . "\" title=\"" . $char_array[$count] . "\">" . $char_array[$count] . "</a></td>";
			} else {
				$linksHTML .= "<td align=\"center\" class=\"char\"><a href=\"" . $_SERVER['PHP_SELF'] . "?char=" . $char_array[$count] . "\" title=\"" . $char_array[$count] . "\">" . $char_array[$count] . "</a></td>";
			}
		}
		$count++;
	}


	$linksHTML .= "</tr></table>";
	print($linksHTML);
}

function showBanner($location, $showOne)
{
	// show random banner where status is 1
	if ($showOne == 0) {
		$stringQry = "SELECT * FROM banner WHERE status_id = 1 AND bloc_id = " . $location;
	} else {
		$stringQry = "SELECT * FROM banner WHERE status_id = 1 AND bloc_id = " . $location . " ORDER BY RAND()";
	}
	$nRst = mysqli_query($GLOBALS['conn'], $stringQry);
	if (mysqli_num_rows($nRst) >= 1) {
		print("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">");
		while ($rowb = mysqli_fetch_object($nRst)) {
			$totalView = $rowb->banner_display + 1;
			$banID = $rowb->banner_id;
			print("<tr>");
			print("<td>");
			if ($rowb->bformat_id == 2) {
				print("<a href=\"bannerclick.php?banid=" . $banID . "&url=" . $rowb->banner_url . "\" title=\"" . $rowb->banner_alttext . "\" target=\"_blank\">");
				print("<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" border=\"0\">");
				print("<param name=\"movie\" value=\"" . $rowb->banner_source . "\">");
				print("<param name=\"quality\" value=\"high\">");
				print("<embed src=\"" . $rowb->banner_source . "\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\"></embed></object>");
				print("</a>");
			} else {
				print("<a href=\"bannerclick.php?banid=" . $banID . "&url=" . $rowb->banner_url . "\" title=\"" . $rowb->banner_alttext . "\" target=\"_blank\"><img src=\"" . $rowb->banner_source . "\" alt=\"" . $rowb->banner_alttext . "\" border=\"0\" align=\"absbottom\" class=\"img\"></a>");
			}
			print("		</td>");
			print("	</tr>");
			print("<tr><td height=\"10\"></td></tr>");
		}
		print("</table>");
		mysqli_query($GLOBALS['conn'], "UPDATE banner SET banner_display=" . $totalView . " WHERE banner_id = " . $banID);
	}
}

function showBanner2($btype)
{
	// show random banner where status is 1
	$stringQry = "SELECT * FROM banners WHERE status_id = 1 AND ban_start_date <= '" . date("Y-m-d") . "' AND ban_end_date >= '" . date("Y-m-d") . "' AND btype_id = " . $btype . " ORDER BY RAND()";
	$nRst = mysqli_query($GLOBALS['conn'], $stringQry);
	if (mysqli_num_rows($nRst) >= 1) {
		print("<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">");
		while ($rowb = mysqli_fetch_object($nRst)) {
			$totalView = $rowb->ban_display + 1;
			$banid = $rowb->ban_id;
			print("<tr>");
			print("<td>");
			/*	
			if($rowb->bformat_id == 2){
				print("<a href=\"bannerclick.php?banid=".$banID."&url=".$rowb->banner_url."\" title=\"".$rowb->banner_alttext."\" target=\"_blank\">");
				print("<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" border=\"0\">");
				print("<param name=\"movie\" value=\"".$rowb->banner_source."\">");
				print("<param name=\"quality\" value=\"high\">");
				print("<embed src=\"".$rowb->banner_source."\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\"></embed></object>");
				print("</a>");
			}
			else{
		*/
			print("<a href=\"bannerclick.php?banid=" . $banid . "&url=" . $rowb->ban_link . "\" target=\"_blank\"><img src=\"banner_files/" . $rowb->ban_image . "\" alt=\"" . $rowb->ban_alt_text . "\" border=\"0\" align=\"absbottom\" class=\"img\"></a>");
			//	}
			print("		</td>");
			print("	</tr>");
			print("<tr><td height=\"10\"></td></tr>");
		}
		print("</table>");
		mysqli_query($GLOBALS['conn'], "UPDATE banners SET ban_display=" . $totalView . " WHERE ban_id = " . $banid);
	}
}

function createThumbnail($imageDirectory, $imageName, $thumbDirectory, $thumbWidth)
{
	$srcImg = imagecreatefromjpeg("$imageDirectory/$imageName");
	$origWidth = imagesx($srcImg);
	$origHeight = imagesy($srcImg);

	$ratio = $origWidth / $thumbWidth;

	$thumbHeight = $origHeight / $ratio;
	/*echo $origHeight ."</br>";
echo $origWidth ."</br>";
echo $ratio ."</br>";
echo $thumbHeight ."</br>";
echo $thumbWidth;
die();*/
	$thumbImg = imagecreate($thumbWidth, $thumbHeight);
	imagecopyresized($thumbImg, $srcImg, 0, 0, 0, 0, $thumbWidth, $thumbHeight, imagesx($thumbImg), imagesy($thumbImg));

	imagejpeg($thumbImg, "$thumbDirectory/$imageName");
}

function createThumbnail3($imageDirectory, $imageName, $thumbDirectory, $thumbWidth, $thumbHeight)
{
	$file_path = $imageDirectory . "/" . $imageName;

	$option['jpeg_quality'] = 75;
	$option['png_quality'] = 9;

	$new_img = imagecreatetruecolor($thumbWidth, $thumbHeight);
	switch (strtolower(substr(strrchr($imageName, '.'), 1))) {
		case 'jpg':
		case 'jpeg':
			$srcImg = imagecreatefromjpeg($file_path);
			$write_image = 'imagejpeg';
			$image_quality = isset($options['jpeg_quality']) ?
				$options['jpeg_quality'] : 75;
			break;
		case 'gif':
			@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
			$srcImg = @imagecreatefromgif($file_path);
			$write_image = 'imagegif';
			$image_quality = null;
			break;
		case 'png':
			imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
			imagealphablending($new_img, false);
			imagesavealpha($new_img, true);
			$srcImg = @imagecreatefrompng($file_path);
			$write_image = 'imagepng';
			$image_quality = isset($options['png_quality']) ?
				$options['png_quality'] : 9;
			break;
		default:
			$srcImg = null;
	}
	$sourceWidth = imagesx($srcImg);
	$sourceHeight = imagesy($srcImg);
	echo $thumbDirectory . $imageName . "</br>";
	$success = $srcImg && @imagecopyresampled(
		$new_img,
		$srcImg,
		0,
		0,
		0,
		0,
		$thumbWidth,
		$thumbHeight,
		$sourceWidth,
		$sourceHeight
	) && $write_image($new_img, $thumbDirectory . '/' . $imageName, $image_quality);
	return $success;
}

function createThumbnail2($imageDirectory, $imageName, $thumbDirectory, $thumbWidth, $thumbHeight)
{
	$file_path = $imageDirectory . $imageName;
	$option['jpeg_quality'] = 75;
	$option['png_quality'] = 9;
	$createThumb = 1;
	// calculate thumbnail size
	switch (strtolower(substr(strrchr($imageName, '.'), 1))) {
		case 'jpg':
		case 'jpeg':
			$srcImg = @imagecreatefromjpeg($file_path);
			break;
		case 'gif':
			$srcImg = @imagecreatefromgif($file_path);
			break;
		case 'png':
			$srcImg = @imagecreatefrompng($file_path);
			break;
		default:
			$srcImg = null;
			$createThumb = 0;
			copy($imageDirectory . $imageName, $thumbDirectory . $imageName);
			break;
	}
	print_r($srcImg);
	if ($createThumb) {
		$srcWidth = imagesx($srcImg);
		$srcHeight = imagesy($srcImg);

		$new_width = $thumbWidth;
		$new_height = floor($srcHeight * ($thumbWidth / $srcWidth));
		if ($new_height > $thumbHeight) {
			$new_height = $thumbHeight;
			$new_width = floor($srcWidth * ($thumbWidth / $srcHeight));
		}
		$new_img = @imagecreatetruecolor($new_width, $new_height);
		switch (strtolower(substr(strrchr($imageName, '.'), 1))) {
			case 'jpg':
			case 'jpeg':
				$srcImg = @imagecreatefromjpeg($file_path);
				$write_image = 'imagejpeg';
				$image_quality = isset($options['jpeg_quality']) ?
					$options['jpeg_quality'] : 75;
				break;
			case 'gif':
				@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
				$srcImg = @imagecreatefromgif($file_path);
				$write_image = 'imagegif';
				$image_quality = null;
				break;
			case 'png':
				@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
				@imagealphablending($new_img, false);
				@imagesavealpha($new_img, true);
				$srcImg = @imagecreatefrompng($file_path);
				$write_image = 'imagepng';
				$image_quality = isset($options['png_quality']) ?
					$options['png_quality'] : 9;
				break;
			default:
				$srcImg = null;
		}
		//echo $thumbDirectory . $imageName . "</br>";
		$success = $srcImg && @imagecopyresampled(
			$new_img,
			$srcImg,
			0,
			0,
			0,
			0,
			$new_width,
			$new_height,
			$srcWidth,
			$srcHeight
		) && @$write_image($new_img, $thumbDirectory . $imageName, $image_quality);
	}

	return $success;
}

function createThumbnail23($imageDirectory, $imageName, $thumbDirectory, $thumbWidth, $thumbHeight)
{
	$file_path = $imageDirectory . $imageName;
	$option['jpeg_quality'] = 75;
	$option['png_quality'] = 9;
	$createThumb = 1;
	// calculate thumbnail size
	switch (strtolower(substr(strrchr($imageName, '.'), 1))) {
		case 'jpg':
		case 'jpeg':
			$srcImg = @imagecreatefromjpeg($file_path);
			break;
		case 'gif':
			$srcImg = @imagecreatefromgif($file_path);
			break;
		case 'png':
			$srcImg = @imagecreatefrompng($file_path);
			break;
		default:
			$srcImg = null;
			$createThumb = 0;
			copy($imageDirectory . $imageName, $thumbDirectory . $imageName);
			break;
	}
	// print_r($srcImg);
	if ($createThumb) {
		$srcWidth = imagesx($srcImg);
		$srcHeight = imagesy($srcImg);

		$new_width = $thumbWidth;
		$new_height = floor($srcHeight * ($thumbWidth / $srcWidth));
		/*if ($new_height > $thumbHeight) {
			$new_height = $thumbHeight;
			$new_width = floor($srcWidth * ($thumbWidth / $srcHeight));
		}*/
		$new_img = @imagecreatetruecolor($new_width, $new_height);
		switch (strtolower(substr(strrchr($imageName, '.'), 1))) {
			case 'jpg':
			case 'jpeg':
				$srcImg = @imagecreatefromjpeg($file_path);
				$write_image = 'imagejpeg';
				$image_quality = isset($options['jpeg_quality']) ?
					$options['jpeg_quality'] : 75;
				break;
			case 'gif':
				@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
				$srcImg = @imagecreatefromgif($file_path);
				$write_image = 'imagegif';
				$image_quality = null;
				break;
			case 'png':
				@imagecolortransparent($new_img, @imagecolorallocate($new_img, 0, 0, 0));
				@imagealphablending($new_img, false);
				@imagesavealpha($new_img, true);
				$srcImg = @imagecreatefrompng($file_path);
				$write_image = 'imagepng';
				$image_quality = isset($options['png_quality']) ?
					$options['png_quality'] : 9;
				break;
			default:
				$srcImg = null;
		}
		//echo $thumbDirectory . $imageName . "</br>";
		$success = $srcImg && @imagecopyresampled(
			$new_img,
			$srcImg,
			0,
			0,
			0,
			0,
			$new_width,
			$new_height,
			$srcWidth,
			$srcHeight
		) && @$write_image($new_img, $thumbDirectory . $imageName, $image_quality);
	}

	return $success;
}

function left_side_menu($Table, $IDField, $TextField, $ID, $parentField, $section, $table2, $page)
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table WHERE $parentField = 0 AND section_id='" . $section . "' AND status_id=1 ";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			$strQry = "SELECT $IDField, $TextField FROM $Table WHERE $parentField = $row[0] AND status_id=1";
			$nRs = mysqli_query($GLOBALS['conn'], $strQry);
			if (mysqli_num_rows($nRs) >= 1) {
				echo "<a class='menuheader expandable'>$row[1]</a>";
				echo "<ul class='categoryitems'>";
				while ($row1 = mysqli_fetch_row($nRs)) {
					if ($row1[0] == $ID) {
						echo ("$row1[1]");
					} else {
						$total_sub_products = TotalRecords($table2, " WHERE cat_id='" . $row1[0] . "' AND status_id=1 ");
						echo "<li><a href='" . $page . "$row1[0]'>$row1[1] ($total_sub_products) </a></li>";
					}
				}
				echo "</ul>";
			} else {
				if ($row[0] == $ID) {
					echo ("$row[1]");
				} else {
					echo ("<a class='menuheader expandable'>$row[1]</a>");
				}
			}
		}
	}
}

// FOR 2 LEVEL CATEGORIES
function left_side_menu2($Table, $IDField, $TextField, $ID, $parentField, $section)
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table WHERE $parentField = 0 AND section_id='" . $section . "'";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			$strQry = "SELECT $IDField, $TextField FROM $Table WHERE $parentField = $row[0]";
			echo "<option disabled='disabled' style='font-weight:bold'>$row[1]</option>";
			$nRs = mysqli_query($GLOBALS['conn'], $strQry);
			if (mysqli_num_rows($nRs) >= 1) {
				while ($row1 = mysqli_fetch_row($nRs)) {
					if ($row1[0] == $ID) {
						echo "<option value='$row1[0]' selected='selected'>$row1[1]</option>";
					} else {
						echo "<option value='$row1[0]'>$row1[1]</option>";
					}
				}
			}
		}
	}
}

// FOR 3 LEVEL CATEGORIES
function left_side_menu3($Table, $IDField, $TextField, $ID, $parentField, $section)
{
	$strQuery = "SELECT $IDField, $TextField FROM $Table WHERE $parentField = 0 AND section_id='" . $section . "'";
	$nResult = mysqli_query($GLOBALS['conn'], $strQuery);
	if (mysqli_num_rows($nResult) >= 1) {
		while ($row = mysqli_fetch_row($nResult)) {
			echo "<option disabled='disabled' style='font-weight:bold'>$row[1]</option>";
			$strQry = "SELECT $IDField, $TextField FROM $Table WHERE $parentField = $row[0]";
			$nRs = mysqli_query($GLOBALS['conn'], $strQry);
			if (mysqli_num_rows($nRs) >= 1) {
				while ($row1 = mysqli_fetch_row($nRs)) {
					echo "<option value='$row1[0]' style='font-weight:bold'> &nbsp; &nbsp; $row1[1]</option>";
					$strQry3 = "SELECT $IDField, $TextField FROM $Table WHERE $parentField = $row1[0]";
					$nRs3 = mysqli_query($GLOBALS['conn'], $strQry3);
					if (mysqli_num_rows($nRs3) >= 1) {
						while ($row3 = mysqli_fetch_row($nRs3)) {
							if ($row3[0] == $ID) {
								echo "<option value='$row3[0]' selected='selected'> &nbsp; &nbsp; &nbsp; &nbsp; $row3[1]</option>";
							} else {
								echo "<option value='$row3[0]'> &nbsp; &nbsp; &nbsp; &nbsp; $row3[1]</option>";
							}
						}
					}
				}
			}
		}
	}
}

function dateTime($date, $displayTime)
{
	if ($date != "") {
		$arrtime = '';
		$time = '';
		$arrdate = @explode("-", $date);
		$arrdate2 = @explode(" ", $arrdate[2]);
		if (@sizeof($arrdate2) > 1) {
			$arrtime = @explode(":", $arrdate2[1]);
			$time = @date("g:i:s a", @mktime($arrtime[0], $arrtime[1], $arrtime[2]));
		}
		$date = @date("M j, Y", @mktime(0, 0, 0, $arrdate[1], $arrdate2[0], $arrdate[0]));
		if ($date == "0000-00-00" or $date == "0000-00-00 00:00:00") {
			$date = '';
		}
		if ($displayTime == 1) {
			return $date = $date . ' ' . $time;
		} else {
			return $date = $date;
		}
	}
}

function displayAllRecords($field, $from, $where)
{
	$counter = 0;
	$Query =  "SELECT $field FROM $from WHERE $where ";
	$pro = mysqli_query($GLOBALS['conn'], $Query);
	$total_rec = mysqli_num_rows($pro);
	while ($row = mysql_fetch_assoc($pro)) {
		$counter++;
		echo $row[$field];
		if ($total_rec != $counter) {
			echo ' , ';
		}
	}
}

function redirect($url)
{
	if (!headers_sent()) {
		//If headers not sent yet... then do php redirect
		header('Location: ' . $url);
		exit;
	} else {
		//If headers are sent... do javascript redirect... if javascript disabled, do html redirect.
		echo '<script type="text/javascript">';
		echo 'window.location.href="' . $url . '";';
		echo '</script>';
		echo '<noscript>';
		echo '<meta http-equiv="refresh" content="0;url=' . $url . '" />';
		echo '</noscript>';
		exit;
	}
}

function displayDateTime($date, $displayTime)
{
	if ($date != "") {
		$arrtime = '';
		$time = '';
		$arrdate = explode("-", $date);
		$arrdate2 = explode(" ", $arrdate[2]);
		if (sizeof($arrdate2) > 1) {
			$arrtime = explode(":", $arrdate2[1]);
			$time = date("g:i:s a", mktime($arrtime[0], $arrtime[1], $arrtime[2]));
		}
		$date = date("F j", mktime(0, 0, 0, $arrdate[1], $arrdate2[0], $arrdate[0]));
		if ($date == "0000-00-00" or $date == "0000-00-00 00:00:00") {
			$date = '';
		}
		if ($displayTime == 1) {
			return $date = $date . ' ' . $time;
		} else {
			return $date = $date;
		}
	}
}

function Activity($lead_id, $message)
{
	$act_id = getMaximum("activity_logs", "act_id");
	$Query_act = "INSERT INTO activity_logs(act_id,user_id, lead_id,act_details,act_datetime )VALUES(" . $act_id . ", '" . $_SESSION['UserID'] . "','" . $lead_id . "', '" . $message . "',NOW())";

	mysqli_query($GLOBALS['conn'], $Query_act) or die(mysqli_error($GLOBALS['conn']));
}

function dateTimeDiff($datadiff,  $months, $days)
{
	$string = '';

	if ($months != 0) {
		$string = $months . " months ";
	}
	if ($days != 0) {
		$string = $days . " days ";
	}
	if ($days == 0) {
		$datadiff = explode(":", $datadiff);
		$hr  = $datadiff[0];
		$min = $datadiff[1];
		$sec = $datadiff[2];
		if ($hr != 0) {
			$string = $hr . " hr ";
		}

		if ($min == 0) {
			$string = $string . $sec . " Seconds ";
		}
		if ($hr == 0) {
			$string = $string . $min . " min ";
		}
	}
	$string = $string . " ago ";
	return $string;
}

function catDisp($catID)
{
	$rsM = mysqli_query($GLOBALS['conn'], "SELECT * FROM categories WHERE cat_id = " . $catID);
	$rsMem = mysqli_fetch_object($rsM);
	return $rsMem;
	/*$name= $rsMem->cat_name;
			$catdetail= $rsMem->cat_long_details;
			$catimg= $rsMem->cat_img;
			$catimg= $rsMem->cat_header_img;
			$together= $name."-".$catdetail."-".$catimg;
			return $together;*/
}

function create_password($len)
{
	$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	$str = '';
	for ($i = 0; $i < $len; $i++) {
		$str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
	}
	return $str;
}
function get_contant($field, $cnt_id, $wid_id)
{
	$rs = mysqli_query($GLOBALS['conn'], "SELECT $field AS contant FROM `widgets` WHERE wid_id = '$wid_id' AND cnt_id = '$cnt_id'");
	if (mysqli_num_rows($rs) > 0) {
		$row = mysqli_fetch_object($rs);
		$contant = $row->contant;
	} else {
		$contant = "Record not found!";
	}
	return $contant;
}

function shipment_charges($user_countrie, $user_state, $cart_weight_total)
{
	if ($user_countrie == 'Pakistan') {
		if ($user_state == 'Punjab') {
			if ($cart_weight_total <= '3') {
				//shipping_charges calculaion
				if ($cart_weight_total == '0.50') {
					$shipping_charges = get_courier_price($cart_weight_total, "1", "0", " AND cr.cz_id = '2' AND cw.cw_actual_weight = $cart_weight_total");
				} else {
					$shipping_charges = get_courier_price($cart_weight_total, "2", "0", " AND cr.cz_id = '2' AND cw.cw_actual_weight = " . ceil($cart_weight_total) . "");
				}
			} elseif ($cart_weight_total < '5') {
				//shipping_charges calculaion
				$shipping_charges = get_courier_price($cart_weight_total, "2", "1", " AND cr.cz_id = '2' AND cw.cw_actual_weight = '3.00'");
			} elseif ($cart_weight_total == '5') {
				//shipping_charges calculaion
				$shipping_charges = get_courier_price($cart_weight_total, "2", "0", " AND cr.cz_id = '2' AND cw.cw_actual_weight = $cart_weight_total");
			} elseif ($cart_weight_total < '10') {
				//shipping_charges calculaion
				$shipping_charges = get_courier_price($cart_weight_total, "2", "1", " AND cr.cz_id = '2' AND cw.cw_actual_weight = '5.00'");
			}
		} else {
			// other provinc calculation
			if ($cart_weight_total <= '3') {
				//shipping_charges calculaion
				if ($cart_weight_total == '0.50') {
					$shipping_charges = get_courier_price($cart_weight_total, "1", "0", " AND cr.cz_id = '3' AND cw.cw_actual_weight = $cart_weight_total");
				} else {
					$shipping_charges = get_courier_price($cart_weight_total, "2", "0", " AND cr.cz_id = '3' AND cw.cw_actual_weight = $cart_weight_total");
				}
			} elseif ($cart_weight_total < '5') {
				//shipping_charges calculaion
				$shipping_charges = get_courier_price($cart_weight_total, "2", "1", " AND cr.cz_id = '3' AND cw.cw_actual_weight = '3.00'");
			} elseif ($cart_weight_total == '5') {
				//shipping_charges calculaion
				$shipping_charges = get_courier_price($cart_weight_total, "2", "0", " AND cr.cz_id = '3' AND cw.cw_actual_weight = $cart_weight_total");
			} elseif ($cart_weight_total < '10') {
				//shipping_charges calculaion
				$shipping_charges = get_courier_price($cart_weight_total, "2", "1", " AND cr.cz_id = '3' AND cw.cw_actual_weight = '5.00'");
			}
		}
	}
	return $shipping_charges;
}
function get_courier_price($cart_weight_total, $cr_type, $additional_charges, $cr_where)
{
	$courier_price = 0;
	$additional_weight = 0;
	$actual_price = 0;
	$additional_price = 0;
	//echo "SELECT cr.cr_price , cr.cr_additional_price, cw.cw_actual_weight FROM `courier_rate` AS cr LEFT OUTER JOIN courier_weight AS cw ON cw.cw_id=cr.cw_id WHERE cr.cn_id = '1' AND cr.cr_type = '".$cr_type."' ".$cr_where.""; die;
	$rs = mysqli_query($GLOBALS['conn'], "SELECT cr.cr_price , cr.cr_additional_price, cw.cw_actual_weight FROM `courier_rate` AS cr LEFT OUTER JOIN courier_weight AS cw ON cw.cw_id=cr.cw_id WHERE cr.cn_id = '1' AND cr.cr_type = '" . $cr_type . "' " . $cr_where . "");
	if (mysqli_num_rows($rs) > 0) {
		$row = mysqli_fetch_object($rs);
		if ($additional_charges == '0') {
			//echo "if";die;
			$courier_price = $row->cr_price;
		} else {
			//echo "else";die;
			$additional_weight = ceil($cart_weight_total) - $row->cw_actual_weight;
			$actual_price = $row->cr_price;
			$additional_price = $row->cr_additional_price * $additional_weight;
			$courier_price = $actual_price + $additional_price;
		}
	}
	return $courier_price;
}

function get_email_template($eml_id)
{
	$get_data = array();
	$Query = "SELECT * FROM `emails` WHERE `eml_id` = '" . $eml_id . "'";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		$rw = mysqli_fetch_object($rs);
		$get_data[] = array(
			"eml_subject" => $rw->eml_subject,
			"eml_contents" => $rw->eml_contents
		);
	}
	$jsonResults = json_encode($get_data);
	return $jsonResults;
}

function lov_product_size($array_data)
{
	$retValue = array();
	/*print("<pre>");
	print_r($array_data);
	print("</pre>");*/
	$Query = "SELECT * FROM lov_product_size WHERE lov_ps_longartical_no = '" . $array_data['lov_ps_longartical_no'] . "'";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		$rw = mysqli_fetch_object($rs);
		mysqli_query($GLOBALS['conn'], "UPDATE lov_product_size SET  lov_ps_sizeid = '" . $array_data['lov_ps_sizeid'] . "', lov_ps_title = '" . $array_data['lov_ps_title'] . "', manf_code = '" . $array_data['manf_code'] . "' WHERE lov_ps_id = '" . $rw->lov_ps_id . "'") or die(mysqli_error($GLOBALS['conn']));
		$retValue = array("status" => "1", "message" => "Recode updated successfully!");
	} else {
		$lov_ps_id = getMaximum("lov_product_size", "lov_ps_id");
		mysqli_query($GLOBALS['conn'], "INSERT INTO lov_product_size(lov_ps_id, pro_id, manf_code, lov_ps_ean, lov_ps_longartical_no, lov_ps_sizeid, lov_ps_title) VALUES('" . $lov_ps_id . "', '" . $array_data['pro_id'] . "', '" . $array_data['manf_code'] . "', '" . $array_data['lov_ps_ean'] . "', '" . $array_data['lov_ps_longartical_no'] . "', '" . $array_data['lov_ps_sizeid'] . "', '" . $array_data['lov_ps_title'] . "')") or die(mysqli_error($GLOBALS['conn']));
		$retValue = array("status" => "1", "message" => "Recode added successfully!");
	}
	return $retValue;
}

function product_colors($array_data)
{
	$retValue = array();
	/*print("<pre>");
	print_r($array_data);
	print("</pre>");*/
	$Query = "SELECT * FROM product_colors WHERE pro_id = '" . $array_data['pro_id'] . "' AND pc_image_hexcode = '" . $array_data['pc_image_hexcode'] . "'";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		$rw = mysqli_fetch_object($rs);
		mysqli_query($GLOBALS['conn'], "UPDATE product_colors SET pc_image = '" . $array_data['pc_image'] . "'  WHERE pc_id = '" . $rw->pc_id . "'") or die(mysqli_error($GLOBALS['conn']));
		$retValue = array("status" => "1", "message" => "Recode updated successfully!");
	} else {
		$pc_id = getMaximum("product_colors", "pc_id");
		mysqli_query($GLOBALS['conn'], "INSERT INTO product_colors (pc_id, manf_code, pro_id, pc_title, pc_image, pc_image_hexcode) VALUES('" . $pc_id . "', '" . $array_data['manf_code'] . "', '" . $array_data['pro_id'] . "', '" . $array_data['pc_title'] . "', '" . $array_data['pc_image'] . "', '" . $array_data['pc_image_hexcode'] . "')") or die(mysqli_error($GLOBALS['conn']));
		$retValue = array("status" => "1", "message" => "Recode added successfully!");
	}
	//print_r($retValue);
	return $retValue;
}

function product_images($array_data)
{
	$retValue = array();
	/*print("<pre>");
	print_r($array_data);
	print("</pre>");*/
	$Query = "SELECT * FROM pro_images WHERE pro_id = '" . $array_data['pro_id'] . "' AND pc_image_hexcode = '" . $array_data['pc_image_hexcode'] . "' AND pimg_orderby = '" . $array_data['pimg_orderby'] . "'";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		//echo "if";
		$rw = mysqli_fetch_object($rs);
		$pimg_default = 0;
		if ($array_data['pimg_orderby'] == 1 && empty($array_data['pc_image_hexcode'])) {
			$pimg_default = 1;
		}
		mysqli_query($GLOBALS['conn'], "UPDATE pro_images SET pimg_file = '" . $array_data['pimg_file'] . "', pro_id = '" . $array_data['pro_id'] . "', pc_id = '" . $array_data['pc_id'] . "', pc_image_hexcode = '" . $array_data['pc_image_hexcode'] . "', pimg_orderby = '" . $array_data['pimg_orderby'] . "', pimg_bildtype = '" . $array_data['pimg_bildtype'] . "', pimg_default = '" . $pimg_default . "' WHERE pimg_id = '" . $rw->pimg_id . "'") or die(mysqli_error($GLOBALS['conn']));
		$retValue = array("status" => "1", "message" => "Recode updated successfully!");
	} else {
		//echo "else";
		$pimg_id = getMaximum("pro_images", "pimg_id");
		$pimg_default = 0;
		if ($array_data['pimg_orderby'] == 1 && empty($array_data['pc_image_hexcode'])) {
			$pimg_default = 1;
		}
		mysqli_query($GLOBALS['conn'], "INSERT INTO pro_images (pimg_id, pimg_file, pro_id, pc_id, pimg_orderby, pimg_bildtype, pc_image_hexcode, pimg_default) VALUES('" . $pimg_id . "', '" . $array_data['pimg_file'] . "', '" . $array_data['pro_id'] . "', '" . $array_data['pc_id'] . "', '" . $array_data['pimg_orderby'] . "', '" . $array_data['pimg_bildtype'] . "', '" . $array_data['pc_image_hexcode'] . "', '" . $pimg_default . "')") or die(mysqli_error($GLOBALS['conn']));
		$retValue = array("status" => "1", "message" => "Recode added successfully!");
	}
	//print_r($retValue);
	return $retValue;
}

function setdefualt($field, $table,  $IDField, $ID, $checkfield, $checkid)
{
	$retValue = "";
	$Query = "SELECT " . $field . " FROM " . $table . " WHERE " . $IDField . " = " . $ID . " ";
	$rs = mysqli_query($GLOBALS['conn'], $Query);
	if (mysqli_num_rows($rs) > 0) {
		while ($row = mysqli_fetch_row($rs)) {
			if ($row[0] == $checkid) {
				mysqli_query($GLOBALS['conn'], "UPDATE " . $table . " SET " . $checkfield . " = '1' WHERE " . $field . " = " . $row[0] . " ") or die(mysqli_error($GLOBALS['conn']));
			} else {
				mysqli_query($GLOBALS['conn'], "UPDATE " . $table . " SET " . $checkfield . " = '0' WHERE " . $field . " = " . $row[0] . " ") or die(mysqli_error($GLOBALS['conn']));
			}
		}
		$retValue = "Set Defualt";
	} else {
		$retValue = "Not Set Deualt";
	}

	return $retValue;
}

function objectToArray($d)
{
	//print_r($d);die();
	if (is_object($d)) {
		// Gets the properties of the given object
		// with get_object_vars function
		$d = get_object_vars($d);
	}

	if (is_array($d)) {
		/*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
		return array_map(__FUNCTION__, $d);
	} else {
		// Return array
		return $d;
	}
}
function getLangCode($lngID)
{
	$lngCode = "";
	switch ($lngID) {
		case 1:
			$lngCode = "";
			break;
		case 2:
			$lngCode = "_de";
			break;
		default:
			$lngCode = "";
			break;
	}
	return $lngCode;
}

// REQUESTING PAYPAL API
function PaypalRequest($ord_id, $order_net_amount)
{
	//$url = "https://test.vr-pay-ecommerce.de/v1/payments";
	//$url = "https://vr-pay-ecommerce.de/v1/payments";
	$url = "".config_payment_url."";
	//$data = "entityId=".PAYPAL."" .
	$data = "entityId=8ac7a4ca84e4549a0184e6fc903e14a5" .
		"&merchantTransactionId=" . $ord_id .
		"&amount=" . $order_net_amount .
		"&currency=EUR" .
		"&paymentBrand=PAYPAL" .
		"&paymentType=DB" .
		"&shopperResultUrl=" . $GLOBALS['siteURL'] . "checkout_order_complete.php?ord_id=" . $ord_id;
	//  print_r($data);die;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Authorization:Bearer '.config_authorization_bearer.''
	));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$responseData = curl_exec($ch);
	if (curl_errno($ch)) {
		return curl_error($ch);
	}
	curl_close($ch);
	return $responseData;
}

function cardrequest($ord_id, $order_net_amount, $request){

	//$url = "https://vr-pay-ecommerce.de/v1/payments";
	$url = "".config_payment_url."";
	$data = "entityId=".$request['entityId'].
	"&merchantTransactionId=" . $ord_id .
		"&amount=" . $order_net_amount.
		"&currency=".$request['currency'] .
		"&paymentBrand=".$request['brand'] .
		"&paymentType=DB" .
		"&card.number=" . $request['cardnumber'] .
		"&card.holder=" . $request['cardholder'] .
		"&card.expiryMonth=" . $request['cardmonth'] .
		"&card.expiryYear=" . $request['cardyear'] .
		"&card.cvv=" . $request['cvv'] .
		"&shopperResultUrl=" . $GLOBALS['siteURL'] . "checkout_order_complete.php?ord_id=" . $ord_id;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Authorization:Bearer '.config_authorization_bearer.''
	));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$responseData = curl_exec($ch);
	if (curl_errno($ch)) {
		return curl_error($ch);
	}
	curl_close($ch);
	return $responseData;
}

function SepaRequest($request){

	$url = "https://vr-pay-ecommerce.de/v1/payments";
	$data = "entityId=" . SEPA . "" .
		"&amount=" . number_format($request['amount'], 2) .
		"&currency=EUR" .
		"&paymentBrand=DIRECTDEBIT_SEPA" .
		"&paymentType=DB" .
		"&bankAccount.iban=" . $request['iban'] .
		"&bankAccount.country=" . $request['country'] .
		"&bankAccount.holder=" . $request['name'] .
		"&bankAccount.mandate.id=" . $request['madateID'] .
		"&bankAccount.mandate.dateOfSignature=" . $request['madateSignature'];
	// echo '<pre>';print_r($data);die;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Authorization:Bearer OGFjZGE0Y2E4ZGNiMzQ3NzAxOGUwYTg1MWVkMjI2YzB8TnFiZEp0TVdnOUZSZldXbg=='
	));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // this should be set to true in production
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$responseData = curl_exec($ch);
	if (curl_errno($ch)) {
		return curl_error($ch);
	}
	curl_close($ch);
	return $responseData;
}

function motif_product_image($pro_img_path_orig, $pro_img_name_orig, $pro_img_path_genrated, $motif_path_gen, $motif_gen_image, $pimg_start_x_axis, $pimg_start_y_axis, $pimg_end_x_axis, $pimg_end_y_axis){
	$img_gen1 = 0;
	$pro_img_path_name_gen = $pro_img_path_genrated . $pro_img_name_orig;

	
	$img_gen1 = createThumbnail23($pro_img_path_orig, $pro_img_name_orig, $pro_img_path_genrated, 575, 659);
	
	/*echo "0 img_gen1 = ".$img_gen1."<br>";
	echo "1 pro_img_path_orig = ".$pro_img_path_orig."<br>";
	echo "2 pro_img_name_orig = ".$pro_img_name_orig."<br>";
	echo "3 pro_img_path_genrated = ".$pro_img_path_genrated."<br>";
	echo "4 motif_path_gen = ".$motif_path_gen."<br>";
	echo "5 motif_gen_image = ".$motif_gen_image."<br>";*/
	
	//print($img_gen1);die();
	//if($img_gen1 == 1){
		//$dest = imagecreatefrompng($pro_img_path_name_gen);
		switch (strtolower(substr(strrchr($pro_img_name_orig, '.'), 1))) {
			case 'jpg':
			case 'jpeg':
				$dest = @imagecreatefromjpeg($pro_img_path_name_gen);
				break;
			case 'gif':
				$dest = @imagecreatefromgif($pro_img_path_name_gen);
				break;
			case 'png':
				$dest = imagecreatefrompng($pro_img_path_name_gen);
				break;
			default:
				$dest = imagecreatefrompng($pro_img_path_name_gen);
				break;
		}
		$src = imagecreatefrompng($motif_path_gen);
		
		$background = imagecolorallocatealpha($src, 0, 0, 0, 127);
		imagecolortransparent($src, $background);
		imagealphablending($src, true);
		imagesavealpha($src, true);
		
		imagealphablending($dest, true);
		imagesavealpha($dest, true);
		
		//imagecopymerge($dest, $src, 155, 125, 0, 0, 140, 175, 75);
		//imagecopy($dest, $src, 155, 125, 0, 0, 140, 175);
		$pimg_width = $pimg_end_x_axis - $pimg_start_x_axis;
		$pimg_height = imagesy($src);
		//$pimg_height = $pimg_end_y_axis - $pimg_start_y_axis;
		imagecopy($dest, $src, $pimg_start_x_axis, $pimg_start_y_axis, 0, 0, $pimg_width, $pimg_height);
	
		//header('Content-Type: image/png');
		imagepng($dest, $motif_gen_image, 0, -1);
	
		//print('<img src="'.$editor_img_name.'">');
	
		imagedestroy($dest);
		imagedestroy($src);
		
	/*} else{
		print("Img Gen 1: ".$img_gen1);
	}*/
	return $img_gen1;
}
