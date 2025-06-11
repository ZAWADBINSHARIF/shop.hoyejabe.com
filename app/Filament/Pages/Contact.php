<?php

namespace App\Filament\Pages;

use App\Models\Contact as ModelsContact;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Contact extends Page
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.contact';

    public ?ModelsContact $record = null;
    public array $data = [];

    public static function getNavigationSort(): ?int
    {
        return 10;
    }

    public function mount(): void
    {

        $this->record = ModelsContact::first() ?? new ModelsContact();
        $this->data = $this->record->toArray();
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $this->callHook('beforeFill');

        if ($this->record->exists)
            $this->form->fill($this->record->attributesToArray());
        else
            $this->form->fill();

        $this->callHook('afterFill');
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema(
                [
                    Section::make()
                        ->schema([
                            TextInput::make('mobile_number')
                                ->label('Mobile Number')
                                ->required()
                                ->placeholder("+8801XXX-XXXXXX")
                                ->tel(),

                            TextInput::make('email')
                                ->label('Email')
                                ->placeholder("youremail@email.com")
                                ->email(),

                            TextInput::make('facebook')
                                ->label('Facebook URL')
                                ->placeholder("https://www.facebook.com/username")
                                ->url(),

                            TextInput::make('messanger')
                                ->label('Messenger URL')
                                ->placeholder("m.me/username"),

                            TextInput::make('instagram')
                                ->label('Instagram URL')
                                ->placeholder("https://www.instagram.com/username")
                                ->url(),

                            TextInput::make('whatsapp')
                                ->label('WhatsApp Number')
                                ->placeholder("wa.me/+8801XXXXXXXXX"),

                            TextInput::make('office_location')
                                ->label('Office Location')
                                ->visible(false),
                        ])
                ]
            );
    }

    public function save(): void
    {
        $validatedData = $this->form->getState();

        $this->record->fill($validatedData);
        $this->record->save();

        Notification::make()
            ->title("Profile Saved successfully")
            ->success()
            ->send();
    }

    public function getFormActions(): array
    {
        return [
            Action::make("save")
                ->label(_("Save"))
                ->action('save')
        ];
    }
}
