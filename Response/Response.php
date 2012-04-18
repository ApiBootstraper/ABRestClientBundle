<?php

namespace TP\Bundle\TPRestClientBundle\Response;

/**
 * Base Response
 *
 * @author Nicolas Brousse <nicolas@trackline-project.net>
 */
class Response
{
    private $meta;
    private $body;

    public function __constructor($meta, $body)
    {
        $this->meta = $meta;
        $this->body = $body;
        $this->checkErrors();
    }

    public function getMeta()
    {
        return $this->meta;
    }

    public function getBody()
    {
        return $this->body;
    }

    private function processError($body)
    {
        // TODO : Remove this function and try to replace it by a callback
        return $body;
    }


    protected function checkErrors() {
        if (!$this->throw_exceptions) {
            return;
        }
          
        $meta = $this->meta;
        $body = $this->body;
        
        if (!$this->meta) {
            return;
        }
        
        $err = null;
        switch ($this->meta['http_code']) {
            case 400:
                throw new BadRequest($this->processError($this->body));
                break;
            case 401:
                throw new Unauthorized($this->processError($this->body));
                break;
            case 403:
                throw new Forbidden($this->processError($this->body));
                break;
            case 404:
                throw new NotFound($this->processError($this->body));
                break;
            case 405:
                throw new MethodNotAllowed($this->processError($this->body));
                break;
            case 409:
                throw new Conflict($this->processError($this->body));
                break;
            case 410:
                throw new Gone($this->processError($this->body));
                break;
            case 422:
                // Unprocessable Entity -- see http://www.iana.org/assignments/http-status-codes
                // This is now commonly used (in Rails, at least) to indicate
                // a response to a request that is syntactically correct,
                // but semantically invalid (for example, when trying to 
                // create a resource with some required fields missing)
                throw new InvalidRecord($this->processError($this->body));
                break;
            default:
                if ($meta['http_code'] >= 400 && $meta['http_code'] <= 499) {
                    throw new ClientError($this->processError($this->body));
                }
                elseif ($meta['http_code'] >= 500 && $meta['http_code'] <= 599) {
                    throw new ServerError($this->processError($this->body));
                }
                elseif (!$meta['http_code'] || $meta['http_code'] >= 600) {
                    throw new UnknownResponse($this->processError($this->body));
                }
            }
        }
    }
}

class Exception extends \Exception { }
class UnknownResponse extends Exception { }

/* 401-499 */ class ClientError extends Exception {}
/* 400 */ class BadRequest extends ClientError {}
/* 401 */ class Unauthorized extends ClientError {}
/* 403 */ class Forbidden extends ClientError {}
/* 404 */ class NotFound extends ClientError {}
/* 405 */ class MethodNotAllowed extends ClientError {}
/* 409 */ class Conflict extends ClientError {}
/* 410 */ class Gone extends ClientError {}
/* 422 */ class InvalidRecord extends ClientError {}

/* 500-599 */ class ServerError extends Exception {}