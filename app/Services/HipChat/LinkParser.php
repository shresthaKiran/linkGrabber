<?php namespace App\Services\HipChat;


/**
 * Class LinkParser
 * @package App\Services\HipChat
 */
class LinkParser
{
    /**
     * @param $response
     * @return array
     */
    public function parse($response)
    {
        $items = $response['items'];
        $data  = [];

        foreach ($items as $index => $item) {
            if (($link = $this->containsLinkInMessage($item)) && (($user = $this->getLinkOwner($item)) != "")) {
                $data[$index]['link']     = $link;
                $data[$index]['sharedBy'] = $user;
            }
        }

        return $data;
    }

    /**
     * @param $item
     * @return mixed|string
     */
    protected function getLinkOwner($item)
    {
        if (array_key_exists('from', $item)) {
            return array_get($item, 'from.name', '');
        }

        return '';
    }

    /**
     * @param $item
     * @return bool
     */
    protected function containsLinkInMessage($item)
    {
        $message = array_get($item, 'message', '');
        $pattern = '/(https?:\/\/)([\da-z\.]+)\.([a-z\.]{2,6})([\/\w -]*)*([\?\w+\=])*/';

        preg_match($pattern, $message, $matches);

        return (count($matches) > 0) ? $matches[0] : false;
    }
}

