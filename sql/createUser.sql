grant all privileges on putao_wxinterface.* to interface@'192.168.%' identified by 'interface';
grant all privileges on putao_wxinterface.* to interface@'localhost' identified by 'interface';

grant select on putao_wxinterface.* to readonly@'192.168.%' identified by 'readonly';
grant select on putao_wxinterface.* to readonly@'localhost' identified by 'readonly';