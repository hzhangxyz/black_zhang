<?php
function gate($what,$who){
    return run_php($what);
    $con = mysql_connect("localhost","root","");
    mysql_select_db("mysql", $con);
    $result = mysql_query('SELECT lang FROM lang WHERE name="$who"');
    if($row = mysql_fetch_array($result)){
        switch($row["lang"]){
            case "php":
                $ans = run_php($what);
                break;
            case "python":
                $ans = $ans;
                break;
            default:
                mysql_query("UPDATE lang SET lang="php" WHERE name="$who"');
                $ans = run_php($what);
        }
    }else{
        mysql_query('INSERT INTO lang (name, lang) VALUES ("$who", "php")');
        $ans = run_php($what);
    }
    mysql_close($con);
    return $ans;
}

function run_php($what){
    $ans = $what;
    try{
        eval($ans);
    }
    catch(Exception $e){}
    try{
        eval('$ans='.$ans.';');
    }
    catch(Exception $e){}
    return $ans;
}

?>
