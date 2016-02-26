scr=/tmp/`date "+%N"`.c
exe=/tmp/`date "+%N"`.out
cat >$scr
gcc $scr -o $exe
$exe
rm $scr $exe
