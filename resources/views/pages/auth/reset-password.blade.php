<x-layouts::auth :title="__('Reset password')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Reset password')" :description="__('Please enter your new password below')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-6" id="reset-password-form"
            x-data="{
                password: '',
                submitting: false,
                get hasLength() { return this.password.length >= 8 },
                get hasUpper() { return /[A-Z]/.test(this.password) },
            }"
        >
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Email Address -->
            <flux:input
                name="email"
                value="{{ request('email') }}"
                :label="__('Email')"
                type="email"
                required
                autocomplete="email"
            />

            <!-- Password -->
            <flux:input
                name="password"
                x-model="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Password')"
                viewable
            />

            {{-- Password Requirements - Matching register page --}}
            <div class="flex flex-wrap gap-x-4 gap-y-1 px-1 -mt-4">
                <div class="flex items-center gap-1.5 text-[11px]">
                    <div class="w-3 h-3 rounded-full flex items-center justify-center border transition-colors duration-300"
                        x-bind:class="hasLength ? 'bg-emerald-500 border-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.3)]' : 'border-zinc-300 dark:border-zinc-600'">
                        <svg x-show="hasLength" xmlns="http://www.w3.org/2000/svg" class="size-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span x-bind:class="hasLength ? 'text-emerald-600 dark:text-emerald-400 font-medium' : 'text-zinc-400'">{{ __('8+ characters') }}</span>
                </div>
                <div class="flex items-center gap-1.5 text-[11px]">
                    <div class="w-3 h-3 rounded-full flex items-center justify-center border transition-colors duration-300"
                        x-bind:class="hasUpper ? 'bg-emerald-500 border-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.3)]' : 'border-zinc-300 dark:border-zinc-600'">
                        <svg x-show="hasUpper" xmlns="http://www.w3.org/2000/svg" class="size-2 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span x-bind:class="hasUpper ? 'text-emerald-600 dark:text-emerald-400 font-medium' : 'text-zinc-400'">{{ __('1 uppercase') }}</span>
                </div>
            </div>

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirm password')"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="reset-password-button"
                    x-on:click="submitting = true; $nextTick(() => $el.closest('form').submit())"
                    x-bind:disabled="submitting"
                >
                    <span x-show="!submitting" class="flex items-center gap-2">
                        <flux:icon name="key" class="size-4" />
                        {{ __('Reset password') }}
                    </span>
                    <span x-show="submitting" x-cloak class="flex items-center gap-2">
                        <flux:icon.loading class="size-4" />
                        {{ __('Resetting...') }}
                    </span>
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>
