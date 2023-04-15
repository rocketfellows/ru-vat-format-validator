<?php

namespace rocketfellows\RUVatFormatValidator;

use rocketfellows\CountryVatFormatValidatorInterface\CountryVatFormatValidator;

class RUVatFormatValidator extends CountryVatFormatValidator
{
    private const VAT_NUMBER_PATTERN = '/^((\d{12})|(\d{10}))?$/';
    private const ORGANIZATION_VAT_NUMBER_PATTERN = '/^((\d{10}))?$/';
    private const INDIVIDUAL_VAT_NUMBER_PATTERN = '/^((\d{12}))?$/';

    protected function isValidFormat(string $vatNumber): bool
    {
        if (!$this->isValidFormatByGeneralPattern($vatNumber)) {
            return false;
        }

        if (!$this->isValidOrganizationVatNumberFormat($vatNumber)) {
            return false;
        }

        if (!$this->isValidIndividualVatNumberFormat($vatNumber)) {
            return false;
        }

        return false;
    }

    private function isValidFormatByGeneralPattern(string $vatNumber): bool
    {
        return $this->isValidFormatByPattern($vatNumber, self::VAT_NUMBER_PATTERN);
    }

    private function isValidOrganizationVatNumberFormat(string $vatNumber): bool
    {
        return $this->isValidFormatByPattern($vatNumber, self::ORGANIZATION_VAT_NUMBER_PATTERN);
    }

    private function isValidIndividualVatNumberFormat(string $vatNumber): bool
    {
        return $this->isValidFormatByPattern($vatNumber, self::INDIVIDUAL_VAT_NUMBER_PATTERN);
    }

    private function isValidFormatByPattern(string $vatNumber, string $pattern): bool
    {
        return (bool) preg_match($pattern, $vatNumber);
    }
}
