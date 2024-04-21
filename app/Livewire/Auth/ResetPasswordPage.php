<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('Reset Password Page - E-commerce')]
class ResetPasswordPage extends Component
{
    use LivewireAlert;

    public $token;
    #[Url]
    public $email;
    public $password;
    public $password_confirmation;

    public function save()
    {
        $this->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset([
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'token' => $this->token,
        ], function ($user) {
            $user
                ->forceFill([
                    'password' => Hash::make($this->password),
                ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        });

        if ($status === Password::PASSWORD_RESET) {
            session()->flash('success', 'Password reset successfully.');

            return redirect()->route('login');
        }

        $this->alert('error', __($status), [
            'position' => 'top-end',
            'timer' => 4000,
            'toast' => true,
        ]);
    }

    public function mount($token)
    {
        $this->token = $token;
    }

    public function render()
    {
        return view('livewire.auth.reset-password-page');
    }
}
