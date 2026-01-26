<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostOpRequestResource\Pages;
use App\Filament\Resources\PostOpRequestResource\RelationManagers;
use App\Models\PostOpRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostOpRequestResource extends Resource
{
    protected static ?string $model = PostOpRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('patient_id')
                    ->relationship('patient', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('bed_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'requested' => 'Requested',
                        'completed' => 'Completed',
                    ])
                    ->required()
                    ->default('requested'),
                Forms\Components\Hidden::make('added_by'),
                Forms\Components\Hidden::make('completed_by'),
                Forms\Components\Hidden::make('completed_at'),
                Forms\Components\Textarea::make('advice')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('bed_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('patient.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('advice')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'requested' => 'warning',
                        'completed' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('addedBy.name')
                    ->label('Added By'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\Filter::make('pending')
                    ->query(fn (Builder $query) => $query->where('status', 'requested'))
                    ->default(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('complete')
                    ->requiresConfirmation()
                    ->action(fn (PostOpRequest $record) => $record->update([
                        'status' => 'completed',
                        'completed_by' => auth()->id(),
                        'completed_at' => now(),
                    ]))
                    ->visible(fn (PostOpRequest $record) => $record->status === 'requested'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPostOpRequests::route('/'),
            'create' => Pages\CreatePostOpRequest::route('/create'),
            'edit' => Pages\EditPostOpRequest::route('/{record}/edit'),
        ];
    }
}
