<?php

namespace App\GraphQL\Mutation;

use App\Model\Entities\Transmittal;
use App\Model\Repositories\Contracts\TransmittalRepository;
use App\Processesors\UploadTransmittalProcessor;
use Folklore\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;

class UploadTransmittalMutation extends Mutation
{
    protected $attributes = [
        'name' => 'uploadTransmittal'
    ];

    /**
     * @var TransmittalRepository
     */
    private $transmittals;
    /**
     * @var UploadTransmittalProcessor
     */
    private $uploader;

    public function __construct(
        $attributes = [],
        TransmittalRepository $transmittal,
        UploadTransmittalProcessor $uploader
    ) {
        $this->transmittals = $transmittal;
        $this->uploader = $uploader;
        parent::__construct($attributes);
    }

    public function type()
    {
        return GraphQL::type('Transmittal');
    }

    /**
     * @return array
     */
    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::nonNull(Type::ID())],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $this->transmittals->resetScope();
        $this->transmittals->skipPresenter(true);

        \DB::beginTransaction();

        /** @var Transmittal $transmittal */
        $transmittal = $this->transmittals->find($args['id']);
        if (empty($transmittal->receipt_ticket_no)) {
            $this->uploader->uploadTransmittal($transmittal);
            $this->uploader->mapTransmittal($transmittal);
        } elseif(!$transmittal->is_mapped) {
            $this->uploader->mapTransmittal($transmittal);
        }

        \DB::commit();

        $this->transmittals->skipPresenter(false);
        return $this->transmittals->present($transmittal);
    }
}
