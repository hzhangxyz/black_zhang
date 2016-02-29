USE mysql;
DROP TABLE IF EXISTS lang ;
CREATE TABLE lang(name CHAR(32), lang CHAR(32), if_cache CHAR(4), cache CHAR(255));
