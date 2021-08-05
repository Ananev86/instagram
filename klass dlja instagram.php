<?php
class Instagram{
    const URL_INSTAGRAM_API = 'https://graph.instagram.com/me/';
    private $access_token = 0;
    public $token_params = 0;
    public $count_post = 0;
    public $error = "";
    public $App = "";
    public function __construct(){
      //  $token = 'IGQVJXN0ltalJGYTJ5OVBxOXo3RUVVaXZAQWjJfZA2FWUDBjSkw5cmp4UGViRFExTFBXU25La1BobWlVS1NQRTk0WlNKZAXJpMGtVSW9QMjVDVGduQnFqUlFLVWFkM19TSWp1Y3ltal9sYWZANUXFmMW1KTAZDZD';
     //  $token='IGQVJWTzRLU2VqMjlEYTRuckJmYnRnNHg3NGZAmUWc4MEtmbUFETnNHcmhXN2RNaDBHRFRDTUlvS012NjNlZAmM4TDBPYWVZAMWowX0I4S3RLbDhxdHl4TEFsUldNLVlNVlk1bnVjMWJzTXdWemk2LTkzWgZDZD';
	 // $token='IGQVJVY2Rfend6ZAVM0cFZAMTHZALZAVY1U3VNZA3E0QW1mRmM2N1BhRzYxWThpQmFZARFBadmdyaVVsa0RaamZArQmhsLXQwazRWRm0xcjhfTWp4NGRHeVFSeTFGTzFNcEhJdkpTOGlBdGlOU3dRYVZAxWmxxMQZDZD';
	  $token='IGQVJXdHdKYjRGQkl6ZAUl1akRnNFBUck1zYl9oN29IRkJCeEJXV1RmQzNLbWpVUmpmUE9fNlBpS29Bb0RIQU9HSnRJNUp4ck1vUVFfX3ZAOcUdfeUJ1TjljVjRpb2hjRUFRQ2FkUFlqTXV5NGFPQUplVwZDZD';
	  $count = 6;
       global $APPLICATION;
        $this->token_params = $token;
        $this->count_post = $count;
        $this->App=$APPLICATION;
    }
    public function checkApiToken(){
        if(!strlen($this->token_params)){
            $this->error="No API token instagram";
        }
        $this->access_token='/?access_token='.$this->token_params;
    }
    public function getFormatResult($method, $fields = ''){
        if(function_exists('curl_init'))
        {
            if($fields) {
                $method.$this->access_token .= '&fields='.$fields;
            }
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, self::URL_INSTAGRAM_API.$method.$this->access_token."&limit=".$this->count_post);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $out = curl_exec($curl);
            $data =  $out ? $out : curl_error($curl);
        }
        else
        {
            $data = file_get_contents(self::URL_INSTAGRAM_API.$method.$this->access_token);
        }

        $data = json_decode($data, true);
        return $data;
    }
    public function getInstagramPosts(){
        $this->checkApiToken();
        if($this->error){
            return array("ERROR" => "Y", "MESSAGE" => $this->error);
        }else{
            $data=$this->getFormatResult('media', 'id,caption,media_url,permalink,username,timestamp,thumbnail_url');
        }
        return $data;
    }

    public function getInstagramUser(){
        $this->checkApiToken();
        if($this->error){
            return $this->error;
        }else{
            $data=$this->getFormatResult('users/self');
        }
        return $data;
    }
    public function getInstagramTag($tag) {
        $this->checkApiToken();
        if($this->error){
            return $this->error;
        }else{
            $data=$this->getFormatResult('tag/'.$tag.'/media/recent');
        }
        return $data;
    }
}
