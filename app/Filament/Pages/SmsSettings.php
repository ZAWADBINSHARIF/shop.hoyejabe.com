<?php

namespace App\Filament\Pages;

use App\Models\SmsConfiguration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SmsSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'SMS Settings';
    protected static ?string $title = 'SMS Settings';
    protected static ?string $slug = 'sms-settings';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationGroup = 'Settings';
    protected static string $view = 'filament.pages.sms-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $smsConfig = SmsConfiguration::first();

        $this->form->fill([
            'active_provider' => $smsConfig?->active_provider ?? 'smsq',
            'smsq_api_key' => $smsConfig?->smsq_api_key ?? '',
            'smsq_client_id' => $smsConfig?->smsq_client_id ?? '',
            'smsq_sender_id' => $smsConfig?->smsq_sender_id ?? '',
            'bulksmsbd_api_key' => $smsConfig?->bulksmsbd_api_key ?? '',
            'bulksmsbd_sender_id' => $smsConfig?->bulksmsbd_sender_id ?? '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Active SMS Provider')
                    ->schema([
                        Forms\Components\Radio::make('active_provider')
                            ->label('Select Active Provider')
                            ->options([
                                'smsq' => 'SMSQ',
                                'bulksmsbd' => 'BulkSMSBD',
                            ])
                            ->required()
                            ->inline()
                            ->helperText('Choose which SMS provider to use for sending messages'),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('SMSQ Configuration')
                    ->schema([
                        Forms\Components\TextInput::make('smsq_api_key')
                            ->label('API Key')
                            ->password()
                            ->revealable()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('smsq_client_id')
                            ->label('Client ID')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('smsq_sender_id')
                            ->label('Sender ID')
                            ->maxLength(255),
                    ])
                    ->columns(1)
                    ->collapsible(),

                Forms\Components\Section::make('BulkSMSBD Configuration')
                    ->schema([
                        Forms\Components\TextInput::make('bulksmsbd_api_key')
                            ->label('API Key')
                            ->password()
                            ->revealable()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('bulksmsbd_sender_id')
                            ->label('Sender ID')
                            ->maxLength(255),
                    ])
                    ->columns(1)
                    ->collapsible(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $smsConfig = SmsConfiguration::firstOrNew();
        $smsConfig->fill($data);
        $smsConfig->save();

        Notification::make()
            ->title('SMS Settings Updated')
            ->success()
            ->send();
    }
}
