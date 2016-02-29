USE mysql;
DROP TABLE lang;
CREATE TABLE lang(name CHAR(32), lang CHAR(32), if_cache CHAR(1), cache CHAR(1024));
