<?php

        $request = 'mytime:'.time().' end...';
        $client = 1;
        
        $fp = fsockopen("unix:///tmp/tmp_socket", -1, $errno, $errstr, 30);
        if (!$fp) {
            echo "$errstr ($errno)<br />\n";
        } else {
            fwrite($fp, $request);
            while (!feof($fp)) {
                echo fgets($fp, 128);
             }
            fclose($fp);
        }

//
//	  OR USE stream_socket_client PHP API to controll all data
//        set_time_limit(30);
//
//        $wait_only = false;
//
//        $time_set = time();
//
//        if (!$socket = stream_socket_client("unix://tmp/tmp_socket", $errno, $errstr, 5))
//        {
//            die('Socket refused'.PHP_EOL);
//        }
//
//        $message = $request;
//        /*
//        if (!isset($socket))
//        {
//            echo "$errstr ($errno)<br />\n";
//            die;
//        }
//*/
//        if (!$wait_only && strlen($request) > 0)
//        {
//            $e = fwrite($socket, $message);
//        }
//
//        $msg = "";
//        $data = "";
//
//        stream_set_timeout($socket, 2);
//
//        
//        
//        while (1)
//        {
//            
//            
//            $sdata = fread($socket, 512);
//            if (feof($socket))
//            { 
//                // It will skip for timeout
//                break;
//            }
//            
//            $data = $data.$sdata;
//
//            $i = strpos($data, "\n");
//
//            if ($i === false) 
//            {
//                // If timeout has been occured
//
//                $time2 = time();
//                $total_time = $time2-$time_set;
//
//                if ($total_time > 20)
//                {
//                    die('>>>>>>>>>> TIMEOUT EXCEPTION');
//                }
//
//                
//            }
//            else 
//            {
//                $lstr = substr($data, 0, $i);
//                $l = intval($lstr); 
//
//                $data = substr($data, $i+1);
//
//                while (strlen($data) < $l)
//                {
//                    echo "Read: ".strlen($data)." of ".$l."\n";
//                    $sdata = fread($socket, 1);
//                    //echo "SData: ".$sdata." ".$l."\n";
//                    if (feof($socket))
//                        break;
//                    $data = $data.$sdata;
//
//                }
//                break;
//            }
//        }
//
//        fclose($socket);
//        
//        echo '>>>>>>>>>>> DATA START >>>>>>>>>>>'.PHP_EOL;
//        echo $data;
//        echo '>>>>>>>>>>> DATA END >>>>>>>>>>>'.PHP_EOL;