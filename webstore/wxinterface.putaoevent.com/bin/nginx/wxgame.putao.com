server {
    listen       80;
    server_name wxgame.kemido.com;
    access_log /var/log/nginx/planet.access.log;
    error_log /var/log/nginx/planet.error.log;
	
    location / {
		proxy_pass http://127.0.0.1:8888;
		proxy_http_version 1.1;
		proxy_set_header Upgrade $http_upgrade;
		proxy_set_header Connection 'upgrade';
		proxy_set_header X-Forwarded-For $remote_addr;
		proxy_set_header Host $host;
    }
    
    location ~ ^/(js|css|images|fonts)/ {
		root /home/sysadm/community/webstore/planet.putao.com/kerisy/application/static;
		#expires 30d;
	}

	location ~ ^/favicon\.ico$ {
		root /home/sysadm/wxgame/webstore/wxgame.putao.com/kerisy/application/static/images;
	}

}

