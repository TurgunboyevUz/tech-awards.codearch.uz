<?php
namespace Filament\Resources;

use App\Models\Convertation;
use Filament\Forms\Form;
use Filament\Resources\ConvertationResource\Pages;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ConvertationResource extends Resource
{
    protected static ?string $model = Convertation::class;

    protected static ?string $label = 'konvertatsiya';

    protected static ?string $pluralLabel = 'Konvertatsiyalar';

    protected static ?string $navigationLabel = 'Konvertatsiyalar';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Foydalanuvchi')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Turi')
                    ->badge()
                    ->color(function ($state) {
                        return $state == 'buy' ? 'info' : 'primary';
                    })
                    ->formatStateUsing(function ($state) {
                        return $state == 'buy' ? 'Sotib olish' : 'Sotish';
                    }),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Miqdori')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('converted_amount')
                    ->label('Qabul qilingan miqdor')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('card_number')
                    ->label('Karta raqami')
                    ->placeholder('Mavjud emas')
                    ->searchable(),
                Tables\Columns\IconColumn::make('status')
                    ->icon(function ($state) {
                        return match ($state) {
                            0 => 'heroicon-o-clock',
                            1 => 'heroicon-o-check-circle',
                            2 => 'heroicon-o-x-circle',
                        };
                    })
                    ->color(function ($state) {
                        return match ($state) {
                            0 => 'secondary',
                            1 => 'success',
                            2 => 'danger',
                        };
                    }),
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
                Action::make('allow')
                    ->label('Tasdiqlash')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (Convertation $record) {
                        $record->status = 1;
                        $record->save();
                    })
                    ->visible(function (Convertation $record) {
                        return $record->status == 0;
                    }),

                Action::make('deny')
                    ->label('Rad etish')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function (Convertation $record) {
                        $record->status = 2;
                        $record->save();

                        $record->user->increment('balance', $record->amount);
                    })
                    ->visible(function (Convertation $record) {
                        return $record->status == 0;
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->recordUrl(null);
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
            'index'  => Pages\ListConvertations::route('/'),
            'create' => Pages\CreateConvertation::route('/create'),
            'edit'   => Pages\EditConvertation::route('/{record}/edit'),
        ];
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
