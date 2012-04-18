<?php

namespace TP\Bundle\TPRestClientBundle\Request;

class XmlRequest extends Request
{
    public function __construct()
    {
        $this->format = 'xml';
        $this->addHeaders(array(
            'Accept: application/xml',
            'Content-Type: application/xml',
        ));
        parent::__construct();
    }
}
