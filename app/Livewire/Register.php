<?php

namespace App\Livewire;

use App\Models\EmailVerification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use Livewire\Component;

class Register extends Component
{
    // Current step (1, 2, or 3)
    public int $step = 1;

    // Step 1: Account Details
    public string $username = '';
    public string $email = '';
    public string $name = '';       // First Name
    public string $surname = '';    // Last Name
    public string $password = '';
    public string $password_confirmation = '';

    // Step 2: Organization Details
    public string $org_type = '';
    public string $org_type_other = '';
    public string $province = '';
    public string $org_name = '';
    public bool $is_lis_cmu = false;
    public string $student_id = '';
    public bool $terms = false;

    // Step 3: OTP
    public string $otp = '';
    public ?User $createdUser = null;

    // Flash messages
    public string $otpMessage = '';
    public bool $otpSent = false;

    /**
     * Organization type options
     */
    public function getOrgTypesProperty(): array
    {
        return [
            'university'          => __('มหาวิทยาลัย'),
            'high_school'         => __('โรงเรียนมัธยม'),
            'opportunity_school'  => __('โรงเรียนขยายโอกาส'),
            'primary_school'      => __('โรงเรียนประถม'),
            'private_company'     => __('บริษัทเอกชน'),
            'personal'            => __('ส่วนบุคคล'),
            'other'               => __('อื่นๆ'),
        ];
    }

    /**
     * All 77 Thai provinces
     */
    public function getProvincesProperty(): array
    {
        return [
            'กรุงเทพมหานคร' => 'กรุงเทพมหานคร',
            'กระบี่' => 'กระบี่',
            'กาญจนบุรี' => 'กาญจนบุรี',
            'กาฬสินธุ์' => 'กาฬสินธุ์',
            'กำแพงเพชร' => 'กำแพงเพชร',
            'ขอนแก่น' => 'ขอนแก่น',
            'จันทบุรี' => 'จันทบุรี',
            'ฉะเชิงเทรา' => 'ฉะเชิงเทรา',
            'ชลบุรี' => 'ชลบุรี',
            'ชัยนาท' => 'ชัยนาท',
            'ชัยภูมิ' => 'ชัยภูมิ',
            'ชุมพร' => 'ชุมพร',
            'เชียงราย' => 'เชียงราย',
            'เชียงใหม่' => 'เชียงใหม่',
            'ตรัง' => 'ตรัง',
            'ตราด' => 'ตราด',
            'ตาก' => 'ตาก',
            'นครนายก' => 'นครนายก',
            'นครปฐม' => 'นครปฐม',
            'นครพนม' => 'นครพนม',
            'นครราชสีมา' => 'นครราชสีมา',
            'นครศรีธรรมราช' => 'นครศรีธรรมราช',
            'นครสวรรค์' => 'นครสวรรค์',
            'นนทบุรี' => 'นนทบุรี',
            'นราธิวาส' => 'นราธิวาส',
            'น่าน' => 'น่าน',
            'บึงกาฬ' => 'บึงกาฬ',
            'บุรีรัมย์' => 'บุรีรัมย์',
            'ปทุมธานี' => 'ปทุมธานี',
            'ประจวบคีรีขันธ์' => 'ประจวบคีรีขันธ์',
            'ปราจีนบุรี' => 'ปราจีนบุรี',
            'ปัตตานี' => 'ปัตตานี',
            'พระนครศรีอยุธยา' => 'พระนครศรีอยุธยา',
            'พะเยา' => 'พะเยา',
            'พังงา' => 'พังงา',
            'พัทลุง' => 'พัทลุง',
            'พิจิตร' => 'พิจิตร',
            'พิษณุโลก' => 'พิษณุโลก',
            'เพชรบุรี' => 'เพชรบุรี',
            'เพชรบูรณ์' => 'เพชรบูรณ์',
            'แพร่' => 'แพร่',
            'ภูเก็ต' => 'ภูเก็ต',
            'มหาสารคาม' => 'มหาสารคาม',
            'มุกดาหาร' => 'มุกดาหาร',
            'แม่ฮ่องสอน' => 'แม่ฮ่องสอน',
            'ยโสธร' => 'ยโสธร',
            'ยะลา' => 'ยะลา',
            'ร้อยเอ็ด' => 'ร้อยเอ็ด',
            'ระนอง' => 'ระนอง',
            'ระยอง' => 'ระยอง',
            'ราชบุรี' => 'ราชบุรี',
            'ลพบุรี' => 'ลพบุรี',
            'ลำปาง' => 'ลำปาง',
            'ลำพูน' => 'ลำพูน',
            'เลย' => 'เลย',
            'ศรีสะเกษ' => 'ศรีสะเกษ',
            'สกลนคร' => 'สกลนคร',
            'สงขลา' => 'สงขลา',
            'สตูล' => 'สตูล',
            'สมุทรปราการ' => 'สมุทรปราการ',
            'สมุทรสงคราม' => 'สมุทรสงคราม',
            'สมุทรสาคร' => 'สมุทรสาคร',
            'สระแก้ว' => 'สระแก้ว',
            'สระบุรี' => 'สระบุรี',
            'สิงห์บุรี' => 'สิงห์บุรี',
            'สุโขทัย' => 'สุโขทัย',
            'สุพรรณบุรี' => 'สุพรรณบุรี',
            'สุราษฎร์ธานี' => 'สุราษฎร์ธานี',
            'สุรินทร์' => 'สุรินทร์',
            'หนองคาย' => 'หนองคาย',
            'หนองบัวลำภู' => 'หนองบัวลำภู',
            'อ่างทอง' => 'อ่างทอง',
            'อำนาจเจริญ' => 'อำนาจเจริญ',
            'อุดรธานี' => 'อุดรธานี',
            'อุตรดิตถ์' => 'อุตรดิตถ์',
            'อุทัยธานี' => 'อุทัยธานี',
            'อุบลราชธานี' => 'อุบลราชธานี',
        ];
    }

    /**
     * Validate and move to the next step
     */
    public function nextStep(): void
    {
        if ($this->step === 1) {
            $this->validate($this->step1Rules(), $this->customMessages());
            $this->step = 2;
        } elseif ($this->step === 2) {
            $this->validate($this->step2Rules(), $this->customMessages());
            $this->registerUser();
        }
    }

    /**
     * Go back to previous step
     */
    public function previousStep(): void
    {
        if ($this->step > 1) {
            $this->step--;
        }
    }

    /**
     * Register the user and generate OTP
     */
    private function registerUser(): void
    {
        $finalOrgType = $this->org_type === 'other' ? $this->org_type_other : $this->org_type;

        $user = User::create([
            'username'    => $this->username,
            'email'       => $this->email,
            'name'        => $this->name,
            'surname'     => $this->surname,
            'password'    => $this->password,
            'org_type'    => $this->org_type === 'other' ? 'other' : $this->org_type,
            'org_name'    => $this->org_name ?: null,
            'province'    => $this->province,
            'is_lis_cmu'  => $this->is_lis_cmu,
            'student_id'  => $this->is_lis_cmu ? $this->student_id : null,
            'is_verified' => false,
        ]);

        $this->createdUser = $user;
        $this->generateAndSendOtp();
        $this->step = 3;
    }

    /**
     * Generate OTP and save to email_verifications table
     */
    private function generateAndSendOtp(): void
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Invalidate previous OTPs
        EmailVerification::where('user_id', $this->createdUser->id)
            ->where('used', false)
            ->update(['used' => true]);

        EmailVerification::create([
            'user_id'    => $this->createdUser->id,
            'email'      => $this->createdUser->email,
            'code'       => $code,
            'expires_at' => now()->addMinutes(10),
            'used'       => false,
        ]);

        // TODO: Send email when Google App Password is configured
        // Mail::to($this->createdUser->email)->send(new \App\Mail\OtpVerificationMail($code));

        $this->otpSent = true;
        $this->otpMessage = __('OTP has been sent to your email.');
    }

    /**
     * Verify the OTP code
     */
    public function verifyOtp(): void
    {
        $this->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $verification = EmailVerification::where('user_id', $this->createdUser->id)
            ->where('code', $this->otp)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            $this->addError('otp', __('Invalid or expired OTP code. Please try again.'));
            return;
        }

        // Mark OTP as used
        $verification->update([
            'used' => true,
            'verified_at' => now(),
        ]);

        // Mark user as verified
        $this->createdUser->update([
            'is_verified' => true,
        ]);

        // Log in and redirect
        Auth::login($this->createdUser);
        event(new Registered($this->createdUser));

        $this->redirect(route('dashboard'), navigate: true);
    }

    /**
     * Resend OTP
     */
    public function resendOtp(): void
    {
        if ($this->createdUser) {
            $this->generateAndSendOtp();
            $this->otpMessage = __('A new OTP has been sent to your email.');
        }
    }

    /**
     * Step 1 validation rules
     */
    private function step1Rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:50', 'unique:users,username', 'alpha_dash'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'name'     => ['required', 'string', 'max:100'],
            'surname'  => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'regex:/[A-Z]/', 'confirmed'],
            'password_confirmation' => ['required'],
        ];
    }

    /**
     * Step 2 validation rules
     */
    private function step2Rules(): array
    {
        $rules = [
            'org_type' => ['required', 'string'],
            'province' => ['required', 'string'],
            'org_name' => ['nullable', 'string', 'max:255'],
            'terms'    => ['accepted'],
        ];

        if ($this->org_type === 'other') {
            $rules['org_type_other'] = ['required', 'string', 'max:255'];
        }

        if ($this->is_lis_cmu) {
            $rules['student_id'] = ['required', 'string', 'max:20'];
        }

        return $rules;
    }

    /**
     * Custom validation messages
     */
    private function customMessages(): array
    {
        return [
            'password.regex'     => __('Password must contain at least 1 uppercase letter (A-Z).'),
            'password.min'       => __('Password must be at least 8 characters.'),
            'terms.accepted'     => __('You must agree to the Terms of Service.'),
            'org_type_other.required' => __('Please specify your organization type.'),
            'student_id.required' => __('Please enter your student ID.'),
        ];
    }

    public function render()
    {
        return view('pages.auth.register');
    }
}
