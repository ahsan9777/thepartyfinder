<?php
function delRecord($qry){
	mysqli_query($GLOBALS['conn'], $qry);
}

function getreferencetypes($params) {

            $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM lov_reference_types ORDER BY reftyp_id") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $retValue[] = array('reftyp_id' => $row->reftyp_id, 'reftyp_title' => $row->reftyp_title);
                }
            } else {
                $retValue = array();
            }
     
        return $retValue;
    }
    
    function generatepassword($length = 5) {
	$chars = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	$retValue = substr(str_shuffle($chars), 0, $length);
	return $retValue;
}

    
function getshiftimingsV($params) {

            $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM lov_shift_timings ORDER BY shift_id") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $retValue[] = array('shift_id' => $row->shift_id, 'shift_name' => $row->shift_name);
                }
            } else {
                $retValue = array();
            }
     
        return $retValue;
    }
function getQualificationV($params) {

            $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM lov_education ORDER BY edu_name") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $retValue[] = array('edu_id' => $row->edu_id, 'edu_name' => $row->edu_name);
                }
            } else {
                $retValue = array();
            }
     
        return $retValue;
    }
    function getDegressV($params) {

            $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM lov_degree ORDER BY deg_id") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
            if (mysqli_num_rows($rs) > 0) {
                while ($row = mysqli_fetch_object($rs)) {
                    $retValue[] = array('deg_id' => $row->deg_id, 'deg_name' => $row->deg_name);
                }
            } else {
                $retValue = array();
            }
     
        return $retValue;
    }
    
    function getDepartments($params) {
            $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM lov_categories ORDER BY cat_name ASC") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
            if (mysqli_num_rows($rs) > 0) {             
                while ($row = mysqli_fetch_object($rs)) {
                    $retValue[] = array('cat_id' => $row->cat_id, 'cat_name' => $row->cat_name);
                }
            } else {
                $retValue = array();
            }
        return $retValue;
    }
     
    
    function getlov_level($params) {

            $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM lov_level ORDER BY lvl_id") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
            if (mysqli_num_rows($rs) > 0) {
             
                while ($row = mysqli_fetch_object($rs)) {
                    $retValue[] = array('lvl_id' => $row->lvl_id, 'lvl_name' => $row->lvl_name);
                }
            } else {
                $retValue = array();
            }
     
        return $retValue;
    }
    function getlov_last_used($params) {

            $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM lov_last_used ORDER BY lusd_id") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
            if (mysqli_num_rows($rs) > 0) {
             
                while ($row = mysqli_fetch_object($rs)) {
                    $retValue[] = array('lusd_id' => $row->lusd_id, 'lusd_title' => $row->lusd_title);
                }
            } else {
                $retValue = array();
            }
     
        return $retValue;
    }
    
    
	function getUserSkills($params) {
		if(isset($params['userID'])){
			$userID = explode("_", $params['userID']);
			$datafilter="WHERE s.user_id='".$userID[0]."'";
		}else{
			 $datafilter="WHERE s.user_id='".$_SESSION['uid']."'";
		}
		$rs = mysqli_query($GLOBALS['conn'], "SELECT s.uskil_title,s.uskil_exp_year,s.uskil_id,lvl.lvl_name,lov.lusd_title FROM user_skills AS s LEFT OUTER JOIN lov_level AS lvl ON lvl.lvl_id=s.lvl_id LEFT OUTER JOIN lov_last_used AS lov ON lov.lusd_id=s.lusd_id ".$datafilter." ORDER BY s.uskil_id DESC") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
		if (mysqli_num_rows($rs) > 0) {
			while ($row = mysqli_fetch_object($rs)) {
				$retValue[] = $row;
			}
		} else {
			$retValue = array();
		}
		return $retValue;
	}
	
	function getSysSkills(){
		$retVal = "";
		//$rs = mysqli_query($GLOBALS['conn'], "SELECT uskil_title FROM user_skills  GROUP BY uskil_title");
		$rs = mysqli_query($GLOBALS['conn'], "SELECT skl_name AS uskil_title FROM lov_skills GROUP BY skl_name");
		if(mysqli_num_rows($rs)>0){
			while($r=mysqli_fetch_object($rs)){
				$retVal[] = $r;
			}
		}
		return $retVal;
	}
    
//     function getUserSkillsW($params) {
//       
//         if (isset($params['uskilid'])) {
//           $uskilid=$params['uskilid'];
//            $filtdata = " AND s.uskil_id='".$uskilid."'";
//        }  else {
//            $filtdata='';
//        }
//        //echo "SELECT s.* FROM user_skills AS s WHERE  s.user_id='".$_SESSION['uid']."' ".$filtdata."   ";
//            $rs = mysqli_query($GLOBALS['conn'], "SELECT s.* FROM user_skills AS s WHERE  s.user_id='".$_SESSION['uid']."' ".$filtdata."   ") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
//            if (mysqli_num_rows($rs) > 0) {
//             
//                while ($row = mysqli_fetch_object($rs)) {
//                    $retValue[] = $row;
//                }
//            } else {
//                $retValue = array();
//            }
//     
//        return $retValue;
//    }
    
     function getUserPersonal($params) {
                
                if(isset($params['userID'])){
					$userID = explode("_", $params['userID']);
					$datafilter="WHERE u.user_id='".$userID[0]."'"; 
                }  else {
                   $datafilter="WHERE u.user_id='".$_SESSION['uid']."'"; 
                }
                //echo "SELECT u.*,cinfo.*,ind.*,cu.countries_name,s.state_name,ci.city_name FROM user AS u LEFT OUTER JOIN candidate_info AS cinfo ON cinfo.user_id=u.user_id LEFT OUTER JOIN lov_industries AS ind ON ind.ind_id=cinfo.ind_id  LEFT OUTER JOIN countries AS cu ON cu.countries_id=u.countries_id LEFT OUTER JOIN states AS s ON s.state_id=u.state_id  LEFT OUTER JOIN cities AS ci ON ci.city_id=u.city_id  ".$datafilter." ";
                
            $rs = mysqli_query($GLOBALS['conn'], "SELECT u.*,cinfo.*,ind.*,cu.countries_name,s.state_name,ci.city_name FROM user AS u LEFT OUTER JOIN candidate_info AS cinfo ON cinfo.user_id=u.user_id LEFT OUTER JOIN lov_industries AS ind ON ind.ind_id=cinfo.ind_id  LEFT OUTER JOIN countries AS cu ON cu.countries_id=u.countries_id LEFT OUTER JOIN states AS s ON s.state_id=u.state_id  LEFT OUTER JOIN cities AS ci ON ci.city_id=u.city_id  ".$datafilter." ") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
            if (mysqli_num_rows($rs) > 0) {
             
                while ($row = mysqli_fetch_object($rs)) {
                    $retValue[] = $row;
                }
            } else {
                $retValue = array();
            }
     
        return $retValue;
    }
    
	function getUserAcademics($params) {                
		if(isset($params['userID'])){
			$userID = explode("_", $params['userID']);
			$datafilter="WHERE ua.user_id='".$userID[0]."'";
		}  else {
			 $datafilter="WHERE ua.user_id='".$_SESSION['uid']."'";
		}
		
		$rs = mysqli_query($GLOBALS['conn'], "SELECT ua.*,DATE_FORMAT(ua.acinfo_completed_on,'%b %Y') As completedon,d.deg_name,cu.countries_name,s.state_name,ci.city_name FROM user_academic_info AS ua LEFT OUTER JOIN lov_degree AS d ON d.deg_id=ua.deg_id LEFT OUTER JOIN countries AS cu ON cu.countries_id=ua.countries_id LEFT OUTER JOIN states AS s ON s.state_id=ua.state_id  LEFT OUTER JOIN cities AS ci ON ci.city_id=ua.city_id  ".$datafilter." ") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
		if (mysqli_num_rows($rs) > 0) {
			while ($row = mysqli_fetch_object($rs)) {
				$retValue[] = $row;
			}
		} else {
			$retValue = array();
		}
        return $retValue;
    }
	
	function getUserCertifications($params) {
		if(isset($params['userID'])){
			$userID = explode("_", $params['userID']);
			$datafilter="WHERE uc.user_id='".$userID[0]."'";
		}  else {
			 $datafilter="WHERE uc.user_id='".$_SESSION['uid']."'";
		}
		$rs = mysqli_query($GLOBALS['conn'], "SELECT uc.*,DATE_FORMAT(uc.ucer_completed_on,'%b %Y') As completedon,cu.countries_name FROM user_certifications AS uc LEFT OUTER JOIN countries AS cu ON cu.countries_id=uc.countries_id ".$datafilter." ") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
		if (mysqli_num_rows($rs) > 0) {
			while ($row = mysqli_fetch_object($rs)) {
				$retValue[] = $row;
			}
		} else {
			$retValue = array();
		}
        return $retValue;
    }
    
    function getUserReferences($params) {
        
                if(isset($params['userID'])){
					$userID = explode("_", $params['userID']);
					$datafilter="WHERE ur.user_id='".$userID[0]."'";
                }  else {
					$datafilter="WHERE ur.user_id='".$_SESSION['uid']."'";
                }
        
            $rs = mysqli_query($GLOBALS['conn'], "SELECT ur.*,rt.reftyp_title,cu.countries_name,s.state_name,ci.city_name FROM user_references AS ur LEFT OUTER JOIN lov_reference_types AS rt ON rt.reftyp_id=ur.reftyp_id LEFT OUTER JOIN countries AS cu ON cu.countries_id=ur.countries_id LEFT OUTER JOIN states AS s ON s.state_id=ur.state_id  LEFT OUTER JOIN cities AS ci ON ci.city_id=ur.city_id ".$datafilter."  ") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
            if (mysqli_num_rows($rs) > 0) {
             
                while ($row = mysqli_fetch_object($rs)) {
                    $retValue[] = $row;
                }
            } else {
                $retValue = array();
            }
     
        return $retValue;
    }
    
     function getUserExperiences($params) {
         
                if(isset($params['userID'])){
                    $userID = explode("_", $params['userID']);
					$datafilter="WHERE e.user_id='".$userID[0]."' ORDER BY  e.jexp_id DESC ";
                }  else {
                      $datafilter="WHERE e.user_id='".$_SESSION['uid']."' ORDER BY  e.jexp_id DESC ";
                }
         
            $rs = mysqli_query($GLOBALS['conn'], "SELECT e.*,DATE_FORMAT(e.jexp_enddate,'%b %Y') As jenddate, DATE_FORMAT(e.jexp_starteddate,'%b %Y') As jstartsdate,cu.countries_name,s.state_name,ci.city_name FROM user_job_experiance AS e LEFT OUTER JOIN countries AS cu ON cu.countries_id=e.countries_id LEFT OUTER JOIN states AS s ON s.state_id=e.state_id  LEFT OUTER JOIN cities AS ci ON ci.city_id=e.city_id  ".$datafilter." ") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
            if (mysqli_num_rows($rs) > 0) {
             
                while ($row = mysqli_fetch_object($rs)) {
                    $retValue[] = $row;
                }
            } else {
                $retValue = array();
            }
     
        return $retValue;
    }
    
    function getCategoryjob($params) {

            $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM lov_job_category ORDER BY ljcat_title") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
            if (mysqli_num_rows($rs) > 0) {
                
                while ($row = mysqli_fetch_object($rs)) {
                    $retValue[] = array('ljcat_id' => $row->ljcat_id, 'ljcat_title' => $row->ljcat_title);
                }
            } else {
                $retValue = array();
            }
     
        return $retValue;
    }
    
    function getlov_experiance($params) {

            $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM lov_experiance ORDER BY exp_id") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
            if (mysqli_num_rows($rs) > 0) {
               
                while ($row = mysqli_fetch_object($rs)) {
                    $retValue[] = array('exp_id' => $row->exp_id, 'exp_name' => $row->exp_name);
                }
            } else {
                $retValue = array();
            }
     
        return $retValue;
    }
     function getJobtypes($params) {

            $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM lov_job_type ORDER BY jtype_title") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
            if (mysqli_num_rows($rs) > 0) {
              
                while ($row = mysqli_fetch_object($rs)) {
                    $retValue[] = array('jtype_id' => $row->jtype_id, 'jtype_title' => $row->jtype_title);
                }
            } else {
                $retValue = array();
            }
     
        return $retValue;
    }
	
	function getInterviewDetails($jobID, $uID) {
		$rs = mysqli_query($GLOBALS['conn'], "SELECT i.*, v.venue_title FROM lov_interview AS i LEFT OUTER JOIN lov_venue AS v ON v.venue_id=i.venue_id WHERE i.ja_id=".$jobID." AND i.mem_id=".$uID) or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
		if (mysqli_num_rows($rs) > 0) {             
			while ($row = mysql_fetch_assoc($rs)) {
				$retValue[] =$row;
			}
		} else {
			$retValue= array();
		}
        return $retValue;
    }
    
    function getInterviewVenueDis($params) {
       
            $rs = mysqli_query($GLOBALS['conn'], "SELECT v.*,c.city_name FROM lov_venue AS v LEFT OUTER JOIN cities AS c ON c.city_id=v.city_id WHERE v.emp_id='".$_SESSION['uid']."' ORDER BY venue_id DESC") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
            if (mysqli_num_rows($rs) > 0) {             
                while ($row = mysql_fetch_assoc($rs)) {
                    $retValue[] =$row;
                }
            } else {
                $retValue= array();
            }
        return $retValue;
    }
    
      
    function getMyJobsDrop($params) {
             
        $rs = mysqli_query($GLOBALS['conn'], "SELECT j.job_position,j.job_id,j.emp_id FROM  jobs AS j  WHERE j.emp_id=".$_SESSION['uid']." AND j.delete_job=0 AND j.approval_id=1 ORDER BY j.job_id DESC") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
        if (mysqli_num_rows($rs) > 0) {
          
            while ($row = mysql_fetch_assoc($rs)) {
                $retValue[] = $row;
                //$retValue[] = array('job_id' => $row->job_id, 'job_position' => $row->job_position,'city_name'=>$row->city_name,'org_name'=>$row->org_name);
            }
        } else {
            $retValue = array();
        }
        //}

        return $retValue;
    }
	
	function getCVCounts($param) {
		if (isset($param['job_id'])) {
			$qry2 = $param['job_id'];
		}
		else{
			$qry2 = 'SELECT job_id FROM jobs WHERE emp_id='.$_SESSION['uid'];
		}
		$qry = 'SELECT
		(SELECT COUNT(*) FROM jobs_applied WHERE job_id IN ('.$qry2.') AND cv_status_id=0) AS inbox,
		(SELECT COUNT(*) FROM jobs_applied WHERE job_id IN ('.$qry2.') AND cv_status_id=1) AS junk,
		(SELECT COUNT(*) FROM jobs_applied WHERE job_id IN ('.$qry2.') AND cv_status_id=2) AS shortlisted,
		(SELECT COUNT(*) FROM jobs_applied WHERE job_id IN ('.$qry2.') AND cv_status_id=3) AS interview';
        $rs = mysqli_query($GLOBALS['conn'], $qry) or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
        return mysql_fetch_assoc($rs);
    }
    
    
    
        function getresumesReceived($param) {
         //$data = json_decode(file_get_contents("php://input"));
       // $job_id =($data->job_id);
//       print_r($param);
//          die();
        if (isset($param['job_id'])) {
           $job_id=$param['job_id'];
            $filtdata = "AND jp.job_id=" . $job_id . "";
        }  else {
            $filtdata='';
        }
      //$Query="SELECT jp.*,j.job_position,j.status_id,u.user_fname,u.user_lname,u.user_id,c.city_name,org.org_name,cinf.*,(SELECT COUNT(*) FROM  jobs_applied AS jj  WHERE jj.cv_status_id=0) AS cinbox, (SELECT GROUP_CONCAT(ua.degree_title) FROM user_academic_info AS ua WHERE ua.user_id=jp.mem_id) AS edulist  FROM jobs_applied AS jp LEFT OUTER JOIN jobs AS j ON j.job_id=jp.job_id LEFT OUTER JOIN organization AS org ON org.user_id=j.emp_id LEFT OUTER JOIN user As u ON u.user_id=jp.mem_id LEFT OUTER JOIN cities As c ON c.city_id=u.city_id   LEFt OUTER JOIN candidate_info AS cinf ON cinf.user_id=jp.mem_id WHERE j.emp_id=".$_SESSION['uid']."  AND jp.cv_status_id=0  ".$filtdata." ORDER BY jp.job_id DESC";
	  $Query="SELECT jp.*, lsr.sr_value AS expectedSalary,j.job_position,j.status_id,u.user_fname,u.user_lname,u.user_id, (SELECT GROUP_CONCAT(ct.city_name) FROM cities AS ct WHERE ct.city_id IN (SELECT cj.city_id FROM job_cities AS cj WHERE cj.job_id=j.job_id)) AS city_name,org.org_name,cinf.*,(SELECT COUNT(*) FROM  jobs_applied AS jj  WHERE jj.cv_status_id=0) AS cinbox, (SELECT GROUP_CONCAT(ua.degree_title) FROM user_academic_info AS ua WHERE ua.user_id=jp.mem_id) AS edulist  FROM jobs_applied AS jp LEFT OUTER JOIN jobs AS j ON j.job_id=jp.job_id LEFT OUTER JOIN organization AS org ON org.user_id=j.emp_id LEFT OUTER JOIN user As u ON u.user_id=jp.mem_id LEFT OUTER JOIN lov_salary_range AS lsr ON lsr.sr_id=jp.sr_id LEFT OUTER JOIN candidate_info AS cinf ON cinf.user_id=jp.mem_id WHERE j.emp_id=".$_SESSION['uid']."  AND jp.cv_status_id=0  ".$filtdata." ORDER BY jp.job_id DESC";
	  //print($Query);
	  //die();
            $rs = mysqli_query($GLOBALS['conn'], $Query) or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
        if (mysqli_num_rows($rs) > 0) {
            //$retValue = array('status' => 1, 'message' => 'Jobs List');
           
            while ($row = mysql_fetch_assoc($rs)) {
                $retValue[] = $row;
         
            }
        } else {
            $retValue = array();
        }
     

        return $retValue;
    }
    
    
    
    function getresumesReceivedWInterview($param) {
         //$data = json_decode(file_get_contents("php://input"));
       // $job_id =($data->job_id);
//       print_r($param);
//          die();
        if (isset($param['job_id'])) {
           $job_id=$param['job_id'];
            $filtdata = "AND jp.job_id=" . $job_id . " ";
        }  else {
            $filtdata='';
        }
       $Query="SELECT jp.*, lsr.sr_value AS expectedSalary,j.job_position,j.status_id,u.user_fname,u.user_lname,u.user_id, (SELECT GROUP_CONCAT(ct.city_name) FROM cities AS ct WHERE ct.city_id IN (SELECT cj.city_id FROM job_cities AS cj WHERE cj.job_id=j.job_id)) AS city_name,org.org_name,cinf.*, GROUP_CONCAT(ua.degree_title) AS edulist FROM jobs_applied AS jp LEFT OUTER JOIN jobs AS j ON j.job_id=jp.job_id LEFT OUTER JOIN organization AS org ON org.user_id=j.emp_id LEFT OUTER JOIN user As u ON u.user_id=jp.mem_id LEFt OUTER JOIN candidate_info AS cinf ON cinf.user_id=jp.mem_id LEFT OUTER JOIN lov_salary_range AS lsr ON lsr.sr_id=jp.sr_id LEFT OUTER JOIN user_academic_info AS ua ON ua.user_id=jp.mem_id WHERE j.emp_id=".$_SESSION['uid']."  AND cv_status_id=3  ".$filtdata." group BY jp.job_id DESC";
           //die();
            $rs = mysqli_query($GLOBALS['conn'], $Query) or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
        if (mysqli_num_rows($rs) > 0) {
            //$retValue = array('status' => 1, 'message' => 'Jobs List');
           
            while ($row = mysql_fetch_assoc($rs)) {
                $retValue[] = $row;
         
            }
        } else {
            $retValue = array();
        }
     

        return $retValue;
    }
    
    
     function getresumesReceivedWShortListed($param) {
         //$data = json_decode(file_get_contents("php://input"));
       // $job_id =($data->job_id);
//       print_r($param);
//          die();
        if (isset($param['job_id'])) {
           $job_id=$param['job_id'];
            $filtdata = "AND jp.job_id=" . $job_id . " ";
        }  else {
            $filtdata='';
        }
       $Query="SELECT jp.*, lsr.sr_value AS expectedSalary,j.job_position,j.status_id,u.user_fname,u.user_lname,u.user_id, (SELECT GROUP_CONCAT(ct.city_name) FROM cities AS ct WHERE ct.city_id IN (SELECT cj.city_id FROM job_cities AS cj WHERE cj.job_id=j.job_id)) AS city_name,org.org_name,cinf.*, GROUP_CONCAT(ua.degree_title) AS edulist FROM jobs_applied AS jp LEFT OUTER JOIN jobs AS j ON j.job_id=jp.job_id LEFT OUTER JOIN organization AS org ON org.user_id=j.emp_id LEFT OUTER JOIN user As u ON u.user_id=jp.mem_id LEFt OUTER JOIN candidate_info AS cinf ON cinf.user_id=jp.mem_id LEFT OUTER JOIN lov_salary_range AS lsr ON lsr.sr_id=jp.sr_id LEFT OUTER JOIN user_academic_info AS ua ON ua.user_id=jp.mem_id WHERE j.emp_id=".$_SESSION['uid']."  AND cv_status_id=2  ".$filtdata." group BY jp.job_id DESC";
           //die();
            $rs = mysqli_query($GLOBALS['conn'], $Query) or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
        if (mysqli_num_rows($rs) > 0) {
            //$retValue = array('status' => 1, 'message' => 'Jobs List');
           
            while ($row = mysql_fetch_assoc($rs)) {
                $retValue[] = $row;
         
            }
        } else {
            $retValue = array();
        }
     

        return $retValue;
    }
    
    
     function getresumesReceivedWjunk($param) {
         //$data = json_decode(file_get_contents("php://input"));
       // $job_id =($data->job_id);
//       print_r($param);
//          die();
        if (isset($param['job_id'])) {
           $job_id=$param['job_id'];
            $filtdata = "AND jp.job_id=" . $job_id . " ";
        }  else {
            $filtdata='';
        }
       $Query="SELECT jp.*, lsr.sr_value AS expectedSalary,j.job_position,j.status_id,u.user_fname,u.user_lname,u.user_id, (SELECT GROUP_CONCAT(ct.city_name) FROM cities AS ct WHERE ct.city_id IN (SELECT cj.city_id FROM job_cities AS cj WHERE cj.job_id=j.job_id)) AS city_name,org.org_name,cinf.*, GROUP_CONCAT(ua.degree_title) AS edulist FROM jobs_applied AS jp LEFT OUTER JOIN jobs AS j ON j.job_id=jp.job_id LEFT OUTER JOIN organization AS org ON org.user_id=j.emp_id LEFT OUTER JOIN user As u ON u.user_id=jp.mem_id LEFT OUTER JOIN lov_salary_range AS lsr ON lsr.sr_id=jp.sr_id LEFt OUTER JOIN candidate_info AS cinf ON cinf.user_id=jp.mem_id LEFT OUTER JOIN user_academic_info AS ua ON ua.user_id=jp.mem_id WHERE j.emp_id=".$_SESSION['uid']."  AND cv_status_id=1  ".$filtdata." group BY jp.job_id DESC";
           //die();
            $rs = mysqli_query($GLOBALS['conn'], $Query) or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
        if (mysqli_num_rows($rs) > 0) {
            //$retValue = array('status' => 1, 'message' => 'Jobs List');
           
            while ($row = mysql_fetch_assoc($rs)) {
                $retValue[] = $row;
         
            }
        } else {
            $retValue = array();
        }
     

        return $retValue;
    }
    
    
    
    
    
     function getJobsCategoryDrop($params) {
             
        $rs = mysqli_query($GLOBALS['conn'], "SELECT j.cat_name,j.cat_id  FROM  lov_categories AS j  ORDER BY j.cat_id DESC") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
        if (mysqli_num_rows($rs) > 0) {
          
            while ($row = mysql_fetch_assoc($rs)) {
                $retValue[] = $row;
                //$retValue[] = array('job_id' => $row->job_id, 'job_position' => $row->job_position,'city_name'=>$row->city_name,'org_name'=>$row->org_name);
            }
        } else {
            $retValue = array();
        }
        //}

        return $retValue;
    }
    
     function getJobsCategoryDropW($params) {
             
        $rs = mysqli_query($GLOBALS['conn'], "SELECT c.cat_name,c.cat_id  FROM  lov_categories  AS c LEFT OUTER JOIN jobs AS j ON j.cat_id=c.cat_id WHERE j.emp_id='".$_SESSION['uid']."' Group BY c.cat_id  ASC") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
        if (mysqli_num_rows($rs) > 0) {
          
            while ($row = mysql_fetch_assoc($rs)) {
                $retValue[] = $row;
                //$retValue[] = array('job_id' => $row->job_id, 'job_position' => $row->job_position,'city_name'=>$row->city_name,'org_name'=>$row->org_name);
            }
        } else {
            $retValue = array();
        }
        //}

        return $retValue;
    }
    
    function getreportbycategory($param) {
         //$data = json_decode(file_get_contents("php://input"));
       // $job_id =($data->job_id);
//       print_r($param);
//          die();
        if (isset($param['cat_id'])) {
           $job_id=$param['cat_id'];
            $filtdata = "AND j.cat_id=" . $job_id . "";
        }  else {
            $filtdata=' AND jp.ja_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURDATE()';
        }

            $Query="SELECT jp.*,j.job_position,j.status_id, j.cat_id, lc.cat_name,u.user_fname,u.user_lname,u.user_id,c.city_name,org.org_name,cinf.*, (SELECT GROUP_CONCAT(ui.degree_title) FROM user_academic_info AS ui WHERE ui.user_id=u.user_id) AS degree_title FROM jobs_applied AS jp LEFT OUTER JOIN jobs AS j ON j.job_id=jp.job_id LEFT OUTER JOIN organization AS org ON org.user_id=j.emp_id LEFT OUTER JOIN user As u ON u.user_id=jp.mem_id LEFT OUTER JOIN cities As c ON c.city_id=u.city_id   LEFt OUTER JOIN candidate_info AS cinf ON cinf.user_id=jp.mem_id LEFT OUTER JOIN lov_categories AS lc ON lc.cat_id=j.cat_id WHERE  j.emp_id=".$_SESSION['uid']." ".$filtdata." group BY jp.job_id DESC";
          
            $rs = mysqli_query($GLOBALS['conn'], $Query) or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
        if (mysqli_num_rows($rs) > 0) {
            //$retValue = array('status' => 1, 'message' => 'Jobs List');
           
            while ($row = mysql_fetch_assoc($rs)) {
                $retValue[] = $row;
         
            }
        } else {
            $retValue = array();
        }
     

        return $retValue;
    }
    
    
     function getJobsindustriesDrop($params) {
             
        $rs = mysqli_query($GLOBALS['conn'], "SELECT j.industry_name,j.ind_id  FROM  lov_industries AS j  ORDER BY j.industry_name ASC") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
        if (mysqli_num_rows($rs) > 0) {
          
            while ($row = mysql_fetch_assoc($rs)) {
                $retValue[] = $row;
                //$retValue[] = array('job_id' => $row->job_id, 'job_position' => $row->job_position,'city_name'=>$row->city_name,'org_name'=>$row->org_name);
            }
        } else {
            $retValue = array();
        }
        //}

        return $retValue;
    }
    
     function getJobsindustriesDropW($params) {
             
        $rs = mysqli_query($GLOBALS['conn'], "SELECT ind.industry_name,ind.ind_id  FROM  lov_industries AS ind LEFT OUTER JOIN jobs AS j ON  j.ind_id=ind.ind_id WHERE j.emp_id='".$_SESSION['uid']."' GROUP BY ind.ind_id ASC") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
        if (mysqli_num_rows($rs) > 0) {
          
            while ($row = mysql_fetch_assoc($rs)) {
                $retValue[] = $row;
                //$retValue[] = array('job_id' => $row->job_id, 'job_position' => $row->job_position,'city_name'=>$row->city_name,'org_name'=>$row->org_name);
            }
        } else {
            $retValue = array();
        }
        //}

        return $retValue;
    }
    
    function getreportbyindustries($param) {
         //$data = json_decode(file_get_contents("php://input"));
       // $job_id =($data->job_id);
//       print_r($param);
//          die();
        if (isset($param['ind_id'])) {
           $job_id=$param['ind_id'];
            $filtdata = "AND j.ind_id=" . $job_id . "";
        }  else {
            $filtdata='';
        }

            //$Query="SELECT jp.*,j.job_position,j.status_id, j.ind_id, lc.cat_name,u.user_fname,u.user_lname,u.user_id,c.city_name,org.org_name,cinf.* FROM jobs_applied AS jp LEFT OUTER JOIN jobs AS j ON j.job_id=jp.job_id LEFT OUTER JOIN organization AS org ON org.user_id=j.emp_id LEFT OUTER JOIN user As u ON u.user_id=jp.mem_id LEFT OUTER JOIN cities As c ON c.city_id=u.city_id   LEFt OUTER JOIN candidate_info AS cinf ON cinf.user_id=jp.mem_id WHERE jp.ja_date BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURDATE() AND  j.emp_id=".$_SESSION['uid']." ".$filtdata." ORDER BY jp.job_id DESC";
          	$Query="SELECT jp.*,j.job_position,j.status_id, j.ind_id, lv.industry_name,u.user_fname,u.user_lname,u.user_id,c.city_name,org.org_name,cinf.*, (SELECT GROUP_CONCAT(ui.degree_title) FROM user_academic_info AS ui WHERE ui.user_id=u.user_id) AS degree_title FROM jobs_applied AS jp LEFT OUTER JOIN jobs AS j ON j.job_id=jp.job_id LEFT OUTER JOIN organization AS org ON org.user_id=j.emp_id LEFT OUTER JOIN user As u ON u.user_id=jp.mem_id LEFT OUTER JOIN cities As c ON c.city_id=u.city_id LEFt OUTER JOIN candidate_info AS cinf ON cinf.user_id=jp.mem_id LEFT OUTER JOIN lov_industries AS lv ON lv.ind_id=j.ind_id WHERE j.emp_id=".$_SESSION['uid']." ".$filtdata." group BY jp.job_id DESC";
			
            $rs = mysqli_query($GLOBALS['conn'], $Query) or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
        if (mysqli_num_rows($rs) > 0) {
            //$retValue = array('status' => 1, 'message' => 'Jobs List');
           
            while ($row = mysql_fetch_assoc($rs)) {
                $retValue[] = $row;
         
            }
        } else {
            $retValue = array();
        }
     

        return $retValue;
    }
    
    
    function getCountries($params){
	$rs2 = mysqli_query($GLOBALS['conn'], "SELECT countries_id, countries_name FROM countries");
	if(mysqli_num_rows($rs2)>0){
		while($row2=mysqli_fetch_object($rs2)){
			$retValue[] = array('country_id'=>$row2->countries_id, 'country_name'=>$row2->countries_name); 
		}
	}
	return $retValue;
}

function getCountriesW($params){
	$rs2 = mysqli_query($GLOBALS['conn'], "SELECT countries_id, countries_name FROM countries WHERE countries_id=162");
	if(mysqli_num_rows($rs2)>0){
		while($row2=mysqli_fetch_object($rs2)){
			$retValue[] = array('country_id'=>$row2->countries_id, 'country_name'=>$row2->countries_name); 
		}
	}
	return $retValue;
}

function getStates($params) {

            $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM states ORDER BY state_id") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
            if (mysqli_num_rows($rs) > 0) {
              
                while ($row = mysqli_fetch_object($rs)) {
                    $retValue[] = array('state_id' => $row->state_id, 'state_name' => $row->state_name);
                }
            } else {
                $retValue = array();
            }
     
        return $retValue;
    }
    function getSalaryrange($params){
	$rs2 = mysqli_query($GLOBALS['conn'], "SELECT sr_id, sr_value FROM lov_salary_range");
	if(mysqli_num_rows($rs2)>0){
		while($row2=mysqli_fetch_object($rs2)){
			$retValue[] = array('sr_id'=>$row2->sr_id, 'salary_range'=>$row2->sr_value); 
		}
	}
	return $retValue;
}
    function getCities($params) {
		if(isset($params['job_id'])){
			$rs = mysqli_query($GLOBALS['conn'], "SELECT c.*, jc.city_id AS selectedID FROM cities AS c LEFT OUTER JOIN job_cities AS jc ON jc.city_id=c.city_id AND jc.job_id=".$params['job_id']." ORDER BY c.city_id") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
			if (mysqli_num_rows($rs) > 0) {
				while ($row = mysqli_fetch_object($rs)) {
					$retValue[] = array('city_id' => $row->city_id, 'city_name' => $row->city_name, 'selectedID' => $row->selectedID);
				}
			} else {
				$retValue = array();
			}
		}
		else{
            $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM cities ORDER BY city_id") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
			if (mysqli_num_rows($rs) > 0) {
				while ($row = mysqli_fetch_object($rs)) {
					$retValue[] = array('city_id' => $row->city_id, 'city_name' => $row->city_name);
				}
			} else {
				$retValue = array();
			}
		}
        return $retValue;
    }
	
	function getCitiesSel($job_id) {
		$rs = mysqli_query($GLOBALS['conn'], "SELECT c.*, jc.city_id AS selectedID FROM cities AS c LEFT OUTER JOIN job_cities AS jc ON jc.city_id=c.city_id AND jc.job_id=".$job_id." ORDER BY c.city_id") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
		if (mysqli_num_rows($rs) > 0) {
			while ($row = mysqli_fetch_object($rs)) {
				$retValue[] = array('city_id' => $row->city_id, 'city_name' => $row->city_name);
			}
		} else {
			$retValue = array();
		}
		return $retValue;
    }
    
    function getBusinesstypes($params) {

            $rs = mysqli_query($GLOBALS['conn'], "SELECT * FROM lov_business_types ORDER BY btype_id") or die(array('status' => 0, 'message' => mysqli_error($GLOBALS['conn'])));
            if (mysqli_num_rows($rs) > 0) {
              
                while ($row = mysqli_fetch_object($rs)) {
                    $retValue[] = array('btype_id' => $row->btype_id, 'btype_title' => $row->btype_title);
                }
            } else {
                $retValue = array();
            }
     
        return $retValue;
    }
    
function getSites($mem_id){
	$rs1 = mysqli_query($GLOBALS['conn'], "SELECT site_id, site_title FROM mem_sites WHERE mem_id=".$mem_id);
	if(mysqli_num_rows($rs1)>0){
		while($row1=mysqli_fetch_object($rs1)){
			$retValue[] = array('site_id'=>$row1->site_id, 'site_name'=>$row1->site_title); 
		}
	}
	else{
		$retValue = array();
	}
	return $retValue;
}




?>