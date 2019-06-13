<?php

namespace Moonshiner\BrigthenBundle\Services;

use InvalidArgumentException;

use Pimcore\Model\AbstractModel;
use Pimcore\Model\Document\Tag\Areablock;

class FactoryBuilder
{
    /**
     * The model definitions in the container.
     *
     * @var array
     */
    protected $definitions;

    /**
     * The model definitions in the container.
     *
     * @var array
     */
    protected $definitionMaps;

    /**
     * The model being built.
     *
     * @var string
     */
    protected $class;

    /**
     * The name of the model being built.
     *
     * @var string
     */
    protected $name = 'default';

    /**
     * The page area blocks.
     *
     * @var array
     */
    protected $areaBlocks = [];

    /**
     * The model states.
     *
     * @var array
     */
    protected $states;

    /**
     * The model after making callbacks.
     *
     * @var array
     */
    protected $afterMaking = [];

    /**
     * The model after creating callbacks.
     *
     * @var array
     */
    protected $afterCreating = [];

    /**
     * The states to apply.
     *
     * @var array
     */
    protected $activeStates = [];

    /**
     * The Faker instance for the builder.
     *
     * @var \Faker\Generator
     */
    protected $factory;

    /**
     * The number of models to build.
     *
     * @var int|null
     */
    protected $amount = null;

    protected $areaBlockIndex = 1;

    protected $areaBlock;

    protected $results;

    /**
     * Create an new builder instance.
     *
     * @param  Moonshiner\BrigthenBundle\Service\Factory  $factory
     * @param  string  $class
     * @param  string  $name
     * @param  array  $definitions
     * @param  array  $states
     * @param  array  $afterMaking
     * @param  array  $afterCreating
     * @param  array  $areaBLocks
     * @return void
     */
    public function __construct(
        $factory,
        $class,
        $name,
        array $definitions,
        array $states,
        array $afterMaking,
        array $afterCreating,
        array $areaBLocks,
        array $definitionMaps
    ) {
        $this->factory = $factory;
        $this->name = $name;
        $this->class = $class;
        $this->states = $states;
        $this->definitions = $definitions;
        $this->afterMaking = $afterMaking;
        $this->afterCreating = $afterCreating;
        $this->areaBlocks = $areaBLocks;
        $this->definitionMaps = $definitionMaps;
    }

    /**
     * Set the amount of models you wish to create / make.
     *
     * @param  int  $amount
     * @return $this
     */
    public function times($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Set the state to be applied to the model.
     *
     * @param  string  $state
     * @return $this
     */
    public function state($state)
    {
        return $this->states([$state]);
    }

    /**
     * Set the states to be applied to the model.
     *
     * @param  array|mixed  $states
     * @return $this
     */
    public function states($states)
    {
        $this->activeStates = is_array($states) ? $states : func_get_args();

        return $this;
    }

    /**
     * Create a collection of models and persist them to the database.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function create(array $attributes = [])
    {
        $results = $this->make($attributes);
        $this->results = $results;
        if ($results instanceof AbstractModel) {
            $this->store(collect([$results]));
        } else {
            $this->store($results);
        }



        // if ($results instanceof Model) {
        //     $this->store(collect([$results]));

        //     $this->callAfterCreating(collect([$results]));
        // } else {
        //     $this->store($results);

        //     $this->callAfterCreating($results);
        // }

        return new HigherOrderModelProxy($results, $this);
    }

    /**
     * Set the connection name on the results and store them.
     *
     * @param  \Illuminate\Support\Collection  $results
     * @return void
     */
    protected function store($results)
    {
        $results->each(function ($model) {
            $model->save();
        });
    }

    /**
     * Create a collection of models.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function make(array $attributes = [])
    {
        if ($this->amount === null) {
            $model = $this->makeInstance($attributes);
            return $model;

            // var_dump($model); exit;
            // return tap($this->makeInstance($attributes), function ($instance) {
            //     $this->callAfterMaking(collect([$instance]));
            // });
        }

        if ($this->amount < 1) {
            return (new $this->class)->newCollection();
        }

        $instances = (new $this->class)->newCollection(array_map(function () use ($attributes) {
            return $this->makeInstance($attributes);
        }, range(1, $this->amount)));

        $this->callAfterMaking($instances);

        return $instances;
    }

    /**
     * Define a area block with a given set of attributes.
     *
     * @param  string  $class
     * @param  string  $name
     * @param  callable|array  $attributes
     * @return $this
     */
    public function withAreaBlock($class, $name, $attributes)
    {
        $reflect = new \ReflectionClass($class);
        $blockType = strtolower($reflect->getShortName());
        $blockData[] = [
            'type' => $blockType,
            'key' =>  $this->areaBlockIndex
        ];

        if (isset($this->areaBlock)) {
            $indices  = $this->areaBlock->getData();
            $blockData = array_merge($blockData, $indices);
            $this->areaBlock->delete();
        }

        $this->areaBlock = $this->factory->create(areaBlock::class, [
            'documentId' => $this->results->getId(),
            'dataFromEditmode' => $blockData
        ]);

        $this->factory->create($class, array_merge([
            'name' => "{$name}:{$this->areaBlockIndex}.",
            'documentId' => $this->results->getId(),
        ], $attributes));
        $this->areaBlockIndex++;
    }

    /**
     * Create an array of raw attribute arrays.
     *
     * @param  array  $attributes
     * @return mixed
     */
    public function raw(array $attributes = [])
    {
        if ($this->amount === null) {
            return $this->getRawAttributes($attributes);
        }

        if ($this->amount < 1) {
            return [];
        }

        return array_map(function () use ($attributes) {
            return $this->getRawAttributes($attributes);
        }, range(1, $this->amount));
    }

    /**
     * Get a raw attributes array for the model.
     *
     * @param  array  $attributes
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    protected function getRawAttributes(array $attributes = [])
    {
        if (!isset($this->definitions[$this->class][$this->name])) {
            throw new InvalidArgumentException("Unable to locate factory with name [{$this->name}] [{$this->class}].");
        }

        $definition = call_user_func(
            $this->definitions[$this->class][$this->name],
            $this->faker,
            $attributes
        );

        return $this->expandAttributes(
            array_merge($this->applyStates($definition, $attributes), $attributes)
        );
    }

    /**
     * Make an instance of the model with the given attributes.
     *
     * @param  array  $attributes
     * @return \Pimcore\Model
     */
    protected function makeInstance(array $attributes = [])
    {
        $instance = new $this->class();
        if (isset($this->definitionMaps[$this->class])) {
            return $this->applyMap($this->getRawAttributes($attributes), $this->definitionMaps[$this->class][$this->name]);
            return $instance;
        }
        $instance->setValues($this->getRawAttributes($attributes));
        return $instance;
    }

    protected function applyMap($attributes, $classMaps)
    {
        $models = collect();
        foreach ($classMaps as $attribute => $class) {
            $model = new $class();
            $model->setDataFromResource($attributes[$attribute]);
            $name = $attributes['name'] ? "{$attributes['name']}{$attribute}" : $attribute;
            $model->setName($name);
            $model->setDocumentId($attributes['documentId']);
            $models->add($model);
        }
        return $models;
    }

    /**
     * Apply the active states to the model definition array.
     *
     * @param  array  $definition
     * @param  array  $attributes
     * @return array
     */
    protected function applyStates(array $definition, array $attributes = [])
    {
        foreach ($this->activeStates as $state) {
            if (!isset($this->states[$this->class][$state])) {
                if ($this->stateHasAfterCallback($state)) {
                    continue;
                }

                throw new InvalidArgumentException("Unable to locate [{$state}] state for [{$this->class}].");
            }

            $definition = array_merge(
                $definition,
                $this->stateAttributes($state, $attributes)
            );
        }

        return $definition;
    }

    /**
     * Get the state attributes.
     *
     * @param  string  $state
     * @param  array  $attributes
     * @return array
     */
    protected function stateAttributes($state, array $attributes)
    {
        $stateAttributes = $this->states[$this->class][$state];

        if (!is_callable($stateAttributes)) {
            return $stateAttributes;
        }

        return call_user_func(
            $stateAttributes,
            $this->faker,
            $attributes
        );
    }

    /**
     * Expand all attributes to their underlying values.
     *
     * @param  array  $attributes
     * @return array
     */
    protected function expandAttributes(array $attributes)
    {
        foreach ($attributes as &$attribute) {
            if (is_callable($attribute) && !is_string($attribute) && !is_array($attribute)) {
                $attribute = $attribute($attributes);
            }

            if ($attribute instanceof static) {
                $attribute = $attribute->create()->getKey();
            }

            if ($attribute instanceof Model) {
                $attribute = $attribute->getKey();
            }
        }

        return $attributes;
    }

    /**
     * Run after making callbacks on a collection of models.
     *
     * @param  \Illuminate\Support\Collection  $models
     * @return void
     */
    public function callAfterMaking($models)
    {
        $this->callAfter($this->afterMaking, $models);
    }

    /**
     * Run after creating callbacks on a collection of models.
     *
     * @param  \Illuminate\Support\Collection  $models
     * @return void
     */
    public function callAfterCreating($models)
    {
        $this->callAfter($this->afterCreating, $models);
    }

    /**
     * Call after callbacks for each model and state.
     *
     * @param  array  $afterCallbacks
     * @param  \Illuminate\Support\Collection  $models
     * @return void
     */
    protected function callAfter(array $afterCallbacks, $models)
    {
        $states = array_merge([$this->name], $this->activeStates);

        $models->each(function ($model) use ($states, $afterCallbacks) {
            foreach ($states as $state) {
                $this->callAfterCallbacks($afterCallbacks, $model, $state);
            }
        });
    }

    /**
     * Call after callbacks for each model and state.
     *
     * @param  array  $afterCallbacks
     * @param  \Illuminate\Support\Collection  $models
     * @return void
     */
    protected function createAreaBlocks($pages)
    {
        return;
        $areaBlocks = $this->areaBlocks;

        $pages->each(function ($page) use ($areaBlocks) {
            // $index = 1;
            // foreach ($areaBlocks as $className => $attributes) {
            //     $block = new $className();
            //     $block->setValues($attributes);
            //     $block->setName( "{$page->name}:{$index}.{$attributes['name']}"  );
            //     $index++;
            //     // $this->callAfterCallbacks($afterCallbacks, $model, $state);
            // }
        });
    }

    /**
     * Call after callbacks for each model and state.
     *
     * @param  array  $afterCallbacks
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $state
     * @return void
     */
    protected function callAfterCallbacks(array $afterCallbacks, $model, $state)
    {
        if (!isset($afterCallbacks[$this->class][$state])) {
            return;
        }

        foreach ($afterCallbacks[$this->class][$state] as $callback) {
            $callback($model, $this->faker);
        }
    }

    /**
     * Determine if the given state has an "after" callback.
     *
     * @param  string  $state
     * @return bool
     */
    protected function stateHasAfterCallback($state)
    {
        return isset($this->afterMaking[$this->class][$state]) ||
            isset($this->afterCreating[$this->class][$state]);
    }
}
