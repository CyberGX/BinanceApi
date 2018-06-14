<?php
class Binance{

	private $apiKey, $apiSecret;
	private $apiBaseUrl = 'https://api.binance.com';
	private $apiPath = '/api';

	function __cunstruct($apiKey, $apiSecret){
		$this->apiKey = $apiKey;
		$this->apiSecret = $apiSecret;
	}

    /**
        General endpoints
    */

    public function exchangeInfo()
    {
        return $this->binanceRequest('/exchangeInfo');
    }

    public function ping()
    {
        return $this->binanceRequest('/v1/ping');
    }


    public function depth($symbol='BTCUSDT', $limit=100)
    {
        return $this->binanceRequest('/v1/depth', [
            'get'=>[
                'symbol'=>$symbol,
                'limit'=>$limit,
            ]
        ]);
    }

    public function trades($symbol='BTCUSDT', $limit=100)
    {
        return $this->binanceRequest('/v1/trades', [
            'get'=>[
                'symbol'=>$symbol,
                'limit'=>$limit,
            ]
        ]);
    }

    public function historicalTrades($symbol='BTCUSDT', $tradeId=null, $limit=500)
    {
        return $this->binanceRequest('/v1/historicalTrades', [
            'get'=>[
                'symbol'=>$symbol,
                'limit'=>$limit,
                'fromId'=>$tradeId
            ]
        ]);
    }


    // If both startTime and endTime are sent, limit should not be sent AND the distance between startTime and endTime must be less than 24 hours.
    // If frondId, startTime, and endTime are not sent, the most recent aggregate trades will be returned.

    public function aggTrades($symbol='BTCUSDT', $tradeId=null, $startTime=null, $endTime=null, $limit=500)
    {
        $request_array = [
            'get'=>[
                'symbol'=>$symbol,
                'limit'=>$limit,
            ]
        ];

        if($tradeId != null)
        {
            $request_array['get']['fromId'] = $tradeId;
        }
        
        if($startTime != null)
        {
            $request_array['get']['startTime'] = $startTime;
        }   
             
        if($endTime != null)
        {
            $request_array['get']['endTime'] = $endTime;
        }

        return $this->binanceRequest('/v1/aggTrades', $request_array);
    }

    // If startTime and endTime are not sent, the most recent klines are returned.
	public function klines($symbol='BTCUSDT', $interval='KLINE_INTERVAL_1MONTH', $startTime=null, $endTime=null, $limit=500)
	{
        $request_array = [
            'get'=>[
                'symbol'=>$symbol,
                'limit'=>$limit,
            ]
        ];

        if($interval != null)
        {
            $request_array['get']['interval'] = $this->enums($interval);
        }
        
        if($startTime != null)
        {
            $request_array['get']['startTime'] = $startTime;
        }   
             
        if($endTime != null)
        {
            $request_array['get']['endTime'] = $endTime;
        }

		return $this->binanceRequest('/v1/klines', $request_array);
	}


    public function ticker24hr($symbol='BTCUSDT')
    {
        return $this->binanceRequest('/v1/ticker/24hr', [
            'get'=>[
                'symbol'=>$symbol,
            ]
        ]);
    }

    public function tickerPrice($symbol='BTCUSDT')
    {
        return $this->binanceRequest('/v3/ticker/price', [
            'get'=>[
                'symbol'=>$symbol,
            ]
        ]);
    }

    public function bookTicker($symbol='BTCUSDT')
    {
        return $this->binanceRequest('/v3/ticker/bookTicker', [
            'get'=>[
                'symbol'=>$symbol,
            ]
        ]);
    }

    /**
        Account endpoints
    */

    /*
        LIMIT   timeInForce, quantity, price
        MARKET  quantity
        STOP_LOSS   quantity, stopPrice
        STOP_LOSS_LIMIT     timeInForce, quantity, price, stopPrice
        TAKE_PROFIT     quantity, stopPrice
        TAKE_PROFIT_LIMIT   timeInForce, quantity, price, stopPrice
        LIMIT_MAKER     quantity, price
    */
    public function order($symbol='BTCUSDT', $side='SIDE_BUY', $type='MARKET', $quantity='100', $timestamp=time(), $optionals_array=[])
    {
        $mandatory_data_array = [
                'symbol'=>$symbol,
                'side'=>$this->enums($side),
                'type'=>$type,
                'quantity'=>$quantity,
                'timestamp'=>$timestamp,
            ];
        $data_array = array_merge($request_data_array, $optionals)

        return $this->binanceRequest('/api/v3/order', [
            'post'=>$data_array
        ]);
    }   

    private function enums($item_key)
    {
        $enums['SYMBOL_TYPE_SPOT'] = 'SPOT';

        $enums['ORDER_STATUS_NEW'] = 'NEW';
        $enums['ORDER_STATUS_PARTIALLY_FILLED'] = 'PARTIALLY_FILLED';
        $enums['ORDER_STATUS_FILLED'] = 'FILLED';
        $enums['ORDER_STATUS_CANCELED'] = 'CANCELED';
        $enums['ORDER_STATUS_PENDING_CANCEL'] = 'PENDING_CANCEL';
        $enums['ORDER_STATUS_REJECTED'] = 'REJECTED';
        $enums['ORDER_STATUS_EXPIRED'] = 'EXPIRED';

        $enums['KLINE_INTERVAL_1MINUTE'] = '1m';
        $enums['KLINE_INTERVAL_3MINUTE'] = '3m';
        $enums['KLINE_INTERVAL_5MINUTE'] = '5m';
        $enums['KLINE_INTERVAL_15MINUTE'] = '15m';
        $enums['KLINE_INTERVAL_30MINUTE'] = '30m';
        $enums['KLINE_INTERVAL_1HOUR'] = '1h';
        $enums['KLINE_INTERVAL_2HOUR'] = '2h';
        $enums['KLINE_INTERVAL_4HOUR'] = '4h';
        $enums['KLINE_INTERVAL_6HOUR'] = '6h';
        $enums['KLINE_INTERVAL_8HOUR'] = '8h';
        $enums['KLINE_INTERVAL_12HOUR'] = '12h';
        $enums['KLINE_INTERVAL_1DAY'] = '1d';
        $enums['KLINE_INTERVAL_3DAY'] = '3d';
        $enums['KLINE_INTERVAL_1WEEK'] = '1w';
        $enums['KLINE_INTERVAL_1MONTH'] = '1M';

        $enums['SIDE_BUY'] = 'BUY';
        $enums['SIDE_SELL'] = 'SELL';

        $enums['ORDER_TYPE_LIMIT'] = 'LIMIT';
        $enums['ORDER_TYPE_MARKET'] = 'MARKET';
        $enums['ORDER_TYPE_STOP_LOSS'] = 'STOP_LOSS';
        $enums['ORDER_TYPE_STOP_LOSS_LIMIT'] = 'STOP_LOSS_LIMIT';
        $enums['ORDER_TYPE_TAKE_PROFIT'] = 'TAKE_PROFIT';
        $enums['ORDER_TYPE_TAKE_PROFIT_LIMIT'] = 'TAKE_PROFIT_LIMIT';
        $enums['ORDER_TYPE_LIMIT_MAKER'] = 'LIMIT_MAKER';

        $enums['TIME_IN_FORCE_GTC'] = 'GTC';  // Good till cancelled
        $enums['TIME_IN_FORCE_IOC'] = 'IOC';  // Immediate or cancel
        $enums['TIME_IN_FORCE_FOK'] = 'FOK';  // Fill or kill

        $enums['ORDER_RESP_TYPE_ACK'] = 'ACK';
        $enums['ORDER_RESP_TYPE_RESULT'] = 'RESULT';
        $enums['ORDER_RESP_TYPE_FULL'] = 'FULL';

        $enums['WEBSOCKET_DEPTH_5'] = '5';
        $enums['WEBSOCKET_DEPTH_10'] = '10';
        $enums['WEBSOCKET_DEPTH_20'] = '20';

        return $enums[$item_key];
    }

    private function signString($string)
    {
        return hash_hmac('sha256', $string, $this->apiSecret);
    }

	private function binanceRequest($method, $dataArray=array(), $signRequest=false)
	{
        $url = $this->apiBaseUrl . $this->apiPath . $method;

        if(!empty($dataArray['get']))
        {
            $queryString =http_build_query($dataArray['get']);
            $url .= '?' . $queryString;
        }

        if($signRequest)
        {
            if(!empty($postArray))
            {
                $postString = http_build_query($dataArray['post']);
                $signString = $this->signString($queryString.$postString);
                $dataArray['post']['signature'] = $signString;
            }
            else
            {
                $signString = $this->signString($queryString);
                $url .= $queryString . '&signature='. $signString;
            }
        }

        $response = $this->httpRequest($url, $dataArray['post']);
		return json_decode($response, true);
	}

    private function httpRequest($url, $postArray=array())
    {
        if(empty($url)){ return false;}

        // Open Connection
        $ch = curl_init();

        // Binance Api Key
        $headers = [
            'X-MBX-APIKEY: '. $this->apiKey,
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        if(!empty($postArray))
        {
            $postArray =http_build_query($postArray);
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$postArray);
        }

        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);

        curl_close($ch);
        return $result;
    }


}



$binance = new Binance('85IaQeVtJJ9xqY1XxiUTw6ymTPoUWxAjKMKIkQVEf2ByoBkkWlenb6haSdrLNVdQ', 'Llfe2nHCec2yKq6lP7b6EOipmQtkzKAoeLAQULw6sx2ZA2lNvWLPqXuGk1SgqXqm');
print_r($binance->bookTicker());