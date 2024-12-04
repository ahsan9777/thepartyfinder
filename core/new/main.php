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

    public function get_category($params)
    {
        $retValue = array();

        $Query = "SELECT tt.*, t.name, t.slug FROM wp_term_taxonomy AS tt LEFT OUTER JOIN wp_terms AS t ON t.term_id = tt.term_id WHERE tt.taxonomy = 'category' ";
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

    public function get_post($params)
    {
        $retValue = array();

        if (isset($params['post_type']) && !empty($params['post_type'])) {
            $qryWhere = " AND vpost.post_type='" . $params['post_type'] . "'";
        } else {
            $qryWhere = " AND vpost.post_type='post'";
        }

        if (isset($params['post_id']) && $params['post_id'] > 0) {
            $qryWhere .= " AND vpost.ID = '" . $params['post_id'] . "'";
        }
        if (isset($params['cat_id']) && $params['cat_id'] > 0) {
            $qryWhere .= " AND vpost.ID IN (SELECT tr.object_id FROM wp_term_relationships AS tr WHERE tr.term_taxonomy_id = '" . $params['cat_id'] . "')";
        }

        $Query = "SELECT vpost.*, (SELECT vp.guid FROM wp_posts AS vp WHERE vp.post_type = 'attachment' AND vp.post_mime_type LIKE 'image/%' AND vp.ID = (SELECT meta_value FROM `wp_postmeta` WHERE post_id = vpost.ID AND meta_key = 'event_main_picture_events_more')) AS thumbnail_image_url FROM wp_posts AS vpost  WHERE vpost.post_status='publish' " . $qryWhere . " ";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            $retValue = array("status" => "1", "message" => "Get post data");
            while ($rw = mysqli_fetch_object($rs)) {
                $arr['post_id'] = $rw->ID;
                $retValue['data'][] = array(
                    "id" => $rw->ID,
                    "post_modified" => strval($rw->post_modified),
                    "post_author" => strval($rw->post_author),
                    //"post_content" => strval($rw->post_content),
                    "post_title" => strval($rw->post_title),
                    "guid" => strval($rw->guid),
                    "thumbnail_image_url" => strval($rw->thumbnail_image_url),
                    "post_meta" => $this->get_postmeta($arr)
                );
            }
        } else {
            $retValue = array("status" => "0", "message" => "Record not found!");
        }
        return $retValue;
    }

    public function get_postmeta($params)
    {
        $retValue = array();
        $Query = "SELECT vpm.*, (SELECT vp.guid FROM wp_posts AS vp WHERE vp.ID = vpm.meta_value AND vpm.meta_key = 'story_thumbnail_events_more') AS url_story_thumb FROM wp_postmeta AS vpm WHERE vpm.post_id = '" . $params['post_id'] . "' AND vpm.meta_key NOT LIKE '\\_%'";
        $rs = mysqli_query($GLOBALS['conn'], $Query);
        if (mysqli_num_rows($rs) > 0) {
            while ($rw = mysqli_fetch_object($rs)) {
                if($rw->meta_key == 'story_thumbnail_events_more'){
                    $retValue[] = array(
                        $rw->meta_key => $rw->url_story_thumb
    
                    );
                } else{
                    $retValue[] = array(
                        $rw->meta_key => $rw->meta_value
    
                    );
                }
            }
        }
        return $retValue;
    }

    public function form_submit($params)
    {
        $retValue = array();
        $response = array();

        $message = 0;
        $email_message = "";

        if (isset($params['form_id']) && !empty($params['form_id'])) {
            $form_id = $params['form_id'];

            if($form_id == 3){
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
                $serial_number = $this->func->getMaximumwhere("wp_fluentform_submissions", "serial_number", "form_id = '".$form_id."'");
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_submissions (form_id, serial_number, response, source_url, device, created_at, updated_at) VALUES ('" . $form_id . "', '" . $serial_number . "', '".$this->func->dbStr($json_response)."', 'http://partyfindersdxb.com/?fluent_forms_pages=1&design_mode=1&preview_id=3', '".$this->func->dbStr($params['platform'])."', '".date('Y-m-d H:i:s')."', '".date('Y-m-d H:i:s')."')") or die(mysqli_error($GLOBALS['conn']));
                $submission_id = $this->func->getMaximum("wp_fluentform_submissions", "id");
                if($submission_id > 1){
                    $submission_id = $submission_id - 1;
                }
            }
            if (isset($params['cpt_selection']) && !empty($params['cpt_selection'])) {
                $field_value = " '" . $this->func->dbStr($params['cpt_selection']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'cpt_selection', '', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><strong>Select Event: </strong> ".$params['cpt_selection'];
            }
            if (isset($params['datetime']) && !empty($params['datetime'])) {
                $field_value = " '" . $this->func->dbStr($params['datetime']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'datetime', '', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><strong>Date: </strong> ".$params['datetime'];
            }
            if (isset($params['first_name']) && !empty($params['first_name'])) {
                $field_value = " '" . $this->func->dbStr($params['first_name']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'names', 'first_name', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><strong>First Name: </strong> ".$params['first_name'];
            }
            if (isset($params['last_name']) && !empty($params['last_name'])) {
                $field_value = " '" . $this->func->dbStr($params['last_name']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'names', 'last_name', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><strong>Last Name: </strong> ".$params['last_name'];
            }
            if (isset($params['email']) && !empty($params['email'])) {
                $field_value = " '" . $this->func->dbStr($params['email']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'email', '', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><strong>Email: </strong> ".$params['email'];
            }
            if (isset($params['phone']) && !empty($params['phone'])) {
                $field_value = " '" . $this->func->dbStr($params['phone']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'phone', '', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><strong>Contact No: </strong> ".$params['phone'];
            }
            if (isset($params['city']) && !empty($params['city'])) {
                $field_value = " '" . $this->func->dbStr($params['city']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'address_2', 'city', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><strong>City: </strong> ".$params['city'];
            }
            if (isset($params['dropdown']) && !empty($params['dropdown'])) {
                $field_value = " '" . $this->func->dbStr($params['dropdown']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'dropdown', '', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><strong>Gents: </strong> ".$params['dropdown'];
            }
            if (isset($params['dropdown_1']) && !empty($params['dropdown_1'])) {
                $field_value = " '" . $this->func->dbStr($params['dropdown_1']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'dropdown_1', '', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><strong>Ladies: </strong> ".$params['dropdown_1'];
            }
            if (isset($params['description']) && !empty($params['description'])) {
                $field_value = " '" . $this->func->dbStr($params['description']) . "'";
                mysqli_query($GLOBALS['conn'], "INSERT INTO wp_fluentform_entry_details (form_id, submission_id, field_name, sub_field_name, field_value) VALUES ('" . $form_id . "', '" . $submission_id . "', 'description', '', " . $field_value . ")") or die(mysqli_error($GLOBALS['conn']));
                $message = 1;
                $email_message .= "<br><br><strong>Message: </strong><br> ".$params['description'];
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

    public function email_test($params){
        $retValue = array();
        $retValue = $retValue = array("mail-test" => $this->mailer->test("Hello Ahsan"));
        return $retValue;
    }
}
