<?php



use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts::auth')] class extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    public bool $remember = false;

    public function onSubmit()
    {
        $credentials = $this->validate();

        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();

            return redirect()->intended('/');
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }
};

?>

<div class="auth">
    <form wire:submit="onSubmit">
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" wire:model="email">
            @error('email') <p>{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" wire:model="password">
            @error('password') <p>{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="remember">Remember me</label>
            <input type="checkbox" id="remember" name="remember" wire:model="remember"/>
        </div>

        <button type="submit">Login</button>
    </form>
</div>
