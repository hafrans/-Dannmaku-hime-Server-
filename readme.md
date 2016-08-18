#弹幕姬服务端
##注意事项
1. PHP版本5.2+ 并支持Redis库
2. Redis
##各个文件的名称以及用处
* HSMTPClient.class.php    
> 引用此文件，发送弹幕至服务器，只需要配置好密钥与服务器地址就可以使用   

* HSMTPServer.class.php   
> HSMTP 服务端，负责数据的验证与传输

* getmsg.php 
> 信息下行文件--注意：文件名称不能更改

* index.php 
> 信息上行文件--注意：文件名称不能更改

* key.php 
> 存储着配置信息

* send.php 
> 示例弹幕发送器文件

