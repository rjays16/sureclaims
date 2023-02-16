<?php

/**
 * DeleteReturnDocumentMutation.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Mutation;

use App\GraphQL\Concerns\NormalizesFillableData;
use App\GraphQL\Concerns\ResolvesQueryFields;
use App\Model\Entities\ReturnDocument;
use App\Model\Repositories\Contracts\ReturnDocumentRepository;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

/**
 *
 * Description of DeleteReturnDocumentMutation
 *
 */

class DeleteReturnDocumentMutation extends Mutation
{
    use NormalizesFillableData, ResolvesQueryFields;

    /** @var ReturnDocumentRepository */
    protected $returnDocuments;

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'deleteReturnDocument'
    ];

    /**
     * DeleteReturnDocumentMutation constructor.
     *
     * @param array $attributes
     * @param ReturnDocumentRepository $returnDocuments
     */
    public function __construct(
        $attributes = [],
        ReturnDocumentRepository $returnDocuments
    ) {
        $this->returnDocuments = $returnDocuments;
        parent::__construct($attributes);
    }

    /**
     * @return GraphQL\Type\Definition\Type
     */
    public function type()
    {
        return GraphQL::type('ReturnDocument');
    }

    /**
     * @return array
     */
    public function args()
    {
        return [
            'id' => ['name' => 'id', 'type' => Type::nonNull(Type::id())],
        ];
    }


    /**
     * @param $root
     * @param array $args
     * @param $context
     * @param ResolveInfo $info
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $this->returnDocuments->resetScope();
        ['includes' => $includes, 'fieldSets' => $fieldSets] =
            $this->resolveQueryFields($info);

        $this->returnDocuments->resetScope();
        $this->returnDocuments->parsePresenterIncludes($includes);

        /** @var ReturnDocument $returnDocument */
        $returnDocument = $this->returnDocuments->skipPresenter()->find($args['id']);
        if (empty($returnDocument)) {
            throw new \Exception('Document does not exist in the records');
        }

        $result = $this->returnDocuments->skipPresenter(false)->present($returnDocument);
        $returnDocument->delete();

        return $result;
    }
}
