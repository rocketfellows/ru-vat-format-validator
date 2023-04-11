<?php

namespace rocketfellows\RUVatFormatValidator\tests\unit;

use PHPUnit\Framework\TestCase;

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
        ];
    }
}
