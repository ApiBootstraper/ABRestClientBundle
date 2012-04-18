<?php

namespace TP\Bundle\TPRestClientBundle\Request;

class JsonRequest extends Request
{
    public function __construct()
    {
        $this->setFormat('json');
        $opts[CURLOPT_HTTPHEADER][] = 'Accept: application/json';
        $opts[CURLOPT_HTTPHEADER][] = 'Content-Type: application/json';
        parent::__construct();
    }

    /**
     * Add or Set Datas and Get datas
     */
    public function addData($data)
    {
        $this->datas[] = $data;
        return $this;
    }
    public function setDatas(array $datas)
    {
        $this->datas = $datas;
        return $this;
    }
    protected function getDatas()
    {
        return json_encode($this->datas);
    }
}
