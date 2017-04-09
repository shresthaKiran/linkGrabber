<?php namespace App\Services\Guzzle;


use GuzzleHttp\Client;

/**
 * Class HipChat
 * @package App\Services\Guzzle
 */
class HipChat
{
    /**
     * @var Client
     */
    private $client;

    /**
     * HipChat constructor.
     */
    public function __construct()
    {
        $this->client = $this->createClient();

    }

    public function action($uri, $endDate)
    {
        $limitDate = ($endDate == date('Y-m-d')) ? '': date('Y-m-d', strtotime("+1 day", strtotime($endDate)));

        $url       = sprintf(
            'https://yipl.hipchat.com/v2/%s/history?auth_token=%s&end-date=%s&date=%s',
            $uri,
            'nud8E4k2KepgzE6XOrhkIa0wRnmYrUgvQkwOak9n',
            $endDate,
            $limitDate
        );
        $request   = $this->client->request('get', $url);
        
        return $request->getBody()->getContents();
    }


    protected function createClient()
    {
        return new Client(
            [
                'timeout' => 0,
                'verify'  => false
            ]
        );
    }
}

