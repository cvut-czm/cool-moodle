<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * Object CURL wrapper
 *
 * @package local_cool
 * @category core
 * @copyright 2018 CVUT CZM, Jiri Fryc
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_cool;

defined('MOODLE_INTERNAL') || die();


class ocurl {
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';
    const METHOD_PUT = 'PUT';

    private $ch;
    private $url = null;
    private $query = null;

    /**
     * Standard construct.
     */
    public function __construct() {
        $this->ch = curl_init();
    }

    /**
     * Returns new ocurl object.
     *
     * @return ocurl
     */
    public static function create(): ocurl {
        return new ocurl();
    }

    /**
     * Sets url for curl request.
     *
     * @param string $url Url to be set.
     * @return $this Returns self. (Fluent API)
     */
    public function set_url(string $url) {
        $this->url = $url;
        curl_setopt($this->ch, CURLOPT_URL, $this->url . ($this->query == null ? '' : '?' . $this->query));
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
    public function set_query(array $query) {
        $this->query = http_build_query($query);
        if ($this->url != null) {
            curl_setopt($this->ch, CURLOPT_URL, $this->url . '?' . $this->query);
        }
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
    public function set_method(string $method) {
        switch ($method) {
            case self::METHOD_POST:
                curl_setopt($this->ch, CURLOPT_POST, true);
                break;
            case self::METHOD_GET:
                curl_setopt($this->ch, CURLOPT_POST, false);
                break;
            default:
                throw new todo();
                break;
        }
        return $this;
    }
}