<?php

namespace App\Filament\Dashboard\Resources\ClassRooms;

use App\Filament\Dashboard\Resources\ClassRooms\Pages\ManageClassRooms;
use App\Models\ClassRoom;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ClassRoomResource extends Resource
{
    protected static ?string $model = ClassRoom::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Kelas';

    protected static ?int $navigationSort = 6;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('academic_year_id')
                    ->label('Tahun Ajaran')
                    ->relationship(
                        name: 'academic_year',
                        titleAttribute: 'year',
                        modifyQueryUsing: fn (Builder $query) => $query->where('status', true),
                    )
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('grade_level')
                    ->label('Kelas')
                    ->options([
                        10 => 'X',
                        11 => 'XI',
                        12 => 'XII',
                    ])
                    ->afterStateUpdated(function (Set $set, $state): void {
                        if ((int) $state === 10) {
                            $set('major_id', null);
                        }
                    })
                    ->required()
                    ->live(),
                Select::make('major_id')
                    ->label('Jurusan')
                    ->relationship('major', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->disabled(fn (Get $get): bool => (int) $get('grade_level') === 10),
                TextInput::make('name')
                    ->label('Nama Kelas')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('academic_year.year')
                    ->label('Tahun Ajaran')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('grade_level')
                    ->label('Kelas')
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        10 => 'X',
                        11 => 'XI',
                        12 => 'XII',
                        default => (string) $state,
                    })
                    ->sortable(),
                TextColumn::make('major.name')
                    ->label('Jurusan')
                    ->placeholder('-')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nama Kelas')
                    ->searchable()
                    ->sortable(),
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageClassRooms::route('/'),
        ];
    }
}
