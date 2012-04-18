<?php

namespace TP\Bundle\TPRestClientBundle\Request;

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

    public static function head()
    {
        $request = new Request();
        return $request;
    }

    public static function get()
    {
        $request = new Request();
        return $request;
    }

    public static function post()
    {
        $request = new Request();
        return $request;
    }

    public static function put()
    {
        $request = new Request();
        return $request;
    }

    public static function delete()
    {
        $request = new Request();
        return $request;
    }

    public function setPath($path, array $pathParams=array())
    {
        return $this;
    }

    public function setQuery(array $query)
    {
        return $this;
    }

    public function setBody(array $body)
    {
        return $this;
    }

}
