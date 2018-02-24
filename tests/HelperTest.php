<?php

namespace Tusimo\Utility\Tests;

use PHPUnit_Framework_TestCase;

class HelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testIsCli()
    {
        $this->assertEquals(true, is_cli());
    }

    /**
     * @test
     */
    public function testClassConstants()
    {
        $expectedArray = [
            'GENDER_MALE' => 'male',
            'GENDER_FEMALE' => 'female',
            'COLOR_RED' => 'red',
            'COLOR_GREEN' => 'green',
        ];
        $this->assertEquals($expectedArray, class_constants(ConstantsClass::class));
        $expectedArray = [
            'GENDER_MALE' => 'male',
            'GENDER_FEMALE' => 'female',
        ];
        $this->assertEquals($expectedArray, class_constants(ConstantsClass::class, 'GENDER'));
    }

    /**
     * @test
     */
    public function testDistance()
    {
        $distance = 4.9704;
        $this->assertEquals($distance, distance(31.2014966,121.40233369999998,31.22323799999999,121.44552099999998));
    }

    /**
     * @test
     */
    public function testRenameArrayKeys()
    {
        $array = [
            'hotel_id' => 1,
            'hotel_name' => 'test',
            'detail' => [
                'hotel_price' => 1,
                'hotel_contact' => '李四',
            ],
            'image' => [
                [
                    "url_string" => '1'
                ],
                [
                    "url_string" => '1'
                ]
            ]
        ];
        $newArray = $array;
        rename_array_keys($newArray, 'camel_case');
        $this->assertEquals([
            'hotelId' => 1,
            'hotelName' => 'test',
            'detail' => [
                'hotelPrice' => 1,
                'hotelContact' => '李四',
            ],
            'image' => [
                [
                    "urlString" => '1'
                ],
                [
                    "urlString" => '1'
                ]
            ]
        ], $newArray);
    }

    /**
     * @test
     */
    function testIsMobile()
    {
        $mobile = 13751223433;
        $this->assertEquals(true, is_mobile($mobile));
    }

    /**
     * @test
     */
    function testIsEmail()
    {
        $email = 'test@google.com';
        $this->assertEquals(true, is_email($email));
    }
}

class ConstantsClass {
    const GENDER_MALE = 'male';
    const GENDER_FEMALE = 'female';
    const COLOR_RED = 'red';
    const COLOR_GREEN = 'green';
}