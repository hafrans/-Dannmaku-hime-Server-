<?php
    error_reporting(0);
    date_default_timezone_set("PRC"); // China
    $config = require './key.php';
    require './HSMTPServer.class.php';
    HSMTPServer::checkRequestObject();
    $hsmtp = new HSMTPServer($config['upload_key'], $config['download_key']);

    $redis = new Redis(); // initialize Redis
    //鉴权
    try {
        $hsmtp->checkUploadKey();
        $redis->connect('127.0.0.1', 6379);
    } catch (Exception $e) {
        $err_msg['err_msg'] = $e->getMessage();
        exit(json_encode($err_msg));
    }

    $postData = $hsmtp->getData();
    if(!empty($postData)){
        if(is_array($postData)){
            foreach ($postData as $v){
                try{
                    $redis->rPush($config['queue-name'],$v);
                }catch (Exception $e){
                    $err_msg['err_msg'] = $e->getMessage();
                    exit(json_encode($err_msg));
                }
            }
        }else{
            try{
                $redis->rPush($config['queue-name'],$postData);
            }catch (Exception $e){
                $err_msg['err_msg'] = $e->getMessage();
                exit(json_encode($err_msg));
            }
        }
    }

    $hsmtp->returnError("0");
