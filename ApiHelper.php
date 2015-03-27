<?php

/**
 * Api Helper
 * @author Victor Demin <demin@trabeja.com>
 */
class ApiHelper {

    const USERNAME = 'user';
    const PASSWORD = 'password';
    const ENCRIPTION_KEY = '1234567891234567';

    /**
     * Will send POST request to the url with data. 
     * Can get the json response and convert it to stdClass
     * @param string $url
     * @param mixed $data
     * @return stdClass
     */
    public static function sendRequest($url, $data) {
        $token = self::generateRequestToken($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['token' => $token]));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $result = curl_exec($ch);
        return curl_errno($ch) ? 'Error on curl request.' : json_decode($result);
    }

    /**
     * Will generate token with username,password and data parameters embedded.
     * @param mixed $data
     * @return string
     */
    public static function generateRequestToken($data) {
        $array = [];
        $array['username'] = self::USERNAME;
        $array['password'] = self::PASSWORD;
        $array['data'] = $data;
        $token = serialize($array);
        Yii::app()->securityManager->cryptAlgorithm = 'rijndael-256';
        $token = Yii::app()->securityManager->encrypt($token, self::ENCRIPTION_KEY);
        $token = base64_encode($token);
        return $token;
    }

    /**
     * Will get the request data from the token. if username and password are valid
     * @param string $token
     * @return mixed|null
     */
    public static function getRequestData($token) {
        $token = base64_decode($token);
        Yii::app()->securityManager->cryptAlgorithm = 'rijndael-256';
        $token = Yii::app()->securityManager->decrypt($token, self::ENCRIPTION_KEY);
        $array = unserialize($token);
        if ($array['username'] == self::USERNAME && $array['password'] == self::PASSWORD) {
            return $array['data'];
        }
    }

}
