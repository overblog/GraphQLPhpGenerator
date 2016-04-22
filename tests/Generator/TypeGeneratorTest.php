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
     * @expectedExceptionMessageRegExp  /Template ".*fake.code.php" not found./
     */
    public function testWrongGetSkeletonDirs()
    {
        $this->typeGenerator->getSkeletonContent('fake.code.php');
    }
}
