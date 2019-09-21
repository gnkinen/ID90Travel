<?php
function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();
    
    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

            if($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
            
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            curl_setopt($curl, CURLOPT_URL, $url);
            break;
        case "GET":
            
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($curl, CURLOPT_MAXREDIRS, 4);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($curl, CURLOPT_HTTPGET, TRUE); 
            curl_setopt($curl, CURLOPT_POST, FALSE);
                        
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
                $result = file_get_contents($url);
                //print_r($result);exit;
    }


    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");    
    
    $result = curl_exec($curl);
    
    curl_close($curl);

    $arr_result = json_decode($result, true);    

    return $arr_result;
}
?>