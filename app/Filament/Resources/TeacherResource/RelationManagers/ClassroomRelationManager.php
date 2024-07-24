<?php

namespace App\Filament\Resources\TeacherResource\RelationManagers;

use App\Models\Classroom;
use App\Models\Periode;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassroomRelationManager extends RelationManager
{
    protected static string $relationship = 'classroom';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('classrooms_id')
                    ->label('Select Class')
                    ->options(Classroom::all()->pluck('name','id'))
                    ->searchable()
                    ->relationship(name: 'classroom', titleAttribute: 'name')
                    ->createOptionForm([
                        TextInput::make('name')
                        ->live()
                        ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                            if (($get('slug') ?? '') !== Str::slug($old)) {
                                return;
                            }
                        
                            $set('slug', Str::slug($state));
                        }),
                        TextInput::make('slug')
                    ])
                    ->createOptionAction(function (Forms\Components\Actions\Action $action){
                        return $action
                            ->modalHeading('Add ClassRoom')
                            ->modalButton('Add Classromm')
                            ->modalWidth('3xl');
                    }),
                Select::make('periode_id')
                    ->label('Select Periode')
                    ->options(Periode::all()->pluck('name','id'))
                    ->searchable()
                    ->relationship(name: 'periode', titleAttribute: 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('classroom.name'),
                TextColumn::make('periode.name'),
                ToggleColumn::make('is_open')
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
