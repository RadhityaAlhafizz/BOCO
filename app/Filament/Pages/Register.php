<?php

namespace App\Filament\Pages;

use App\Models\Author;
use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Events\Auth\Registered;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\RegistrationResponse;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Support\Facades\Hash;

class Register extends BaseRegister
{
    public function form(Form $form): Form
    {
        return $form
        ->schema([
            TextInput::make('name')
                ->label('Nama')
                ->required()
                ->maxLength(255),
            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->maxLength(255)
                ->unique(User::class),
            TextInput::make('username')
                ->label('Username')
                ->required()
                ->maxLength(255)
                ->unique(Author::class),
            TextInput::make('password')
                ->label('password')
                ->required()
                ->password()
                ->minLength(8),
            TextInput::make('passwordConfirmation')
                ->label('Konfirmasi password')
                ->required()
                ->password()
                ->minLength(8)
                ->dehydrated(false),
            FileUpload::make('avatar')
                ->label('Avatar')
                ->image()
                ->required()
                ->disk('public')
                ->directory('avatars'),
            Textarea::make('bio')
                ->label('Bio')
                ->required()
                ->maxLength(1000)
                ->rows(3),
        ])->statePath('data');
    }

    public function register(): ?RegistrationResponse
    {
        try{
            $this->rateLimit(5);
        }catch(TooManyRequestsException $exception) {
            Notification::make()
                ->title('Terlalu Banyak Percobaan')
                ->body('Coba ulang kembali dalam' . $exception->secondsUntilAvailable . 'detik.')
                ->success()
                ->send();

            return null;
        }
            $data = $this->form->getState();

            $user = User::create([
                'name' => $data['name'],
                'email'=> $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'author',
            ]);

            $author = Author::create([
                'user_id' => $user->id,
                'username' => $data['username'],
                'avatar' => $data['avatar'],
                'bio' => $data['bio']
            ]);

            event(new Registered($user));

            $this->form->fill();

            Notification::make()
                ->title('Registrasi Berhasil')
                ->success()
                ->send();

            return app(RegistrationResponse::class);
    }

}
