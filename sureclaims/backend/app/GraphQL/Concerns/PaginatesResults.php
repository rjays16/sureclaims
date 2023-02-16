<?php

/**
 * PaginatesResults
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL;

use App\Model\Repositories\Contracts\RepositoryInterface;

/**
 *
 * Description of PaginatesResults
 *
 */

trait PaginatesResults
{

    /**
     * @param RepositoryInterface $repository
     * @param null $page
     * @param null $pageSize
     *
     * @return array
     */
    public function paginate(
        RepositoryInterface $repository,
        $page = null,
        $pageSize = null
    ) : array
    {
        $results = $repository->paginate($page, $pageSize);
        [
            'meta' => $meta
        ] = $results;
        unset($results['meta']);

        $pageInfo = [];
        if (isset($meta['pagination'])) {
            $pagination = $meta['pagination'];
            $pageInfo = [
                'total' => $pagination['total'],
                'count' => $pagination['count'],
                'pageSize' => $pagination['per_page'],
                'currentPage' => $pagination['current_page'],
                'lastPage' => $pagination['total_pages'],
                'hasMorePages' => $pagination['current_page'] !== $pagination['total_pages'],
                'links' => $pagination['links'],
            ];
        }

        return $results + [
            'pageInfo' => $pageInfo,
        ];
    }

}

