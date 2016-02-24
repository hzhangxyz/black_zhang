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

function gate($what,$who){
    $con = mysql_connect("localhost","root","");
    mysql_select_db("mysql", $con);
    $result = mysql_query('SELECT lang FROM lang WHERE name="'.$who.'"');
    if($row = mysql_fetch_array($result)){
        switch($row["lang"]){
            case "php":
                $ans = runer("php -a",$what);
                break;
            case "python":
                $ans = runer("python",$what);
                break;
            default:
                mysql_query('UPDATE lang SET lang="php" WHERE name="'.$who.'"');
                $ans = runer("php -a",$what);
        }
    }else{
        mysql_query('INSERT INTO lang (name, lang) VALUES ("'.$who.'", "php")');
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
