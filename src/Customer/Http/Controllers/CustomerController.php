<?php namespace Shopalicious\Customer\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Shopalicious\Customer\Processors\Customer as Processor;
use Shopalicious\Customer\Contracts\Listener\CustomerInterface as CustomerListener;
use Shopalicious\Support\Contracts\Listener\ResourceInterface as ResourceListener;

class CustomerController extends Controller implements CustomerListener, ResourceListener
{
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    public function index()
    {
        return $this->processor->index($this);
    }

    public function show($customerId)
    {
        return $this->processor->show($this, $customerId);
    }

    public function create()
    {
        return $this->processor->create($this);
    }

    public function store(Request $request)
    {
        return $this->processor->store($this, $request->all());
    }

    public function edit($customerId)
    {
        return $this->processor->edit($this, $customerId);
    }

    public function update(Request $request, $customerId)
    {
        return $this->processor->update($this, $customerId, $request->all());
    }

    public function destroy($customerId)
    {
        return $this->processor->destroy($this, $customerId);
    }

    public function showAllCustomers(array $data)
    {

    }

    public function showCustomer(array $data)
    {

    }

    public function showForm(array $data)
    {
        
    }
}
