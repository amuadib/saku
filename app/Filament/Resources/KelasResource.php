<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Filament\Resources\KelasResource\RelationManagers;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Arr;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Query\JoinClause;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;
    protected static ?string $navigationGroup = 'Master';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('periode_id')
                    ->relationship('periode', 'nama')
                    ->required(),
                Forms\Components\Radio::make('lembaga_id')
                    ->label('Lembaga')
                    ->inline()
                    ->inlineLabel(false)
                    ->options(Arr::except(config('custom.lembaga'), [99]))
                    ->required(),
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->modifyQueryUsing(function (Builder $query) {
                if (!auth()->user()->isAdmin()) {
                    return $query->where('lembaga_id', auth()->user()->authable->lembaga_id);
                }
            })
            ->columns([
                TextColumn::make('nama')
                    ->sortable()
                    ->label('Kelas'),
                TextColumn::make('periode.nama')
                    ->sortable(),
                TextColumn::make('lembaga_id')
                    ->label('Lembaga')
                    ->sortable()
                    ->formatStateUsing(fn(string $state): string => config('custom.lembaga')[$state]),
            ])
            ->filters([
                Filter::make('aktif')
                    ->label('Periode Aktif')
                    ->default()
                    ->toggle()
                    ->query(fn(Builder $query): Builder => $query->whereHas('periode', fn($query) => $query->where('aktif', true)))
                // ->query(fn (Builder $query): Builder => $query->where('periode.aktif', true))
                // ->query(function ($query) {
                //     $query->join('periode', function (JoinClause $join) {
                //         $join->on('periode.id', '=', 'periode_id');
                //     })->where('periode.aktif', true);
                // })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'view' => Pages\ViewKelas::route('/{record}'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }
}
