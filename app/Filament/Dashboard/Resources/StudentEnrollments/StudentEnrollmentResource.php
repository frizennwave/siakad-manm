<?php

namespace App\Filament\Dashboard\Resources\StudentEnrollments;

use App\Filament\Dashboard\Resources\StudentEnrollments\Pages\ManageStudentEnrollments;
use App\Models\StudentEnrollment;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class StudentEnrollmentResource extends Resource
{
    protected static ?string $model = StudentEnrollment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Student Enrollments';

    protected static ?int $navigationSort = 7;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_id')
                    ->label('Siswa')
                    ->relationship('student', 'id')
                    ->getOptionLabelFromRecordUsing(
                        fn ($record): string => $record->user->name
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('class_room_id')
                    ->label('Kelas')
                    ->relationship('classRoom', 'name')
                    ->getOptionLabelFromRecordUsing(
                        fn ($record): string => "{$record->name} - ".($record->academic_year?->year ?? '-')
                    )
                    ->required()
                    ->searchable()
                    ->preload(),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->live()
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Aktif',
                        'completed' => 'Selesai',
                        'transferred' => 'Pindah',
                        'withdrawn' => 'Mengundurkan Diri',
                        'graduated' => 'Lulus',
                    ])
                    ->default('active')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function (Set $set, ?string $state): void {
                        if ($state === 'active') {
                            $set('end_date', null);
                        }
                    }),
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai')
                    ->afterOrEqual('start_date')
                    ->required(fn (Get $get): bool => $get('status') !== 'active')
                    ->live(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('student.user.name')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('classRoom.name')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('classRoom.academic_year.year')
                    ->label('Tahun Ajaran')
                    ->sortable(),
                TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date('d M Y')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'completed' => 'Selesai',
                        'transferred' => 'Pindah',
                        'withdrawn' => 'Mengundurkan Diri',
                        'graduated' => 'Lulus',
                        default => $state,
                    }),
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
                EditAction::make()
                    ->before(function (
                        EditAction $action,
                        StudentEnrollment $record,
                        array $data
                    ): void {
                        if (($data['status'] ?? null) !== 'active') {
                            return;
                        }

                        $hasAnotherActiveEnrollment = StudentEnrollment::query()
                            ->where('student_id', $data['student_id'])
                            ->where('status', 'active')
                            ->whereKeyNot($record->getKey())
                            ->exists();

                        if ($hasAnotherActiveEnrollment) {
                            Notification::make()
                                ->danger()
                                ->title('Enrollment tidak dapat diaktifkan')
                                ->body(
                                    'Siswa ini masih memiliki enrollment aktif lain. '.
                                    'Selesaikan enrollment aktif tersebut terlebih dahulu.'
                                )
                                ->send();

                            $action->halt();
                        }
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageStudentEnrollments::route('/'),
        ];
    }
}
