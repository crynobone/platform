<?php namespace Shopalicious\Customer\Contracts\Listener;

interface CustomerInterface
{
    public function showAllCustomers(array $data);

    public function showCustomer(array $data);
}
