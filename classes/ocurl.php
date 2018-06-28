<?php
/**
 * Created by PhpStorm.
 * User: frycj
 * Date: 26/06/2018
 * Time: 14:05
 */

namespace local_cool;

/**
 * Object CURL
 *
 * Object wrapper for curl functions.
*/
class ocurl
{
    const METHOD_POST='POST';
    const METHOD_GET='GET';
    const METHOD_PUT='PUT';

    private $ch;
    private $url=null;
    private $query=null;

    /**
     * Standard construct.
    */
    public function __construct()
    {
        $this->ch=curl_init();
    }

    /**
     * Returns new ocurl object.
     *
     * @return ocurl
    */
    public static function create() : ocurl
    {
        return new ocurl();
    }

    /**
     * Sets url for curl request.
     *
     * @param string $url Url to be set.
     * @return $this Returns self. (Fluent API)
    */
    public function set_url(string $url)
    {
        $this->url=$url;
        curl_setopt($this->ch, CURLOPT_URL, $this->url.($this->query==null?'':'?'.$this->query));
        return $this;
    }

    /**
     * Sets query parameters.
     *
     * Mapping: ['key'=>'value'] => ?key=value
     *
     * @param string[] $query Query parameters.
     * @return $this Returns self. (Fluent API)
    */
    public function set_query(array $query)
    {
        $this->query=http_build_query($query);
        if($this->url!=null)
            curl_setopt($this->ch, CURLOPT_URL, $this->url.'?'.$this->query);
        return $this;
    }

    /**
     * Sets method type.
     *
     * @see ocurl::METHOD_GET
     * @see ocurl::METHOD_POST
     * @see ocurl::METHOD_PUT
     * @param string $method Method to be set.
     * @return $this
    */
    public function set_method(string $method)
    {
        switch ($method)
        {
            case self::METHOD_POST:
                curl_setopt($this->ch,CURLOPT_POST,true);
                break;
            case self::METHOD_GET:
                curl_setopt($this->ch,CURLOPT_POST,false);
                break;
            default:
                throw new todo();
                break;
        }
        return $this;
    }
}