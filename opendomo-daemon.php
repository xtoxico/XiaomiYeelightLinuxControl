<?php
$clickLuzTv = false;
$clickLuzLampi = false;
$lastStateLuzTv = false;
$lastStateLuzLampi = false;
exec("./yeelight-scene.sh 0 Off");
$realStateLuzTv = false;

while (true):   
    $stream = stream_socket_client("tcp://192.168.1.200:1729",$errno,$errstr);

    if (!$stream){
        echo "{$errno}: {$erstr}\n";
        die();
    }
    fwrite($stream,"ver");
    stream_socket_shutdown($stream, STREAM_SHUT_WR);
    fclose($stream);
    $stream = stream_socket_client("tcp://192.168.1.200:1729",$errno,$errstr);
    if (!$stream){
        echo "{$errno}: {$erstr}\n";
        die();
    }
    fwrite($stream,"lst");
    stream_socket_shutdown($stream, STREAM_SHUT_WR);
    $contents = stream_get_contents($stream);
    fclose($stream);

    if (!strrpos($contents,"LuzTV:OFF")){
        $clickLuzTv=false;
        //echo "encendida";
    }else{
        $clickLuzTv=true;
       //echo "apagada";
    }

    if ($lastStateLuzTv!=$clickLuzTv){
        $lastStateLuzTv=$clickLuzTv;
        if ($realStateLuzTv){
            echo "apagando";
            exec("./yeelight-toggle.sh 0");
            $realStateLuzTv=false;
        }else{
            echo "encendiendo";
            exec("./yeelight-toogle.sh 0 On");
            $realStateLuzTv=true;
        }
    }
    //echo $contents;
endwhile;

?>

