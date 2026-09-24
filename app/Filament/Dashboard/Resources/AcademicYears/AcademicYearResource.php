<?php

namespace App\Filament\Dashboard\Resources\AcademicYears;

use App\Filament\Dashboard\Resources\AcademicYears\Pages\ManageAcademicYears;
use App\Models\AcademicYear;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class AcademicYearResource extends Resource
{
    protected static ?string $model = AcademicYear::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Tahun Ajaran';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'year';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('year')
                    ->label('Tahun Ajaran')
                    ->placeholder('e.g. 2026/2027')
                    ->unique(ignoreRecord: true)
                    ->length(9)
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('year')
            ->columns([
                TextColumn::make('year')
                    ->label('Tahun Ajaran')
                    ->searchable()
                    ->sortable(),
                ToggleColumn::make('status')
                    ->label('Status')
                    ->onColor('success')
                    ->offColor('danger')
                    ->disabled(fn (AcademicYear $record): bool => $record->status)
                    ->updateStateUsing(function (
                        AcademicYear $record,
                        bool $state
                    ): bool {
                        if (! $state) {
                            return true;
                        }

                        DB::transaction(function () use ($record): void {
                            AcademicYear::query()
                                ->where('status', true)
                                ->where('id', '!=', $record->getKey())
                                ->update([
                                    'status' => false,
                                ]);

                            $record->update([
                                'status' => true,
                            ]);
                        });

                        return true;
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
                    ->successNotificationTitle(
                        'Tahun ajaran berhasil di ubah!'
                    ),

                DeleteAction::make()
                    ->disabled(
                        fn (AcademicYear $record): bool => $record->status
                    )
                    ->tooltip(
                        fn (AcademicYear $record): ?string => $record->status
                            ? 'Tahun ajaran aktif tidak dapat dihapus.'
                            : null
                    )
                    ->successNotificationTitle(
                        'Tahun ajaran berhasil dihapus!'
                    ),
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
            'index' => ManageAcademicYears::route('/'),
        ];
    }
}
