<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Verification Code') }}</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f4f4f5; margin: 0; padding: 40px 20px;">
    <div style="max-width: 420px; margin: 0 auto; background: #ffffff; border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); overflow: hidden;">
        {{-- Header --}}
        <div style="background: #18181b; padding: 32px 32px 24px; text-align: center;">
            <h1 style="color: #ffffff; font-size: 20px; font-weight: 700; margin: 0; letter-spacing: -0.02em;">Babybib</h1>
        </div>

        {{-- Content --}}
        <div style="padding: 32px;">
            <h2 style="font-size: 18px; font-weight: 600; color: #18181b; margin: 0 0 8px;">{{ __('Verify your email address') }}</h2>
            <p style="font-size: 14px; color: #71717a; margin: 0 0 24px; line-height: 1.6;">
                {{ __('Please use the following verification code to complete your registration:') }}
            </p>

            {{-- OTP Code --}}
            <div style="background: #f4f4f5; border-radius: 12px; padding: 20px; text-align: center; margin-bottom: 24px;">
                <span style="font-size: 32px; font-weight: 700; letter-spacing: 8px; color: #18181b; font-family: monospace;">{{ $code }}</span>
            </div>

            <p style="font-size: 13px; color: #a1a1aa; margin: 0; line-height: 1.5;">
                {{ __('This code will expire in 10 minutes. If you did not request this, please ignore this email.') }}
            </p>
        </div>

        {{-- Footer --}}
        <div style="padding: 20px 32px; border-top: 1px solid #f4f4f5; text-align: center;">
            <p style="font-size: 12px; color: #a1a1aa; margin: 0;">&copy; {{ date('Y') }} Babybib. {{ __('All rights reserved.') }}</p>
        </div>
    </div>
</body>
</html>
