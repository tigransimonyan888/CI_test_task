<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json_response
{

    /**
     * @var array
     */
    private  $statusTexts = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',            // RFC2518
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',          // RFC4918
        208 => 'Already Reported',      // RFC5842
        226 => 'IM Used',               // RFC3229
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',    // RFC7238
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',                                               // RFC2324
        421 => 'Misdirected Request',                                         // RFC7540
        422 => 'Unprocessable Entity',                                        // RFC4918
        423 => 'Locked',                                                      // RFC4918
        424 => 'Failed Dependency',                                           // RFC4918
        425 => 'Reserved for WebDAV advanced collections expired proposal',   // RFC2817
        426 => 'Upgrade Required',                                            // RFC2817
        428 => 'Precondition Required',                                       // RFC6585
        429 => 'Too Many Requests',                                           // RFC6585
        431 => 'Request Header Fields Too Large',                             // RFC6585
        451 => 'Unavailable For Legal Reasons',                               // RFC7725
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates (Experimental)',                      // RFC2295
        507 => 'Insufficient Storage',                                        // RFC4918
        508 => 'Loop Detected',                                               // RFC5842
        510 => 'Not Extended',                                                // RFC2774
        511 => 'Network Authentication Required',                             // RFC6585
    );

    /**
     * @var array
     */
    private $responseBody;
    
    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var string
     */
    private $statusText;

    function __construct()
    {
        $this->CI =& get_instance();
    }

    /**
     *  If request is not an ajax
     */
    public function check_ajax_request (){
        if (!$this->CI->input->is_ajax_request()){
            $this->_json_response(null, 400);
        }
    }
    
    public function json_response ($data){
        $this->_json_response($data);
    }


    private function _json_response ($response_data = array(), $response_code = null, $response_message = null){

        $this->statusCode = $response_code;

        if ( ($response_message === null && $response_code === null) || !array_key_exists($response_code, $this->statusTexts )) {
            $this->statusCode = 200;
        }

        $this->statusText = $this->statusTexts[$this->statusCode];

        header('Content-Type: application/json; charset=UTF-8');
        header('HTTP/1.1 ' . $this->statusCode . ' ' . $this->statusText);

        $this->responseBody = (count($response_data) > 0) ? $response_data : array('message' => $this->statusText, 'code' => $this->statusCode);
        
        die(json_encode($this->responseBody));
    }
}