<?php

namespace rocketfellows\RUVatFormatValidator;

use rocketfellows\CountryVatFormatValidatorInterface\CountryVatFormatValidator;

class RUVatFormatValidator extends CountryVatFormatValidator
{
    private const VAT_NUMBER_PATTERN = '/^((\d{12})|(\d{10}))?$/';
    private const ORGANIZATION_VAT_NUMBER_PATTERN = '/^((\d{10}))?$/';
    private const INDIVIDUAL_VAT_NUMBER_PATTERN = '/^((\d{12}))?$/';

    private const ORGANIZATION_VAT_NUMBER_CHECKSUM_MULTIPLIERS = [2, 4, 10, 3, 5, 9, 4, 6, 8];

    protected function isValidFormat(string $vatNumber): bool
    {
        if (!$this->isValidFormatByGeneralPattern($vatNumber)) {
            return false;
        }

        if (!$this->isValidOrganizationVatNumberFormat($vatNumber)) {
            return $this->isValidOrganizationVatNumberChecksum($vatNumber);
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

    private function isValidOrganizationVatNumberChecksum(string $vatNumber): bool
    {
        $vatNumberDigits = $this->getVatNumberDigits($vatNumber);
        $vatNumberChecksumDigits = array_slice($vatNumberDigits, 0, 9);
        $calculatedKey = $this->calculateKey(
            $vatNumberChecksumDigits,
            self::ORGANIZATION_VAT_NUMBER_CHECKSUM_MULTIPLIERS
        );
        $calculatedChecksum = $this->calculateChecksum($calculatedKey);

        return ($calculatedChecksum === end($vatNumberDigits));
    }

    /**
     * @param string $vatNumber
     * @return int[]
     */
    private function getVatNumberDigits(string $vatNumber): array
    {
        return array_map(
            static function (string $item): int {
                return (int) $item;
            },
            str_split($vatNumber)
        );
    }

    private function calculateKey(array $vatNumberDigits, array $vatNumberChecksumMultipliers): int
    {
        return array_sum(
            array_map(
                static function (int $vatNumberDigit, int $vatNumberChecksumMultiplier) {
                    return $vatNumberDigit * $vatNumberChecksumMultiplier;
                },
                $vatNumberDigits, $vatNumberChecksumMultipliers
            )
        );
    }

    private function calculateChecksum(int $key): int
    {
        return ($key % 11) % 10;
    }
}
