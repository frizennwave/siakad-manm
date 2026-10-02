<?php

namespace App\Filament\Dashboard\Resources\Teachers\RelationManagers;

use App\Filament\Dashboard\Resources\Teachers\TeacherResource;
use App\Models\AcademicYear;
use App\Models\ClassRoom;
use App\Models\TeachingAssignment;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TeachingAssignmentResourceRelationManager extends RelationManager
{
    protected static string $relationship = 'TeachingAssignments';

    protected static ?string $relatedResource = TeacherResource::class;

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('subject_id')
                    ->label('Mata Pelajaran')
                    ->relationship(
                        name: 'subject',
                        titleAttribute: 'name',
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('class_room_id')
                    ->label('Kelas')
                    ->relationship(
                        name: 'classRoom',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query->whereHas(
                            'academic_year',
                            fn (Builder $query) => $query->where('status', true)
                        ),
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn (ClassRoom $record): string => $record->name.
                            ' - '.
                            ($record->academic_year?->year ?? '-')
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('semester')
                    ->label('Semester')
                    ->options([
                        1 => 'Semester 1',
                        2 => 'Semester 2',
                    ])
                    ->native(false)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject.name')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('classRoom.name')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('classRoom.academic_year.year')
                    ->label('Tahun Ajaran')
                    ->sortable(),

                TextColumn::make('semester')
                    ->label('Semester')
                    ->formatStateUsing(
                        fn ($state): string => $state == 1
                                ? 'Semester 1'
                                : 'Semester 2'
                    )
                    ->sortable(),
            ])

            ->filters([
                SelectFilter::make('subject_id')
                    ->label('Mata Pelajaran')
                    ->relationship(
                        name: 'subject',
                        titleAttribute: 'name',
                    )
                    ->searchable()
                    ->preload(),

                SelectFilter::make('class_room_id')
                    ->label('Kelas')
                    ->relationship(
                        name: 'classRoom',
                        titleAttribute: 'name',
                    )
                    ->getOptionLabelFromRecordUsing(
                        fn (ClassRoom $record): string => $record->name.
                            ' - '.
                            ($record->academic_year?->year ?? '-')
                    )
                    ->searchable()
                    ->preload(),

                SelectFilter::make('academic_year_id')
                    ->label('Tahun Ajaran')
                    ->options(
                        AcademicYear::query()
                            ->orderByDesc('year')
                            ->pluck('year', 'id')
                            ->toArray()
                    )
                    ->query(function (
                        Builder $query,
                        array $data
                    ): Builder {
                        return $query->when(
                            $data['value'] ?? null,
                            fn (
                                Builder $query,
                                $academicYearId
                            ): Builder => $query->whereHas(
                                'classRoom',
                                fn (Builder $query): Builder => $query->where(
                                    'academic_year_id',
                                    $academicYearId
                                )
                            )
                        );
                    }),

                SelectFilter::make('semester')
                    ->label('Semester')
                    ->options([
                        1 => 'Semester 1',
                        2 => 'Semester 2',
                    ]),
            ])

            ->recordActions([
                EditAction::make()
                    ->before(function (
                        array $data,
                        TeachingAssignment $record,
                        EditAction $action
                    ): void {
                        $exists = TeachingAssignment::query()
                            ->where('teacher_id', $this->getOwnerRecord()->getKey())
                            ->where('subject_id', $data['subject_id'])
                            ->where('class_room_id', $data['class_room_id'])
                            ->where('semester', $data['semester'])
                            ->whereKeyNot($record->getKey())
                            ->exists();

                        if ($exists) {
                            Notification::make()
                                ->danger()
                                ->title('Penugasan Mengajar Duplikat')
                                ->body(
                                    'Guru ini sudah memiliki penugasan '.
                                    'untuk mata pelajaran, kelas, dan semester tersebut.'
                                )
                                ->send();

                            $action->halt();
                        }
                    }),
            ])

            ->headerActions([
                CreateAction::make()
                    ->before(function (
                        array $data,
                        CreateAction $action
                    ): void {
                        $teacherId = $this->getOwnerRecord()->getKey();

                        $exists = TeachingAssignment::query()
                            ->where('teacher_id', $teacherId)
                            ->where('subject_id', $data['subject_id'])
                            ->where('class_room_id', $data['class_room_id'])
                            ->where('semester', $data['semester'])
                            ->exists();

                        if ($exists) {
                            Notification::make()
                                ->danger()
                                ->title('Penugasan Mengajar Duplikat')
                                ->body(
                                    'Guru ini sudah memiliki penugasan '.
                                    'untuk mata pelajaran, kelas, dan semester tersebut.'
                                )
                                ->send();

                            $action->halt();
                        }
                    }),
            ]);
    }
}
