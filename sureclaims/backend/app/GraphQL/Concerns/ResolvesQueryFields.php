<?php

/**
 * ResolvesQueryFields.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Concerns;

use App\Model\Repositories\PresentableRepository;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Retrieves the list of fields and model relationships as resolved in the GraphQL query.
 *
 */
trait ResolvesQueryFields
{
    /**
     * @param ResolveInfo $info
     * @param PresentableRepository $repository
     * @param string $rootKey The presenter item/collection key used for the root object
     * @param int $depth
     *
     * @return array
     *
     * @todo FieldSets are not being correctly utilized because of limitations in the Repository library
     */
    public function resolveQueryFields(
        ResolveInfo $info,
        $rootKey = null,
        $depth = 5
    ) {
        $fields = $info->getFieldSelection($depth);
        $fieldSets = [];
        $includes = [];

        if ($rootKey) {
            $fields = $fields[$rootKey];
        }

        $this->resolveFieldsForCurrentRoot($rootKey, $rootKey, $fields, $fieldSets, $includes, '', $depth);

        return [
            'fieldSets' => $fieldSets,
            'includes' => $includes,
        ];
    }

    /**
     * @param $root
     * @param $currentRoot
     * @param array $fields
     * @param $currentFieldSets
     * @param $currentIncludes
     * @param $currentRootPath
     * @param int $depth
     */
    private function resolveFieldsForCurrentRoot(
        $root,
        $currentRoot,
        $fields = [],
        &$currentFieldSets,
        &$currentIncludes,
        $currentRootPath,
        $depth = 0
    ) {
        if (!isset($currentFieldSets[$currentRoot])) {
            $currentFieldSets[$currentRoot] = '';
        };

        foreach ($fields as $field => $value) {

            if ($value === true) {
                // Process fieldsets
                $currentFieldSets[$currentRoot] .=
                    (!empty($currentFieldSets[$currentRoot]) ? ',' : '') .
                    $field;
            } else {
                // Process includes
                if (--$field >= 0) {
                    $currentFieldSets[$currentRoot] .= (
                        !empty($currentFieldSets[$currentRoot]) ?
                        ',' :
                        ''
                    );
                    $currentFieldSets[$currentRoot] .= $field;

                    $rootPath = $currentRootPath;
                    if ($rootPath) {
                        $currentIncludes[] = $rootPath . '.' . $field;
                        $rootPath .= '.' . $field;
                    } else {
                        $currentIncludes[] = $field;
                        $rootPath = $field;
                    }

                    $this->resolveFieldsForCurrentRoot(
                        $root,
                        $field,
                        $value,
                        $currentFieldSets,
                        $currentIncludes,
                        $rootPath,
                        $depth
                    );
                }
            }
        }
    }

}

