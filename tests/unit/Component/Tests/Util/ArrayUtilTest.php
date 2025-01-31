<?php
namespace Imi\Test\Component\Tests\Util;

use Imi\Test\BaseTest;
use Imi\Util\ArrayUtil;

/**
 * @testdox Imi\Util\ArrayUtil
 */
class ArrayUtilTest extends BaseTest
{
    /**
     * @testdox remove
     */
    public function testRemove()
    {
        $list = [];
        for($i = 0; $i < 10; ++$i)
        {
            $obj = new \stdClass;
            $obj->index = $i;
            $list[] = $obj;
        }
        $resultList = ArrayUtil::remove($list, $list[1], $list[3], $list[9]);
        $this->assertEquals(7, count($resultList));
        for($i = 0; $i < 7; ++$i)
        {
            $this->assertTrue(isset($resultList[$i]));
        }
        $this->assertNotEquals($list[1], $resultList[1] ?? null);
        $this->assertNotEquals($list[3], $resultList[3] ?? null);
        $this->assertNotEquals($list[9], $resultList[9] ?? null);
    }

    /**
     * @testdox removeKeepKey
     */
    public function testRemoveKeepKey()
    {
        $list = [];
        for($i = 0; $i < 10; ++$i)
        {
            $obj = new \stdClass;
            $obj->index = $i;
            $list[] = $obj;
        }
        $resultList = ArrayUtil::removeKeepKey($list, $list[1], $list[3], $list[9]);
        $this->assertEquals(7, count($resultList));
        $this->assertNull($resultList[1] ?? null);
        $this->assertNull($resultList[3] ?? null);
        $this->assertNull($resultList[9] ?? null);
    }

    /**
     * @testdox recursiveMerge
     */
    public function testRecursiveMerge()
    {
        $arr1 = [
            'a' =>  [
                'a-1'   =>  [
                    'id'    =>  1,
                    'name'  =>  'yurun',
                ],
                'a-2'   =>  [
                    'id'    =>  2,
                    'name'  =>  'imi',
                ],
                'a-3'   =>  [
                    'id'    =>  3,
                    'name'  =>  'https://www.yurunsoft.com',
                ],
            ]
        ];
        $arr2 = [
            'a' =>  [
                'a-1'   =>  [
                    'name'  =>  'https://www.imiphp.com',
                ],
                'a-2'   =>  100,
            ],
            'b' =>  200,
        ];
        $actual = [
            'a' =>  [
                'a-1'   =>  [
                    'id'    =>  1,
                    'name'  =>  'https://www.imiphp.com',
                ],
                'a-2'   =>  100,
                'a-3'   =>  [
                    'id'    =>  3,
                    'name'  =>  'https://www.yurunsoft.com',
                ],
            ],
            'b' =>  200,
        ];
        $result = ArrayUtil::recursiveMerge($arr1, $arr2);
        $this->assertEquals($actual, $result);
    }

    /**
     * @testdox columnToKey
     */
    public function testColumnToKey()
    {
        $list = [
            ['id' => 11, 'title' => 'aaa'],
            ['id' => 22, 'title' => 'bbb'],
            ['id' => 33, 'title' => 'ccc'],
        ];
        $actualKeepOld = [
            11 => ['id' => 11, 'title' => 'aaa'],
            22 => ['id' => 22, 'title' => 'bbb'],
            33 => ['id' => 33, 'title' => 'ccc'],
        ];
        $actualNotKeepOld = [
            11 => ['title' => 'aaa'],
            22 => ['title' => 'bbb'],
            33 => ['title' => 'ccc'],
        ];
        $this->assertEquals($actualKeepOld, ArrayUtil::columnToKey($list, 'id'));
        $this->assertEquals($actualNotKeepOld, ArrayUtil::columnToKey($list, 'id', false));
    }

    /**
     * @testdox isAssoc
     */
    public function testIsAssoc()
    {
        $assocArr = [
            0   =>  'a',
            1   =>  'b',
            2   =>  'c',
            'a' =>  'd',
        ];
        $indexArr = [
            'a', 'b', 'c'
        ];
        $this->assertTrue(ArrayUtil::isAssoc($assocArr));
        $this->assertFalse(ArrayUtil::isAssoc($indexArr));
    }

    /**
     * @testdox random
     */
    public function testRandom()
    {
        $arr = [
            'a' =>  1,
            'b' =>  2,
            'c' =>  3,
            'd' =>  4,
            'e' =>  5,
        ];
        $result = ArrayUtil::random($arr, 3);
        foreach($result as $k => $v)
        {
            $this->assertEquals($arr[$k] ?? null, $v);
        }
    }

}
