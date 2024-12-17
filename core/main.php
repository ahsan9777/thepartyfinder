<?php

class Main
{
    private $func;
    private $mailer;

    public function __construct(Functions $functions, Mailer $mailer)
    {
        $this->func = $functions;
        $this->mailer = $mailer;
    }

    public function get_category_bk($params)
    {
        $retValue = array();

        $Query = "SELECT tt.*, t.name, t.slug FROM wp_term_taxonomy AS tt LEFT OUTER JOIN wp_terms AS t ON t.term_id = tt.term_id WHERE tt.taxonomy = 'event_category' ";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $retValue = array("status" => "1", "message" => "Get category data");
            while ($rw = mysqli_fetch_object($rs)) {
                $retValue['data'][] = array(
                    "cat_id" => strval($rw->term_id),
                    "cat_title" => strval($rw->name),
                    "cat_slug" => strval($rw->slug)
                );
            }
        } else {
            $retValue = array("status" => "0", "message" => "Record not found!");
        }
        return $retValue;
    }


    public function get_nav_event($params)
    {
        $retValue = array();
        $retValue = array("status" => "1", "message" => "Get event data");
        $event = array("Nightclub", "Beach Club", "Lounge", "Restaurant", "Brunch", "Ladies Night", "After Party", "Special");
        foreach ($event as $value) {
            $retValue['data'][] = array(
                'event_index' => str_replace(" ", "-", strtolower($value)),
                'event_title' => $value
            );
        }

        return $retValue;
    }

    public function get_nav_event_weekly($params)
    {
        $retValue = array();
        $retValue = array("status" => "1", "message" => "Get event data");
        $day = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
        foreach ($day as  $value) {
            $retValue['data'][] = array(
                'slug' => substr($value, 0, 3),
                'day' => $value
            );
        }

        return $retValue;
    }

    public function get_post($params)
    {
        $retValue = array();
        $order_by = "";
        if (isset($params['post_type']) && $params['post_type'] == 'event') {
            //$qryWhere = " AND p.post_parent = '14' AND p.ID IN (SELECT pm.post_id FROM wp_postmeta AS pm WHERE pm.meta_key = 'add_home_events_more' AND meta_value != '' AND pm.post_id = p.ID)";
            $qryWhere = " AND p.post_parent = '14' AND p.ID IN (SELECT pm.post_id FROM wp_postmeta AS pm WHERE pm.meta_key = 'archive_events_more' AND meta_value = '' AND pm.post_id = p.ID)";
            $order_by = "ORDER BY p.ID DESC";
        }

        if (isset($params['post_id']) && $params['post_id'] > 0) {
            $qryWhere .= " AND p.ID = '" . $params['post_id'] . "'";
        }

        //$Query = "SELECT p.ID, p.post_modified, p.post_author, p.post_title, p.guid,  (SELECT vp.guid FROM wp_posts AS vp WHERE vp.post_type = 'attachment' AND vp.post_mime_type LIKE 'image/%' AND vp.ID = (SELECT meta_value FROM `wp_postmeta` WHERE post_id = p.ID AND meta_key = 'thumbnail_events_more')) AS thumbnail_image_url, (SELECT day_pm.meta_value FROM wp_postmeta AS day_pm WHERE day_pm.post_id = p.ID AND day_pm.meta_key = 'location_name_events_more') AS post_location, (SELECT day_pm.meta_value FROM wp_postmeta AS day_pm WHERE day_pm.post_id = p.ID AND day_pm.meta_key = 'day_events_more') AS post_day, (SELECT day_pm.meta_value FROM wp_postmeta AS day_pm WHERE day_pm.post_id = p.ID AND day_pm.meta_key = 'time_events_more') AS post_time, (SELECT day_pm.meta_value FROM wp_postmeta AS day_pm WHERE day_pm.post_id = p.ID AND day_pm.meta_key = 'capacity_events_more') AS post_capacity, (SELECT day_pm.meta_value FROM wp_postmeta AS day_pm WHERE day_pm.post_id = p.ID AND day_pm.meta_key = 'category_events_more') AS post_category FROM wp_posts AS p  WHERE p.post_status='publish' AND p.post_type = 'page' " . $qryWhere . " ".$order_by." ";
        $Query = "SELECT p.ID, p.post_modified, p.post_author, p.post_title, p.guid,  (SELECT vp.guid FROM wp_posts AS vp WHERE vp.post_type = 'attachment' AND vp.post_mime_type LIKE 'image/%' AND vp.ID = (SELECT meta_value FROM `wp_postmeta` WHERE post_id = p.ID AND meta_key = 'thumbnail_events_more')) AS thumbnail_image_url, (SELECT day_pm.meta_value FROM wp_postmeta AS day_pm WHERE day_pm.post_id = p.ID AND day_pm.meta_key = 'location_events_more') AS post_location, (SELECT day_pm.meta_value FROM wp_postmeta AS day_pm WHERE day_pm.post_id = p.ID AND day_pm.meta_key = 'day_events_more') AS post_day, (SELECT day_pm.meta_value FROM wp_postmeta AS day_pm WHERE day_pm.post_id = p.ID AND day_pm.meta_key = 'time_events_more') AS post_time, (SELECT day_pm.meta_value FROM wp_postmeta AS day_pm WHERE day_pm.post_id = p.ID AND day_pm.meta_key = 'instagram_events_more') AS post_instagram, (SELECT day_pm.meta_value FROM wp_postmeta AS day_pm WHERE day_pm.post_id = p.ID AND day_pm.meta_key = 'music_events_more') AS post_music, (SELECT day_pm.meta_value FROM wp_postmeta AS day_pm WHERE day_pm.post_id = p.ID AND day_pm.meta_key = 'category_events_more') AS post_category, (SELECT day_pm.meta_value FROM wp_postmeta AS day_pm WHERE day_pm.post_id = p.ID AND day_pm.meta_key = 'story_video_events_more') AS story_link FROM wp_posts AS p  WHERE p.post_status='publish' AND p.post_type = 'page' " . $qryWhere . " " . $order_by . " ";
        //print($Query);die();
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $retValue = array("status" => "1", "message" => "Get post data");
            while ($rw = mysqli_fetch_object($rs)) {
                $arr['post_id'] = $rw->ID;
                $arr['post_category'] = $rw->post_category;
                if (isset($params['event_index'])) {

                    $post_category_array = unserialize($rw->post_category);
                    //print_r($post_category_array);die();
                    foreach ($post_category_array as $val) {
                        if (ucwords(str_replace("-", " ", $params['event_index'])) == $val) {
                            $retValue['data'][] = array(
                                "post_id" => $rw->ID,
                                "post_modified" => strval($rw->post_modified),
                                "post_author" => strval($rw->post_author),
                                //"post_content" => strval($rw->post_content),
                                "post_title" => strval($rw->post_title),
                                "guid" => strval($rw->guid),
                                "post_location" => strval($rw->post_location),
                                "post_day_time" => strval($rw->post_day . " " . $rw->post_time),
                                "post_music" => strval($rw->post_music),
                                "post_instagram" => strval("@" . $rw->post_instagram),
                                "post_capacity" => "",
                                "story_link" => strval($rw->story_link),
                                "thumbnail_image_url" => strval($rw->thumbnail_image_url),
                                //"post_category" => unserialize($rw->post_category),
                                //"post_category" => $this->post_category($arr),
                                "essentials_events_detail" => $this->essentials_events_detail($arr)
                            );
                        }
                    }
                } else {
                    $arr['post_category'] = $rw->post_category;
                    $retValue['data'][] = array(
                        "post_id" => $rw->ID,
                        "post_modified" => strval($rw->post_modified),
                        "post_author" => strval($rw->post_author),
                        //"post_content" => strval($rw->post_content),
                        "post_title" => strval($rw->post_title),
                        "guid" => strval($rw->guid),
                        "post_location" => strval($rw->post_location),
                        "post_day_time" => strval($rw->post_day . " " . $rw->post_time),
                        "post_music" => strval($rw->post_music),
                        "post_instagram" => strval("@" . $rw->post_instagram),
                        "post_capacity" => "",
                        "story_link" => strval($rw->story_link),
                        "thumbnail_image_url" => strval($rw->thumbnail_image_url),
                        //"post_category" => unserialize($rw->post_category),
                        //"post_category" => $this->post_category($arr),
                        "essentials_events_detail" => $this->essentials_events_detail($arr)
                    );
                }
            }
        } else {
            $retValue = array("status" => "0", "message" => "Record not found!");
        }
        return $retValue;
    }

    public function get_weekly_post($params)
    {
        if (isset($params['day']) && ($params['day'])) {
            $day = strtolower($params['day']);
        } else {
            $day = "sunday";
        }
        $tempArray = array();
        $retValue = array();
        //$Query1 = "SELECT pm.meta_value FROM wp_postmeta AS pm WHERE pm.post_id = '15' AND pm.meta_key LIKE '%_url_".$day."_weekly_event' AND pm.meta_key NOT LIKE '_".$day."_weekly_events%'";
        $Query1 = "SELECT pm.*, p.guid FROM wp_postmeta AS pm LEFT OUTER JOIN wp_posts AS p ON p.ID = pm.meta_value AND pm.meta_key LIKE '%_image_" . $day . "_weekly_event' WHERE pm.post_id = '15' AND pm.meta_key LIKE '" . $day . "_weekly_events_%' ORDER BY pm.meta_key ASC;";
        //print($Query1);
        $rs1 = mysqli_query($GLOBALS['conn'], $Query1);
        if (mysqli_num_rows($rs1) > 0) {
            $retValue = array("status" => "1", "message" => "Get weekly post data");
            while ($rw = mysqli_fetch_object($rs1)) {
                $metaKey = $rw->meta_key;
                if (strpos($metaKey, '_url_' . $day . '_weekly_event') !== false) {
                    $tempArray['post_id'] = $rw->meta_value;
                } elseif (strpos($metaKey, '_name_' . $day . '_weekly_event') !== false) {
                    $tempArray['post_title'] = $rw->meta_value;
                } elseif (strpos($metaKey, '_venue_' . $day . '_weekly_event') !== false) {
                    $tempArray['post_location'] = $rw->meta_value;
                } elseif (strpos($metaKey, '_day_' . $day . '_weekly_event') !== false) {
                    $tempArray['post_day'] = $rw->meta_value;
                } elseif (strpos($metaKey, '_time_' . $day . '_weekly_event') !== false) {
                    $tempArray['post_time'] = $rw->meta_value;
                } elseif (strpos($metaKey, '_capacity_' . $day . '_weekly_event') !== false) {
                    $tempArray['post_capacity'] = $rw->meta_value;
                } elseif (strpos($metaKey, '_image_' . $day . '_weekly_event') !== false) {
                    $tempArray['thumbnail_image_url'] = $rw->guid;
                }
                //(SELECT day_pm.meta_value FROM wp_postmeta AS day_pm WHERE day_pm.post_id = p.ID AND day_pm.meta_key = 'music_events_more') AS post_music
                if (isset($tempArray['post_id']) && isset($tempArray['post_title']) && isset($tempArray['post_location']) && isset($tempArray['post_day']) && isset($tempArray['post_time']) && isset($tempArray['post_capacity']) && isset($tempArray['thumbnail_image_url'])) {
                    $retValue['data'][] = array(
                        'post_id' => $tempArray['post_id'],
                        'post_modified' => "0000-00-00 00:00:00",
                        'post_author' => "0",
                        'post_title' => $tempArray['post_title'],
                        'guid' => "",
                        'post_location' => $tempArray['post_location'],
                        'post_day_time' => $tempArray['post_day'] . " " . $tempArray['post_time'],
                        'post_capacity' => $tempArray['post_capacity'] . " Capacity",
                        'post_music' => $this->func->returnName("meta_value", "wp_postmeta", "post_id", $tempArray['post_id'], "AND meta_key = 'music_events_more'"),
                        'post_instagram' => "@" . $this->func->returnName("meta_value", "wp_postmeta", "post_id", $tempArray['post_id'], "AND meta_key = 'instagram_events_more'"),
                        'story_link' => $this->func->returnName("meta_value", "wp_postmeta", "post_id", $tempArray['post_id'], "AND meta_key = 'story_video_events_more'"),
                        'thumbnail_image_url' => $tempArray['thumbnail_image_url'],
                        "essentials_events_detail" => $this->essentials_events_detail($tempArray)
                    );
                    $tempArray = array();
                }
            }
        } else {
            $retValue = array("status" => "0", "message" => "Record not found!");
        }
        return $retValue;
    }


    public function post_category($params)
    {
        $retValue = array();
        $unserialized_array = unserialize($params['post_category']);
        foreach ($unserialized_array as $value) {
            $retValue[] = array(
                'value' => $value
            );
        }
        return $retValue;
    }
    public function essentials_events_detail($params)
    {
        $tempArray = array();
        $retValue = array();
        $Query = "SELECT * FROM wp_postmeta AS vpm WHERE vpm.post_id = '" . $params['post_id'] . "' AND vpm.meta_key LIKE 'essentials_events_more_%'";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            /*$rw = mysqli_fetch_object($rs);
            print("<pre>");
            print_r($rw);
            print("</pre>");*/
            while ($rw = mysqli_fetch_object($rs)) {
                $tempArray[] = array(
                    'meta_key' => $rw->meta_key,
                    'meta_value' => $rw->meta_value
                );
            }

            for ($i = 0; $i < count($tempArray); $i += 2) {
                $title = isset($tempArray[$i]['meta_value']) ? $tempArray[$i]['meta_value'] : '';
                $description = isset($tempArray[$i + 1]['meta_value']) ? $tempArray[$i + 1]['meta_value'] : '';

                $retValue[] = array(
                    'title' => $title,
                    'description' => $description
                );
            }
        }
        return $retValue;
    }
    public function get_story($params)
    {
        $tempArray = array();
        $retValue = array();
        //$Query = "SELECT pm.*, p.guid FROM wp_postmeta AS pm LEFT OUTER JOIN wp_posts AS p ON p.ID = pm.meta_value AND pm.meta_key LIKE '%_image_storyfp' WHERE pm.post_id = '9' AND pm.meta_key LIKE 'storyfp_%' AND pm.meta_key NOT LIKE '%video_iframe_storyfp' AND pm.meta_value != ''";
        $Query = "SELECT pm.*, p.guid FROM wp_postmeta AS pm LEFT OUTER JOIN wp_posts AS p ON p.ID = pm.meta_value AND pm.meta_key LIKE '%_image_storyfp' WHERE pm.post_id = '9' AND pm.meta_key LIKE 'storyfp_%' AND pm.meta_key NOT LIKE '%_url_storyfp' AND pm.meta_value != '' ORDER BY pm.meta_key";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $retValue = array("status" => "1", "message" => "Get story data");
            while ($rw = mysqli_fetch_object($rs)) {
                $metaKey = $rw->meta_key;

                if (strpos($metaKey, '_image_storyfp') !== false) {
                    $tempArray['image'] = $rw->guid;
                } elseif (strpos($metaKey, '_name_storyfp') !== false) {
                    $tempArray['title'] = $rw->meta_value;
                } elseif (strpos($metaKey, '_video_iframe_storyfp') !== false) {
                    //$tempArray['url'] = $rw->meta_value; _url_storyfp
                    if (preg_match('/src="([^"]+)"/', $rw->meta_value, $matches)) {
                        $tempArray['url'] = $matches[1]; // Extract the src URL
                    }
                }

                if (isset($tempArray['image']) && isset($tempArray['title']) && isset($tempArray['url'])) {
                    $retValue['data'][] = array(
                        'story_image_url' => $tempArray['image'],
                        'story_title' => $tempArray['title'],
                        'story_video_url' => $tempArray['url']
                    );
                    $tempArray = array();
                }
            }
        }
        return $retValue;
    }


    public function get_venue($params)
    {
        $tempArray = array();
        $retValue = array();
        $Query = "SELECT pm.*, p.guid FROM wp_postmeta AS pm LEFT OUTER JOIN wp_posts AS p ON p.ID = pm.meta_value AND pm.meta_key LIKE '%_image_venue_item' WHERE pm.post_id = '12' AND pm.meta_key LIKE 'venues_items_%'  ORDER BY CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(pm.meta_key, 'venues_items_', -1), '_', 1) AS UNSIGNED), pm.meta_key ASC";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $retValue = array("status" => "1", "message" => "Get vanue data");
            while ($rw = mysqli_fetch_object($rs)) {
                $metaKey = $rw->meta_key;
                //print_r($metaKey);die();
                if (strpos($metaKey, '_archive_venue_item') !== false) {
                    $archive_venue_item = $rw->meta_value;
                }
                //print($action_venue_item);die();
                if(empty($archive_venue_item) ){
                    if (strpos($metaKey, '_url_venue_item') !== false) {
                        $tempArray['id'] = $rw->meta_value;
                    } elseif (strpos($metaKey, '_name_venue_item') !== false) {
                        $tempArray['title'] = $rw->meta_value;
                    } elseif (strpos($metaKey, '_location_venue_item') !== false) {
                        $tempArray['location'] = $rw->meta_value;
                    } elseif (strpos($metaKey, '_category_venue_item') !== false) {
                        $tempArray['category'] = $rw->meta_value;
                    } elseif (strpos($metaKey, '_image_venue_item') !== false) {
                        $tempArray['image'] = $rw->guid;
                    }
                }

                if (isset($tempArray['id']) && isset($tempArray['image']) && isset($tempArray['title']) && isset($tempArray['location']) && isset($tempArray['category'])) {
                    $retValue['data'][] = array(
                        'vanue_id' => $tempArray['id'],
                        'vanue_image' => $tempArray['image'],
                        'vanue_title' => $tempArray['title'],
                        'vanue_location' => $tempArray['location'],
                        'vanue_category' => $tempArray['category']
                        //"essentials_vanue_detail" => $this->essentials_events_detail($tempArray)
                    );
                    $tempArray = array();
                }
            }
        }
        return $retValue;
    }

    public function get_top_venue($params)
    {
        $tempArray = array();
        $retValue = array();
        $Query = "SELECT pm.*, p.guid FROM wp_postmeta AS pm LEFT OUTER JOIN wp_posts AS p ON p.ID = pm.meta_value AND pm.meta_key LIKE '%_img_top_venue_list' WHERE pm.post_id = '5821' AND pm.meta_key LIKE 'top_venue_list_%'";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $retValue = array("status" => "1", "message" => "Get top vanue data");
            while ($rw = mysqli_fetch_object($rs)) {
                $metaKey = $rw->meta_key;

                if (strpos($metaKey, '_name_top_venue_list') !== false) {
                    $tempArray['title'] = $rw->meta_value;
                } elseif (strpos($metaKey, '_link_top_venue_list') !== false) {
                    $tempArray['read_more'] = $rw->meta_value;
                } elseif (strpos($metaKey, '_description_top_venue_list') !== false) {
                    $tempArray['description'] = $rw->meta_value;
                } elseif (strpos($metaKey, '_img_top_venue_list') !== false) {
                    $tempArray['image'] = $rw->guid;
                }

                if (isset($tempArray['image']) && isset($tempArray['title']) && isset($tempArray['read_more']) && isset($tempArray['description'])) {
                    $retValue['data'][] = array(
                        'vanue_image' => $tempArray['image'],
                        'vanue_title' => $tempArray['title'],
                        'vanue_description' => $tempArray['description'],
                        'vanue_read_more_url' => $tempArray['read_more']
                    );
                    $tempArray = array();
                }
            }
        }
        return $retValue;
    }

    public function get_venue_title($params)
    {
        $tempArray = array();
        $retValue = array();
        $Query = "SELECT pm.*, p.guid FROM wp_postmeta AS pm LEFT OUTER JOIN wp_posts AS p ON p.ID = pm.meta_value AND pm.meta_key LIKE '%_img_top_venue_list' WHERE pm.post_id = '5821' AND pm.meta_key LIKE 'top_venue_list_%'";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $retValue = array("status" => "1", "message" => "Get top vanue title data");
            while ($rw = mysqli_fetch_object($rs)) {
                $metaKey = $rw->meta_key;

                if (strpos($metaKey, '_name_top_venue_list') !== false) {
                    $tempArray['title'] = $rw->meta_value;
                }

                if (isset($tempArray['title'])) {
                    $retValue['data'][] = array(
                        'vanue_title' => $tempArray['title']
                    );
                    $tempArray = array();
                }
            }
        }
        return $retValue;
    }

    public function get_ladies_night($params)
    {
        $tempArray = array();
        $retValue = array();
        $Query = "SELECT pm.*, p.guid FROM wp_postmeta AS pm LEFT OUTER JOIN wp_posts AS p ON p.ID = pm.meta_value AND pm.meta_key LIKE '%_image_ladies_offer_item' WHERE pm.post_id = '5472' AND pm.meta_key LIKE 'ladies_offer_items_%'";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $retValue = array("status" => "1", "message" => "Get ladies offer data");
            while ($rw = mysqli_fetch_object($rs)) {
                $metaKey = $rw->meta_key;

                if (strpos($metaKey, '_name_ladies_offer_item') !== false) {
                    $tempArray['title'] = $rw->meta_value;
                } elseif (strpos($metaKey, '_description_ladies_offer_item') !== false) {
                    $tempArray['description'] = $rw->meta_value;
                } elseif (strpos($metaKey, '_image_ladies_offer_item') !== false) {
                    $tempArray['image'] = $rw->guid;
                }

                if (isset($tempArray['image']) && isset($tempArray['title']) && isset($tempArray['description'])) {
                    $retValue['data'][] = array(
                        'ladies_offer_image' => $tempArray['image'],
                        'ladies_offer_title' => $tempArray['title'],
                        'ladies_offer_description' => $tempArray['description']
                    );
                    $tempArray = array();
                }
            }
        }
        return $retValue;
    }

    public function get_video($params)
    {
        $retValue = array();
        /*$video_list = $this->func->returnName("meta_value", "wp_postmeta", "post_id", 7881, " AND meta_key = 'video_list'");
        if($video_list > 0){
            $video_list = $video_list - 1;
        } else{
            $video_list = 0;
        }*/
        //$Query = "SELECT pm.meta_value, p.ID, p.guid FROM wp_postmeta AS pm LEFT OUTER JOIN wp_posts AS p ON p.ID = pm.meta_value WHERE pm.post_id = '7881' AND pm.meta_key = 'video_list_".$video_list."_add_video_list' ";
        $Query = "SELECT p.ID, p.guid FROM wp_posts AS p WHERE p.ID = '7904' ";
        //print($Query);die();
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $retValue = array("status" => "1", "message" => "Get video data");
            while ($rw = mysqli_fetch_object($rs)) {
                $retValue['data'][] = array(
                    "video_url" => strval($rw->guid)
                );
            }
        } else {
            $retValue = array("status" => "0", "message" => "Record not found!");
        }
        return $retValue;
    }

    public function get_pdf($params)
    {
        $retValue = array();
        $video_list = $this->func->returnName("meta_value", "wp_postmeta", "post_id", 7886, " AND meta_key = 'weekly_calendar'");
        if ($video_list > 0) {
            $video_list = $video_list - 1;
        } else {
            $video_list = 0;
        }
        $Query = "SELECT pm.meta_value, p.ID, p.guid FROM wp_postmeta AS pm LEFT OUTER JOIN wp_posts AS p ON p.ID = pm.meta_value WHERE pm.post_id = '7886' AND pm.meta_key = 'weekly_calendar_" . $video_list . "_pdf_weekly_calendar' ";
        //print($Query);die();
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $retValue = array("status" => "1", "message" => "Get pdf data");
            while ($rw = mysqli_fetch_object($rs)) {
                $retValue['data'][] = array(
                    "pdf_url" => strval($rw->guid)
                );
            }
        } else {
            $retValue = array("status" => "0", "message" => "Record not found!");
        }
        return $retValue;
    }


    public function form_submit($params)
    {
        $retValue = array();

        $this->mailer->form_submit($params['event_name'], $params['vanue_name'],  $params['full_name'],  $params['phone_no']);
        $retValue = array("status" => "1", "message" => "Record Added Successfully");

        return $retValue;
    }

    public function form_submit_bk($params)
    {
        $retValue = array();
        $response = array();

        $message = 0;
        $email_message = "";

        if (isset($params['form_id']) && !empty($params['form_id'])) {
            $form_id = $params['form_id'];

            if ($form_id == 3) {
                $response = array(
                    "__fluent_form_embded_post_id" => "2",
                    "_fluentform_3_fluentformnonce" => "6ba5a3b982",
                    "_wp_http_referer" => "/?fluent_forms_pages=1&design_mode=1&preview_id=3",
                    "cpt_selection" => isset($params['cpt_selection']) ? $params['cpt_selection'] : null,
                    "datetime" => isset($params['datetime']) ? $params['datetime'] : null,

                    "names" => array(
                        "first_name" => isset($params['first_name']) ? $params['first_name'] : null
                    ),

                    "email" => isset($params['email']) ? $params['email'] : null,
                    "phone" => isset($params['phone']) ? $params['phone'] : null,

                    "address_2" => array(
                        "city" => isset($params['city']) ? $params['city'] : null
                    ),

                    "dropdown" => isset($params['dropdown']) ? $params['dropdown'] : null,
                    "dropdown_1" => isset($params['dropdown_1']) ? $params['dropdown_1'] : null,
                    "description" => isset($params['description']) ? $params['description'] : null
                );

                $json_response = json_encode($response);
                $serial_number = $this->func->getMaximumwhere("wp_fluentform_submissions", "serial_number", "form_id = '" . $form_id . "'");
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_submissions (form_id, serial_number, response, source_url, device, created_at, updated_at) VALUES ('" . $form_id . "', '" . $serial_number . "', '" . $this->func->dbStr($json_response) . "', 'http://partyfindersdxb.com/?fluent_forms_pages=1&design_mode=1&preview_id=3', '" . $this->func->dbStr($params['platform']) . "', '" . date('Y-m-d H:i:s') . "', '" . date('Y-m-d H:i:s') . "')") or die(mysqli_error($GLOBALS['conn']));
                $submission_id = $this->func->getMaximum("wp_fluentform_submissions", "id");
                if ($submission_id > 1) {
                    $submission_id = $submission_id - 1;
                }
            }
            if (isset($params['cpt_selection']) && !empty($params['cpt_selection'])) {
                $field_value = " '" . $this->func->dbStr($params['cpt_selection']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'cpt_selection', '', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><strong>Select Event: </strong> " . $params['cpt_selection'];
            }
            if (isset($params['datetime']) && !empty($params['datetime'])) {
                $field_value = " '" . $this->func->dbStr($params['datetime']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'datetime', '', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><strong>Date: </strong> " . $params['datetime'];
            }
            if (isset($params['first_name']) && !empty($params['first_name'])) {
                $field_value = " '" . $this->func->dbStr($params['first_name']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'names', 'first_name', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><strong>First Name: </strong> " . $params['first_name'];
            }
            if (isset($params['last_name']) && !empty($params['last_name'])) {
                $field_value = " '" . $this->func->dbStr($params['last_name']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'names', 'last_name', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><strong>Last Name: </strong> " . $params['last_name'];
            }
            if (isset($params['email']) && !empty($params['email'])) {
                $field_value = " '" . $this->func->dbStr($params['email']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'email', '', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><strong>Email: </strong> " . $params['email'];
            }
            if (isset($params['phone']) && !empty($params['phone'])) {
                $field_value = " '" . $this->func->dbStr($params['phone']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'phone', '', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><strong>Contact No: </strong> " . $params['phone'];
            }
            if (isset($params['city']) && !empty($params['city'])) {
                $field_value = " '" . $this->func->dbStr($params['city']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'address_2', 'city', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><strong>City: </strong> " . $params['city'];
            }
            if (isset($params['dropdown']) && !empty($params['dropdown'])) {
                $field_value = " '" . $this->func->dbStr($params['dropdown']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'dropdown', '', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><strong>Gents: </strong> " . $params['dropdown'];
            }
            if (isset($params['dropdown_1']) && !empty($params['dropdown_1'])) {
                $field_value = " '" . $this->func->dbStr($params['dropdown_1']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'dropdown_1', '', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><strong>Ladies: </strong> " . $params['dropdown_1'];
            }
            if (isset($params['description']) && !empty($params['description'])) {
                $field_value = " '" . $this->func->dbStr($params['description']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'description', '', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><br><strong>Message: </strong><br> " . $params['description'];
            }

            if ($message == 1 && $submission_id > 0) {
                if (isset($params['email']) && !empty($params['email'])) {
                    $this->mailer->form_submit($params['email'], "Booking Inquiry", $params['first_name'], $email_message);
                }
                $retValue = array("status" => "1", "message" => "Record Added Successfully");
            } else {
                $retValue = array("status" => "0", "message" => "Please send the atleast one field data");
            }
        } else {
            $retValue = array("status" => "0", "message" => "Please set the form_id");
        }
        return $retValue;
    }

    public function email_test($params)
    {
        $retValue = array();
        //echo "email_test";die();
        $retValue = array("mail-test" => $this->mailer->test($params['to']));
        return $retValue;
    }

    public function get_title($params)
    {
        $retValue = array();
        $order_by = "";
        if (isset($params['title']) && $params['title'] == 'event') {
            $qryWhere = " AND p.post_parent = '14' AND p.ID IN (SELECT pm.post_id FROM wp_postmeta AS pm WHERE pm.meta_key = 'archive_events_more' AND meta_value = '' AND pm.post_id = p.ID)";
            $order_by = "ORDER BY p.ID DESC";

            $Query = "SELECT p.ID,  p.post_title FROM wp_posts AS p  WHERE p.post_status='publish' AND p.post_type = 'page' " . $qryWhere . " " . $order_by . " ";
            //print($Query);die();
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                $retValue = array("status" => "1", "message" => "Get event title data");
                while ($rw = mysqli_fetch_object($rs)) {
                    $retValue['data'][] = array(
                        //"event_id" => $rw->ID,
                        "title" => strval($rw->post_title),
                    );
                }
            } else {
                $retValue = array("status" => "0", "message" => "Record not found!");
            }
        } elseif (isset($params['title']) && $params['title'] == 'vanue') {

            $tempArray = array();
           $Query = "SELECT pm.*, p.guid FROM wp_postmeta AS pm LEFT OUTER JOIN wp_posts AS p ON p.ID = pm.meta_value AND pm.meta_key LIKE '%_img_top_venue_list' WHERE pm.post_id = '5821' AND pm.meta_key LIKE 'top_venue_list_%'";
            $rs = mysqli_query($GLOBALS['conn'], $Query);
            if (mysqli_num_rows($rs) > 0) {
                $retValue = array("status" => "1", "message" => "Get top vanue title data");
                while ($rw = mysqli_fetch_object($rs)) {
                    $metaKey = $rw->meta_key;

                    if (strpos($metaKey, '_name_top_venue_list') !== false) {
                        $tempArray['title'] = $rw->meta_value;
                    }

                    if (isset($tempArray['title'])) {
                        $retValue['data'][] = array(
                            'title' => $tempArray['title']
                        );
                        $tempArray = array();
                    }
                }
            }
        } else {
            $retValue = array("status" => "0", "message" => "Please set the required parameter");
        }
        return $retValue;
    }
}
