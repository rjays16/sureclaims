<?php

namespace App\GraphQL\Query;

use App\GraphQL\Concerns\ResolvesQueryFields;
use App\GraphQL\PaginatesResults;
use App\Model\Repositories\Contracts\ReturnDocumentRepository;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;

class ReturnDocumentsQuery extends Query
{
    use ResolvesQueryFields, PaginatesResults;

    /** @var ReturnDocumentRepository */
    private $returnDocuments;

    /**
     * ReturnDocumentsQuery constructor.
     *
     * @param array $attributes
     * @param ReturnDocumentRepository $returnDocuments
     */
    public function __construct(
        $attributes = [],
        ReturnDocumentRepository $returnDocuments
    )
    {
        parent::__construct($attributes);
        $this->returnDocuments = $returnDocuments;
    }

    protected $attributes = [
        'name' => 'returnDocuments',
        'description' => 'Retrieves the list of return documents attached to a claim'
    ];

    public function type()
    {
        return Type::listOf(Type::nonNull(GraphQL::type('ReturnDocument')));
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
        $this->returnDocuments->resetScope();
        ['includes' => $includes, 'fieldSets' => $fieldSets] =
            $this->resolveQueryFields($info);

        $this->returnDocuments
            ->parsePresenterIncludes($includes)
            ->orderBy('created_at', 'desc');

        $claimId = @$args['claim'];
        $patientId = @$args['patient'];
        $supportDocumentId = @$args['supportDocumentId'];

        $this->returnDocuments->scopeQuery(function (Builder $query) use ($claimId, $patientId, $supportDocumentId) {
            if ($claimId) {
                $query = $query->where('claim_id', '=', $claimId);
            } elseif ($patientId) {
                $query = $query->where('patient_id', '=', $patientId);
            } elseif ($supportDocumentId) {
                $query = $query->where('support_document_id', '=', $supportDocumentId);
            }
            return $query;
        });

        $result = array_get($this->paginate($this->returnDocuments, 1, 100), 'returnDocuments');
        return $result;
    }
}
