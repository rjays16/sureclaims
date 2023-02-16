<?php

/**
 * GraphQLTestCase.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace Tests\Feature\GraphQL;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\Concerns\InteractsWithTenancy;
use Tests\Concerns\ProvidesModels;
use Tests\Concerns\RefreshesDatabase;
use Tests\Feature\GraphQL\Concerns\GraphQLAssertions;
use Tests\TestCase as BaseTestCase;

/**
 *
 * Description of GraphQLTestCase
 *
 */
abstract class GraphQLTestCase extends BaseTestCase
{
    use InteractsWithTenancy,
        RefreshesDatabase,
        GraphQLAssertions,
        WithoutMiddleware,
        ProvidesModels,
        WithFaker;

    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpTenancy();
    }

    /**
     * @param string $query
     *
     * @return TestResponse
     */
    protected function executeQuery($query, $variables = []) : TestResponse
    {
        return $this->post('/graphql', [
            'query' => $query,
            'variables' => $variables,
        ], [
            'Accepts' => 'application/json',
        ]);
    }

    /**
     * @param array $args
     * @return string
     */
    protected function asQueryArguments($args = []) : string
    {
        if (empty($args)) {
            return '';
        }
        $normalizedArgs = array_reduce(array_keys($args), function ($carry, $key) use ($args) {
            $carry[] = "$key: " . \GuzzleHttp\json_encode($args[$key]);
            return $carry;
        }, []);
        return '( ' . implode(', ', $normalizedArgs) . ' )';
    }
}
