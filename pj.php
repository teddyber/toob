<?php
$headers = array('Content-Type: ', 'Content-Length: ');
$file = file_get_contents($_GET['url'], false, null, 0, 0);
foreach ($http_response_header as $header) {
    // echo $header . "\n";
    // echo substr($header, 0, strlen('Content-Length: '))."\n";
    foreach ($headers as $key) {
        if (substr($header, 0, strlen($key)) == $key) {
            $r[$key] = substr($header, strlen($key));
        }
    }
}
if ($r['Content-Length: '] < 2000000 && substr($r['Content-Type: '], 0, 5) == 'image') {
    header('Content-Type: ' . $r['Content-Type: ']);
    echo base64_encode(file_get_contents($_GET['url'], false, null, 0, $r['Content-Length: ']));
}
die;
?>