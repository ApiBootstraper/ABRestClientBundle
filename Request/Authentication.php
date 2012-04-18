<?php

namespace TP\Bundle\TPRestClientBundle\Request;

use TP\Bundle\TPRestClientBundle\Response\Response;

/**
 * Request Authentication
 *
 * @author Nicolas Brousse <nicolas@trackline-project.net>
 */
class Authentication
{
    public static function setHTTPAuth(Request $request, $user, $pass, $auth='basic')
    {
        $request->addCurlopt(CURLOPT_HTTPAUTH,  constant('CURLAUTH_'.strtoupper($auth)));
        $request->addCurlopt(CURLOPT_USERPWD,   "$user:$pass");
    }
}
