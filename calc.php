<?php
function runer($cmd,$script){
    $descriptorspec = array(
        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
        1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
        2 => array("file", "/dev/null", "a") // stderr is a file to write to
    );
    $cwd = '/';
    $env = array('some_option' => 'aeiou');
    $process = proc_open($cmd, $descriptorspec, $pipes, $cwd, $env);
    fwrite($pipes[0], $script);
    fclose($pipes[0]);
    $ans=stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    return $ans;
}

function set_lang($name,$lang){
    $con = mysql_connect("localhost","root","");
    mysql_select_db("mysql", $con);
    $result = mysql_query('SELECT lang FROM lang WHERE name="'.$name.'"');
    if($row = mysql_fetch_array($result)){
        mysql_query('UPDATE lang SET lang="'.$lang.'" WHERE name="'.$name.'"');
    }
    else{
        mysql_query('INSERT INTO lang (name, lang) VALUES ("'.$name.'", "'.$lang.'")');
    }
    mysql_close($con);
    return 0;
}

function get_lang($name){
    $con = mysql_connect("localhost","root","");
    mysql_select_db("mysql", $con);
    $result = mysql_query('SELECT lang FROM lang WHERE name="'.$name.'"');
    if($row = mysql_fetch_array($result)){
        $ans = $row["lang"];
    }
    else{
        $ans = "normal";
        mysql_query('INSERT INTO lang (name, lang) VALUES ("'.$name.'", "'.$ans.'")');
    }
    mysql_close($con);
    return $ans;
} 

function gate($what,$who){
    switch($what){
        case "help":
            return "try: help, mode, normal, python, php";
        case "mode":
            return get_lang($who);
        case "python":
            set_lang($who, "python");
            return "python mode";
        case "php":
            set_lang($who, "php");
            return "php mode";
        case "normal":
        case "exit":
        case "quit":
            set_lang($who, "normal");
            return "normal mode";
    }
    $lang = get_lang($who);
    switch($lang){
        case "php":
            return runer("php","<?php \n".$what."\n?>");
        case "python":
            return runer("python",$what);
        case "normal":
            return normal_run($what);
        default:
            set_lang($who,"normal");
            return normal_run($what);
    }
}

function normal_run($ans){
    try{
        eval('$ans='.$ans.';');
    }catch(Exception $e){}
    return $ans;
}

?>
