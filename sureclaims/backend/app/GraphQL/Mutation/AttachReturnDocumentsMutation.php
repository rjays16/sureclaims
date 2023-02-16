<?php

namespace App\GraphQL\Mutation;

use App\Model\Entities\ReturnDocument;
use App\Model\Repositories\Contracts\ClaimRepository;
use App\Model\Repositories\Contracts\ReturnDocumentRepository;
use App\Model\Repositories\Contracts\SupportingDocumentRepository;
use Folklore\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;

class AttachReturnDocumentsMutation extends Mutation
{
    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'createReturnDocument',
        'description' => 'Attaches a return document to a claim'
    ];

    /**
     * @var ReturnDocumentRepository
     */
    private $returnDocs;

    /**
     * @var SupportingDocumentRepository
     */
    private $supportingDocs;

    /**
     * @var ClaimRepository
     */
    private $claimRepository;

    /**
     * AttachReturnDocumentsMutation constructor.
     *
     * @param array $attributes
     * @param ReturnDocumentRepository $returnDocumentRepository
     * @param SupportingDocumentRepository $supportingDocs
     * @param ClaimRepository $claimRepository
     * @internal param ReturnDocumentRepository $returnDocs ;
     */
    public function __construct(
        $attributes = [],
        ReturnDocumentRepository $returnDocumentRepository,
        SupportingDocumentRepository $supportingDocs,
        ClaimRepository $claimRepository
    ) {
        parent::__construct($attributes);
        $this->returnDocs = $returnDocumentRepository;
        $this->claimRepository = $claimRepository;
        $this->supportingDocs = $supportingDocs;
    }

    /**
     * @return Type
     */
    public function type()
    {
        return Type::listOf(Type::nonNull(GraphQL::type('ReturnDocument')));
    }

    /**
     * @return array
     */
    public function args()
    {
        return [
            'documents' => [
                'name' => 'documents',
                'type' => Type::listOf(Type::nonNull(GraphQL::type('ReturnDocumentInput'))),
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

        $this->returnDocs->resetScope();
        $this->supportingDocs->resetScope();

        $results = [];
        foreach ($args['documents'] as $documentArgs) {
            $result[] = $this->supportingDocs->update([
                'claim_id' => $documentArgs['claimId'],
                'patient_id' => $documentArgs['patientId'],
                'document_type' => $documentArgs['documentType'],
                'file_name' => $documentArgs['fileName'],
            ], $documentArgs['id']);

            $rth[] = $this->returnDocs->create([
                'claim_id' => $documentArgs['claimId'],
                'patient_id' => $documentArgs['patientId'],
                'support_document_id' => $documentArgs['id']
            ]);
        }

        $claim = $this->claimRepository->skipPresenter()->find($documentArgs['claimId']);
        $claim->save();

        \DB::commit();

        return $results;
    }
}
