<?php

/**
 * See LICENSE.md for license details.
 */

declare(strict_types=1);

namespace Dhl\Sdk\ParcelDe\Returns\Model;

use Dhl\Sdk\ParcelDe\Returns\Exception\RequestValidatorException;
use Dhl\Sdk\ParcelDe\Returns\Model\ReturnLabelRequestValidator as Validator;
use PHPUnit\Framework\TestCase;

class ReturnLabelRequestBuilderTest extends TestCase
{
    /**
     * @return ReturnLabelRequestBuilder[][]
     */
    public function dataProvider(): array
    {
        $missingReceiverIdBuilder = new ReturnLabelRequestBuilder();
        $missingReceiverIdBuilder->setShipper('Test Tester', 'DEU', '04229', 'Leipzig', 'Klingerweg', '6');

        $invalidReceiverIdsBuilder = new ReturnLabelRequestBuilder();
        $invalidReceiverIdsBuilder->setReceiverIds(['CHE' => 'che', 'DNK' => 'dnk']);
        $invalidReceiverIdsBuilder->setShipper('Test Tester', 'DEU', '04229', 'Leipzig', 'Klingerweg', '6');

        $missingShipperBuilder = new ReturnLabelRequestBuilder();
        $missingShipperBuilder->setReceiverId('deu');
        $missingShipperBuilder->setShipperContact('tester@nettest.eu', '+00 1337');

        $missingNameBuilder = new ReturnLabelRequestBuilder();
        $missingNameBuilder->setReceiverId('deu');
        $missingNameBuilder->setShipper('', 'DEU', '04229', 'Leipzig', 'Klingerweg', '6');

        $missingCityBuilder = new ReturnLabelRequestBuilder();
        $missingCityBuilder->setReceiverId('deu');
        $missingCityBuilder->setShipper('Test Tester', 'DEU', '04229', '', 'Klingerweg', '6');

        $missingPostalCodeBuilder = new ReturnLabelRequestBuilder();
        $missingPostalCodeBuilder->setReceiverId('deu');
        $missingPostalCodeBuilder->setShipper('Test Tester', 'DEU', '', 'Leipzig', 'Klingerweg', '6');

        $wrongCountryBuilder = new ReturnLabelRequestBuilder();
        $wrongCountryBuilder->setReceiverId('deu');
        $wrongCountryBuilder->setShipper('Test Tester', 'DE', '04229', 'Leipzig', 'Klingerweg', '6');

        $invalidWeightBuilder = new ReturnLabelRequestBuilder();
        $invalidWeightBuilder->setReceiverId('deu');
        $invalidWeightBuilder->setShipper('Test Tester', 'DEU', '04229', 'Leipzig', 'Klingerweg', '6');
        $invalidWeightBuilder->setPackageWeight(3, 'lbs');

        $invalidCurrencyBuilder = new ReturnLabelRequestBuilder();
        $invalidCurrencyBuilder->setReceiverId('deu');
        $invalidCurrencyBuilder->setShipper('Test Tester', 'DEU', '04229', 'Leipzig', 'Klingerweg', '6');
        $invalidCurrencyBuilder->setPackageValue(59, 'SFR');

        $invalidCustomsCurrencyBuilder = new ReturnLabelRequestBuilder();
        $invalidCustomsCurrencyBuilder->setReceiverId('che');
        $invalidCustomsCurrencyBuilder->setShipper('Test Tester', 'CHE', '8005', 'Zürich', 'Lagerstrasse', '10');
        $invalidCustomsCurrencyBuilder->addCustomsItem(3, 'DHL Foo', 59, 'SFR', 800, 'kg', 'DEU', '123456');

        $tooManyPositionsBuilder = new ReturnLabelRequestBuilder();
        $tooManyPositionsBuilder->setReceiverId('che');
        $tooManyPositionsBuilder->setShipper('Test Tester', 'CHE', '8005', 'Zürich', 'Lagerstrasse', '10');
        for ($i = 1; $i < 22; $i++) {
            $tooManyPositionsBuilder->addCustomsItem(3, 'DHL Foo', 59, 'EUR', 800, 'kg', 'DEU', '123456');
        }

        $wrongOriginBuilder = new ReturnLabelRequestBuilder();
        $wrongOriginBuilder->setReceiverId('che');
        $wrongOriginBuilder->setShipper('Test Tester', 'CHE', '8005', 'Zürich', 'Lagerstrasse', '10');
        $wrongOriginBuilder->addCustomsItem(3, 'DHL Foo', 59, 'EUR', 800, 'kg', 'DE', '123456');

        return [
            'missing_receiver_id' => [$missingReceiverIdBuilder, Validator::MSG_RECEIVER_ID_REQUIRED],
            'invalid_receiver_ids' => [$invalidReceiverIdsBuilder, Validator::MSG_RECEIVER_ID_INVALID],
            'missing_shipper' => [$missingShipperBuilder, Validator::MSG_SHIPPER_ADDRESS_REQUIRED],
            'missing_name' => [$missingNameBuilder, Validator::MSG_SHIPPER_ADDRESS_FIELD_REQUIRED],
            'missing_city' => [$missingCityBuilder, Validator::MSG_SHIPPER_ADDRESS_FIELD_REQUIRED],
            'missing_postal_code' => [$missingPostalCodeBuilder, Validator::MSG_SHIPPER_ADDRESS_FIELD_REQUIRED],
            'shipper_country_iso' => [$wrongCountryBuilder, Validator::MSG_COUNTRY_ISO_INVALID],
            'invalid_package_weight' => [$invalidWeightBuilder, Validator::MSG_WEIGHT_UOM_INVALID],
            'invalid_currency_code' => [$invalidCurrencyBuilder, Validator::MSG_CURRENCY_INVALID],
            'customs_invalid_currency_code' => [$invalidCustomsCurrencyBuilder, Validator::MSG_CURRENCY_INVALID],
            'customs_position_exceeds_max' => [$tooManyPositionsBuilder, Validator::MSG_CUSTOMS_POSITIONS_COUNT],
            'customs_position_country_iso' => [$wrongOriginBuilder, Validator::MSG_COUNTRY_ISO_INVALID],
        ];
    }

    /**
     * Assert valid request is built properly.
     *
     * @test
     * @throws RequestValidatorException
     */
    public function validRequest()
    {
        $builder = new ReturnLabelRequestBuilder();
        $builder->setReceiverId($receiverId = 'che');
        $builder->setCustomerReference($customerReference = '22222222225301');
        $builder->setShipmentReference($shipmentReference = 'RMA #1');
        $builder->setShipper(
            $shipperName = 'Test Tester',
            $shipperCountry = 'CHE',
            $shipperPostalCode = '8005',
            $shipperCity = 'Zürich',
            $shipperStreetName = 'Lagerstrasse',
            $shipperStreetNumber = '10',
            $shipperCompany = 'Test Co.',
            $shipperNameAddition = 'z.H. Thea Tester',
            [$shipperStreetAddition1 = '3rd Floor', $shipperStreetAddition2 = 'Apartment 12'],
            $shipperState = 'ZH'
        );
        $builder->setShipperContact($email = 'tester@nettest.eu', $phone = '+00 1337');
        $builder->setPackageWeight($weight = 4200, $weightUom = 'g');
        $builder->setPackageValue($value = 200, $currency = 'CHF');
        $builder->addCustomsItem(
            $qty1 = 1,
            $desc1 = 'DHL Foo',
            $value1 = 100,
            $currency1 = 'CHF',
            $weight1 = 800,
            $weightUom1 = 'g',
            $origin1 = 'DEU',
            $hsCode1 = '123456'
        );
        $builder->addCustomsItem(
            $qty2 = 2,
            $desc2 = 'DHL Bar',
            $value2 = 50,
            $currency2 = 'USD',
            $weight2 = 0.2,
            $weightUom2 = 'kg',
            $origin2 = 'CHN',
            $hsCode2 = '654321'
        );

        $request = $builder->create();
        $requestJson = json_encode($request, JSON_UNESCAPED_UNICODE);

        self::assertStringContainsString("\"receiverId\":\"$receiverId\"", $requestJson);
        self::assertStringContainsString("\"customerReference\":\"$customerReference\"", $requestJson);
        self::assertStringContainsString("\"shipmentReference\":\"$shipmentReference\"", $requestJson);
        self::assertStringContainsString("\"email\":\"$email\"", $requestJson);
        self::assertStringContainsString("\"phone\":\"$phone\"", $requestJson);
        self::assertStringContainsString("\"name1\":\"$shipperName\"", $requestJson);
        self::assertStringContainsString("\"name2\":\"$shipperCompany\"", $requestJson);
        self::assertStringContainsString("\"name3\":\"$shipperNameAddition\"", $requestJson);
        self::assertStringContainsString("\"country\":\"$shipperCountry\"", $requestJson);
        self::assertStringContainsString("\"postalCode\":\"$shipperPostalCode\"", $requestJson);
        self::assertStringContainsString("\"city\":\"$shipperCity\"", $requestJson);
        self::assertStringContainsString("\"addressStreet\":\"$shipperStreetName\"", $requestJson);
        self::assertStringContainsString("\"addressHouse\":\"$shipperStreetNumber\"", $requestJson);
        self::assertStringContainsString("\"additionalAddressInformation1\":\"$shipperStreetAddition1\"", $requestJson);
        self::assertStringContainsString("\"additionalAddressInformation2\":\"$shipperStreetAddition2\"", $requestJson);
        self::assertStringContainsString("\"state\":\"$shipperState\"", $requestJson);
        self::assertStringContainsString("\"value\":$weight", $requestJson);
        self::assertStringContainsString("\"uom\":\"$weightUom\"", $requestJson);
        self::assertStringContainsString("\"value\":$value", $requestJson);
        self::assertStringContainsString("\"currency\":\"$currency\"", $requestJson);

        self::assertStringContainsString("\"itemDescription\":\"$desc1\"", $requestJson);
        self::assertStringContainsString("\"packagedQuantity\":$qty1", $requestJson);
        self::assertStringContainsString("\"uom\":\"$weightUom1\"", $requestJson);
        self::assertStringContainsString("\"value\":$weight1", $requestJson);
        self::assertStringContainsString("\"currency\":\"$currency1\"", $requestJson);
        self::assertStringContainsString("\"value\":$value1", $requestJson);
        self::assertStringContainsString("\"countryOfOrigin\":\"$origin1\"", $requestJson);
        self::assertStringContainsString("\"hsCode\":\"$hsCode1\"", $requestJson);

        self::assertStringContainsString("\"itemDescription\":\"$desc2\"", $requestJson);
        self::assertStringContainsString("\"packagedQuantity\":$qty2", $requestJson);
        self::assertStringContainsString("\"uom\":\"$weightUom2\"", $requestJson);
        self::assertStringContainsString("\"value\":$weight2", $requestJson);
        self::assertStringContainsString("\"currency\":\"$currency2\"", $requestJson);
        self::assertStringContainsString("\"value\":$value2", $requestJson);
        self::assertStringContainsString("\"countryOfOrigin\":\"$origin2\"", $requestJson);
        self::assertStringContainsString("\"hsCode\":\"$hsCode2\"", $requestJson);
    }

    /**
     * Assert invalid requests throw RequestValidatorException.
     *
     * @test
     * @dataProvider dataProvider
     *
     * @param ReturnLabelRequestBuilder $builder
     * @param string $exceptionMessage
     * @throws RequestValidatorException
     */
    public function invalidRequest(ReturnLabelRequestBuilder $builder, string $exceptionMessage)
    {
        self::expectException(RequestValidatorException::class);
        if (str_contains($exceptionMessage, '%s')) {
            $regularExpression = str_replace('%s', '.+', $exceptionMessage);
            self::expectExceptionMessageMatches("|$regularExpression|");
        } else {
            self::expectExceptionMessage($exceptionMessage);
        }

        $builder->create();
    }
}
