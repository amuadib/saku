<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Infolists;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Illuminate\Support\Arr;
use Filament\Forms\Components\Section;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $modelLabel = 'Pengguna';
    protected static ?string $navigationGroup = 'Master';
    protected static ?int $navigationSort = 9;
    protected static ?string $recordTitleAttribute = 'username';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\FileUpload::make('authable.foto')
                            ->nullable()
                            ->image()
                            ->imageEditor()
                            ->directory('foto'),
                        Radio::make('authable.lembaga_id')
                            ->label('Lembaga')
                            ->options(Arr::except(config('custom.lembaga'), [99]))
                            ->required(),
                        TextInput::make('authable.nama')
                            ->required(),
                        Radio::make('authable.jenis_kelamin')
                            ->options(['l' => 'Laki-laki', 'p' => 'Perempuan'])
                            ->inline()
                            ->inlineLabel(false)
                            ->required(),
                        Forms\Components\Textarea::make('authable.alamat')
                            ->columnSpanFull(),
                        TextInput::make('authable.telepon'),
                        TextInput::make('authable.email')
                            ->email(),
                    ])
                    ->columns(2),
                Section::make()
                    ->schema([
                        Forms\Components\Hidden::make('authable_id')
                            ->visible(fn (string $operation): bool => $operation == 'edit'),
                        TextInput::make('username')
                            ->required()
                            ->columnSpanFull()
                            ->readOnly(fn (string $operation): bool => $operation == 'edit'),
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->confirmed()
                            ->required(fn (string $operation): bool => $operation == 'create'),
                        TextInput::make('password_confirmation')
                            ->password()
                            ->revealable()
                            ->required(fn (string $operation): bool => $operation == 'create'),
                        Forms\Components\Select::make('role_id')
                            ->label('Status')
                            ->options(config('custom.roles'))
                            ->required(),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->rowIndex(),
                TextColumn::make('username'),
                TextColumn::make('authable.nama')
                    ->label('Nama')
                    ->searchable(),
                TextColumn::make('authable.lembaga_id')
                    ->label('Lembaga')
                    ->formatStateUsing(fn (string $state): string => config('custom.lembaga')[$state]),
                TextColumn::make('role_id')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => config('custom.roles')[$state])
            ])
            ->filters([
                //
            ])
            ->actions([
                Impersonate::make(),
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
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\ImageEntry::make('authable.foto')
                    ->label('Foto')
                    ->width(170)
                    ->height(230)
                    ->defaultImageUrl(url('/storage/no_photo.jpg'))
                    ->columnSpanFull(),
                TextEntry::make('username'),
                TextEntry::make('authable.nama')
                    ->label('Nama'),
                TextEntry::make('authable.lembaga_id')
                    ->label('Lembaga')
                    ->formatStateUsing(fn (string $state): string => config('custom.lembaga')[$state]),
                TextEntry::make('role_id')
                    ->label('Status')
                    ->formatStateUsing(fn (string $state): string => config('custom.roles')[$state]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
