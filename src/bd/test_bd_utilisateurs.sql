drop user if exists `admin` @localhost,
`alice` @localhost,
`charlie` @localhost,
`diana` @localhost,
`eric` @localhost,
`frank` @localhost,
`gestion` @localhost,
`grace` @localhost,
`isabel` @localhost,
`jack` @localhost,
`karen` @localhost,
`louis` @localhost,
`marie` @localhost,
`queen` @localhost,
`tec1` @localhost,
`tec2` @localhost,
`tec3` @localhost,
`tec4` @localhost,
`tec5` @localhost,
`tec6` @localhost,
visiteur @localhost,
sys @localhost,
adminsys @localhost,
adminweb @localhost;
create user adminsys @localhost identified by 'fHPZWIRfklk=';
grant ADMIN_SYS to adminsys @localhost;
set default role ADMIN_SYS for adminsys @localhost;
create user adminweb @localhost identified by 'fHPZWIRbjkg=';
grant ADMIN_WEB to adminweb @localhost;
set default role ADMIN_WEB for adminweb @localhost;
create user visiteur @localhost identified by 'UEXMVroemmLTU10kyBbSvnY1PjusE+2VmdJapjAsUzyFU/VgiD1KbS1og78+qg==';
grant VISITEUR to visiteur @localhost;
set default role VISITEUR for visiteur @localhost;
create user sys @localhost identified by "KV+DX5xOklLfBxEU91bduE1UHCqaL8PmlNt8pD8jVG2JQctH3GkWQCxLkLQctOki6fY=";
grant UTILISATEUR to sys @localhost with ADMIN OPTION;
grant TECHNICIEN to sys @localhost with ADMIN OPTION;
set default role UTILISATEUR for sys @localhost;
grant create user on *.* to sys @localhost;
grant ALL PRIVILEGES on mysql.* to sys @localhost;
