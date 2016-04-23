<?php

/*
 * This file is part of the OverblogGraphQLPhpGenerator package.
 *
 * (c) Overblog <http://github.com/overblog/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Overblog\GraphQLGenerator\Tests\Generator;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class TypeGeneratorTest extends AbstractTypeGeneratorTest
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Skeleton dir "fake" not found.
     */
    public function testWrongSetSkeletonDirs()
    {
        $this->typeGenerator->setSkeletonDirs('fake');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Skeleton dir "fake" not found.
     */
    public function testGoodSetSkeletonDirs()
    {
        $this->typeGenerator->setSkeletonDirs('fake');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp  /Template ".*fake.php.skeleton" not found./
     */
    public function testWrongGetSkeletonDirs()
    {
        $this->typeGenerator->getSkeletonContent('fake');
    }

    public function testTypeAlias2String()
    {
        $this->generateClasses($this->getConfigs());

        /** @var ObjectType $type */
        $type = $this->getType('T');

        $this->assertInstanceOf('GraphQL\Type\Definition\StringType', $type->getField('string')->getType());
        $this->assertInstanceOf('GraphQL\Type\Definition\IntType', $type->getField('int')->getType());
        $this->assertInstanceOf('GraphQL\Type\Definition\IDType', $type->getField('id')->getType());
        $this->assertInstanceOf('GraphQL\Type\Definition\FloatType', $type->getField('float')->getType());
        $this->assertInstanceOf('GraphQL\Type\Definition\BooleanType', $type->getField('boolean')->getType());

        $this->assertEquals(Type::nonNull(Type::string()), $type->getField('nonNullString')->getType());
        $this->assertEquals(Type::listOf(Type::string()), $type->getField('listOfString')->getType());
        $this->assertEquals(Type::listOf(Type::listOf(Type::string())), $type->getField('listOfListOfString')->getType());
        $this->assertEquals(
            Type::nonNull(
                Type::listOf(
                    Type::nonNull(
                        Type::listOf(
                            Type::nonNull(Type::string())
                        )
                    )
                )
            ),
            $type->getField('listOfListOfStringNonNull')->getType()
        );
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Malformed ListOf wrapper type "[String" expected "]" but got "g".
     */
    public function testTypeAlias2StringInvalidListOf()
    {
        $this->generateClasses(
            [
                'T' => [
                    'type' => 'object',
                    'config' => [
                        'fields' => [
                            'invalidlistOfString' => ['type' => '[String'],
                        ]
                    ],
                ]
            ], $this->tmpDir

        );
    }

    public function testAddTraitAndClearTraits()
    {
        $trait = __NAMESPACE__ . '\\FooTrait';
        $this->typeGenerator->addTrait($trait);
        $this->generateClasses($this->getConfigs());

        /** @var ObjectType $type */
        $type = $this->getType('T');

        $this->assertEquals('Foo::bar', $type->bar());
    }

    private function getConfigs()
    {
        return [
            'T' => [
                'type' => 'object',
                'config' => [
                    'fields' => [
                        'string' => ['type' => 'String'],
                        'int' => ['type' => 'Int'],
                        'id' => ['type' => 'ID'],
                        'float' => ['type' => 'Float'],
                        'boolean' => ['type' => 'Boolean'],
                        'nonNullString' => ['type' => 'String!'],
                        'listOfString' => ['type' => '[String]'],
                        'listOfListOfString' => ['type' => '[[String]]'],
                        'listOfListOfStringNonNull' => ['type' => '[[String!]!]!'],
                    ]
                ],
            ]
        ];
    }
}
