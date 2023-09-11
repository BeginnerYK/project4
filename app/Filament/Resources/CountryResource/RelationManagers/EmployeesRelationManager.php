<?php

namespace App\Filament\Resources\CountryResource\RelationManagers;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\State;
use App\Models\Employee;
use App\Models\Country;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    protected static ?string $recordTitleAttribute = 'first_name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('country_id')
                ->label('Country')
                ->options(Country::all()->pluck('name', 'id')->toArray())
                ->required()
                ->reactive()
                ->afterStateUpdated(fn (callable $set) => $set('state_id', null)),
            Select::make('state_id')
                ->label('State')
                ->required()
                ->options(function (callable $get){
                  $country = Country::find($get('country_id'));
                  if(!$country){
                      return State::all()->pluck('name', 'id');
                  }
                  return $country->states->pluck('name', 'id');
                })
                ->reactive(),
            Select::make('city_id')
                ->label('City')
                ->options(function (callable $get){
                  $state = State::find($get('state_id'));
                  if(!$state){
                      return City::all()->pluck('name', 'id');
                  }
                  return $state->cities->pluck('name', 'id');
                })
                ->required()
                ->reactive(),                     
               Select::make('department_id')
               ->relationship('department', 'name')->required(), 
                  TextInput::make('first_name')->required(),
                  TextInput::make('last_name')->required(),
                  TextInput::make('address')->required(),
                  TextInput::make('zip_code')->required(),
                  DatePicker::make('birth_date')->required(),
                  DatePicker::make('date_hired')->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('first_name')->sortable()->searchable(),
                TextColumn::make('last_name')->sortable()->searchable(),
                TextColumn::make('department.name')->sortable(),
                TextColumn::make('date_hired')->date(),
                TextColumn::make('created_at')->dateTime()
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
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }    
}
