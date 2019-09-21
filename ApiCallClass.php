<?php

namespace API\Http;

class ApiCallClass
{
    private $url;
    private $method;
    private $data;

    /**
     * @param array  $method cURL method
     * @param string $url     Request URL
     * @param array  $mix cURL data
     */
    public function __construct($method, $url, $data = false)
    {
        $this->method = $method;
        $this->url = $url;
        $this->data = $data;
    }

    /**
     * Get the response
     * @return string
     * @throws \RuntimeException On cURL error
     */
    public function __invoke()
    {
        $curl = curl_init();

        switch ($this->method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_URL, $this->url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);

                if($this->data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $this->data);
                break;
                
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                curl_setopt($curl, CURLOPT_URL, $this->url);
                break;
            case "GET":
                
                curl_setopt($curl, CURLOPT_URL, $this->url);
                curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
                curl_setopt($curl, CURLOPT_MAXREDIRS, 4);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($curl, CURLOPT_HTTPGET, TRUE); 
                curl_setopt($curl, CURLOPT_POST, FALSE);
                            
            default:
                if ($this->data)
                    $this->url = sprintf("%s?%s", $this->url, http_build_query($this->data));
                $result = file_get_contents($this->url);
        }
            
        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");    
        
        $result = curl_exec($curl);
        $error = curl_error($curl);
        $errno = curl_errno($curl);        

        if (is_resource($curl)) {
            curl_close($curl);
        }

        if (0 !== $errno) {
            throw new \RuntimeException($error, $errno);
        }

        $arr_result = json_decode($result, true);

        return $arr_result;

    }
}