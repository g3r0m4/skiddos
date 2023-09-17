<?php

function partition( $list, $p ) {
        $listlen = count( $list );
        $partlen = floor( $listlen / $p );
        $partrem = $listlen % $p;
        $partition = array();
        $mark = 0;
        for ($px = 0; $px < $p; $px++) {
                $incr = ($px < $partrem) ? $partlen + 1 : $partlen;
                $partition[$px] = array_slice( $list, $mark, $incr );
                $mark += $incr;
        }
        return $partition;
}

if($argc != 5)
{
    exit("Usage: php file.php input.lst output.lst threads size.to.filter(eg 5000)\r\n");
}
$childcount = $argv[3];
$part = array();
$array = file($argv[1], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$array = array_unique($array);
if($childcount < 1) exit("Invalid thread count!\r\n");
echo "Count before: ".count($array)."\r\n";
if($childcount > count($array)) $childcount = count($array);
$part = partition($array, $childcount);
file_put_contents($argv[2], "");

for($i = 0; $i < $childcount; $i ++)
{
        $pid = pcntl_fork();
        if ($pid == -1) {
                echo "failed to fork on loop $i of forking\n";
                exit;
        } else if ($pid) {
                continue;
        } else {
                $buf = "a";
                    foreach($part[$i] as $ip)
                    {
                list($ip, $oldsize) = explode(" ", $ip);
                            $fp = fsockopen("udp://".$ip, 19);
                            socket_set_timeout($fp, 2);
                            fwrite($fp, $buf);
                            $size = 0;
                            while(($data = fgets($fp, 4096)) != "")
                            {
                                    $size += strlen($data);
                                }
                                if($size > $argv[4])
                                {
                                        file_put_contents($argv[2], $ip." ".$size."\r\n", FILE_APPEND);
                            }
            }
            $q++;
                die;
        }
}

for($j = 0; $j < $childcount; $j++)
{
        $pid = pcntl_wait($status);
}

$after = count(file($argv[2], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));
echo "Count after: ".$after."\r\n";