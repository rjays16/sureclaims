<?php

namespace Tests\Feature;

use App\Model\Entities\Hci;
use Tests\Feature\GraphQL\GraphQLTestCase;


class HciQueryTest extends GraphQLTestCase
{
    /**
     * @test
     */
    public function shouldReturnHci()
    {
        $this->provideHcis(5)->each(function (Hci $hci) {
            $hci->save();
        });
        $response = $this->executeQuery($this->query(), [
            // Empty
        ]);

        $result = $response->json();

        $data = array_get($result, 'data.hcis', []);
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
query Hcis(
  $search: String
  $pageSize: Int
    
) {
  hcis(
    search: $search
    pageSize: $pageSize
  ) {
      id
      accreditationCode
      pmccNo
      hospitalName
      createdAt
      updatedAt
  }
}
GRAPHQL;
    }

}
