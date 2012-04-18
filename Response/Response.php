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
    private $throw_exceptions = true;

    public function __construct($meta, $body)
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

    public function getStatus()
    {
        return $this->meta['http_code'];
    }

    public function disable_exceptions()
    {
        $this->throw_exceptions = false;
    }

    public function enable_exceptions()
    {
        $this->throw_exceptions = true;
    }

    private function processError()
    {
        return $this->body;
    }


    protected function checkErrors() {
        if (!$this->throw_exceptions) {
            return;
        }
        if (!$this->meta) {
            return;
        }

        $err = null;
        switch ($this->meta['http_code']) {
            case 400:
                throw new BadRequest($this->processError());
                break;
            case 401:
                throw new Unauthorized($this->processError());
                break;
            case 403:
                throw new Forbidden($this->processError());
                break;
            case 404:
                throw new NotFound($this->processError());
                break;
            case 405:
                throw new MethodNotAllowed($this->processError());
                break;
            case 409:
                throw new Conflict($this->processError());
                break;
            case 410:
                throw new Gone($this->processError());
                break;
            case 422:
                // Unprocessable Entity -- see http://www.iana.org/assignments/http-status-codes
                // This is now commonly used (in Rails, at least) to indicate
                // a response to a request that is syntactically correct,
                // but semantically invalid (for example, when trying to 
                // create a resource with some required fields missing)
                throw new InvalidRecord($this->processError());
                break;
            default:
                if ($meta['http_code'] >= 400 && $meta['http_code'] <= 499) {
                    throw new ClientError($this->processError());
                }
                elseif ($meta['http_code'] >= 500 && $meta['http_code'] <= 599) {
                    throw new ServerError($this->processError());
                }
                elseif (!$meta['http_code'] || $meta['http_code'] >= 600) {
                    throw new UnknownResponse($this->processError());
                }
            }
        }
    }
}

class Exception extends \Exception {}
class UnknownResponse extends Exception {}

/* 401-499 */   class ClientError       extends Exception {}
/* 400 */       class BadRequest        extends ClientError {}
/* 401 */       class Unauthorized      extends ClientError {}
/* 403 */       class Forbidden         extends ClientError {}
/* 404 */       class NotFound          extends ClientError {}
/* 405 */       class MethodNotAllowed  extends ClientError {}
/* 409 */       class Conflict          extends ClientError {}
/* 410 */       class Gone              extends ClientError {}
/* 422 */       class InvalidRecord     extends ClientError {}

/* 500-599 */   class ServerError       extends Exception {}
