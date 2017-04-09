<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Services\Guzzle\HipChat;
use App\Services\HipChat\LinkParser;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Link;

/**
 * Class HomeController
 * @package App\Http\Controllers
 */
class HomeController extends Controller
{
    /**
     * @var HipChat
     */
    protected $chat;
    /**
     * @var LinkParser
     */
    protected $linkParser;

    /**
     * Create a new controller instance.
     * @param HipChat    $chat
     * @param LinkParser $linkParser
     * @internal param HipChat $chat
     */
    public function __construct(HipChat $chat, LinkParser $linkParser)
    {
        $this->chat       = $chat;
        $this->linkParser = $linkParser;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = $this->getData(date('Y-m-d'));

        return view('home', compact('data'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|string|\Symfony\Component\HttpFoundation\Response
     */
    public function byDate(Request $request)
    {
        $date = $request->get('date');
        $data = $this->getData($date);

        return (count($data) > 0) ? view('table', compact('data'))->render() : response(['status' => false]);
    }

    /**
     * @param $data
     * @param $date
     */
    protected function storeInJson($data, $date)
    {
        if (!file_exists($this->getPath())) {
            exec(mkdir('Message'));
        }

        $path = $this->getPath($date);
        file_put_contents($path, json_encode($data));
    }

    /**
     * @param $filename
     * @return string
     */
    protected function getPath($filename = null)
    {
        if ($filename) {
            return sprintf('%s/%s.json', public_path('Message'), $filename);
        }

        return public_path('Message');
    }

    /**
     * @param $date
     * @return bool
     */
    protected function isFilePresent($date)
    {
        return (file_exists($this->getPath($date))) ? true : false;
    }

    /**
     * @param $date
     * @return mixed
     */
    protected function getStoredData($date)
    {
        return json_decode(file_get_contents($this->getPath($date)), true);
    }

    /**
     * @param $date
     * @return array|mixed
     */
    protected function getData($date)
    {
        if ((!$this->isFilePresent($date)) || $date == date('Y-m-d')) {
            $response = $this->chat->action('room/688910', $date);
            $data     = $this->linkParser->parse(json_decode($response, true));
                        (!$data) ?: $this->storeInJson($data, $date);

            return $data;
        }

        return $this->getStoredData($date);
    }
}

