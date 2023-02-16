<?php

/**
 * DeleteDoctorMutation.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Mutation;

use App\GraphQL\Concerns\NormalizesFillableData;
use App\GraphQL\Concerns\ResolvesQueryFields;
use App\Model\Entities\Doctor;
use App\Model\Repositories\Contracts\DoctorRepository;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

/**
 *
 * Description of DeleteDoctorMutation
 *
 */

class DeleteDoctorMutation extends Mutation
{
    use NormalizesFillableData, ResolvesQueryFields;

    /** @var DoctorRepository */
    protected $doctors;

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'deleteDoctor'
    ];

    /**
     * DeleteDoctorMutation constructor.
     *
     * @param array $attributes
     * @param DoctorRepository $doctors
     */
    public function __construct(
        $attributes = [],
        DoctorRepository $doctors
    ) {
        $this->doctors = $doctors;
        parent::__construct($attributes);
    }

    /**
     * @return GraphQL\Type\Definition\Type
     */
    public function type()
    {
        return GraphQL::type('Doctor');
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
     */
    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        $this->doctors->resetScope();
        ['includes' => $includes, 'fieldSets' => $fieldSets] =
            $this->resolveQueryFields($info);

        $this->doctors->resetScope();
        $this->doctors->parsePresenterIncludes($includes);

        /** @var Doctor $doctor */
        $doctor = $this->doctors->skipPresenter()->find($args['id']);
        if (empty($doctor)) {
            throw new \Exception('Doctor does not exist in the records');
        }

        $result = $this->doctors->skipPresenter(false)->present($doctor);

        $doctor->delete();

        return $result;
    }
}
