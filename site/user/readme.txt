注意：最好删去上级目录site中的login和signup等与用户相关的php文件。

userconfig.php          配置文件，记录路径和key信息
userfunc.php            其他php文件会用到的函数

【AJAX检查】
checkemailused.php	检查email是否可用
checkusernameused.php	检查用户名是否可用

doubancon.php		连接豆瓣
doubanconcallback.php	用户授权后处理文件

weibocon.php            连接微博
weiboconcallback.php    用户授权后处理文件

conexistuser.php	连接到一个已有的账户上
connewuser.php		连接到一个新建的账户上

discondoubanlink.php	断开此用户与豆瓣的连接
disconsinalink.php	断开此用户与微博的连接

login.php		使用email和pwd登陆
logout.php              登出
signup.php              在本站注册
constatus.php		查看连接状态

数据库表初始化：
CREATE TABLE `user` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`username` char(10) NOT NULL,
`email` char(48) DEFAULT NULL,
`passwd` char(33) DEFAULT NULL,
`keywords` char(128) DEFAULT NULL,

`sinaid` char(30) DEFAULT NULL,
`sinaname` char(33) DEFAULT NULL,
`sinaackey` char(128) DEFAULT NULL,
`sinaacsec` char(128) DEFAULT NULL,

`doubanid` char(30) DEFAULT NULL,
`dname`  char(33) DEFAULT NULL,
`dackey` char(128) DEFAULT NULL,
`dacsec` char(128) DEFAULT NULL,

PRIMARY KEY (`id`)
)ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

