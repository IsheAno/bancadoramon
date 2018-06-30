<?php 

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, "https://api.CloudFlare.com/client/v4/zones/3a25b1a4d75c408d3491608817b55c52/purge_cache");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"purge_everything\":true}");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");


$headers = array();
$headers[] = "X-Auth-Email: mucci@mucci.co";
$headers[] = "X-Auth-Key: 7bef7a18d3f701e5cf2e47d12160c2c7195c6";
$headers[] = "Content-Type: application/json";

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
echo $result;

if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

curl_close ($ch);

?>