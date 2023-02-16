<?php

namespace App\GraphQL\Mutation;

use App\Lib\Soap\EclaimsServiceInterface;
use App\Lib\Soap\Exceptions\PhilhealthException;
use App\Model\Entities\Claim;
use App\Model\Repositories\Contracts\ClaimRepository;
use App\Model\Repositories\Contracts\ReturnDocumentRepository;
use Folklore\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Collection;
use Monolog\Logger;

class RefileMutation extends Mutation
{
    /** @var ClaimRepository */
    private $claims;

    /** @var ReturnDocumentRepository */
    private $returnDocuments;

    /** @var EclaimsServiceInterface */
    private $eclaimsService;

    /**
     * CreateClaimMutation constructor.
     *
     * @param array $attributes
     * @param EclaimsServiceInterface $eclaimsService
     * @param ReturnDocumentRepository $returnDocuments
     * @param ClaimRepository $claims
     */
    public function __construct(
        $attributes = [],
        EclaimsServiceInterface $eclaimsService,
        ReturnDocumentRepository $returnDocuments,
        ClaimRepository $claims
    )
    {
        $this->eclaimsService = $eclaimsService;
        $this->claims = $claims;
        $this->returnDocuments = $returnDocuments;
        parent::__construct($attributes);
    }

    protected $attributes = [
        'name' => 'refile',
        'description' => 'A mutation'
    ];

    public function type()
    {
        return Type::listOf(Type::string());
    }

    /**
     * @return array
     */
    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::nonNull(Type::ID())],
            'lhioSeriesNo' => ['name' => 'lhioSeriesNo', 'type' => Type::nonNull(Type::string())],
            'returnDocuments' => ['name' => 'returnDocuments', 'type' => Type::nonNull(Type::string())],
            'supportingDocuments' => ['name' => 'supportingDocuments', 'type' => Type::nonNull(Type::string())],
        ];
    }

    public function resolve($root, $args)
    {
        $claimId = @$args['id'];
        $lhio = @$args['lhioSeriesNo'];

        if (empty($claimId)) {
            \App::abort(400, 'Parameter Claim id\'s cannot be empty!');
        }

        try {
            \DB::beginTransaction();

            $return = json_decode(@$args['returnDocuments'], true);

            $xml = $result = [];

            foreach ($return as $datum) {
                if (!$datum['isUploaded']) {
                    $xml[] = [
                        'url' => $datum['supportingDocument']['publicPath'],
                        'docType' => $datum['supportingDocument']['documentType'],
                        'filename' => $datum['supportingDocument']['fileName'],
                    ];
                }
            }

            $result = $this->eclaimsService->addDocument($lhio, $xml);

            if ($result) {
                foreach ($return as $datum) {
                    $this->returnDocuments->update([
                        'is_uploaded' => true
                    ], $datum['id']);
                }
            }

            \DB::commit();
        } catch (PhilhealthException $e) {
            \DB::rollBack();
            Logger($e);
            throw new \Exception($e->reason());
        } catch (\Exception $e) {
            \DB::rollBack();
            Logger($e);
            throw $e;
        }

        return [];
    }
}
