<?php

/**
 * SupportingDocumentsQuery.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Query;

use App\GraphQL\Concerns\ResolvesQueryFields;
use App\GraphQL\PaginatesResults;
use App\Model\Entities\SupportingDocument;
use App\Model\Repositories\Contracts\SupportingDocumentRepository;
use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL;
use Illuminate\Database\Eloquent\Builder;

/**
 *
 * Description of SupportingDocumentsQuery
 *
 */
class SupportingDocumentsQuery extends Query
{

    use ResolvesQueryFields, PaginatesResults;

    /** @var SupportingDocumentRepository */
    private $supportingDocuments;

    /**
     * SupportingDocumentsQuery constructor.
     *
     * @param array $attributes
     * @param SupportingDocumentRepository $supportingDocuments
     */
    public function __construct(
        $attributes = [],
        SupportingDocumentRepository $supportingDocuments
    ) {
        parent::__construct($attributes);
        $this->supportingDocuments = $supportingDocuments;
    }

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'supportingDocuments',
        'description' => 'Retrieves the list of supporting documents attached to a claim'
    ];

    public function type()
    {
        return Type::listOf(Type::nonNull(GraphQL::type('SupportingDocument')));
    }

    public function args()
    {
        return [
            'claim' => ['name' => 'claim', 'type' => Type::string()],
            'patient' => ['name' => 'patient', 'type' => Type::string()],
            'supportDocumentId' => ['name' => 'supportDocumentId', 'type' => Type::string()],
        ];
    }

    /**
     * @param $root
     * @param $args
     * @param $context
     * @param ResolveInfo $info
     *
     * @return array
     */
    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $this->supportingDocuments->resetScope();
        ['includes' => $includes, 'fieldSets' => $fieldSets] =
            $this->resolveQueryFields($info);

        $this->supportingDocuments
            ->parsePresenterIncludes($includes)
            ->orderBy('file_name', 'asc');

        $claimId = @$args['claim'];
        $patientId = @$args['patient'];
        $supportDocumentId = @$args['supportDocumentId'];

        $this->supportingDocuments->scopeQuery(function (Builder $query) use ($claimId, $patientId, $supportDocumentId) {
            if ($claimId) {
                $query = $query->where('claim_id', '=', $claimId);
            } elseif ($patientId) {
                $query = $query->where('patient_id', '=', $patientId);
            } elseif ($supportDocumentId) {
                $query = $query->where('id', '=', $supportDocumentId);
            }
            return $query;
        });

        $result = array_get($this->paginate($this->supportingDocuments, 1,100), 'supportingDocuments');
        return $result;
    }
}
