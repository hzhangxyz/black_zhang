<?php

function set($name, $arr){
    $con = mysql_connect("localhost","root","");
    mysql_select_db("mysql",$con);
    $result = mysql_query('SELECT * FROM lang WHERE name="'.$name.'"');
    if(!(mysql_fetch_array($result))){
        mysql_query('INSERT INTO lang (name, lang, if_cache, cache) VALUE ("'.$name.'", "normal", "N","")');
    }
    if(array_key_exists("lang",$arr))mysql_query('UPDATE lang SET lang="'.$arr["lang"].'" WHERE name="'.$name.'"');
    if(array_key_exists("if_cache",$arr))mysql_query('UPDATE lang SET if_cache="'.$arr["if_cache"].'" WHERE name="'.$name.'"');
    if(array_key_exists("cache",$arr))mysql_query('UPDATE lang SET cache="'.$arr["cache"].'" WHERE name="'.$name.'"');
    mysql_close($con);
    return 0;
}

function get($name){
    $con = mysql_connect("localhost","root","");
    mysql_select_db("mysql",$con);
    $result = mysql_query('SELECT * FROM lang WHERE name="'.$name.'"');
    if($row = mysql_fetch_array($result)){
        $ans = $row;
    }
    else{
        mysql_query('INSERT INTO lang (name, lang, if_cache, cache) VALUE ("'.$name.'", "normal", "N","")');
        $ans = array("name"=>$name, "lang"=>"normal", "if_cache"=>"N", "cache"=>"");
    }
    mysql_close($con);
    return $ans;
}

?>
