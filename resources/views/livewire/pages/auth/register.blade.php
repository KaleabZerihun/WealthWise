<?php

use App\Models\Admin;
use App\Models\Advisor;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $first_name = '';
    public string $user_type = 'user';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $last_name = '';
    public string $country = '';
    public string $state = '';
    public string $street_address = '';
    public string $gender = '';
    public string $phone = '';
    public string $zip_code = '';
    public string $specialization = '';
    public string $certification = '';
    public string $years_of_experience = '';
    public bool $show_div;
    public bool $is_admin;

    public function handleUserTypeChange()
    {
        if ($this->user_type === 'advisor') {
            $this->show_div = true;
        } else {
            $this->show_div = false;
        }

        if ($this->user_type === 'admin') {
            $this->is_admin = true;
        } else {
            $this->is_admin = false;
        }


    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {

        if ($this->user_type === 'admin') {
            $validated = $this->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Admin::class],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ]);

        } elseif ($this->user_type === 'advisor') {
            $validated = $this->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'specialization' => ['required', 'string', 'max:255'],
                'certification' => ['required', 'string', 'max:255'],
                'years_of_experience' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . Advisor::class],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ]);


        } else {
            $validated = $this->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'country' => ['required', 'string', 'max:255'],
                'state' => ['required', 'string', 'max:255'],
                'street_address' => ['required', 'string', 'max:255'],
                'gender' => ['required', 'string', 'max:255'],
                'zip_code' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            ]);
        }

        $validated['password'] = Hash::make($validated['password']);

        if ($this->user_type === 'admin') {
            $admin = Admin::create($validated);
            Auth::guard('admin')->login($admin);
            redirect()->route('admin.dashboard');
        } elseif ($this->user_type === 'advisor') {
            $adv = Advisor::create($validated);
            Auth::guard('advisor')->login($adv);
            redirect()->route('advisor.dashboard');
        } else {
            $web = User::create($validated);
            Auth::guard('web')->login($web);
            $this->redirect(route('dashboard', absolute: false), navigate: true);
        }
        //dd($user);

        //Auth::login($user);


    }
}; ?>

<div>
    <form wire:submit="register">
        <x-input-label for="user_type" :value="__('User Type')"/>
        <select
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full"
            name="user_type" wire:model="user_type" wire:change="handleUserTypeChange">
            <option value="user">User</option>
            <option value="admin">Admin</option>
            <option value="advisor">Advisor</option>
        </select>


        @if($is_admin)
            <!-- Name -->
            <div>
                <x-input-label for="first_name" :value="__('First Name')"/>
                <x-text-input wire:model="first_name" id="first_name" class="block mt-1 w-full" type="text"
                              name="first_name" required autofocus autocomplete="first_name"/>
                <x-input-error :messages="$errors->get('first_name')" class="mt-2"/>
            </div>

            <!-- Name -->
            <div>
                <x-input-label for="last_name" :value="__('Last Name')"/>
                <x-text-input wire:model="last_name" id="last_name" class="block mt-1 w-full" type="text" name="last_name"
                              required autofocus autocomplete="last_name"/>
                <x-input-error :messages="$errors->get('last_name')" class="mt-2"/>
            </div>
            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')"/>
                <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required
                              autocomplete="username"/>
                <x-input-error :messages="$errors->get('email')" class="mt-2"/>
            </div>

        @else
            <!-- Name -->
            <div>
                <x-input-label for="first_name" :value="__('First Name')"/>
                <x-text-input wire:model="first_name" id="first_name" class="block mt-1 w-full" type="text"
                              name="first_name" required autofocus autocomplete="first_name"/>
                <x-input-error :messages="$errors->get('first_name')" class="mt-2"/>
            </div>

            <!-- Name -->
            <div>
                <x-input-label for="last_name" :value="__('Last Name')"/>
                <x-text-input wire:model="last_name" id="last_name" class="block mt-1 w-full" type="text" name="last_name"
                              required autofocus autocomplete="last_name"/>
                <x-input-error :messages="$errors->get('last_name')" class="mt-2"/>
            </div>
            <!-- Conditionally Display Textboxes -->
            @if ($show_div)
                <!-- specialization -->
                <div>
                    <x-input-label for="specialization" :value="__('Specialization')"/>
                    <x-text-input wire:model="specialization" id="specialization" class="block mt-1 w-full" type="text"
                                  name="specialization" required autofocus autocomplete="specialization"/>
                    <x-input-error :messages="$errors->get('specialization')" class="mt-2"/>
                </div>
                <!-- certification -->
                <div>
                    <x-input-label for="certification" :value="__('Certification')"/>
                    <x-text-input wire:model="certification" id="certification" class="block mt-1 w-full" type="text"
                                  name="certification" required autofocus autocomplete="certification"/>
                    <x-input-error :messages="$errors->get('certification')" class="mt-2"/>
                </div>
                <!-- years_of_experience -->
                <div>
                    <x-input-label for="years_of_experience" :value="__('Years of Experience')"/>
                    <x-text-input wire:model="years_of_experience" id="years_of_experience" class="block mt-1 w-full"
                                  type="text" name="years_of_experience" required autofocus
                                  autocomplete="years_of_experience"/>
                    <x-input-error :messages="$errors->get('years_of_experience')" class="mt-2"/>
                </div>
            @endif
            @if(!$show_div)
                <!-- Country -->
                <div>
                    <x-input-label for="country" :value="__('Country')"/>
                    <x-text-input wire:model="country" id="country" class="block mt-1 w-full" type="text" name="country"
                                  required autofocus autocomplete="country"/>
                    <x-input-error :messages="$errors->get('country')" class="mt-2"/>
                </div>
                <!-- Conditionally Display Textboxes -->


                <!-- State -->
                <div>
                    <x-input-label for="state" :value="__('State')"/>
                    <x-text-input wire:model="state" id="state" class="block mt-1 w-full" type="text" name="state" required
                                  autofocus autocomplete="state"/>
                    <x-input-error :messages="$errors->get('state')" class="mt-2"/>
                </div>

                <!-- Street Address-->
                <div>
                    <x-input-label for="street_address" :value="__('Street Address')"/>
                    <x-text-input wire:model="street_address" id="street_address" class="block mt-1 w-full" type="text"
                                  name="street_address" required autofocus autocomplete="street_address"/>
                    <x-input-error :messages="$errors->get('street_address')" class="mt-2"/>
                </div>

                <!-- Zip Code-->
                <div>
                    <x-input-label for="zip_code" :value="__('Zip Code')"/>
                    <x-text-input wire:model="zip_code" id="zip_code" class="block mt-1 w-full" type="text" name="zip_code"
                                  required autofocus autocomplete="zip_code"/>
                    <x-input-error :messages="$errors->get('zip_code')" class="mt-2"/>
                </div>
                <div>
                    <x-input-label for="gender" :value="__('Gender')"/>
                    <x-text-input wire:model="gender" id="gender" class="block mt-1 w-full" type="text" name="gender" required
                                  autofocus autocomplete="gender"/>
                    <x-input-error :messages="$errors->get('gender')" class="mt-2"/>
                </div>
            @endif
            <!-- Phone Number-->
            <div>
                <x-input-label for="phone" :value="__('Phone Number')"/>
                <x-text-input wire:model="phone" id="phone" class="block mt-1 w-full" type="text" name="phone" required
                              autofocus autocomplete="phone"/>
                <x-input-error :messages="$errors->get('phone')" class="mt-2"/>
            </div>
            <!-- Gender-->


            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')"/>
                <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required
                              autocomplete="username"/>
                <x-input-error :messages="$errors->get('email')" class="mt-2"/>
            </div>
        @endif

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')"/>

            <x-text-input wire:model="password" id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="new-password"/>

            <x-input-error :messages="$errors->get('password')" class="mt-2"/>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')"/>

            <x-text-input wire:model="password_confirmation" id="password_confirmation" class="block mt-1 w-full"
                          type="password"
                          name="password_confirmation" required autocomplete="new-password"/>

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2"/>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
               href="{{ route('login') }}" wire:navigate>
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</div>
