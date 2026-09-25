<?php

namespace App\Filament\Dashboard\Resources\Teachers;

use App\Filament\Dashboard\Resources\Teachers\Pages\ManageTeachers;
use App\Models\Teacher;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use UnitEnum;

class TeacherResource extends Resource
{
    protected static ?string $model = Teacher::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Guru & Tendik';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'user.name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255)
                    ->formatStateUsing(fn (?Teacher $record): ?string => $record?->user?->name),
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
                    ->formatStateUsing(fn (?Teacher $record): ?string => $record?->user?->email),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->hiddenOn('edit')
                    ->maxLength(255),
                TextInput::make('position')
                    ->label('Jabatan')
                    ->required()
                    ->maxLength(100),
                Toggle::make('status')
                    ->label('Status')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
                    ->columnSpanFull(),
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
                TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('position')
                    ->label('Jabatan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'laki-laki' => 'Laki-Laki',
                        'perempuan' => 'Perempuan',
                        default => '-',
                    }),
                TextColumn::make('address')
                    ->label('Alamat')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('joined_at')
                    ->label('Tanggal Masuk')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                ToggleColumn::make('status')
                    ->label('Status')
                    ->onColor('success')
                    ->offColor('danger'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->using(function (Teacher $record, array $data): Teacher {
                        return DB::transaction(function () use ($record, $data): Teacher {
                            $user = $record->user;

                            $emailChanged = strcasecmp($user->email, $data['email']) !== 0;

                            $user->update([
                                'name' => $data['name'],
                                'email' => $data['email'],
                                'email_verified_at' => $emailChanged ? null : $user->email_verified_at,
                            ]);

                            $record->update([
                                'position' => $data['position'],
                                'status' => $data['status'],
                            ]);

                            if ($emailChanged) {
                                DB::afterCommit(function () use ($user): void {
                                    $user->sendEmailVerificationNotification();
                                });
                            }

                            return $record;
                        });
                    })
                    ->successNotificationTitle('Data guru berhasil diubah!'),
                DeleteAction::make()
                    ->successNotificationTitle('Data guru berhasil dihapus!'),
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
            'index' => ManageTeachers::route('/'),
        ];
    }
}
