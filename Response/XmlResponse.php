<?php

namespace TP\Bundle\TPRestClientBundle\Response;

/**
 * Xml Response
 *
 * @author Nicolas Brousse <nicolas@trackline-project.net>
 */
class XmlResponse extends Response
{
    public function getBody()
    {
        libxml_use_internal_errors(true);
        if (empty($this->body) || preg_match('/^\s+$/', $this->body))
            return null;

        $xml = simplexml_load_string($this->body);
        
        if (!$xml)
        {
            $err = "Couldn't parse XML response because:\n";
            foreach(libxml_get_errors() as $xml_err) {
                $err .= "\n    - " . $xml_err->message;
            }
            $err .= "\nThe response was:\n";
            $err .= $this->body;
            throw new XmlException($err);
        }
        
        return $xml;
    }

    public function processError() {
        try {
            $xml = $this->processBody($this->body);
            if (!$xml) {
                return $this->body;
            }
          
            $error = $xml->xpath('//error');
          
            if ($error && $error[0])
                return strval($error[0]);
            else
                return $this->body;
        }
        catch (XmlException $e) {
            return $this->body;
        }
    }
}

class XmlException extends Exception {}
