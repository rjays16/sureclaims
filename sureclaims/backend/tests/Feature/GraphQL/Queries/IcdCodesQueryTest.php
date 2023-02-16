<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Model\Entities\IcdCode;;
use Tests\Feature\GraphQL\GraphQLTestCase;

/**
 * Class IcdCodesQueryTest
 * @package Tests\Feature\GraphQL\Queries
 */
class IcdCodesQueryTest extends GraphQLTestCase
{
    /**
     *
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpTenancy();
    }

    /**
     * @test
     */
    public function shouldReturnIcdCodes()
    {
        $this->provideIcdCodes(5)->each(function (IcdCode $icdCode) {
            $icdCode->save();
        });
        $response = $this->executeQuery($this->query(), [
            // Empty
        ]);

        $result = $response->json();

        $data = array_get($result, 'data.icdCodes', []);
        $errors = array_get($result, 'errors');

        $this->assertEmpty($errors);
        $this->assertSameSize(range(1, 5), $data);
    }

    /**
     *
     */
    private function query()
    {
        return <<<'GRAPHQL'
query IcdCodes(
  $search: String
  $pageSize: Int
    
) {
  icdCodes(
    search: $search
    pageSize: $pageSize
  ) {
    id
    code
    description
    createdAt
    updatedAt
  }
}
GRAPHQL;
    }
}
