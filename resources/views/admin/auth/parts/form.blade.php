
<form class="mb-3 validated-form form" action="{{route('admin.login')}}" method="POST" novalidate>
    @csrf
    <div class="row">
        <x-form.email :options="['name' => 'email', 'class' => 'col-md-12', 'isRequired' => true]" />
        <x-form.password :options="['name' => 'password', 'class' => 'col-md-12', 'isRequired' => true,'minLength' => 3, 'maxLength' => 10  ]" />
    </div>

    <div class="mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="remember-me" name="remember-me" />
            <label class="form-check-label" for="remember-me"> {{__('admin/auth.remember_me')}} </label>
        </div>
    </div>

    <button type="submit" class="btn btn-primary d-grid w-100">{{__('admin/auth.login')}}</button>
</form>
