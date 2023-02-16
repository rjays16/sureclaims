<?php

namespace App\GraphQL\Query;

use App;
use App\GraphQL\Concerns\ResolvesQueryFields;
use App\GraphQL\PaginatesResults;
use App\Lib\Soap\EclaimsServiceInterface;
use App\Lib\Soap\Exceptions\PhilhealthException;
use App\Model\Entities\Claim;
use App\Model\Repositories\Contracts\ClaimRepository;
use App\Model\Repositories\Contracts\ClaimStatusDetailRepository;
use App\Model\Repositories\Contracts\PersonRepository;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ClaimStatusDetailQuery extends Query
{
    use ResolvesQueryFields, PaginatesResults;

    /** @var ClaimStatusDetailRepository */
    private $claimsStatusDetail;

    /**
     * CreateClaimMutation constructor.
     *
     * @param array $attributes
     * @param EclaimsServiceInterface $eclaimsService
     * @param PersonRepository $persons
     * @param ClaimRepository $claims
     * @param ClaimStatusDetailRepository $claimsStatusDetail
     */
    public function __construct(
        $attributes = [],
        ClaimStatusDetailRepository $claimsStatusDetail
    )
    {
        $this->claimsStatusDetail = $claimsStatusDetail;
        parent::__construct($attributes);
    }

    protected $attributes = [
        'name' => 'claimStatusDetail',
        'description' => 'Detail Status per claim'
    ];

    public function type()
    {
        return GraphQL::type('ClaimStatusDetail');
    }

    public function args()
    {
        return [
            'claim' => ['name' => 'claim', 'type' => Type::string()],
            'patient' => ['name' => 'patient', 'type' => Type::string()],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $this->claimsStatusDetail->resetScope();
        ['includes' => $includes, 'fieldSets' => $fieldSets] =
            $this->resolveQueryFields($info);

        $this->claimsStatusDetail
            ->parsePresenterIncludes($includes)
            ->orderBy('date_inquiry', 'desc');

        $claimId = @$args['claim'];
        $patientId = @$args['patient'];

        $this->claimsStatusDetail->scopeQuery(function (Builder $query) use ($claimId, $patientId) {
            if ($claimId) {
                $query = $query->where('claim_id', '=', $claimId);
            } elseif ($patientId) {
                $query = $query->where('patient_id', '=', $patientId);
            }
            return $query;
        });

        $result = array_get($this->paginate($this->claimsStatusDetail), 'claimsStatusDetail');
        return $result;
    }
}
