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
    // $response = Request::get()->setPath("user/:uuid", array("uuid", $uuid));
    //                           // ->setQuery()
    //                           // ->setBody();
    private $curl;
    private $base_url;
    private $path;
    private $datas;
    private $queries;
    private $curl_opts;


    /**
     * Statics methods
     */
    public static function head()
    {
        $request = new Request();
        $this->addCurlopt(CURLOPT_CUSTOMREQUEST, 'HEAD');
        return $request;
    }

    public static function get()
    {
        $request = new Request();
        $this->addCurlopt(CURLOPT_CUSTOMREQUEST, 'GET');
        return $request;
    }

    public static function post()
    {
        $request = new Request();
        $this->addCurlopt(CURLOPT_CUSTOMREQUEST, 'POST');
        return $request;
    }

    public static function put()
    {
        $request = new Request();
        $this->addCurlopt(CURLOPT_CUSTOMREQUEST, 'PUT');
        return $request;
    }

    public static function delete()
    {
        $request = new Request();
        $this->addCurlopt(CURLOPT_CUSTOMREQUEST, 'DELETE');
        return $request;
    }


    /**
     * Constructor
     */
    public function __constructor()
    {
        if (!function_exists('curl_init')) {
            throw new Exception('CURL module not available! Pest requires CURL. See http://php.net/manual/en/book.curl.php');
        }

        $this->addCurlopt(CURLOPT_CUSTOMREQUEST,    'GET'); // set Get as default HTTP Method
        $this->addCurlopt(CURLOPT_RETURNTRANSFER,   true);  // return result instead of echoing
        $this->addCurlopt(CURLOPT_SSL_VERIFYPEER,   false); // stop cURL from verifying the peer's certificate
        $this->addCurlopt(CURLOPT_FOLLOWLOCATION,   true);  // follow redirects, Location: headers
        $this->addCurlopt(CURLOPT_MAXREDIRS,        10);    // but dont redirect more than 10 times
    }


    /**
     * Set Url path
     */
    public function setPath($path, array $pathParams=array())
    {
        $this->path = !preg_match("#^/#", $path) ? "/".$path : $path;
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
    public function setHeaders(array $headers)
    {
        foreach($headers as $header) {
            $this->addHeader($header);
        }
        return $this;
    }


    /**
     * Add or Set Datas
     */
    public function addData($data)
    {
        $this->datas[] = (is_array($data)) ? http_build_query($data) : $data;
        return $this;
    }
    public function setDatas(array $datas)
    {
        foreach($datas as $data) {
            $this->addData($data);
        }
        return $this;
    }


    /**
     * Add or Set Queries
     */
    public function addQuery($query)
    {
        $this->queries[] = $query;
        return $this;
    }
    public function setQueries(array $queries)
    {
        foreach($queries as $query) {
            $this->addQuery($query);
        }
        return $this;
    }


    /**
     * Execute Request
     */
    public function execute()
    {
        $this->addCurlopt(CURLOPT_HTTPHEADER, $this->headers);
        $this->curl = $this->prepRequest($this->curl_opts, $url);

        // If request is post or put, we add the datas
        if ()
        {
            $this->addHeader('Content-Length: '.strlen($data));
            $this->addCurlopt(CURLOPT_POSTFIELDS, $data);
        }

        // Do the request
        $meta = curl_getinfo($curl);
        $body = curl_exec($curl);
        $this->last_response = new Response($meta, $body);
        curl_close($this->curl);
        
        return $this->processBody($body);
    }


    /**
     * Add a Curl option
     */
    private function addCurlopt($type, $content)
    {
        $this->curl_opts[$type] = $content;
    }



    /**
     * TODO remove it
     */
    protected function processBody($body) {
        // Override this in classes that extend Pest.
        // The body of every GET/POST/PUT/DELETE response goes through 
        // here prior to being returned.
        return $body;
    }
}
