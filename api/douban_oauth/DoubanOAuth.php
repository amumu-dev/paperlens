<?php
require_once('OAuth.php');

$hostname = "http://localhost/";
$request_url = "http://www.douban.com/service/auth/request_token";
$authorize_url = "http://www.douban.com/service/auth/authorize";
$accesstoken_url = "http://www.douban.com/service/auth/access_token";
$confirm_callback = $hostname."/doubancon/saveauth";
$sign_method = new OAuthSignatureMethod_HMAC_SHA1();

$my_consumer_key = "0be63badad20e46a23b2b3090762ad3e";
$my_consumer_secret = "c078d2b357a0cff8";



class DoubanOAuthClient{
    private $consumer_key;
    private $consumer_secret;
    private $server = "www.douban.com";
    private $consumer;

    static $request_url = "http://www.douban.com/service/auth/request_token";
    #static $sign_method = new OAuthSignatureMethod_HMAC_SHA1();

    function __construct($consumer_key,$consumer_secret,$server="www.douban.com")
    {
        $this->consumer_key = $consumer_key;
        $this->consumer_secret = $consumer_secret;
        $this->server = $server;
        $this->consumer = new OAuthConsumer($consumer_key,$consumer_secret);
    }

    /*
     * get the content according to the oauth url
     * parser the content 
     */
    function fetch_token($oauth_request)
    {
        $theurl = $oauth_request->to_url();
        $content = file_get_contents($theurl);
        $pairs = explode('&',$content);
        $tmparr = array();
        foreach($pairs as $p){
            $vs = explode('=',$p);
            $tmparr[$vs[0]] = $vs[1];
        }
        return $tmparr;
    }

    /*
     * create the oauth_request of get request token
     * fetch token
     * return result
     */
    function get_request_token(){
        global $sign_method;
        $params = array();
        #$oauth_request = OAuthRequest::from_consumer_and_token($this->consumer,NULL,"GET",$request_url,$params);
        $oauth_request = OAuthRequest::from_consumer_and_token($this->consumer,NULL,"GET",DoubanOAuthClient::$request_url,$params);
        $oauth_request->sign_request($sign_method,$this->consumer,NULL);
        #$oauth_request->sign_request(DoubanOAuthClient::$sign_method,$this->consumer,NULL);
        return $this->fetch_token($oauth_request);
    }

    /*
     * get the authorization url
     */
    function get_authorization_url($rt_key,$rt_secret,$callbackurl=""){
        global $authorize_url;
        $callbackurl = $callbackurl."?tkey=".$rt_key."&tsecret=".$rt_secret;
        $tmp_url = $authorize_url."?oauth_token=$rt_key&oauth_callback=".urlencode($callbackurl);
        return $tmp_url;
    }

    /*
     * get access token after the auhorization
     */
    function get_access_token($rt_key,$rt_secret){
        $params = array();
        global $accesstoken_url;
        global $sign_method;
        $tmp_token = new OAuthToken($rt_key,$rt_secret);
        $oauth_request = OAuthRequest::from_consumer_and_token($this->consumer,$tmp_token,"GET",$accesstoken_url,$params);
        $oauth_request->sign_request($sign_method,$this->consumer,$tmp_token);
        return $this->fetch_token($oauth_request);
    }

}

?>
