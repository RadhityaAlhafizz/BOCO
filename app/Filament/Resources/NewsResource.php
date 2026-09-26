<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Filament\Resources\NewsResource\RelationManagers;
use App\Models\News;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Set;
use Illuminate\Support\Str;

class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'News';

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->isAdmin()) {
            return $query; 
        }
        return $query->where('author_id', auth()->user()->author->id); 
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('author_id')
                    ->relationship('author','username')
                    ->default(fn () => auth()->user()->author?->id)
                    ->disabled(fn() => ! auth()->user()->isAdmin())
                    ->dehydrated()
                    ->required(),
                Forms\Components\Select::make('news_category_id')
                    ->relationship('newsCategory','title')
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state)))
                    ->required(),
                Forms\Components\TextInput::make('slug')
                    ->readOnly(),
                Forms\Components\FileUpload::make('thumbnail')
                    ->image()
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('content')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_featured')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail'),
                Tables\Columns\TextColumn::make('author.username'),
                Tables\Columns\TextColumn::make('newsCategory.title'),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\ToggleColumn::make('is_featured')
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('author_id')
                    ->relationship('author', 'username')
                    ->label('Select Author')
                    ->placeholder('All Authors'),
                Tables\Filters\SelectFilter::make('news_category_id')
                    ->relationship('newsCategory', 'title')
                    ->label('Select Category')
                    ->placeholder('All Categories')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
