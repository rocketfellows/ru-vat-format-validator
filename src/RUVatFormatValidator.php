<?php

namespace rocketfellows\RUVatFormatValidator;

use rocketfellows\CountryVatFormatValidatorInterface\CountryVatFormatValidator;

class RUVatFormatValidator extends CountryVatFormatValidator
{
    private const VAT_NUMBER_PATTERN = '/^(([0-9]{12})|([0-9]{10}))$/';
    private const ORGANIZATION_VAT_NUMBER_PATTERN = '/^((\d{10}))?$/';
    private const INDIVIDUAL_VAT_NUMBER_PATTERN = '/^((\d{12}))?$/';

    private const ORGANIZATION_VAT_NUMBER_CHECKSUM_MULTIPLIERS = [2, 4, 10, 3, 5, 9, 4, 6, 8];
    private const INDIVIDUAL_VAT_NUMBER_PENULTIMATE_DIGIT_CHECKSUM_MULTIPLIERS = [7, 2, 4, 10, 3, 5, 9, 4, 6, 8];
    private const INDIVIDUAL_VAT_NUMBER_LAST_DIGIT_CHECKSUM_MULTIPLIERS = [3, 7, 2, 4, 10, 3, 5, 9, 4, 6, 8];

    protected function isValidFormat(string $vatNumber): bool
    {
        if (!$this->isValidFormatByGeneralPattern($vatNumber)) {
            return false;
        }

        if ($this->isValidOrganizationVatNumberFormat($vatNumber)) {
            return $this->isValidOrganizationVatNumberChecksum($vatNumber);
        }

        if ($this->isValidIndividualVatNumberFormat($vatNumber)) {
            return $this->isValidIndividualVatNumberChecksum($vatNumber);
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
        return $this->isValidVatNumberChecksum(
            $vatNumber,
            self::ORGANIZATION_VAT_NUMBER_CHECKSUM_MULTIPLIERS,
            9,
            9
        );
    }

    private function isValidIndividualVatNumberChecksum(string $vatNumber): bool
    {
        $isValidIndividualVatNumberPenultimateDigitChecksum = $this->isValidVatNumberChecksum(
            $vatNumber,
            self::INDIVIDUAL_VAT_NUMBER_PENULTIMATE_DIGIT_CHECKSUM_MULTIPLIERS,
            10,
            10
        );

        if (!$isValidIndividualVatNumberPenultimateDigitChecksum) {
            return false;
        }

        $isValidIndividualVatNumberLastDigitChecksum = $this->isValidVatNumberChecksum(
            $vatNumber,
            self::INDIVIDUAL_VAT_NUMBER_LAST_DIGIT_CHECKSUM_MULTIPLIERS,
            11,
            11
        );

        return $isValidIndividualVatNumberLastDigitChecksum;
    }

    private function isValidVatNumberChecksum(
        string $vatNumber,
        array $vatNumberChecksumMultipliers,
        int $toVatNumberDigitPosition,
        int $checkingDigitPosition
    ): bool {
        $vatNumberDigits = $this->getVatNumberDigits($vatNumber);
        $vatNumberChecksumDigits = array_slice($vatNumberDigits, 0, $toVatNumberDigitPosition);
        $calculatedChecksum = $this->calculateChecksumByKey($vatNumberChecksumDigits, $vatNumberChecksumMultipliers);

        return ($calculatedChecksum === $vatNumberDigits[$checkingDigitPosition]);
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

    private function calculateChecksumByKey(array $vatNumberDigits, array $vatNumberChecksumMultipliers): int
    {
        $key = array_sum(
            array_map(
                static function (int $vatNumberDigit, int $vatNumberChecksumMultiplier) {
                    return $vatNumberDigit * $vatNumberChecksumMultiplier;
                },
                $vatNumberDigits,
                $vatNumberChecksumMultipliers
            )
        );

        return ($key % 11) % 10;
    }
}
