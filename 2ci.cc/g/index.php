<?php
#echo "__FILE__: ==>" . __FILE__;
#echo "<br />";
#echo "__DIR__: ==>" . __DIR__;

#$files = scandir(__DIR__);
#$files = array_diff($files, array(".", ".."));
#print_r($files);

$dirs = array_filter(glob('*'), 'is_dir');
#print_r($dirs);

foreach($dirs as $dir){
    echo "<a href=\"$dir\"><img src=\"$dir/thumb.png\" /></a><br />";
}
?>
