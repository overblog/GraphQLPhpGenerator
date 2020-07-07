<?php declare(strict_types=1);

/*
 * This file is part of the OverblogGraphQLPhpGenerator package.
 *
 * (c) Overblog <http://github.com/overblog/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Overblog\GraphQLGenerator\Tests;

use GraphQL\Error\Debug;
use GraphQL\Error\DebugFlag;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use Overblog\GraphQLGenerator\Tests\Generator\AbstractTypeGeneratorTest;

abstract class AbstractStarWarsTest extends AbstractTypeGeneratorTest
{
    /**
     * @var Schema
     */
    protected $schema;

    public function setUp(): void
    {
        parent::setUp();

        $this->generateClasses();

        Resolver::setHumanType($this->getType('Human'));
        Resolver::setDroidType($this->getType('Droid'));

        $this->schema = new Schema(['query' => $this->getType('Query')]);
        $this->schema->assertValid();
    }

    protected function assertValidQuery(string $query, array $expected, array $variables = null): void
    {
        // TODO(mcg-web): remove this when migrating to webonyx/graphql-php >= 14.0
        $debug = class_exists('GraphQL\Error\DebugFlag') ? DebugFlag::class : Debug::class;
        
        $actual = GraphQL::executeQuery($this->schema, $query, null, null, $variables)
            ->toArray($debug::INCLUDE_DEBUG_MESSAGE | $debug::INCLUDE_TRACE);
        $expected = ['data' => $expected];
        $this->assertEquals($expected, $actual, \json_encode($actual));
    }

    protected function sortSchemaEntry(array &$entries, string $entryKey, string $sortBy): void
    {
        if (isset($entries['data']['__schema'][$entryKey])) {
            $data = &$entries['data']['__schema'][$entryKey];
        } else {
            $data = &$entries['__schema'][$entryKey];
        }

        \usort($data, function ($data1, $data2) use ($sortBy) {
            return \strcmp($data1[$sortBy], $data2[$sortBy]);
        });
    }
}
