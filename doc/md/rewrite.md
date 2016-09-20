## Rewrite说明

1.主机为Apache，请修改/doc/rewrite/下的apache文件为.htaccess并放于项目根目录下，或直接将以下内容置于项目根目录下的.htaccess文件；
    
    <IfModule mod_rewrite.c>
        Options +FollowSymLinks
        RewriteEngine on
        RewriteCond %{REQUEST_FILENAME} !-d
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^(.*)$ index.php/$1 [QSA,PT,L]
    </IfModule>

2.主机为Nginx，请将/doc/rewirte/下nginx文件中的内容添加在nginx虚拟主机配置文件中，或在nginx配置文件中增加以下内容：

    location / {
        if (!-e $request_filename) {
            rewrite ^(.*)$ /index.php?s=$1 last;
            break;
        }
    }