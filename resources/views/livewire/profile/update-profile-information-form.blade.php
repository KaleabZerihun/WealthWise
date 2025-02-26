<?php

use App\Models\User;
use App\Models\Advisor;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component
{
    // Common fields for user/admin
    public string $first_name = '';
    public string $last_name = '';
    public string $email = '';
    public string $phone = '';
    public string $country = '';
    public string $state = '';
    public string $street_address = '';
    public string $gender = '';
    public string $zip_code = '';

    // Advisor-only fields
    public string $specialization = '';
    public string $certification = '';
    public string $years_of_experience = '';

    /**
     * Mount the component and fill fields from the logged-in user.
     */
    public function mount(): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        // Common: first name, last name, email
        $this->first_name = $user->first_name ?? '';
        $this->last_name  = $user->last_name  ?? '';
        $this->email      = $user->email      ?? '';

        // If user_type is 'user' or 'admin', we assume they have address/phone/gender
        if ($user->user_type === 'user' || $user->user_type === 'admin') {
            $this->phone           = $user->phone           ?? '';
            $this->country         = $user->country         ?? '';
            $this->state           = $user->state           ?? '';
            $this->street_address  = $user->street_address  ?? '';
            $this->gender          = $user->gender          ?? '';
            $this->zip_code        = $user->zip_code        ?? '';
        }

        // Advisor only
        if ($user->user_type === 'advisor') {
            $this->specialization      = $user->specialization      ?? '';
            $this->certification       = $user->certification       ?? '';
            $this->years_of_experience = $user->years_of_experience ?? '';
            $this->phone               = $user->phone               ?? '';
        }
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        // Base rules for name
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
        ];

        // Determine user_type for email uniqueness
        if ($user->user_type === 'user') {
            $rules['email'] = [
                'required','string','lowercase','email','max:255',
                Rule::unique(User::class)->ignore($user->id),
            ];
            // Add fields for a normal user
            $rules['phone']          = ['required','string','max:255'];
            $rules['country']        = ['required','string','max:255'];
            $rules['state']          = ['required','string','max:255'];
            $rules['street_address'] = ['required','string','max:255'];
            $rules['gender']         = ['required','string','max:255'];
            $rules['zip_code']       = ['required','string','max:255'];

        } elseif ($user->user_type === 'advisor') {
            $rules['email'] = [
                'required','string','lowercase','email','max:255',
                Rule::unique(Advisor::class)->ignore($user->id),
            ];
            // Advisor-only fields
            $rules['specialization']       = ['required','string','max:255'];
            $rules['certification']        = ['required','string','max:255'];
            $rules['years_of_experience']  = ['required','string','max:255'];
            $rules['phone']                = ['required','string','max:255'];

        } else { // admin
            $rules['email'] = [
                'required','string','lowercase','email','max:255',
                Rule::unique(Admin::class)->ignore($user->id),
            ];
        }

        // Validate
        $validated = $this->validate($rules);

        // Fill user record
        $user->fill($validated);

        // If email changed, reset email verification
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Re-login to ensure user remains authenticated
        $guard = null;
        if (Auth::guard('advisor')->check()) {
            $guard = 'advisor';
        } elseif (Auth::guard('admin')->check()) {
            $guard = 'admin';
        } else {
            $guard = 'web'; // normal user
        }
        Auth::guard($guard)->login($user);

        // Dispatch event + reload page
        $this->dispatch('profile-updated', name: $user->name);
        session()->flash('message','Information Updated successfully!');
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function sendVerification(): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $user->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }
};

?>

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
            <!-- Admin fields -->
            <div>
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input wire:model="first_name" id="first_name" class="block mt-1 w-full" type="text"
                              name="first_name" required autofocus autocomplete="first_name"/>
                <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="last_name" :value="__('Last Name')" />
                <x-text-input wire:model="last_name" id="last_name" class="block mt-1 w-full" type="text"
                              name="last_name" required autofocus autocomplete="last_name"/>
                <x-input-error :messages="$errors->get('last_name')" class="mt-2"/>
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email"
                              name="email" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>



        @elseif(Auth::guard('advisor')->check())
            <!-- Advisor fields -->
            <div>
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input wire:model="first_name" id="first_name" class="block mt-1 w-full" type="text"
                              name="first_name" required autofocus autocomplete="first_name"/>
                <x-input-error :messages="$errors->get('first_name')" class="mt-2"/>
            </div>

            <div>
                <x-input-label for="last_name" :value="__('Last Name')" />
                <x-text-input wire:model="last_name" id="last_name" class="block mt-1 w-full" type="text"
                              name="last_name" required autofocus autocomplete="last_name"/>
                <x-input-error :messages="$errors->get('last_name')" class="mt-2"/>
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email"
                              name="email" required autocomplete="username"/>
                <x-input-error :messages="$errors->get('email')" class="mt-2"/>
            </div>

            <!-- Advisor-specific fields -->
            <div>
                <x-input-label for="specialization" :value="__('Specialization')" />
                <x-text-input wire:model="specialization" id="specialization" class="block mt-1 w-full" type="text"
                              name="specialization" required />
                <x-input-error :messages="$errors->get('specialization')" class="mt-2"/>
            </div>

            <div>
                <x-input-label for="certification" :value="__('Certification')" />
                <x-text-input wire:model="certification" id="certification" class="block mt-1 w-full" type="text"
                              name="certification" required />
                <x-input-error :messages="$errors->get('certification')" class="mt-2"/>
            </div>

            <div>
                <x-input-label for="years_of_experience" :value="__('Years of Experience')" />
                <x-text-input wire:model="years_of_experience" id="years_of_experience" class="block mt-1 w-full"
                              type="text" name="years_of_experience" required />
                <x-input-error :messages="$errors->get('years_of_experience')" class="mt-2"/>
            </div>

            <div>
                <x-input-label for="phone" :value="__('Phone Number')" />
                <x-text-input wire:model="phone" id="phone" class="block mt-1 w-full" type="text"
                              name="phone" required />
                <x-input-error :messages="$errors->get('phone')" class="mt-2"/>
            </div>

        @else
            <!-- Normal user fields -->
            <div>
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input wire:model="first_name" id="first_name" class="block mt-1 w-full" type="text"
                              name="first_name" required autofocus autocomplete="first_name"/>
                <x-input-error :messages="$errors->get('first_name')" class="mt-2"/>
            </div>

            <div>
                <x-input-label for="last_name" :value="__('Last Name')" />
                <x-text-input wire:model="last_name" id="last_name" class="block mt-1 w-full" type="text"
                              name="last_name" required autofocus autocomplete="last_name"/>
                <x-input-error :messages="$errors->get('last_name')" class="mt-2"/>
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email"
                              name="email" required autocomplete="username"/>
                <x-input-error :messages="$errors->get('email')" class="mt-2"/>
            </div>

            <div>
                <x-input-label for="phone" :value="__('Phone Number')" />
                <x-text-input wire:model="phone" id="phone" class="block mt-1 w-full" type="text"
                              name="phone" required autofocus autocomplete="phone"/>
                <x-input-error :messages="$errors->get('phone')" class="mt-2"/>
            </div>

            <div>
                <x-input-label for="country" :value="__('Country')" />
                <x-text-input wire:model="country" id="country" class="block mt-1 w-full" type="text"
                              name="country" required autofocus autocomplete="country"/>
                <x-input-error :messages="$errors->get('country')" class="mt-2"/>
            </div>

            <div>
                <x-input-label for="state" :value="__('State')" />
                <x-text-input wire:model="state" id="state" class="block mt-1 w-full" type="text"
                              name="state" required autofocus autocomplete="state"/>
                <x-input-error :messages="$errors->get('state')" class="mt-2"/>
            </div>

            <div>
                <x-input-label for="street_address" :value="__('Street Address')" />
                <x-text-input wire:model="street_address" id="street_address" class="block mt-1 w-full" type="text"
                              name="street_address" required autofocus autocomplete="street_address"/>
                <x-input-error :messages="$errors->get('street_address')" class="mt-2"/>
            </div>

            <div>
                <x-input-label for="zip_code" :value="__('Zip Code')" />
                <x-text-input wire:model="zip_code" id="zip_code" class="block mt-1 w-full" type="text"
                              name="zip_code" required autofocus autocomplete="zip_code"/>
                <x-input-error :messages="$errors->get('zip_code')" class="mt-2"/>
            </div>

            <div>
                <x-input-label for="gender" :value="__('Gender')" />
                <x-text-input wire:model="gender" id="gender" class="block mt-1 w-full" type="text"
                              name="gender" required autofocus autocomplete="gender"/>
                <x-input-error :messages="$errors->get('gender')" class="mt-2"/>
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            <!-- Success message -->
            @if(session('message'))
                <div class="mb-4 text-green-600 font-semibold">
                    {{ session('message') }}
                </div>
            @endif
        </div>
    </form>
</section>
