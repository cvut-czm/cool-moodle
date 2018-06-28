<?php
/**
 * Created by PhpStorm.
 * User: frycj
 * Date: 26/06/2018
 * Time: 14:05
 */

namespace local_cool;


class ocurl
{
    const METHOD_POST='POST';
    const METHOD_GET='GET';
    const METHOD_PUT='PUT';

    private $ch;
    private $url=null;
    private $query=null;

    public function __construct()
    {
        $this->ch=curl_init();
    }
    public static function create() : ocurl
    {
        return new ocurl();
    }

    public function set_url(string $url)
    {
        $this->url=$url;
        curl_setopt($this->ch, CURLOPT_URL, $this->url.($this->query==null?'':'?'.$this->query));
    }
    public function set_query(array $query)
    {
        $this->query=http_build_query($query);
        if($this->url!=null)
            curl_setopt($this->ch, CURLOPT_URL, $this->url.'?'.$this->query);
    }
    public function set_method(string $method)
    {
        switch ($method)
        {
            case self::METHOD_POST:
                curl_setopt($this->ch,CURLOPT_POST,true);
            case self::METHOD_GET:
                curl_setopt($this->ch,CURLOPT_POST,false);
            default:
                throw new todo();
        }
    }
}