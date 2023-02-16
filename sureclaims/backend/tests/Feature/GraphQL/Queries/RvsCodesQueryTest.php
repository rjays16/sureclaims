<?php

namespace Tests\Feature\GraphQL\Queries;

use App\Model\Entities\RvsCode;
use Tests\Feature\GraphQL\GraphQLTestCase;

class RvsCodesQueryTest extends GraphQLTestCase
{
    /**
     * @test
     */
    public function shouldReturnRvsCodes()
    {
        $this->provideRvsCodes(5)->each(function (RvsCode $rvsCode) {
            $rvsCode->save();
        });
        $response = $this->executeQuery($this->query(), [
            // Empty
        ]);

        $result = $response->json();

        $data = array_get($result, 'data.rvsCodes', []);
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
query RvsCodes(
  $search: String
  $pageSize: Int
    
) {
  rvsCodes(
    search: $search
    pageSize: $pageSize
  ) {
    id
    code
    rvu
    description
    createdAt
    updatedAt
  }
}
GRAPHQL;
    }



}
