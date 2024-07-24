<?php

namespace App\Filament\Resources\StudentResource\RelationManagers;

use App\Models\HomeRoom;
use App\Models\Periode;
use App\Models\StudentHasClass;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HomeroomRelationManager extends RelationManager
{
    protected static string $relationship = 'homeroom';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('homerooms_id')
                    ->label('Select Class')
                    ->options(
                        HomeRoom::with('teacher')->get()->mapWithKeys(function ($homeroom) {
                            return [$homeroom->id => $homeroom->teacher->name];
                        })
                    )
                    ->searchable(),
                Select::make('periode_id')
                    ->label('Select Periode')
                    ->options(Periode::all()->pluck('name','id'))
                    ->searchable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
        ->recordTitleAttribute('id')
        ->columns([
            TextColumn::make('homeroom.teacher.name')
                ->label('Teacher Name')
                ->sortable()
                ->searchable(),

            TextColumn::make('homeroom.classroom.name')
                ->label('Classroom Name')
                ->sortable()
                ->searchable(),

            TextColumn::make('periode.name')
                ->label('Period Name')
                ->sortable()
                ->searchable(),

            ToggleColumn::make('is_open')
                ->label('Is Open'),
        ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
