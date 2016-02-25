scr=/tmp/`date "+%N"`.c
exe=/tmp/`date "+%N"`.out
cat >$src
gcc $scr -o $exe
$exe
rm $scr $exe
