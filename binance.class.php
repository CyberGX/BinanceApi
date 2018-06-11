<?php
class Binance{

	private $apiKey, $apiSecret;
	private $apiBaseUrl = 'https://api.binance.com';
	private $apiVersionUrl = '/api/v1';

	function __cunstruct($apiKey, $apiSecret){
		$this->apiKey = $apiKey;
		$this->apiSecret = $apiSecret;
	}

	public function exchangeInfo()
	{
		print binanceRequest('/exchangeInfo');
	}

	private function binanceRequest($method)
	{
		return $this->httpRequest($this->apiBaseUrl + $this->apiVersionUrl + $method);
	}

    private function httpRequest($url, $postArray=array(), $upload=null)
    {
        if(empty($url)){ return false;}
        //open connection
        $ch = curl_init();

        if($upload != null)
        {
            $postArray =http_build_query($postArray);
        }

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $this->apiBaseUrl + $url);
        if(!empty($postArray))
        {
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$postArray);
        }

        //curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);

        //echo(curl_error($ch));
        curl_close($ch);
        return $result;
    }

}



$binance = new Binance('85IaQeVtJJ9xqY1XxiUTw6ymTPoUWxAjKMKIkQVEf2ByoBkkWlenb6haSdrLNVdQ', 'Llfe2nHCec2yKq6lP7b6EOipmQtkzKAoeLAQULw6sx2ZA2lNvWLPqXuGk1SgqXqm');
