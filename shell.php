<?php

    //是否开启调试，thinkphp会进行编译保存在runtime文件夹下
    define('APP_DEBUG', true);

    //项目名称
    define('APP_NAME', 'App');

    //项目路径
    define('APP_PATH', './App/');

    define('MODE_NAME', 'cli');  //todo cli模式，脚本执行命令的当前目录必须是该文件（shell.php）所在的目录，所以需要通过cd命令来切换

    require '/data/web/thinkphp_cli/ThinkPHP/ThinkPHP.php'; //cli模式，使用绝对路径

?>
