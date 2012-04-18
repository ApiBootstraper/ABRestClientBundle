<?php

namespace TP\Bundle\TPRestClientBundle\Response;

/**
 * Json Response
 *
 * @author Nicolas Brousse <nicolas@trackline-project.net>
 */
class JsonResponse extends Response
{
    public function getBody()
    {
        return json_decode($this->body);
    }
}
