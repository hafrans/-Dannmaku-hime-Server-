<?php

/**
 * Hafrans Simplified Messages Transfer Protocol
 * @author Hafrans
 *
 */
class HSMTPClient
{

    private $serverAddress;

    private $key;

    private $enctyped;

    private $ssl;

    /**
     * Class Constructor
     *
     * @param String $server
     * @param String $key
     * @param bool $ssl
     */
    public function __construct($server, $key, $ssl = null)
    {
        $this->serverAddress = $server . "/index.php";
        $this->key = $key;
        $this->ssl = $ssl;
    }

    /**
     * Send data to Server
     *
     * @param String $string
     */
    public function openConnection($string)
    {
        $ch = curl_init($this->serverAddress);
        $arr = array(
            "HSMTPCLIENT_UPLOAD_KEY" => urlencode($this->generateKey()),
            "HSMTPCLIENT_UPLOAD_MSG" => $string,
            "HSMTPCLIENT_UPLOAD_ARR" => is_array($string)
        );

        // set OPT

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);
        curl_setopt($ch, CURLOPT_USERAGENT, "HSMTP 1.0 / Lari Protocol is Submiter");
        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($code != 200) {
            throw new HSMTPException("Server Failed");
        }
        $result = json_decode($result, true);
        if ($result['err_msg'] != "0") {
            throw new HSMTPException($result['err_msg']);
        }
        if (curl_errno($ch) != 0) {
            throw new HSMTPException(curl_error($ch));
        }
        curl_close($ch);
        return true;
    }

    /**
     *
     * @return String enctyped string
     */
    private function generateKey()
    {
        $timestamp = (int) (time() / 10);
        return md5($timestamp . $this->key);
    }
}

/**
 *
 * @author Hafrans
 *
 */
class HSMTPException extends Exception
{

    public function __construct($message = null, $code = null, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}





