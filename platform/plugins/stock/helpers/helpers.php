<?php

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Stock\Repositories\Interfaces\PackageInterface;
use Botble\Stock\Repositories\Interfaces\ContractInterface;
use Botble\Ecommerce\Repositories\Interfaces\CustomerInterface;

use Illuminate\Support\Collection;

if (!function_exists('get_package')) {
    /**
     * @return array
     */
    function get_package($packageID)
    {
        return app(PackageInterface::class)->getPackageById($packageID);
    }
}


if (!function_exists('get_customer')) {
    /**
     * @return array
     */
    function get_customer($customerID)
    {
        return app(CustomerInterface::class)->getCustomerById($customerID);
    }
}

if (!function_exists('get_contract')) {
    /**
     * @return array
     */
    function get_contract($contractID)
    {
        return app(ContractInterface::class)->getContractById($contractID);
    }
}


