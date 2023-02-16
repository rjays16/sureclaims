<?php

/**
 * DeleteSupportingDocumentMutation.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Mutation;

use App\GraphQL\Concerns\NormalizesFillableData;
use App\GraphQL\Concerns\ResolvesQueryFields;
use App\Model\Entities\SupportingDocument;
use App\Model\Repositories\Contracts\SupportingDocumentRepository;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

/**
 *
 * Description of DeleteSupportingDocumentMutation
 *
 */

class DeleteSupportingDocumentMutation extends Mutation
{
    use NormalizesFillableData, ResolvesQueryFields;

    /** @var SupportingDocumentRepository */
    protected $supportingDocuments;

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'deleteSupportingDocument'
    ];

    /**
     * DeleteSupportingDocumentMutation constructor.
     *
     * @param array $attributes
     * @param SupportingDocumentRepository $supportingDocuments
     */
    public function __construct(
        $attributes = [],
        SupportingDocumentRepository $supportingDocuments
    ) {
        $this->supportingDocuments = $supportingDocuments;
        parent::__construct($attributes);
    }

    /**
     * @return GraphQL\Type\Definition\Type
     */
    public function type()
    {
        return GraphQL::type('SupportingDocument');
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
        $this->supportingDocuments->resetScope();
        ['includes' => $includes, 'fieldSets' => $fieldSets] =
            $this->resolveQueryFields($info);

        $this->supportingDocuments->resetScope();
        $this->supportingDocuments->parsePresenterIncludes($includes);

        /** @var SupportingDocument $supportingDocument */
        $supportingDocument = $this->supportingDocuments->skipPresenter()->find($args['id']);
        if (empty($supportingDocument)) {
            throw new \Exception('Document does not exist in the records');
        }

        $result = $this->supportingDocuments->skipPresenter(false)->present($supportingDocument);
        $supportingDocument->delete();

        return $result;
    }
}
