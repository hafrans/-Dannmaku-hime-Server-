<?php
    error_reporting(0);
    date_default_timezone_set("PRC");
    $config = require "./key.php";
    require './HSMTPServer.class.php';
    HSMTPServer::checkRequestObject();
    $hsmtp = new HSMTPServer($config['upload_key'], $config['download_key']);

    // initialize Redis system
    $redis = new Redis();
    try {
        $hsmtp->checkDownloadKey();
        $redis->connect('127.0.0.1', 6379);
    } catch (Exception $e) {
        $err_msg['err_msg'] = $e->getMessage();
        exit(json_encode($err_msg));
    }
    try {
        $arr = $redis->lrange($config["queue-name"], 0, 19); // fetch 0-19 results
        $string = array();
        foreach ($arr as $value) {
            $string[] = htmlspecialchars_decode(stripslashes($value));
        }
        $redis->ltrim($config["queue-name"], 20, - 1); // pop them
    } catch (Exception $e) {
        $err_msg['err_msg'] = $e->getMessage();
        exit(json_encode($err_msg));
    }
    $hsmtp->returnData($string);
