<?php

/**
 * AttachSupportingDocumentsMutation.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Mutation;

use App\Model\Entities\Claim;
use App\Model\Entities\SupportingDocument;
use App\Model\Repositories\Contracts\ClaimRepository;
use App\Model\Repositories\Contracts\SupportingDocumentRepository;
use Folklore\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;

/**
 * Class AttachSupportingDocumentsMutation
 *
 * @package App\GraphQL\Mutation
 */
class AttachSupportingDocumentsMutation extends Mutation
{
    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'createSupportingDocument',
        'description' => 'Attaches a supporting document to a claim'
    ];

    /**
     * @var SupportingDocumentRepository
     */
    private $supportingDocs;
    /**
     * @var ClaimRepository
     */
    private $claimRepository;

    /**
     * AttachSupportingDocumentsMutation constructor.
     *
     * @param array $attributes
     * @param SupportingDocumentRepository $supportingDocumentRepository
     * @param ClaimRepository $claimRepository
     * @internal param SupportingDocumentRepository $supportingDocs ;
     */
    public function __construct(
        $attributes = [],
        SupportingDocumentRepository $supportingDocumentRepository,
        ClaimRepository $claimRepository
    ) {
        parent::__construct($attributes);
        $this->supportingDocs = $supportingDocumentRepository;
        $this->claimRepository = $claimRepository;
    }

    /**
     * @return Type
     */
    public function type()
    {
        return Type::listOf(Type::nonNull(GraphQL::type('SupportingDocument')));
    }

    /**
     * @return array
     */
    public function args()
    {
        return [
            'documents' => [
                'name' => 'documents',
                'type' => Type::listOf(Type::nonNull(GraphQL::type('SupportingDocumentInput'))),
            ],
        ];
    }

    /**
     * @param $root
     * @param $args
     * @param $context
     * @param ResolveInfo $info
     * @return array
     *
     * @throws \Exception
     */
    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        \DB::beginTransaction();

        $this->supportingDocs->resetScope();

        $results = [];
        foreach ($args['documents'] as $documentArgs) {
            $result[] = $this->supportingDocs->update([
                'claim_id' => $documentArgs['claimId'],
                'patient_id' => $documentArgs['patientId'],
                'document_type' => $documentArgs['documentType'],
                'file_name' => $documentArgs['fileName'],
            ], $documentArgs['id']);
        }

        $claim = $this->claimRepository->skipPresenter()->find($documentArgs['claimId']);
        $claim->save();

        \DB::commit();

        return $results;
    }
}
