<?php

namespace App\Filament\Pages;

use App\Enums\StoragePath;
use App\Enums\TextLength;
use App\Forms\Components\BasicEditor;
use App\Models\CompanyDetails as ModelsCompanyDetails;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class CompanyDetails extends Page
{

    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static string $view = 'filament.pages.company-details';

    public ?ModelsCompanyDetails $record = null;
    public array $data = [];


    public function mount(): void
    {

        $this->record = ModelsCompanyDetails::first() ?? new ModelsCompanyDetails();
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
                            FileUpload::make('logo')
                                ->image()
                                ->helperText('Upload a transparent')
                                ->imageEditor()
                                ->directory(StoragePath::LOGOS->value)
                                ->disk('public'),
                            Section::make("Width and Height of Logo")
                                ->schema([
                                    TextInput::make("width")
                                        ->numeric()
                                        ->minValue(0),
                                    TextInput::make("height")
                                        ->numeric()
                                        ->minValue(0)
                                ])->columns(2),
                            Section::make()
                                ->schema([
                                    Toggle::make('show_company_name'),
                                    TextInput::make('name')->maxLength(TextLength::SHORT->value)->required(),
                                ]),
                            BasicEditor::make('about')->maxLength(TextLength::LARGE->value)->required()
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
