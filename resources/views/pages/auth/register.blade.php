<div class="flex flex-col gap-6">
    {{-- Step Indicator --}}
    <div class="flex items-center justify-center gap-2 mb-2">
        @foreach ([1 => __('Account'), 2 => __('Organization'), 3 => __('Verify')] as $num => $label)
            <div class="flex items-center gap-2">
                <div class="flex items-center gap-1.5">
                    <div @class([
                        'w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all duration-300',
                        'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900 shadow-md scale-110' => $step === $num,
                        'bg-emerald-500 text-white shadow-sm' => $step > $num,
                        'bg-zinc-100 text-zinc-400 dark:bg-zinc-800 dark:text-zinc-500' => $step < $num,
                    ])>
                        @if ($step > $num)
                            <flux:icon name="check" class="size-4" />
                        @else
                            {{ $num }}
                        @endif
                    </div>
                    <span @class([
                        'text-xs font-medium transition-colors duration-300 hidden sm:inline',
                        'text-zinc-900 dark:text-white' => $step === $num,
                        'text-emerald-600 dark:text-emerald-400' => $step > $num,
                        'text-zinc-400 dark:text-zinc-500' => $step < $num,
                    ])>{{ $label }}</span>
                </div>
                @if ($num < 3)
                    <div @class([
                        'w-8 h-0.5 rounded-full transition-colors duration-500',
                        'bg-emerald-500' => $step > $num,
                        'bg-zinc-200 dark:bg-zinc-700' => $step <= $num,
                    ])></div>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Step 1: Account Details --}}
    @if ($step === 1)
        <div>
            <x-auth-header :title="__('Account Details')" :description="__('Create your account credentials')" />
        </div>

        <form wire:submit="nextStep" class="flex flex-col gap-4">
            <flux:input
                wire:model="username"
                :label="__('Username')"
                type="text"
                required
                autofocus
                autocomplete="username"
                :placeholder="__('Enter username')"
            />

            <flux:input
                wire:model="email"
                :label="__('Email')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@example.com"
            />

            <div class="grid grid-cols-2 gap-4">
                <flux:input
                    wire:model="name"
                    :label="__('First Name')"
                    type="text"
                    required
                    autocomplete="given-name"
                    :placeholder="__('First name')"
                />
                <flux:input
                    wire:model="surname"
                    :label="__('Last Name')"
                    type="text"
                    required
                    autocomplete="family-name"
                    :placeholder="__('Last name')"
                />
            </div>

            <flux:input
                wire:model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                viewable
            />

            {{-- Password Requirements --}}
            <div class="text-xs space-y-1 px-1 -mt-2">
                <p class="text-zinc-500 dark:text-zinc-400 font-medium">{{ __('Password must have:') }}</p>
                <div class="flex items-center gap-1.5">
                    <div @class([
                        'w-3.5 h-3.5 rounded-full flex items-center justify-center transition-colors',
                        'bg-emerald-500' => strlen($password) >= 8,
                        'bg-zinc-200 dark:bg-zinc-700' => strlen($password) < 8,
                    ])>
                        @if(strlen($password) >= 8)
                            <flux:icon name="check" class="size-2.5 text-white" />
                        @endif
                    </div>
                    <span @class([
                        'transition-colors',
                        'text-emerald-600 dark:text-emerald-400' => strlen($password) >= 8,
                        'text-zinc-400' => strlen($password) < 8,
                    ])>{{ __('At least 8 characters') }}</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <div @class([
                        'w-3.5 h-3.5 rounded-full flex items-center justify-center transition-colors',
                        'bg-emerald-500' => (bool) preg_match('/[A-Z]/', $password),
                        'bg-zinc-200 dark:bg-zinc-700' => !(bool) preg_match('/[A-Z]/', $password),
                    ])>
                        @if((bool) preg_match('/[A-Z]/', $password))
                            <flux:icon name="check" class="size-2.5 text-white" />
                        @endif
                    </div>
                    <span @class([
                        'transition-colors',
                        'text-emerald-600 dark:text-emerald-400' => (bool) preg_match('/[A-Z]/', $password),
                        'text-zinc-400' => !(bool) preg_match('/[A-Z]/', $password),
                    ])>{{ __('At least 1 uppercase letter (A-Z)') }}</span>
                </div>
            </div>

            <flux:input
                wire:model="password_confirmation"
                :label="__('Confirm Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                viewable
            />

            <flux:button type="submit" variant="primary" class="w-full mt-2">
                {{ __('Next') }}
                <flux:icon name="arrow-right" class="size-4 ml-1" />
            </flux:button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Already have an account?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
        </div>
    @endif

    {{-- Step 2: Organization Details --}}
    @if ($step === 2)
        <div>
            <x-auth-header :title="__('Organization Details')" :description="__('Tell us about your organization')" />
        </div>

        <form wire:submit="nextStep" class="flex flex-col gap-4">
            <flux:select wire:model.live="org_type" :label="__('Organization Type')" :placeholder="__('Select organization type')" searchable required>
                @foreach ($this->orgTypes as $value => $label)
                    <flux:select.option value="{{ $value }}">{{ $label }}</flux:select.option>
                @endforeach
            </flux:select>

            {{-- Other org type input --}}
            @if ($org_type === 'other')
                <div class="animate-in fade-in slide-in-from-top-2 duration-300">
                    <flux:input
                        wire:model="org_type_other"
                        :label="__('Specify Organization Type')"
                        type="text"
                        required
                        :placeholder="__('Enter your organization type')"
                    />
                </div>
            @endif

            <flux:select wire:model="province" :label="__('Province')" :placeholder="__('Select province')" searchable required>
                @foreach ($this->provinces as $value => $label)
                    <flux:select.option value="{{ $value }}">{{ $label }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:input
                wire:model="org_name"
                :label="__('Organization Name')"
                type="text"
                autocomplete="organization"
                :placeholder="__('Enter organization name')"
            />

            <flux:separator />

            {{-- LIS CMU Checkbox --}}
            <div>
                <flux:checkbox wire:model.live="is_lis_cmu"
                    :label="__('LIS Student, Faculty of Humanities, Chiang Mai University')" />

                @if ($is_lis_cmu)
                    <div class="mt-3 ml-6 animate-in fade-in slide-in-from-top-2 duration-300">
                        <flux:input
                            wire:model="student_id"
                            :label="__('Student ID')"
                            type="text"
                            required
                            :placeholder="__('Enter student ID')"
                        />
                    </div>
                @endif
            </div>

            {{-- Terms of Service --}}
            <flux:checkbox wire:model="terms"
                :label="__('I agree to the Terms of Service')" required />

            @error('terms')
                <p class="text-sm text-red-500">{{ $message }}</p>
            @enderror

            <div class="flex gap-3 mt-2">
                <flux:button wire:click="previousStep" type="button" variant="ghost" class="flex-1">
                    <flux:icon name="arrow-left" class="size-4 mr-1" />
                    {{ __('Back') }}
                </flux:button>
                <flux:button type="submit" variant="primary" class="flex-1">
                    {{ __('Create Account') }}
                    <flux:icon name="arrow-right" class="size-4 ml-1" />
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Already have an account?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
        </div>
    @endif

    {{-- Step 3: OTP Verification --}}
    @if ($step === 3)
        <form wire:submit="verifyOtp" class="space-y-8">
            <div class="max-w-64 mx-auto space-y-2">
                <flux:heading size="lg" class="text-center">{{ __('Verify your account') }}</flux:heading>
                <flux:text class="text-center">{{ __('Please enter the 6-digit code sent to your email.') }}</flux:text>
                <p class="text-center text-xs text-zinc-400 mt-1">{{ $email }}</p>
            </div>

            @if ($otpMessage)
                <div class="text-center text-sm text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/30 py-2 px-4 rounded-lg">
                    {{ $otpMessage }}
                </div>
            @endif

            <div class="space-y-6">
                <flux:otp wire:model="otp" length="6" submit="auto" class="mx-auto" />

                @error('otp')
                    <p class="text-sm text-red-500 text-center">{{ $message }}</p>
                @enderror
            </div>

            <div class="text-center">
                <flux:button wire:click="resendOtp" type="button" variant="ghost" size="sm">
                    <flux:icon name="arrow-path" class="size-4 mr-1" />
                    {{ __('Resend OTP') }}
                </flux:button>
            </div>
        </form>
    @endif
</div>
