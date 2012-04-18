<?php

namespace TP\Bundle\TPRestClientBundle\Request;

use TP\Bundle\TPRestClientBundle\Response\Response;

/**
 * Base Request
 *
 * @author Nicolas Brousse <nicolas@trackline-project.net>
 */
class Request
{
    private $curl;
    private $base_url;
    private $path;
    protected $datas;
    private $queries;
    private $curlopts;

    private $format;

    private $response;


    /**
     * Statics methods
     */
    public static function head($base_url=null)
    {
        if (!empty($base_url)) $this->setBaseUrl($base_url);

        $request = new Request();
        $this->addCurlopt(CURLOPT_CUSTOMREQUEST, 'HEAD');
        return $request;
    }

    public static function get($base_url=null)
    {
        if (!empty($base_url)) $this->setBaseUrl($base_url);

        $request = new Request();
        $this->addCurlopt(CURLOPT_CUSTOMREQUEST, 'GET');
        return $request;
    }

    public static function post($base_url=null)
    {
        if (!empty($base_url)) $this->setBaseUrl($base_url);

        $request = new Request();
        $this->addCurlopt(CURLOPT_CUSTOMREQUEST, 'POST');
        return $request;
    }

    public static function put($base_url=null)
    {
        if (!empty($base_url)) $this->setBaseUrl($base_url);

        $request = new Request();
        $this->addCurlopt(CURLOPT_CUSTOMREQUEST, 'PUT');
        return $request;
    }

    public static function delete($base_url=null)
    {
        if (!empty($base_url)) $this->setBaseUrl($base_url);

        $request = new Request();
        $this->addCurlopt(CURLOPT_CUSTOMREQUEST, 'DELETE');
        return $request;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        if (!function_exists('curl_init')) {
            throw new Exception('CURL module not available! Pest requires CURL. See http://php.net/manual/en/book.curl.php');
        }

        $this->addCurlopt(CURLOPT_CUSTOMREQUEST,    'GET'); // set Get as default HTTP Method
        $this->addCurlopt(CURLOPT_RETURNTRANSFER,   true);  // return result instead of echoing
        $this->addCurlopt(CURLOPT_SSL_VERIFYPEER,   false); // stop cURL from verifying the peer's certificate
        $this->addCurlopt(CURLOPT_FOLLOWLOCATION,   true);  // follow redirects, Location: headers
        $this->addCurlopt(CURLOPT_MAXREDIRS,        10);    // but dont redirect more than 10 times

        /**
         * TODO set Base Url with config.yml
         */
        $this->setBaseUrl();
    }

    /**
     * Set Request and Response format
     */
    public function setFormat($format)
    {
        if (in_array($format, array("xml", "json")))
        return $this;
    }

    /**
     * Authentication
     */
    public function setAuth($user, $pass, $auth = 'basic')
    {
        /**
         * @todo Create a real Auth namespace
         */
        Authentication::setHTTPAuth($this, $user, $pass, $auth);
        return $this;
    }

    /**
     * Set Url
     */
    public function setBaseUrl($base_url)
    {
        $this->base_url = $base_url;
        return $this;
    }
    public function setPath($path, array $pathParams=array())
    {
        $this->path = !preg_match("#^/#", $path) ? "/".$path : $path;

        foreach($pathParams as $k=>$v) {
            $this->path = preg_replace("#:".$k."#", $v, $this->path);
        }
        $this->path = preg_replace("#(:([A-Za-z0-9_]))#", "", $this->path);

        return $this;
    }


    /**
     * Add or Set Headers
     */
    public function addHeader($header)
    {
        $this->headers[] = $header;
        return $this;
    }
    public function addHeaders(array $headers)
    {
        foreach($headers as $header) {
            $this->addHeader($header);
        }
        return $this;
    }


    /**
     * Add or Set Datas and Get datas
     */
    public function addData($data)
    {
        $this->datas .= (is_array($data)) ? http_build_query($data) : $data;
        return $this;
    }
    public function setDatas($datas)
    {
        // foreach($datas as $data) {
        //     $this->addData($data);
        // }
        $this->datas = (is_array($data)) ? http_build_query($data) : $data;
        return $this;
    }
    protected function getDatas()
    {
        return $this->datas;
    }


    /**
     * Add or Set Queries
     */
    public function addQuery($query)
    {
        $this->queries[] = $query;
        return $this;
    }
    public function addQueries(array $queries)
    {
        foreach($queries as $query) {
            $this->addQuery($query);
        }
        return $this;
    }


    /**
     * Add a Curl option
     */
    public function addCurlopt($type, $content)
    {
        $this->curlopts[$type] = $content;
    }


    /**
     * Execute Request
     */
    public function execute()
    {
        // Prepare request
        if (strncmp($this->path, $this->base_url, strlen($this->base_url)) != 0) {
            $url = $this->base_url . $this->path;
        }
        else {
            $url = $this->path;
        }
        $this->curl = curl_init($url);

        foreach ($this->curlopts as $opt=>$val) {
            curl_setopt($this->curl, $opt, $val);
        }

        $this->addCurlopt(CURLOPT_HTTPHEADER, $this->headers);
        $this->curl = $this->prepRequest($this->curlopts, $url);

        // If request is post or put, we add the datas
        if ($this->curlopts[CURLOPT_CUSTOMREQUEST] == 'POST' || $this->curlopts[CURLOPT_CUSTOMREQUEST] == 'PUT')
        {
            $this->addHeader('Content-Length: '.strlen($this->getDatas()));
            $this->addCurlopt(CURLOPT_POSTFIELDS, $this->getDatas());
        }

        // Do the request
        $meta = curl_getinfo($this->curl);
        $body = curl_exec($this->curl);

        switch ($this->format) {
            case 'json':
                $this->response = new JsonResponse($meta, $body);
                break;

            case 'xml':
                $this->response = new XmlResponse($meta, $body);
                break;
            
            default:
                $this->response = new Response($meta, $body);
                break;
        }

        curl_close($this->curl);
        
        return $this->response;
    }


    /**
     * Get the response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
