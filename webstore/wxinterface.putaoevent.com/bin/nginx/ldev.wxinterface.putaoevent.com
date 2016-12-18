server {
    listen       80;
    server_name ldev.wxinterface.putaoevent.com ldev.admin-wxinterface.putaoevent.com;
    #access_log /var/log/nginx/wxinterface.access.log;
    #error_log /var/log/nginx/wxinterface.error.log;
    #root /Users/allen/community/webstore/wxinterface.putao.com/laravel/public/;


	location / {
    		proxy_pass http://127.0.0.1:7456;
    		proxy_set_header X-Real-IP $remote_addr;
    		proxy_set_header Connection 'upgrade';
    		proxy_set_header X-Forwarded-For $remote_addr;
    		proxy_set_header Host $host;
    	}
	
	location ~ ^/(js|css|images|fonts)/ {
        root /Users/sunjunjie/work/wx_common_interface/webstore/wxinterface.putaoevent.com/kerisy/application/static;
	}

    location ~ ^/favicon\.ico$ {
        root /Users/sunjunjie/wx_common_interface/wxgame/webstore/wxinterface.putaoevent.com/kerisy/application/static/images;

    }
}

