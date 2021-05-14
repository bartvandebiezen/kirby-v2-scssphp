<?php

namespace Herrera\Box\Tests\Signature;

use Herrera\Box\Signature\Hash;
use Herrera\PHPUnit\TestCase;

class HashTest extends TestCase
{
    /**
     * @var Hash
     */
    private $hash;

    public function testInit()
    {
        $this->hash->init('md5', '');

        $this->assertInternalType(
            'resource',
            $this->getPropertyValue($this->hash, 'context')
        );
    }

    public function testInitBadAlgorithm()
    {
        $this->setExpectedException(
            'Herrera\\Box\\Exception\\Exception',
            'Unknown hashing algorithm'
        );

        $this->hash->init('bad algorithm', '');
    }

    /**
     * @depends testInit
     */
    public function testUpdate()
    {
        $this->hash->init('md5', '');
        $this->hash->update('test');

        $this->assertEquals(
            md5('test'),
            hash_final($this->getPropertyValue($this->hash, 'context'))
        );
    }

    /**
     * @depends testInit
     * @depends testUpdate
     */
    public function testVerify()
    {
        $this->hash->init('md5', '');
        $this->hash->update('test');

        $this->assertTrue(
            $this->hash->verify(strtoupper(md5('test')))
        );
    }

    protected function setUp()
    {
        $this->hash = new Hash();
    }
}
