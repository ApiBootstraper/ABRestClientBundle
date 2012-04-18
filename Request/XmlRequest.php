<?php

namespace TP\Bundle\TPRestClientBundle\Request;

class XmlRequest extends Request
{
    public function __construct()
    {
        $this->setFormat('xml');
        $opts[CURLOPT_HTTPHEADER][] = 'Accept: application/xml';
        $opts[CURLOPT_HTTPHEADER][] = 'Content-Type: application/xml';
        parent::__construct();
    }
}
