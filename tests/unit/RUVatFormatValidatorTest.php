<?php

namespace rocketfellows\RUVatFormatValidator\tests\unit;

use PHPUnit\Framework\TestCase;
use rocketfellows\RUVatFormatValidator\RUVatFormatValidator;

class RUVatFormatValidatorTest extends TestCase
{
    /**
     * @var RUVatFormatValidator
     */
    private $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = new RUVatFormatValidator();
    }

    /**
     * @dataProvider getVatNumbersProvidedData
     */
    public function testValidationResult(string $vatNumber, bool $isValid): void
    {
        $this->assertEquals($isValid, $this->validator->isValid($vatNumber));
    }

    public function getVatNumbersProvidedData(): array
    {
        return [
            [
                'vatNumber' => '1234567848',
                'isValid' => true,
            ],
            [
                'vatNumber' => '9857123563',
                'isValid' => true,
            ],
            [
                'vatNumber' => '1312211111',
                'isValid' => true,
            ],
            [
                'vatNumber' => '6316269915',
                'isValid' => true,
            ],
            [
                'vatNumber' => '7725283165',
                'isValid' => true,
            ],
            [
                'vatNumber' => '9702020220',
                'isValid' => true,
            ],
            [
                'vatNumber' => '7736207543',
                'isValid' => true,
            ],
            [
                'vatNumber' => '7705891253',
                'isValid' => true,
            ],
            [
                'vatNumber' => '770970230389',
                'isValid' => true,
            ],
            [
                'vatNumber' => '164904132023',
                'isValid' => true,
            ],
            [
                'vatNumber' => '1234567841',
                'isValid' => false,
            ],
            [
                'vatNumber' => '9857123562',
                'isValid' => false,
            ],
            [
                'vatNumber' => '1312211119',
                'isValid' => false,
            ],
            [
                'vatNumber' => '6316269914',
                'isValid' => false,
            ],
            [
                'vatNumber' => '7725283166',
                'isValid' => false,
            ],
            [
                'vatNumber' => '7705891252',
                'isValid' => false,
            ],
            [
                'vatNumber' => '7736207541',
                'isValid' => false,
            ],
            [
                'vatNumber' => '9702020221',
                'isValid' => false,
            ],
            [
                'vatNumber' => '770970230381',
                'isValid' => false,
            ],
            [
                'vatNumber' => '164904132028',
                'isValid' => false,
            ],
            [
                'vatNumber' => '1649041320280',
                'isValid' => false,
            ],
            [
                'vatNumber' => '16490413202',
                'isValid' => false,
            ],
            [
                'vatNumber' => '164904132',
                'isValid' => false,
            ],
            [
                'vatNumber' => 'RU1649041321',
                'isValid' => false,
            ],
            [
                'vatNumber' => 'RU164904132111',
                'isValid' => false,
            ],
            [
                'vatNumber' => '1',
                'isValid' => false,
            ],
            [
                'vatNumber' => '',
                'isValid' => false,
            ],
        ];
    }
}
