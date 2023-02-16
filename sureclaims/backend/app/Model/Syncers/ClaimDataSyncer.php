<?php

namespace App\Model\Syncers;

use Illuminate\Container\Container;
use App\Model\Entities\Claim;

class ClaimDataSyncer 
{
    /**
     * @var CLaim
     */
    protected $claim;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var array
     */
    private $registry = [];

    /**
     * @var array
     */
    private $result;

    public function __construct(Claim $claim) 
    {
        $this->claim = $claim;
        $this->container = Container::getInstance();
        $this->registry = [
            ProfessionalsDataSanitizer::class
        ];
    }

    protected function formalize() : self
    {
        $this->result = $this->getData();

        foreach ($this->registry as $class) {
            $this->container->make(ProfessionalsDataSanitizer::class)
                ->formalize( $this->result );
        }

        return $this;
    }

    protected function getData(?bool $fresh = false) : array
    {
        if (!$this->result || $fresh) {
            return \GuzzleHttp\json_decode($this->claim->data_json, true);
        }
        return $this->result;
    }

    public function updated() : self
    {
        return $this->formalize();
    }

    public function toArray() : array
    {
        return $this->getData();
    }

    public function toJSON() : string
    {
        return json_encode( $this->getData() );
    }
}
