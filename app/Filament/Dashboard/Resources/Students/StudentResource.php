<?php

namespace App\Filament\Dashboard\Resources\Students;

use App\Filament\Dashboard\Resources\Students\Pages\ManageStudents;
use App\Models\Student;
use App\Models\Teacher;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use UnitEnum;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Siswa';

    protected static ?int $navigationSort = 5;

    protected static ?string $recordTitleAttribute = 'user.name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255)
                    ->formatStateUsing(fn (?Student $record): ?string => $record?->user?->name),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->rules(function (?Teacher $record): array {
                        return [
                            Rule::unique('users', 'email')
                                ->ignore($record?->user_id),
                        ];
                    })
                    ->maxLength(255)
                    ->formatStateUsing(fn (?Student $record): ?string => $record?->user?->email),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->hiddenOn('edit')
                    ->maxLength(255),
                TextInput::make('nis')
                    ->label('NIS')
                    ->required()
                    ->maxLength(20),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name')
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Email'),
                TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'laki-laki' => 'Laki-Laki',
                        'perempuan' => 'Perempuan',
                        default => '-',
                    }),
                TextColumn::make('address')
                    ->label('Alamat'),
                TextColumn::make('user.email_verified_at')
                    ->label('Verifikasi Email')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageStudents::route('/'),
        ];
    }
}
