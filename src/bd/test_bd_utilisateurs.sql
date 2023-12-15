drop user `admin` @localhost,
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
`tec6` @localhost;
create user `admin` @localhost identified by 'de5d59d6517cb6d5a8629a7f4e0bafb3';
grant ADMIN_WEB to `admin` @localhost;
set default role ADMIN_WEB for `admin` @localhost;
create user `alice` @localhost identified by '5d41402abc4b2a76b9719d911017c592';
grant UTILISATEUR to `alice` @localhost;
set default role UTILISATEUR for `alice` @localhost;
create user `charlie` @localhost identified by '1937e316529d8b6e582d0c4cddb456c3';
grant UTILISATEUR to `charlie` @localhost;
set default role UTILISATEUR for `charlie` @localhost;
create user `diana` @localhost identified by 'e99a18c428cb38d5f260853678922e03';
grant UTILISATEUR to `diana` @localhost;
set default role UTILISATEUR for `diana` @localhost;
create user `eric` @localhost identified by 'd891893f26b9d3d9bdce7b7e77ea8b3d';
grant UTILISATEUR to `eric` @localhost;
set default role UTILISATEUR for `eric` @localhost;
create user `frank` @localhost identified by 'ec83ab13db7c9e6dace9b7c98cc78c66';
grant UTILISATEUR to `frank` @localhost;
set default role UTILISATEUR for `frank` @localhost;
create user `gestion` @localhost identified by '3695c56a2c1d477cb1eaad2ccad6dae5';
grant ADMIN_SYS to `gestion` @localhost;
set default role ADMIN_SYS for `gestion` @localhost;
create user `grace` @localhost identified by 'b6d767d2f8ed5d21a44b0e5886680cb9';
grant UTILISATEUR to `grace` @localhost;
set default role UTILISATEUR for `grace` @localhost;
create user `isabel` @localhost identified by '84bbd073b66596192c110a53f4080cd4';
grant UTILISATEUR to `isabel` @localhost;
set default role UTILISATEUR for `isabel` @localhost;
create user `jack` @localhost identified by '54b6c4b298e6696cbd07d30b96c1ae8b';
grant UTILISATEUR to `jack` @localhost;
set default role UTILISATEUR for `jack` @localhost;
create user `karen` @localhost identified by 'd9355b2e1ad292853bd8e30b30b6a01a';
grant UTILISATEUR to `karen` @localhost;
set default role UTILISATEUR for `karen` @localhost;
create user `louis` @localhost identified by 'ca9d93cc991648095ab9e08f3fc13b1c';
grant UTILISATEUR to `louis` @localhost;
set default role UTILISATEUR for `louis` @localhost;
create user `marie` @localhost identified by '02e74f10e0327ad868d138f2b4fdd6f0';
grant UTILISATEUR to `marie` @localhost;
set default role UTILISATEUR for `marie` @localhost;
create user `queen` @localhost identified by 'b0c4a64d7f69f1880b44aafc00d4c990';
grant UTILISATEUR to `queen` @localhost;
set default role UTILISATEUR for `queen` @localhost;
create user `tec1` @localhost identified by 'a152e841783914146e4bcd4f39100686';
grant TECHNICIEN to `tec1` @localhost;
set default role TECHNICIEN for `tec1` @localhost;
create user `tec2` @localhost identified by 'a152e841783914146e4bcd4f39100686';
grant TECHNICIEN to `tec2` @localhost;
set default role TECHNICIEN for `tec2` @localhost;
create user `tec3` @localhost identified by 'a152e841783914146e4bcd4f39100686';
grant TECHNICIEN to `tec3` @localhost;
set default role TECHNICIEN for `tec3` @localhost;
create user `tec4` @localhost identified by '35a3a501b25c7f68110f0c5e7b9ecf85';
grant TECHNICIEN to `tec4` @localhost;
set default role TECHNICIEN for `tec4` @localhost;
create user `tec5` @localhost identified by 'a152e841783914146e4bcd4f39100686';
grant TECHNICIEN to `tec5` @localhost;
set default role TECHNICIEN for `tec5` @localhost;
create user `tec6` @localhost identified by 'a152e841783914146e4bcd4f39100686';
grant TECHNICIEN to `tec6` @localhost;
set default role TECHNICIEN for `tec6` @localhost;
