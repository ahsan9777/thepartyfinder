<?php
class FCM {
    function __construct() {
    }
   /**
    * Sending Push Notification
   */
  public function send_notification($registatoin_ids, $notification,$device_type) {
      $url = 'https://fcm.googleapis.com/fcm/send';
      if($device_type == "Android"){
            $fields = array(
                'to' => $registatoin_ids,
                'data' => $notification
            );
      } else {
            $fields = array(
                'to' => $registatoin_ids,
                'notification' => $notification
            );
      }
      // Firebase API Key
      //$headers = array('Authorization:key=AAAA3CoNFfk:APA91bE8_i9OqSpOwOvMm7nE-W7a5_9kGCFnovYYouedef5hxQutjhLbFoRVr64I445Bqoh-0Lnharkng8drTdMwz63aVlzLSC72Ok2ZkG2dQ555_rtUJD9WIjsPXpKw9dBFx_Yf8fVL','Content-Type:application/json');
      //$headers = array('Authorization:key=AAAA3CoNFfk:APA91bHLhOrjA2b0VPNFrjLBbTeoZPD2ETwW3pYQa5jRDPyrYIgXtEyxYd4uy-SupJke5CqwqzEMCbavK33P2zntrCVBRS017c9FpL-QhnDFl4eaPG0gWXD1aiqg_TEtVkIF_v6iS4rl','Content-Type:application/json');
      define('API_ACCESS_KEY', 'AAAAPmYrOdo:APA91bHdj1tmZswF1OF7ZTFMpHi7XThrc0CiyYKv1yQwKyQrAFybg32rwH9K4W6sxjCmvSIF49BA4LYjLO81VnSmjq9AZb_ZTBnr7vAzx9Afr5-XscqrDKUcYgY8UBVf_9ca5s78_IFm');
      //define('API_ACCESS_KEY', 'AAAA3CoNFfk:APA91bHLhOrjA2b0VPNFrjLBbTeoZPD2ETwW3pYQa5jRDPyrYIgXtEyxYd4uy-SupJke5CqwqzEMCbavK33P2zntrCVBRS017c9FpL-QhnDFl4eaPG0gWXD1aiqg_TEtVkIF_v6iS4rl');
      $headers = array(
            'Authorization: key=' . API_ACCESS_KEY,
            'Content-Type: application/json'
        );
     // Open connection
      $ch = curl_init();
      // Set the url, number of POST vars, POST data
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      // Disabling SSL Certificate support temporarly
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
      $result = curl_exec($ch);
      if ($result === FALSE) {
          die('Curl failed: ' . curl_error($ch));
      }
      curl_close($ch);
  }
}   
?>