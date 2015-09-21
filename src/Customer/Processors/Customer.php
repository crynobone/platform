<?php namespace Shopalicious\Customer\Processors;

use Illuminate\Support\Arr;
use App\User as UserModel;
use Orchestra\Model\Value\Meta;
use Illuminate\Support\Facades\Event;
use Orchestra\Foundation\Processor\Processor;
use Shopalicious\Customer\Model\Customer as CustomerModel;
use Shopalicious\Customer\Http\Validators\Customer as Validator;
use Shopalicious\Customer\Http\Presenters\Customer as Presenter;
use Shopalicious\Customer\Contracts\Listener\CustomerInterface as CustomerListener;
use Shopalicious\Support\Contracts\Listener\ResourceInterface as ResourceListener;

class Customer extends Processor
{
    public function __construct(Presenter $presenter, Validator $validator)
    {
        $this->presenter = $presenter;
        $this->validator = $validator;
    }

    public function index(CustomerListener $listener)
    {
        $customers = CustomerModel::orderBy('created_at', 'ASC')
            ->paginate();
    }

    public function show(CustomerListener $listener, $customerId)
    {
        $customer = CustomerModel::findOrFail($customerId);
    }

    public function create(ResourceListener $listener)
    {
        $customer = new CustomerModel;

        $data = [
            'form' => $this->presenter->form($customer)
        ];

        return $listener->showForm();
    }

    public function store(ResourceListener $listener, array $inputs)
    {
        $validation = $this->validator->on('create')->with($inputs);
        if ($validation->fails()) {

        }

        $account = $this->saveCustomerAccount(new UserModel, Arr::only($inputs, ['email', 'fullname', 'password']));

        $profile = [
            'user_id' => $account->id,
            'metadata' => $inputs['meta']
        ];
        $customer = $this->saveCustomerProfile(new CustomerModel, $profile);

        $this->fireEvents('created', [$customer]);
    }

    public function edit(ResourceListener $listener, $customerId)
    {
        $customer = CustomerModel::findOrFail($customerId);

        $data = [
            'form' => $this->presenter->form($customer)
        ];

        return $listener->showForm();
    }

    public function update(ResourceListener $listener, $customerId, array $inputs)
    {
        $validation = $this->validator->on('update')->with($inputs);
        if ($validation->fails()) {

        }

        $model = CustomerModel::findOrFail($customerId);

        $profile = [
            'metadata' => $inputs['meta']
        ];
        $customer = $this->saveCustomerProfile($model, $profile);

        $this->fireEvents('updated', [$customer]);
    }

    public function destroy(ResourceListener $listener, $customerId)
    {
        $customer = CustomerModel::findOrFail($customerId);

        $customer->account->delete();
        $customer->delete();

        $this->fireEvents('deleted', [$customer]);
    }

    protected function saveCustomerAccount(UserModel $user, array $data)
    {
        $user->setAttribute('email', $data['email']);
        $user->setAttribute('fullname', $data['name']);

        if (Arr::has($data, 'password')) {
            $user->setAttribute('password', $data['password']);
        }

        $user->save();

        return $user;
    }

    protected function saveCustomerProfile(CustomerModel $customer, array $data)
    {
        if (Arr::has($data, 'user_id')) {
            $customer->setAttribute('user_id', $data['user_id']);
        }

        $customer->setAttribute('meta', new Meta($data['metadata']));
        $customer->save();

        return $customer;
    }

    protected function fireEvents($eventAction, $parameters = [])
    {
        Event::fire("shopalicious.customers: {$eventAction}", $parameters);
    }
}
