<?php

/**
 * HSMTP Server
 * @author Hafrans
 *
 */
class HSMTPServer
{

    private $downloadKey;

    private $uploadKey;

    private $ckstatus = false;

    private $err_msg = array(
        "err_msg" => ""
    );

    public function __construct($uploadKey, $downloadKey)
    {
        $this->downloadKey = $downloadKey;
        $this->uploadKey = $uploadKey;

        // send Headers
        @header("Content-Type: application/json; charset=utf-8");
        @header("Pragma: no-cache");
    }
    public static function checkRequestObject($url = null){
        if($_SERVER['HTTP_USER_AGENT'] !== "HSMTP 1.0 / Lari Protocol is Submiter"){

            if(empty($url)){
                @header("HTTP/1.0 505 HTTP Version not supported");
                exit("");
            }else{
                @header("Location: ".$url);
            }
            return false;
        }else{
            return true;
        }

    }

    /**
     * 检测用户请求的KEY的合法性
     *
     * @return boolean
     */
    public function checkDownloadKey()
    {
        $key = $this->getDownloadKeys();
        $remoteKey = urldecode(file_get_contents("php://input"));
        foreach ($key as $v) {
            if ($v == $remoteKey) {
                $this->ckstatus = true;
                return true;
            }
        }
        throw new HSMTPException("Auth Failed");
    }

    /**
     * 检测用户请求的KEY的合法性
     *
     * @return boolean
     */
    public function checkUploadKey()
    {
        $key = $this->getUploadKeys();
        $remoteKey = urldecode($_POST['HSMTPCLIENT_UPLOAD_KEY']);
        foreach ($key as $v) {
            if ($v == $remoteKey) {
                $this->ckstatus = true;
                return true;
            }
        }
        throw new HSMTPException("Auth Failed");
        return false;
    }

    /**
     * 从用户上传的请求中获取数据
     */
    public function getData()
    {
        if (! $this->ckstatus)
            throw new HSMTPException("KEY CK FAILED");
        return $_POST["HSMTPCLIENT_UPLOAD_MSG"];
    }

    /**
     * 返回错误，如果没有任何错误返回false
     *
     * @param String $string
     *            = null
     * @return boolean
     */
    public function returnError($string = null)
    {
        if ($string != null) {
            $this->err_msg['err_msg'] = $string;
            exit(json_encode($this->err_msg));
        } else {
            if ($this->ckstatus) {
                $this->err_msg['err_msg'] = "Auth Failed!";
                exit(json_encode($this->err_msg));
            } else {
                return false;
            }
        }
    }

    /**
     * 返回数据信息
     *
     * @param array $arr
     */
    public function returnData($arr)
    {
        exit(json_encode($arr));
    }

    /**
     * Key的周期30s
     * Key的生命为10s
     */
    private function getUploadKeys()
    {
        $timestamp = $this->getTimestampDiv();
        $timestamp --;
        $arr[] = md5($timestamp ++ . $this->uploadKey);
        $arr[] = md5($timestamp ++ . $this->uploadKey);
        $arr[] = md5($timestamp ++ . $this->uploadKey);
        return $arr;
    }

    private function getDownloadKeys()
    {
        $timestamp = $this->getTimestampDiv();
        $timestamp --;
        $arr[] = md5($this->downloadKey . $timestamp ++);
        $arr[] = md5($this->downloadKey . $timestamp ++);
        $arr[] = md5($this->downloadKey . $timestamp ++);
        return $arr;
    }

    private function getTimestampDiv()
    {
        return (int) (time() / 10);
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
