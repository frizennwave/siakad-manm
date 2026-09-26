<?php

namespace App\Filament\Dashboard\Resources\Students\RelationManagers;

use App\Models\StudentEnrollment;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Override;

class EnrollmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';

    protected static ?string $title = 'Enrollments';

    #[Override]
    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('class_room_id')
                    ->label('Kelas')
                    ->relationship('classRoom', 'name')
                    ->getOptionLabelFromRecordUsing(
                        fn ($record): string => "{$record->name} - ".($record->academic_year->year ?? '-')
                    )
                    ->required()
                    ->searchable()
                    ->preload(),

                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required()
                    ->live(),

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

    public function table(Table $table): Table
    {
        return $table
            ->columns([
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
            ])
            ->defaultSort('start_date', 'desc')
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Enrollment')
                    ->before(function (CreateAction $action): void {
                        $student = $this->getOwnerRecord();

                        $hasActiveEnrollment = StudentEnrollment::query()
                            ->where('student_id', $student->getKey())
                            ->where('status', 'active')
                            ->exists();

                        if ($hasActiveEnrollment) {
                            Notification::make()
                                ->danger()
                                ->title('Enrollment tidak dapat ditambahkan')
                                ->body(
                                    'Siswa ini masih memiliki enrollment aktif. '.
                                    'Selesaikan enrollment sebelumnya terlebih dahulu.'
                                )
                                ->send();

                            $action->halt();
                        }
                    }),
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
                            ->where('student_id', $record->student_id)
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
}
