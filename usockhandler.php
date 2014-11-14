<?php

if (!extension_loaded('sockets')) {
    die('>>>>>>>>>>>>>>>>>>>>>>>> Socket extension \"sockets\" not loaded.');
}

error_reporting(E_ALL);

set_time_limit(0);

ob_implicit_flush();

$pid = pcntl_fork();

$service_address = '/tmp/tmp_socket';
$service_port = '6002';

if (file_exists($service_address))
{
    echo '>>>>>>>>>>>>>>>>>>>>>>>> Temporary socket already in use: '.$service_address . PHP_EOL;
    echo '>>>>>>>>>>>>>>>>>>>>>>>> Trying to unlink: '.$service_address . PHP_EOL;
    
    if (!unlink($service_address))
    {
        die('>>>>>>>>>>>>>>>>>>>>>>>> Failed to unlink '.$service_address.' (Permission problem)') . PHP_EOL;
    }
    
    echo('>>>>>>>>>>>>>>>>>>>>>>>> Success unlink '.$service_address) . PHP_EOL;
}

if ($pid > 0)
{
     $status = null;
     pcntl_wait($status);
}
else if ($pid == -1)
{
    die("Could not fork!" . PHP_EOL);
}
else
{
    echo ">>>>>>>>>>>>>>>>>>>>>>>> BEGIN" . PHP_EOL;
    
    //$fp = stream_socket_client("unix:///usr/tmp/uds_socket", $errno, $errstr, 30);
    //$stream_socket = @stream_socket_server("unix:///usr/tmp/uds_socket", $errno, $errstr, STREAM_SERVER_BIND | STREAM_SERVER_LISTEN) or die("Cannot create socket.\n");
    
    $stream_socket = socket_create(AF_UNIX, SOCK_STREAM, 0);

    if (!socket_set_option($stream_socket, SOL_SOCKET, SO_REUSEADDR, 1))
    {
        echo '>>>>>>>>>>>>>>>>>>>>>>>> Unable to set option on socket: '. socket_strerror(socket_last_error()) . PHP_EOL;
        die;
    }
    
    if (socket_bind($stream_socket, $service_address) === false)
    {
        die('>>>>>>>>>>>>>>>>>>>>>>>> Unable to bind socket: '. socket_strerror(socket_last_error()) . PHP_EOL);
    }
    
//    if (!socket_listen($stream_socket, 0)) {
//        die('>>>>>>>>>>>>>>>>>>>>>>>> Unable to listen: '. socket_strerror(socket_last_error()) . PHP_EOL);
//    }
    
    
    
    echo ">>>>>>>>>>>>>>>>>>>>>>>> Starting listen" . PHP_EOL;
    
    while (true)
    {
        while (socket_listen($stream_socket, 0))
        {
            $socket_connection = socket_accept($stream_socket);
            
            if ($socket_connection)
            {
                while ($out = socket_read($socket_connection, 2048)) {
                    echo $out;
                    socket_close($socket_connection);
                    break;
                }
                
            }
        }
        
        
        //var_dump(socket_accept($stream_socket), gettype(socket_accept($stream_socket)));
        echo 'x';
        
        
        
        sleep(1);
        
        /*
        if (socket_accept($stream_socket) !== null)
        {
            $connection_client = socket_accept($stream_socket);
            
            if ($connection_client)
            {
                if (!socket_recv($connection_client, $buf, 2048, MSG_CMSG_CLOEXEC))
                {
                    //socket_close($stream_socket);
                }
                else
                {
                    var_dump('x');
                    //socket_close($stream_socket);
                }
            }
        }
        
        socket_close($stream_socket);   
        */
        
        
        
      
        /*
        while (){
            echo $buffer;
        }
        
        if (socket_last_error($stream_socket) == 104) {
            echo "Connection closed";
        }
         * 
         */
    }
    
    
    
    /*
    while (true)
    {
        $socket_accept = socket_accept($stream_socket);
    
        $data_response = null;
        
        while (true)
        {
            if (!$socket_accept)
            {
                die('>>>>>>>>>>>>>>>>>>>>>>>> Unable to accept socket: '. socket_strerror(socket_last_error()) . PHP_EOL);
            }

            echo ">>>>>>>>>>>>>>>>>>>>>>>> Reading" . PHP_EOL;

            $data = socket_read($socket_accept, 10, PHP_BINARY_READ);

            if (strlen($data) > 0)
            {
                $data_response .= $data;
            }
            else
            {
                break;
            }
        }
    }
    */
    
    //echo $data_response;
    
//    //fputs(STDOUT, "Waiting for connections...\n");
//    /*
//    while (true)
//    {
//        if (($msgsock = socket_accept($stream_socket)) !== false)
//        {
//            while (true)
//            {
//                if (false === ($buf = socket_read($msgsock, 2048)))
//                {
//
//                }
//
//                //$stream_socket_packet = stream_socket_recvfrom($stream_socket, 1, 0, $peer);
//                //var_dump($stream_socket_packet);
//                sleep(1);
//
//            }
//        }
//    }
//    */
    
    echo ">>>>>>>>>>>>>>>>>>>>>>>> END" . PHP_EOL;
    exit(0);
}

