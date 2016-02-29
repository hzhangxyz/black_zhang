<?php
require_once("sql.php")

function runer($cmd,$script){
    $descriptorspec = array(
        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
        1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
        2 => array("file", "/dev/null", "a") // stderr is a file to write to
    );
    $process = proc_open($cmd, $descriptorspec, $pipes);
    fwrite($pipes[0], $script);
    fclose($pipes[0]);
    $ans=stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    proc_close($process);
    return $ans;
}

function set_lang($name,$lang){
    set($name,array("lang"=>$lang));
    return 0;
}

function get_lang($name){
    return get($name)["lang"];
} 

function gate($what,$who){
    switch($what){
        case "help":
            return "try: help, mode, normal, python, php, bash, c";
        case "mode":
            return get_lang($who);
        case "python":
            set_lang($who, "python");
            return "python mode";
        case "php":
            set_lang($who, "php");
            return "php mode";
        case "bash":
            set_lang($who, "bash");
            return "bash mode";
        case "c":
            set_lang($who,"c");
            return "c mode";
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
        case "bash":
            return runer("bash",$what);
        case "c":
            return runer("sh gcc.sh",$what);
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
