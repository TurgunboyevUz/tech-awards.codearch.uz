<?php
namespace Filament\Resources;

use App\Models\Currency;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\CurrencyResource\Pages;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CurrencyResource extends Resource
{
    protected static ?string $model = Currency::class;

    protected static ?string $label = 'valyuta';

    protected static ?string $pluralLabel = 'Valyutalar';

    protected static ?string $navigationLabel = 'Valyutalar';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Ismi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('buy_price')
                    ->label('Sotilish kursi')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sell_price')
                    ->label('Sotib olish kursi')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Ismi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Simvoli')
                    ->searchable(),
                Tables\Columns\TextColumn::make('buy_price')
                    ->label('Sotilish kursi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sell_price')
                    ->label('Sotib olish kursi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Yaratilgan vaqti')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Yangilangan vaqti')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
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
            'index'  => Pages\ListCurrencies::route('/'),
            'create' => Pages\CreateCurrency::route('/create'),
            'edit'   => Pages\EditCurrency::route('/{record}/edit'),
        ];
    }
}
