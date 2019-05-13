<?php

namespace Moonshiner\BrigthenBundle\Services;

class HigherOrderModelProxy
{
    /**
     * The model object being targeted.
     *
     * @var mixed
     */
    public $results;

    /**
     * The factory object being targeted.
     *
     * @var mixed
     */
    public $factoryBuilder;

    /**
     * Create a new proxy instance.
     *
     * @param  mixed  $model
     * @param  mixed  $factory
     * @return void
     */
    public function __construct($results, $factoryBuilder)
    {
        $this->results = $results;
        $this->factoryBuilder = $factoryBuilder;
    }

    /**
     * Dynamically pass method calls to the target.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if ($method === 'withAreaBlock') {
            $this->factoryBuilder->{$method}(...$parameters);;
            return $this;
        }
        return $this->results->{$method}(...$parameters);
    }

}
