<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Str;
use Livewire\Component;

class Register extends Component
{
    // Current step (1 or 2)
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

    // CAPTCHA — only the display values are public; the answer is in session
    public string $captcha_answer = '';
    public int $captcha_num1 = 0;
    public int $captcha_num2 = 0;
    public string $captcha_operator = '+';

    // Honeypot — should remain empty; bots fill it
    public string $website = '';

    public function mount(): void
    {
        $this->generateCaptcha();
    }

    /**
     * Generate a math captcha and store answer server-side.
     */
    public function generateCaptcha(): void
    {
        $this->captcha_num1 = random_int(1, 20);
        $this->captcha_num2 = random_int(1, 15);
        $operators = ['+', '-'];
        $this->captcha_operator = $operators[array_rand($operators)];

        // Ensure no negative results for subtraction
        if ($this->captcha_operator === '-' && $this->captcha_num2 > $this->captcha_num1) {
            [$this->captcha_num1, $this->captcha_num2] = [$this->captcha_num2, $this->captcha_num1];
        }

        // Store the correct answer in the session (NOT as a public Livewire property)
        $expected = match ($this->captcha_operator) {
            '+' => $this->captcha_num1 + $this->captcha_num2,
            '-' => $this->captcha_num1 - $this->captcha_num2,
        };
        session(['captcha_expected' => $expected]);

        $this->captcha_answer = '';
    }

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
     * Validate and move to the next step (with rate limiting)
     */
    public function nextStep(): void
    {
        // Honeypot check — bots fill hidden fields
        if (! empty($this->website)) {
            // Silently fail — don't reveal honeypot to attacker
            return;
        }

        // Rate limit: max 10 step transitions per minute per IP
        $key = 'register-step:' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 10)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError('username', __('Too many attempts. Please try again in :seconds seconds.', ['seconds' => $seconds]));
            return;
        }
        RateLimiter::hit($key, 60);

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
     * Register the user and log in directly
     */
    private function registerUser(): void
    {
        // Rate limit: max 3 account creations per IP per hour
        $regKey = 'register-create:' . request()->ip();
        if (RateLimiter::tooManyAttempts($regKey, 3)) {
            $seconds = RateLimiter::availableIn($regKey);
            $this->addError('captcha_answer', __('Too many registration attempts. Please try again in :seconds seconds.', ['seconds' => $seconds]));
            return;
        }

        // Verify CAPTCHA against session-stored answer (not client-exposed)
        $expected = session('captcha_expected');
        if ($expected === null || (int) $this->captcha_answer !== $expected) {
            $this->addError('captcha_answer', __('Incorrect answer. Please try again.'));
            $this->generateCaptcha();
            return;
        }

        // Hit rate limiter only on actual creation attempt
        RateLimiter::hit($regKey, 3600);

        // Sanitize text inputs
        $username    = strip_tags(trim($this->username));
        $email       = filter_var(trim($this->email), FILTER_SANITIZE_EMAIL);
        $name        = strip_tags(trim($this->name));
        $surname     = strip_tags(trim($this->surname));
        $orgName     = strip_tags(trim($this->org_name));
        $orgTypeOther = strip_tags(trim($this->org_type_other));
        $studentId   = strip_tags(trim($this->student_id));

        $user = User::create([
            'username'    => $username,
            'email'       => $email,
            'name'        => $name,
            'surname'     => $surname,
            'password'    => $this->password,
            'org_type'    => $this->org_type === 'other' ? 'other' : $this->org_type,
            'org_name'    => $orgName ?: null,
            'province'    => $this->province,
            'is_lis_cmu'  => $this->is_lis_cmu,
            'student_id'  => $this->is_lis_cmu ? $studentId : null,
        ]);

        // Set verified/active directly (not via mass assignment)
        $user->is_verified = true;
        $user->is_active = true;
        $user->save();

        // Clean up captcha session
        session()->forget('captcha_expected');

        // Log in and redirect
        Auth::login($user);
        event(new Registered($user));

        session()->regenerate();

        $this->redirect(route('dashboard'), navigate: true);
    }

    /**
     * Step 1 validation rules
     */
    private function step1Rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:50', 'unique:users,username', 'alpha_dash'],
            'email'    => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
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
            'org_type'       => ['required', 'string', 'in:university,high_school,opportunity_school,primary_school,private_company,personal,other'],
            'province'       => ['required', 'string'],
            'org_name'       => ['nullable', 'string', 'max:255'],
            'terms'          => ['accepted'],
            'captcha_answer' => ['required', 'integer'],
            'website'        => ['max:0'], // honeypot must be empty
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
            'password.regex'            => __('Password must contain at least 1 uppercase letter (A-Z).'),
            'password.min'              => __('Password must be at least 8 characters.'),
            'email.email'               => __('Please enter a valid email address.'),
            'terms.accepted'            => __('You must agree to the Terms of Service.'),
            'org_type.in'               => __('Please select a valid organization type.'),
            'org_type_other.required'   => __('Please specify your organization type.'),
            'student_id.required'       => __('Please enter your student ID.'),
            'captcha_answer.required'   => __('Please solve the security question.'),
            'captcha_answer.integer'    => __('Please enter a valid number.'),
            'website.max'               => __('Bot detected.'),
        ];
    }

    public function render()
    {
        return view('pages.auth.register');
    }
}
