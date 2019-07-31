<?php
/**
 * Created by PhpStorm.
 * User: Fry
 * Date: 09.03.2018
 * Time: 11:38
 */

namespace formula_x\parser;


class token
{
    const TOKEN_TYPE_UNKNOWN=-1;
    const TOKEN_TYPE_NOOP=0;
    const TOKEN_TYPE_OPERAND=1;
    const TOKEN_TYPE_FUNCTION=2;
    const TOKEN_TYPE_SUBEXPRESSION=3;
    const TOKEN_TYPE_ARGUMENT=4;
    const TOKEN_TYPE_OPERATOR_PREFIX=5;
    const TOKEN_TYPE_OPERATOR_INFIX=6;
    const TOKEN_TYPE_OPERATOR_POSTFIX=7;
    const TOKEN_TYPE_WHITESPACE=8;

    private static $types_names=[
      -1=>'UNKNOWN',
      0=>'NOOP',
      1=>'OPERAND',
      2=>'FUNCTION',
      3=>'SUBEXPRESSION',
      4=>'ARGUMENT',
      5=>'OPERATOR_PREFIX',
      6=>'OPERATOR_INFIX',
      7=>'OPERATOR_POSTFIX',
      8=>'WHITESPACE'
    ];

    const TOKEN_EVENT_NOTHING=0;
    const TOKEN_EVENT_START=1; //SUBEXPRESSION (x*2)/2 OR FUNCTION
    const TOKEN_EVENT_END=2;
    const TOKEN_EVENT_TEXT=3;
    const TOKEN_EVENT_NUMBER=4;
    const TOKEN_EVENT_LOGICAL=5;
    const TOKEN_EVENT_ERROR=6;
    const TOKEN_EVENT_RANGE=7;
    const TOKEN_EVENT_MATH=8;
    const TOKEN_EVENT_CONCATENATION=9;
    const TOKEN_EVENT_INTERSECTION=10;
    const TOKEN_EVENT_UNION=11;

    private static $event_names=[
        0=>'NOTHING',
        1=>'START',
        2=>'END',
        3=>'TEXT',
        4=>'NUMBER',
        5=>'LOGICAL',
        6=>'ERROR',
        7=>'RANGE',
        8=>'MATH',
        9=>'CONCATENATION',
        10=>'INTERSECTION',
        11=>'UNION'
    ];


    /**
     * Value
     *
     * @var string
     */
    private $_value;

    private $_debug_type;
    private $_debug_event;
    /**
     * Token type
     *
     * @var int
     */
    private $_tokenType=token::TOKEN_TYPE_UNKNOWN;
    /**
     * Token event
     *
     * @var int
    */
    private $_tokenEvent=token::TOKEN_EVENT_NOTHING;

    public function __construct(string $value,int $token_type=token::TOKEN_TYPE_UNKNOWN,int $token_event=token::TOKEN_EVENT_NOTHING)
    {
        $this->_value=$value;
        $this->_tokenType=$token_type;
        $this->_tokenEvent=$token_event;
        $this->_debug_event=self::$event_names[$token_event];
        $this->_debug_type=self::$types_names[$token_type];
    }
    /**
     * Get Value
     *
     * @return string
     */
    public function getValue() {
        return $this->_value;
    }

    /**
     * Set Value
     *
     * @param string $value
     */
    public function setValue($value) {
        $this->_value = $value;
    }

    /**
     * Get Token Type
     *
     * @return int
     */
    public function getTokenType() {
        return $this->_tokenType;
    }

    /**
     * Set Token Type
     *
     * @param int $value
     */
    public function setTokenType(int $value = token::TOKEN_TYPE_UNKNOWN) {
        $this->_tokenType = $value;
        $this->_debug_type=self::$types_names[$value];
    }

    /**
     * Get Token Event
     *
     * @return int
     */
    public function getTokenEvent() {
        return $this->_tokenEvent;
    }

    /**
     * Set Token Event
     *
     * @param int	$value
     */
    public function setTokenEvent(int $value = token::TOKEN_EVENT_NOTHING) {
        $this->_tokenEvent = $value;
        $this->_debug_event=self::$event_names[$value];
    }
}