<?php
class Functions{

	public function category_listing($parent_id){
		$retArr = array();
		$rs = mysqli_query($GLOBALS['conn'], "SELECT m.cat_id, m.cat_title, m.parent_id, m.cat_icon, m.cat_image FROM category AS m WHERE m.cat_status='1' AND m.parent_id='".$parent_id."'");
		if(mysqli_num_rows($rs)>0){
			while($rw=mysqli_fetch_object($rs)){
				$cat_icon = "";
                if($rw->cat_icon){
                    $cat_icon = $GLOBALS['siteURL']."files/cat_icons/".$rw->cat_icon;
                }
				$cat_image = "";
                if($rw->cat_image){
                    $cat_image = $GLOBALS['siteURL']."files/categorys/".$rw->cat_image;
                }
				$retArr[] = array("cat_id" => $rw->cat_id, "cat_title" => strval($rw->cat_title), "cat_icon" => strval($cat_icon), "cat_image" => strval($cat_image), "children" => $this->category_listing($rw->cat_id));
			}
		}
		return $retArr;
	}

	public function dbArabic($str){
		$str = str_replace("ک", "ك", $str);
		$str = str_replace("ہ", "ه", $str);
		//$str = str_replace("ی", "ى", $str);
		$str = str_replace("ی", "ي", $str);
		$str = str_replace("ى", "ي", $str);
		$str = str_replace("ۃ", "ة", $str);
		//$str = str_replace("للهِ", "للّٰهِ", $str);
		$str = str_replace("لله", "للّٰه", $str);
		return $str;		
	}
	
	public function chkImage($imgType){
		$isallowed = 0;
		$typesAllowed = array('image/jpeg', 'image/gif', 'image/png');
		if (in_array($imgType, $typesAllowed)) {
			$isallowed = 1;
		}
		return $isallowed;
	}

	public function get_lov_relations($params){
		$retValue = array();
		$lng = "en";
        if(isset($params['lang_id'])){
            $lng = $this->getLangCode($params['lang_id']);
        }
		$Query = "SELECT relation_id, relation_title_".$lng." AS relation_title FROM lov_relations ORDER BY relation_id";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            while ($rw = mysqli_fetch_object($rs)) {
                $retValue[] = array(
                    "relation_id" => $rw->relation_id,
                    "relation_title" => strval($rw->relation_title)
                );
            }
        }
        return $retValue;
	}

	public function get_lov_job_status($params){
		$retValue = array();
		$lng = "en";
        if(isset($params['lang_id'])){
            $lng = $this->getLangCode($params['lang_id']);
        }
		$Query = "SELECT ast_id, ast_name_".$lng." AS ast_name FROM ad_status ORDER BY ast_id";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            while ($rw = mysqli_fetch_object($rs)) {
				$ast_img = $GLOBALS['siteURL']."files/icons/status/".$rw->ast_id.".png";
                $retValue[] = array(
                    "ast_id" => $rw->ast_id,
                    "ast_name" => strval($rw->ast_name),
					"ast_img" => strval($ast_img)
                );
            }
        }
        return $retValue;
	}

	public function getLangCode($lngID){
		$lngCode = "en";
		switch($lngID){
			case 1:
				$lngCode = "en";
				break;
			case 2:
				$lngCode = "de";
				break;
			default:
				$lngCode = "en";
				break;
		}
		return $lngCode;
	}

	public function randomKey($length) {
		$key = "";
		$pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));
		for($i=0; $i < $length; $i++) {
			@$key .= $pool[mt_rand(0, count($pool) - 1)];
		}
		return $key;
	}

	public function curl_Requests($flURL, $apiParams){
		$ch = curl_init();
		//curl_setopt($ch, CURLOPT_URL, 'https://oprotect.flowlu.com/api/v1/module/crm/account/create/?api_key='.flowlu_api_key);
		curl_setopt($ch, CURLOPT_URL, $flURL);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $apiParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		curl_close ($ch);
		return json_decode($response, true);
	}

	public function dbStr($str){
		$string = str_replace("'", "''", $str); 
		return $string;
	}

	public function en_url($str, $lng = 'en'){
		//$string = urlencode(strtolower($str)); 
		$string = str_replace(" ", "-", trim($str)); 
		if($lng == 'en'){
			$string = str_replace("&", "amp", trim($string)); 
			$string = urlencode(strtolower($string));
		}
		//$string = urlencode(trim($str)); 
		return $string;
	}

	public function de_url($str, $lng = 'en'){
		if($lng == 'en'){
			$str = urldecode(strtolower($str));
		}
		$string = str_replace("-", " ", $str); 
		$string = str_replace("amp", "&", $string); 
		return $string;
	}

	public function snglBR($str){
		$str = nl2br($str);
		$str = str_replace("<br><br>", "<br>", $str); 
		return $str;
	}

	public function getMaximum($Table, $Field){
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
	
	public function getMaximumwhere($Table, $Field, $where){
		$maxID = 0;
		$strQry = "SELECT MAX(" . $Field . ")+1 as CID FROM " . $Table . " WHERE ".$where." ";
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

	public function chkExist($Field, $Table, $WHERE){
		$retRes=0;
		$strQry="SELECT $Field FROM $Table $WHERE";
		$nResult=mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
		if (mysqli_num_rows($nResult)>=1){
			$row=mysqli_fetch_row($nResult);
			$retRes = $row[0];
			//$retRes=1;
		}	
		return $retRes;		
	}

	public function createThumbnail2($imageDirectory, $imageName, $thumbDirectory, $thumbWidth, $thumbHeight) {
		$file_path = $imageDirectory . $imageName;
		$option['jpeg_quality'] = 75;
		$option['png_quality'] = 9;

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
		}

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
		return $success;
	}

	public function returnName($Field, $Table, $IDField, $ID, $and = ""){
		$retRes = "";
		$strQry = "SELECT $Field FROM $Table WHERE $IDField='".$this->dbStr($ID)."' ".$and." LIMIT 1";
		//print($strQry);
		$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
		if (mysqli_num_rows($nResult)>=1){		
			$row = mysqli_fetch_row($nResult);
			$retRes = $row[0];
		}	
		return $retRes;	
	}

	public function returnNameArray($Field, $Table, $IDField, $ID, $and = ""){
		$retValue = array();
		
		$Query = "SELECT $Field AS field FROM $Table WHERE $IDField='".$this->dbStr($ID)."' ".$and." ";
		//print($Query);
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            while ($rw = mysqli_fetch_object($rs)) {
                $retValue[] = array(
                    "field" => $rw->field
                );
            }
        }
        return $retValue;
	}

	public function returnNameRepDash($Field, $Table, $IDField, $ID){
		$retRes = "";
		$strQry = "SELECT $Field FROM $Table WHERE REPLACE($IDField, '-', ' ')='".$this->dbStr($ID)."' LIMIT 1";
		$nResult = mysqli_query($GLOBALS['conn'], $strQry) or die("Unable 2 Work");
		if (mysqli_num_rows($nResult)>=1){		
			$row = mysqli_fetch_row($nResult);
			$retRes = $row[0];
		}	
		return $retRes;	
	}

	public function getLatLngFrmAdrs($params){
		$retVal = array();
		
		$formatted_address = str_replace(' ', '+', $params['address']); 
		$call_url = "https://maps.googleapis.com/maps/api/geocode/json?address=$formatted_address&key=".$GLOBALS['GOOGLE_API_KEY'];
		// Get geo data from Google Maps API by address 
		//$geocodeFromAddr = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address={$formatted_address}&key={".$GLOBALS['GOOGLE_API_KEY']."}"); 
		$geocodeFromAddr = file_get_contents($call_url); 
		
		// Decode JSON data returned by API 
		$apiResponse = json_decode($geocodeFromAddr); 

		// Retrieve latitude and longitude from API data 
		if($apiResponse->status == "OK"){
			$status = 1;
			$msg = $apiResponse->status;
			$latitude  = $apiResponse->results[0]->geometry->location->lat;  
			$longitude = $apiResponse->results[0]->geometry->location->lng; 
		} else{
			$status = 0;
			$msg = $apiResponse->status . " - " . $apiResponse->error_message;
			$latitude  = "";  
			$longitude = "";
		}

		$retVal = array("status" => $status, "message" => $msg, "latitude" => $latitude, "longitude" => $longitude);
		return $retVal;
	}
}