<?php

namespace TP\Bundle\TPRestClientBundle\Model;

Abstract class Model
{
    // public function __set($name, $value)
    // {
    //     // $this->data[$name] = $value;
    //     $this->{$name} = $value;
    // }

    // public function __get($name)
    // {
    //     echo "Récupération de '$name'\n";
    //     // if (array_key_exists($name, $this->data)) {
    //     //     return $this->data[$name];
    //     // }
    //     if (property_exists(get_class($this), $name)) {
    //         return $this->{$name};
    //     }

    //     // $trace = debug_backtrace();
    //     // trigger_error(
    //     //     'Propriété non-définie via __get(): ' . $name .
    //     //     ' dans ' . $trace[0]['file'] .
    //     //     ' à la ligne ' . $trace[0]['line'],
    //     //     E_USER_NOTICE);
    //     return null;
    // }

    public function __call($name, $arguments)
    {
        if (property_exists(get_class($this), $name)) {
            return $this->{$name};
        }
        return null;
    }
}
