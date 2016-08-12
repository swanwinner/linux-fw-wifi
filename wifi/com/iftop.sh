#!/bin/sh

path=`readlink -f $0`
dir=`dirname $path`

cd $dir
file="$dir/iftop.txt"
tmp="$dir/iftop.tmp"

date > $tmp
/usr/local/sbin/iftop  -t  -c /root/.iftoprc  -s 40 >> $tmp

/bin/mv -f $tmp $file
