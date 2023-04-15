<?php

namespace rocketfellows\RUVatFormatValidator;

use rocketfellows\CountryVatFormatValidatorInterface\CountryVatFormatValidator;

class RUVatFormatValidator extends CountryVatFormatValidator
{
    private const VAT_NUMBER_PATTERN = '/^((\d{12})|(\d{10}))?$/';

    protected function isValidFormat(string $vatNumber): bool
    {
        if (!$this->isValidFormatByGeneralPattern($vatNumber)) {
            return false;
        }

        return true;
    }

    private function isValidFormatByGeneralPattern(string $vatNumber): bool
    {
        return (bool) preg_match(self::VAT_NUMBER_PATTERN, $vatNumber);
    }
}
