<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    public string $first_name = '';
    public string $email = '';
    public string $last_name = '';
    public string $country = '';
    public string $state = '';
    public string $street_address = '';
    public string $gender = '';
    public string $phone = '';
    public string $zip_code = '';
    public string $user_type = 'user';
    public bool $show_div;
    public string $specialization = '';
    public string $certification = '';
    public string $years_of_experience = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
       if(Auth::guard('advisor')->check()){
           $this->specialization = Auth::user()->specialization ?? '';
           $this->certification = Auth::user()->certification ?? '';
           $this->years_of_experience = Auth::user()->years_of_experience ?? '';
       }

        $this->first_name = Auth::user()->first_name ?? '';
        $this->email = Auth::user()->email ?? '';
        $this->last_name = Auth::user()->last_name ?? '';
        $this->country = Auth::user()->country ?? '';
        $this->state = Auth::user()->state ?? '';
        $this->street_address = Auth::user()->street_address ?? '';
        $this->gender = Auth::user()->gender ?? '';
        $this->phone = Auth::user()->phone ?? '';
        $this->zip_code = Auth::user()->zip_code ?? '';
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'street_address' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:255'],
            'zip_code' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">

        @if(Auth::guard('admin')->check())
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
            @if (Auth::guard('advisor')->check())
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
            @if(!Auth::guard('advisor')->check())
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





    </form>
</section>
