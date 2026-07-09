<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

#[Signature('stockflow:generate-resources')]
#[Description('Génère toutes les Resources Filament (structure séparée) pour le projet StockFlow')]
class GenerateStockFlowResources extends Command
{
    private string $basePath;

    public function __construct()
    {
        parent::__construct();
        $this->basePath = app_path('Filament/Resources');
    }

    public function handle(): void
    {
        $this->info('🚀 Génération des Resources Filament StockFlow...');
        $this->info('');

        $this->generateBrandResource();
        $this->generateAttributeGroupResource();
        $this->generateAttributeResource();
        $this->generateAttributeValueResource();
        $this->generateCategoryResource();
        $this->generateWarehouseResource();
        $this->generateProductResource();
        $this->gener9yMnTm4NSzvG9rrwjM2ec8xZgh1cafXH8();
        $this->generateSupplierResource();
        $this->generateStockMovementResource();
        $this->generateSupplierOrderResource();
        $this->generateAlertResource();
        $this->generateActivityLogResource();

        $this->info('');
        $this->info('✅ Toutes les Resources ont été générées avec succès !');
        $this->info('📁 ' . $this->basePath);
        $this->warn('⚠️  Vérifiez les imports de modèles dans chaque Resource.');
    }

    // ─────────────────────────────────────────────────────────────
    //  Helper : crée l'arborescence et écrit les fichiers
    // ─────────────────────────────────────────────────────────────

    private function scaffold(string $folder, array $files): void
    {
        $root = $this->basePath . '/' . $folder;

        File::ensureDirectoryExists($root . '/Pages');
        File::ensureDirectoryExists($root . '/Schemas');
        File::ensureDirectoryExists($root . '/Tables');

        foreach ($files as $relativePath => $content) {
            File::put($root . '/' . $relativePath, $content);
        }

        $this->info("✓ {$folder}");
    }

    // ─────────────────────────────────────────────────────────────
    //  Stubs Pages
    // ─────────────────────────────────────────────────────────────

    private function stubList(string $folder, string $resource, string $class): string
    {
        return <<<PHP
<?php

namespace App\Filament\Resources\\{$folder}\Pages;

use App\Filament\Resources\\{$folder}\\{$resource};
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class {$class} extends ListRecords
{
    protected static string \$resource = {$resource}::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
PHP;
    }

    private function stubCreate(string $folder, string $resource, string $class): string
    {
        return <<<PHP
<?php

namespace App\Filament\Resources\\{$folder}\Pages;

use App\Filament\Resources\\{$folder}\\{$resource};
use Filament\Resources\Pages\CreateRecord;

class {$class} extends CreateRecord
{
    protected static string \$resource = {$resource}::class;
}
PHP;
    }

    private function stubEdit(string $folder, string $resource, string $class): string
    {
        return <<<PHP
<?php

namespace App\Filament\Resources\\{$folder}\Pages;

use App\Filament\Resources\\{$folder}\\{$resource};
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class {$class} extends EditRecord
{
    protected static string \$resource = {$resource}::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
PHP;
    }

    private function stubView(string $folder, string $resource, string $class): string
    {
        return <<<PHP
<?php

namespace App\Filament\Resources\\{$folder}\Pages;

use App\Filament\Resources\\{$folder}\\{$resource};
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class {$class} extends ViewRecord
{
    protected static string \$resource = {$resource}::class;

    protected function getHeaderActions(): array
    {
        return [Actions\EditAction::make()];
    }
}
PHP;
    }

    // ─────────────────────────────────────────────────────────────
    //  1. Brand
    // ─────────────────────────────────────────────────────────────

    private function generateBrandResource(): void
    {
        $this->scaffold('Brands', [

            'BrandResource.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Brands;

use App\Filament\Resources\Brands\Pages;
use App\Filament\Resources\Brands\Schemas\BrandForm;
use App\Filament\Resources\Brands\Tables\BrandsTable;
use App\Models\Brand;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;
    protected static  \BackedEnum|string|null $navigationIcon = 'heroicon-o-tag';
    protected static \UnitEnum|string|null $navigationGroup = 'Catalogue';
    protected static ?int $navigationSort = 10;
    protected static ?string $modelLabel = 'Marque';
    protected static ?string $pluralModelLabel = 'Marques';

    public static function form(Schema $schema): Schema
    {
        return BrandForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BrandsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit'   => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
PHP,

            'Schemas/BrandForm.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Brands\Schemas;

use App\Models\Brand;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informations générales')->schema([
                TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                    ),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(Brand::class, 'slug', ignoreRecord: true),
                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->columnSpanFull(),
            ])->columns(2),

            Section::make('Contact & localisation')->schema([
                TextInput::make('website')->label('Site web')->url(),
                TextInput::make('country')->label('Pays')->maxLength(100),
                TextInput::make('contact_email')->label('Email')->email(),
                TextInput::make('contact_phone')->label('Téléphone')->tel(),
            ])->columns(2),

            Section::make('Paramètres')->schema([
                FileUpload::make('logo')
                    ->label('Logo')
                    ->image()
                    ->directory('brands/logos')
                    ->columnSpanFull(),
                Textarea::make('notes')->label('Notes')->rows(2)->columnSpanFull(),
                TextInput::make('sort_order')->label("Ordre d'affichage")->numeric()->default(0),
                Toggle::make('is_active')->label('Actif')->default(true),
            ])->columns(2),
        ]);
    }
}
PHP,

            'Tables/BrandsTable.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Brands\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BrandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')->label('Logo')->circular(),
                TextColumn::make('name')->label('Nom')->searchable()->sortable(),
                TextColumn::make('country')->label('Pays')->searchable(),
                TextColumn::make('contact_email')->label('Email')->searchable(),
                TextColumn::make('sort_order')->label('Ordre')->sortable(),
                IconColumn::make('is_active')->label('Actif')->boolean(),
                TextColumn::make('created_at')->label('Créé le')
                    ->dateTime('d/m/Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Statut actif'),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('sort_order');
    }
}
PHP,

            'Pages/ListBrands.php'  => $this->stubList('Brands', 'BrandResource', 'ListBrands'),
            'Pages/CreateBrand.php' => $this->stubCreate('Brands', 'BrandResource', 'CreateBrand'),
            'Pages/EditBrand.php'   => $this->stubEdit('Brands', 'BrandResource', 'EditBrand'),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  2. AttributeGroup
    // ─────────────────────────────────────────────────────────────

    private function generateAttributeGroupResource(): void
    {
        $this->scaffold('AttributeGroups', [

            'AttributeGroupResource.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\AttributeGroups;

use App\Filament\Resources\AttributeGroups\Pages;
use App\Filament\Resources\AttributeGroups\Schemas\AttributeGroupForm;
use App\Filament\Resources\AttributeGroups\Tables\AttributeGroupsTable;
use App\Models\AttributeGroup;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AttributeGroupResource extends Resource
{
    protected static ?string $model = AttributeGroup::class;
    protected static  \BackedEnum|string|null $navigationIcon = 'heroicon-o-squares-2x2';
    protected static \UnitEnum|string|null $navigationGroup = 'Catalogue';
    protected static ?int $navigationSort = 20;
    protected static ?string $modelLabel = "Groupe d'attributs";
    protected static ?string $pluralModelLabel = "Groupes d'attributs";

    public static function form(Schema $schema): Schema
    {
        return AttributeGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttributeGroupsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAttributeGroups::route('/'),
            'create' => Pages\CreateAttributeGroup::route('/create'),
            'edit'   => Pages\EditAttributeGroup::route('/{record}/edit'),
        ];
    }
}
PHP,

            'Schemas/AttributeGroupForm.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\AttributeGroups\Schemas;

use App\Models\AttributeGroup;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AttributeGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Nom')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                    $operation === 'create' ? $set('slug', Str::slug($state)) : null
                ),
            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->unique(AttributeGroup::class, 'slug', ignoreRecord: true),
            TextInput::make('display_name')->label('Nom affiché')->maxLength(255),
            TextInput::make('description')->label('Description')->maxLength(255),
            TextInput::make('sort_order')->label('Ordre')->numeric()->default(0),
            Toggle::make('is_active')->label('Actif')->default(true),
        ]);
    }
}
PHP,

            'Tables/AttributeGroupsTable.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\AttributeGroups\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AttributeGroupsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nom')->searchable()->sortable(),
                TextColumn::make('display_name')->label('Nom affiché'),
                TextColumn::make('sort_order')->label('Ordre')->sortable(),
                IconColumn::make('is_active')->label('Actif')->boolean(),
                TextColumn::make('created_at')->label('Créé le')
                    ->dateTime('d/m/Y')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([TernaryFilter::make('is_active')->label('Actif')])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('sort_order');
    }
}
PHP,

            'Pages/ListAttributeGroups.php'  => $this->stubList('AttributeGroups', 'AttributeGroupResource', 'ListAttributeGroups'),
            'Pages/CreateAttributeGroup.php' => $this->stubCreate('AttributeGroups', 'AttributeGroupResource', 'CreateAttributeGroup'),
            'Pages/EditAttributeGroup.php'   => $this->stubEdit('AttributeGroups', 'AttributeGroupResource', 'EditAttributeGroup'),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  3. Attribute
    // ─────────────────────────────────────────────────────────────

    private function generateAttributeResource(): void
    {
        $this->scaffold('Attributes', [

            'AttributeResource.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Attributes;

use App\Filament\Resources\Attributes\Pages;
use App\Filament\Resources\Attributes\Schemas\AttributeForm;
use App\Filament\Resources\Attributes\Tables\AttributesTable;
use App\Models\Attribute;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AttributeResource extends Resource
{
    protected static ?string $model = Attribute::class;
    protected static  \BackedEnum|string|null $navigationIcon = 'heroicon-o-list-bullet';
    protected static \UnitEnum|string|null $navigationGroup = 'Catalogue';
    protected static ?int $navigationSort = 21;
    protected static ?string $modelLabel = 'Attribut';
    protected static ?string $pluralModelLabel = 'Attributs';

    public static function form(Schema $schema): Schema
    {
        return AttributeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttributesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAttributes::route('/'),
            'create' => Pages\CreateAttribute::route('/create'),
            'edit'   => Pages\EditAttribute::route('/{record}/edit'),
        ];
    }
}
PHP,

            'Schemas/AttributeForm.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Attributes\Schemas;

use App\Models\Attribute;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('attribute_group_id')
                ->label("Groupe d'attributs")
                ->relationship('attributeGroup', 'name')
                ->searchable()->preload()->required(),
            TextInput::make('name')
                ->label('Nom')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                    $operation === 'create' ? $set('slug', Str::slug($state)) : null
                ),
            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->unique(Attribute::class, 'slug', ignoreRecord: true),
            Select::make('type')
                ->label('Type')
                ->options([
                    'select'  => 'Sélection',
                    'color'   => 'Couleur',
                    'text'    => 'Texte',
                    'number'  => 'Nombre',
                    'boolean' => 'Oui/Non',
                ])
                ->default('select')->required(),
            ColorPicker::make('color')->label('Couleur'),
            TextInput::make('value')->label('Valeur par défaut')->maxLength(255),
            TextInput::make('sort_order')->label('Ordre')->numeric()->default(0),
            Toggle::make('is_filterable')->label('Filtrable')->default(true),
            Toggle::make('is_required')->label('Obligatoire')->default(false),
            Toggle::make('is_active')->label('Actif')->default(true),
        ]);
    }
}
PHP,

            'Tables/AttributesTable.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Attributes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AttributesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('attributeGroup.name')->label('Groupe')->sortable()->searchable(),
                TextColumn::make('name')->label('Nom')->searchable()->sortable(),
                BadgeColumn::make('type')->label('Type')
                    ->colors([
                        'primary' => 'select',
                        'warning' => 'color',
                        'success' => 'text',
                        'danger'  => 'number',
                    ]),
                ColorColumn::make('color')->label('Couleur'),
                IconColumn::make('is_filterable')->label('Filtrable')->boolean(),
                IconColumn::make('is_required')->label('Obligatoire')->boolean(),
                IconColumn::make('is_active')->label('Actif')->boolean(),
            ])
            ->filters([
                SelectFilter::make('attribute_group_id')
                    ->label('Groupe')->relationship('attributeGroup', 'name'),
                TernaryFilter::make('is_active')->label('Actif'),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
PHP,

            'Pages/ListAttributes.php'  => $this->stubList('Attributes', 'AttributeResource', 'ListAttributes'),
            'Pages/CreateAttribute.php' => $this->stubCreate('Attributes', 'AttributeResource', 'CreateAttribute'),
            'Pages/EditAttribute.php'   => $this->stubEdit('Attributes', 'AttributeResource', 'EditAttribute'),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  4. AttributeValue
    // ─────────────────────────────────────────────────────────────

    private function generateAttributeValueResource(): void
    {
        $this->scaffold('AttributeValues', [

            'AttributeValueResource.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\AttributeValues;

use App\Filament\Resources\AttributeValues\Pages;
use App\Filament\Resources\AttributeValues\Schemas\AttributeValueForm;
use App\Filament\Resources\AttributeValues\Tables\AttributeValuesTable;
use App\Models\AttributeValue;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AttributeValueResource extends Resource
{
    protected static ?string $model = AttributeValue::class;
    protected static  \BackedEnum|string|null $navigationIcon = 'heroicon-o-swatch';
    protected static \UnitEnum|string|null $navigationGroup = 'Catalogue';
    protected static ?int $navigationSort = 22;
    protected static ?string $modelLabel = "Valeur d'attribut";
    protected static ?string $pluralModelLabel = "Valeurs d'attributs";

    public static function form(Schema $schema): Schema
    {
        return AttributeValueForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttributeValuesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAttributeValues::route('/'),
            'create' => Pages\CreateAttributeValue::route('/create'),
            'edit'   => Pages\EditAttributeValue::route('/{record}/edit'),
        ];
    }
}
PHP,

            'Schemas/AttributeValueForm.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\AttributeValues\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AttributeValueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('attribute_id')
                ->label('Attribut')
                ->relationship('attribute', 'name')
                ->searchable()->preload()->required(),
            TextInput::make('value')
                ->label('Valeur')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                    $operation === 'create' ? $set('slug', Str::slug($state)) : null
                ),
            TextInput::make('slug')->label('Slug')->required()->maxLength(255),
            ColorPicker::make('color')->label('Couleur'),
            FileUpload::make('image')->label('Image')->image()->directory('attributes/values'),
            TextInput::make('sort_order')->label('Ordre')->numeric()->default(0),
            Toggle::make('is_active')->label('Actif')->default(true),
        ]);
    }
}
PHP,

            'Tables/AttributeValuesTable.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\AttributeValues\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AttributeValuesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('attribute.name')->label('Attribut')->sortable()->searchable(),
                TextColumn::make('value')->label('Valeur')->searchable()->sortable(),
                ColorColumn::make('color')->label('Couleur'),
                ImageColumn::make('image')->label('Image')->circular(),
                TextColumn::make('sort_order')->label('Ordre')->sortable(),
                IconColumn::make('is_active')->label('Actif')->boolean(),
            ])
            ->filters([
                SelectFilter::make('attribute_id')->label('Attribut')->relationship('attribute', 'name'),
                TernaryFilter::make('is_active')->label('Actif'),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
PHP,

            'Pages/ListAttributeValues.php'  => $this->stubList('AttributeValues', 'AttributeValueResource', 'ListAttributeValues'),
            'Pages/CreateAttributeValue.php' => $this->stubCreate('AttributeValues', 'AttributeValueResource', 'CreateAttributeValue'),
            'Pages/EditAttributeValue.php'   => $this->stubEdit('AttributeValues', 'AttributeValueResource', 'EditAttributeValue'),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  5. Category
    // ─────────────────────────────────────────────────────────────

    private function generateCategoryResource(): void
    {
        $this->scaffold('Categories', [

            'CategoryResource.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Categories;

use App\Filament\Resources\Categories\Pages;
use App\Filament\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static  \BackedEnum|string|null $navigationIcon = 'heroicon-o-folder';
    protected static \UnitEnum|string|null $navigationGroup = 'Catalogue';
    protected static ?int $navigationSort = 5;
    protected static ?string $modelLabel = 'Catégorie';
    protected static ?string $pluralModelLabel = 'Catégories';

    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit'   => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
PHP,

            'Schemas/CategoryForm.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Nom')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true)
                ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                    $operation === 'create' ? $set('slug', Str::slug($state)) : null
                ),
            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->unique(Category::class, 'slug', ignoreRecord: true),
            Select::make('parent_id')
                ->label('Catégorie parente')
                ->relationship('parent', 'name')
                ->searchable()->preload()->nullable(),
            Textarea::make('description')->label('Description')->rows(3)->columnSpanFull(),
            TextInput::make('icon')->label('Icône')->maxLength(255),
            ColorPicker::make('color')->label('Couleur'),
            TextInput::make('sort_order')->label('Ordre')->numeric()->default(0),
            Toggle::make('is_active')->label('Actif')->default(true),
        ]);
    }
}
PHP,

            'Tables/CategoriesTable.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nom')->searchable()->sortable(),
                TextColumn::make('parent.name')->label('Parente')->sortable(),
                ColorColumn::make('color')->label('Couleur'),
                TextColumn::make('sort_order')->label('Ordre')->sortable(),
                IconColumn::make('is_active')->label('Actif')->boolean(),
            ])
            ->filters([TernaryFilter::make('is_active')->label('Actif')])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('sort_order');
    }
}
PHP,

            'Pages/ListCategories.php'  => $this->stubList('Categories', 'CategoryResource', 'ListCategories'),
            'Pages/CreateCategory.php'  => $this->stubCreate('Categories', 'CategoryResource', 'CreateCategory'),
            'Pages/EditCategory.php'    => $this->stubEdit('Categories', 'CategoryResource', 'EditCategory'),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  6. Warehouse
    // ─────────────────────────────────────────────────────────────

    private function generateWarehouseResource(): void
    {
        $this->scaffold('Warehouses', [

            'WarehouseResource.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Warehouses;

use App\Filament\Resources\Warehouses\Pages;
use App\Filament\Resources\Warehouses\Schemas\WarehouseForm;
use App\Filament\Resources\Warehouses\Tables\WarehousesTable;
use App\Models\Warehouse;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class WarehouseResource extends Resource
{
    protected static ?string $model = Warehouse::class;
    protected static  \BackedEnum|string|null $navigationIcon = 'heroicon-o-building-office-2';
    protected static \UnitEnum|string|null $navigationGroup = 'Stock';
    protected static ?int $navigationSort = 30;
    protected static ?string $modelLabel = 'Entrepôt';
    protected static ?string $pluralModelLabel = 'Entrepôts';

    public static function form(Schema $schema): Schema
    {
        return WarehouseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WarehousesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWarehouses::route('/'),
            'create' => Pages\CreateWarehouse::route('/create'),
            'edit'   => Pages\EditWarehouse::route('/{record}/edit'),
        ];
    }
}
PHP,

            'Schemas/WarehouseForm.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Warehouses\Schemas;

use App\Models\Warehouse;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class WarehouseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informations')->schema([
                TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                    ),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(Warehouse::class, 'slug', ignoreRecord: true),
                Textarea::make('description')->label('Description')->rows(2)->columnSpanFull(),
            ])->columns(2),

            Section::make('Localisation')->schema([
                Textarea::make('address')->label('Adresse')->rows(2)->columnSpanFull(),
                TextInput::make('city')->label('Ville')->maxLength(100),
                TextInput::make('postal_code')->label('Code postal')->maxLength(20),
                TextInput::make('country')->label('Pays')->default('Togo')->maxLength(100),
            ])->columns(2),

            Section::make('Paramètres')->schema([
                Toggle::make('is_default')->label('Entrepôt par défaut')->default(false),
                Toggle::make('is_active')->label('Actif')->default(true),
            ])->columns(2),
        ]);
    }
}
PHP,

            'Tables/WarehousesTable.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Warehouses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class WarehousesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nom')->searchable()->sortable(),
                TextColumn::make('city')->label('Ville')->searchable(),
                TextColumn::make('country')->label('Pays'),
                IconColumn::make('is_default')->label('Par défaut')->boolean(),
                IconColumn::make('is_active')->label('Actif')->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Actif'),
                TrashedFilter::make(),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([
                DeleteBulkAction::make(),
                ForceDeleteBulkAction::make(),
                RestoreBulkAction::make(),
            ])]);
    }
}
PHP,

            'Pages/ListWarehouses.php'  => $this->stubList('Warehouses', 'WarehouseResource', 'ListWarehouses'),
            'Pages/CreateWarehouse.php' => $this->stubCreate('Warehouses', 'WarehouseResource', 'CreateWarehouse'),
            'Pages/EditWarehouse.php'   => $this->stubEdit('Warehouses', 'WarehouseResource', 'EditWarehouse'),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  7. Product
    // ─────────────────────────────────────────────────────────────

    private function generateProductResource(): void
    {
        $this->scaffold('Products', [

            'ProductResource.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages;
use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static  \BackedEnum|string|null $navigationIcon = 'heroicon-o-archive-box';
    protected static \UnitEnum|string|null $navigationGroup = 'Catalogue';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Produit';
    protected static ?string $pluralModelLabel = 'Produits';

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
PHP,

            'Schemas/ProductForm.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Product;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identification')->schema([
                TextInput::make('name')
                    ->label('Nom du produit')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                    )
                    ->columnSpanFull(),
                TextInput::make('sku')
                    ->label('SKU')
                    ->required()
                    ->unique(Product::class, 'sku', ignoreRecord: true),
                TextInput::make('barcode')
                    ->label('Code-barres')
                    ->unique(Product::class, 'barcode', ignoreRecord: true),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(Product::class, 'slug', ignoreRecord: true)
                    ->columnSpanFull(),
                Select::make('category_id')
                    ->label('Catégorie')
                    ->relationship('category', 'name')
                    ->searchable()->preload(),
                Select::make('brand_id')
                    ->label('Marque')
                    ->relationship('brand', 'name')
                    ->searchable()->preload(),
            ])->columns(2),

            Section::make('Description')->schema([
                Textarea::make('description')->label('Description')->rows(4)->columnSpanFull(),
                Textarea::make('specifications')->label('Spécifications techniques')->rows(3)->columnSpanFull(),
            ]),

            Section::make('Tarification')->schema([
                TextInput::make('purchase_price')->label("Prix d'achat")->numeric()->prefix('FCFA')->default(0),
                TextInput::make('selling_price')->label('Prix de vente HT')->numeric()->prefix('FCFA')->default(0),
                TextInput::make('tax_rate')->label('Taux TVA (%)')->numeric()->suffix('%')->default(18),
                TextInput::make('selling_price_with_tax')->label('Prix TTC')->numeric()->prefix('FCFA')->default(0),
            ])->columns(2),

            Section::make('Stock')->schema([
                TextInput::make('stock_quantity')->label('Quantité en stock')->numeric()->default(0),
                TextInput::make('min_stock_level')->label('Seuil min')->numeric()->default(0),
                TextInput::make('max_stock_level')->label('Seuil max')->numeric()->default(0),
                TextInput::make('unit')->label('Unité')->default('unité'),
                TextInput::make('weight')->label('Poids')->maxLength(20),
                TextInput::make('dimensions')->label('Dimensions')->maxLength(50),
            ])->columns(2),

            Section::make('Paramètres')->schema([
                Select::make('type')
                    ->label('Type de produit')
                    ->options(['simple' => 'Simple', 'configurable' => 'Configurable'])
                    ->default('simple')->required(),
                Select::make('stock_status')
                    ->label('Statut stock')
                    ->options([
                        'in_stock'     => 'En stock',
                        'low_stock'    => 'Stock faible',
                        'out_of_stock' => 'Rupture de stock',
                    ])->default('in_stock'),
                Toggle::make('is_active')->label('Actif')->default(true),
                Toggle::make('track_inventory')->label('Suivre les stocks')->default(true),
                Toggle::make('has_variants')->label('A des variantes')->default(false),
            ])->columns(2),
        ]);
    }
}
PHP,

            'Tables/ProductsTable.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')->label('SKU')->searchable()->sortable(),
                TextColumn::make('name')->label('Nom')->searchable()->sortable()->wrap(),
                TextColumn::make('category.name')->label('Catégorie')->sortable(),
                TextColumn::make('brand.name')->label('Marque')->sortable(),
                TextColumn::make('selling_price')->label('Prix vente')->money('XOF')->sortable(),
                TextColumn::make('stock_quantity')->label('Stock')->sortable(),
                BadgeColumn::make('stock_status')->label('Statut stock')
                    ->colors([
                        'success' => 'in_stock',
                        'warning' => 'low_stock',
                        'danger'  => 'out_of_stock',
                    ]),
                IconColumn::make('is_active')->label('Actif')->boolean(),
            ])
            ->filters([
                SelectFilter::make('category_id')->label('Catégorie')->relationship('category', 'name'),
                SelectFilter::make('brand_id')->label('Marque')->relationship('brand', 'name'),
                SelectFilter::make('stock_status')->label('Statut stock')
                    ->options(['in_stock' => 'En stock', 'low_stock' => 'Stock faible', 'out_of_stock' => 'Rupture']),
                TernaryFilter::make('is_active')->label('Actif'),
                TrashedFilter::make(),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([
                DeleteBulkAction::make(),
                ForceDeleteBulkAction::make(),
                RestoreBulkAction::make(),
            ])]);
    }
}
PHP,

            'Pages/ListProducts.php'  => $this->stubList('Products', 'ProductResource', 'ListProducts'),
            'Pages/CreateProduct.php' => $this->stubCreate('Products', 'ProductResource', 'CreateProduct'),
            'Pages/EditProduct.php'   => $this->stubEdit('Products', 'ProductResource', 'EditProduct'),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  8. ProductVariant
    // ─────────────────────────────────────────────────────────────

    private function gener9yMnTm4NSzvG9rrwjM2ec8xZgh1cafXH8(): void
    {
        $this->scaffold('ProductVariants', [

            'ProductVariantResource.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\ProductVariants;

use App\Filament\Resources\ProductVariants\Pages;
use App\Filament\Resources\ProductVariants\Schemas\ProductVariantForm;
use App\Filament\Resources\ProductVariants\Tables\ProductVariantsTable;
use App\Models\ProductVariant;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ProductVariantResource extends Resource
{
    protected static ?string $model = ProductVariant::class;
    protected static  \BackedEnum|string|null $navigationIcon = 'heroicon-o-queue-list';
    protected static \UnitEnum|string|null $navigationGroup = 'Catalogue';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Variante';
    protected static ?string $pluralModelLabel = 'Variantes';

    public static function form(Schema $schema): Schema
    {
        return ProductVariantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductVariantsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProductVariants::route('/'),
            'create' => Pages\CreateProductVariant::route('/create'),
            'edit'   => Pages\EditProductVariant::route('/{record}/edit'),
        ];
    }
}
PHP,

            'Schemas/ProductVariantForm.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\ProductVariants\Schemas;

use App\Models\ProductVariant;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductVariantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('product_id')
                ->label('Produit parent')
                ->relationship('product', 'name')
                ->searchable()->preload()->required(),
            TextInput::make('name')->label('Nom de la variante')->required()->maxLength(255),
            TextInput::make('sku')->label('SKU')->required()
                ->unique(ProductVariant::class, 'sku', ignoreRecord: true),
            TextInput::make('barcode')->label('Code-barres')
                ->unique(ProductVariant::class, 'barcode', ignoreRecord: true),
            TextInput::make('selling_price')->label('Prix de vente')->numeric()->prefix('FCFA'),
            TextInput::make('purchase_price')->label("Prix d'achat")->numeric()->prefix('FCFA'),
            TextInput::make('price_adjustment')->label('Ajustement prix')->numeric()->default(0),
            TextInput::make('stock_quantity')->label('Stock')->numeric()->default(0),
            TextInput::make('min_stock_level')->label('Seuil min')->numeric()->default(0),
            Select::make('stock_status')->label('Statut stock')
                ->options(['in_stock' => 'En stock', 'low_stock' => 'Stock faible', 'out_of_stock' => 'Rupture'])
                ->default('in_stock'),
            FileUpload::make('image')->label('Image')->image()->directory('products/variants'),
            Toggle::make('is_default')->label('Variante par défaut')->default(false),
            Toggle::make('is_active')->label('Actif')->default(true),
        ]);
    }
}
PHP,

            'Tables/ProductVariantsTable.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\ProductVariants\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductVariantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('Image')->circular(),
                TextColumn::make('product.name')->label('Produit')->searchable()->sortable(),
                TextColumn::make('name')->label('Variante')->searchable()->sortable(),
                TextColumn::make('sku')->label('SKU')->searchable(),
                TextColumn::make('selling_price')->label('Prix vente')->money('XOF'),
                TextColumn::make('stock_quantity')->label('Stock')->sortable(),
                BadgeColumn::make('stock_status')->label('Statut')
                    ->colors(['success' => 'in_stock', 'warning' => 'low_stock', 'danger' => 'out_of_stock']),
                IconColumn::make('is_default')->label('Défaut')->boolean(),
                IconColumn::make('is_active')->label('Actif')->boolean(),
            ])
            ->filters([
                SelectFilter::make('product_id')->label('Produit')->relationship('product', 'name'),
                SelectFilter::make('stock_status')->label('Statut stock')
                    ->options(['in_stock' => 'En stock', 'low_stock' => 'Stock faible', 'out_of_stock' => 'Rupture']),
                TernaryFilter::make('is_active')->label('Actif'),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
PHP,

            'Pages/ListProductVariants.php'  => $this->stubList('ProductVariants', 'ProductVariantResource', 'ListProductVariants'),
            'Pages/CreateProductVariant.php' => $this->stubCreate('ProductVariants', 'ProductVariantResource', 'CreateProductVariant'),
            'Pages/EditProductVariant.php'   => $this->stubEdit('ProductVariants', 'ProductVariantResource', 'EditProductVariant'),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  9. Supplier
    // ─────────────────────────────────────────────────────────────

    private function generateSupplierResource(): void
    {
        $this->scaffold('Suppliers', [

            'SupplierResource.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Suppliers;

use App\Filament\Resources\Suppliers\Pages;
use App\Filament\Resources\Suppliers\Schemas\SupplierForm;
use App\Filament\Resources\Suppliers\Tables\SuppliersTable;
use App\Models\Supplier;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;
    protected static  \BackedEnum|string|null $navigationIcon = 'heroicon-o-truck';
    protected static \UnitEnum|string|null $navigationGroup = 'Achats';
    protected static ?int $navigationSort = 40;
    protected static ?string $modelLabel = 'Fournisseur';
    protected static ?string $pluralModelLabel = 'Fournisseurs';

    public static function form(Schema $schema): Schema
    {
        return SupplierForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SuppliersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit'   => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
PHP,

            'Schemas/SupplierForm.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use App\Models\Supplier;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informations principales')->schema([
                TextInput::make('name')
                    ->label('Raison sociale')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Set $set) =>
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                    ),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->unique(Supplier::class, 'slug', ignoreRecord: true),
                TextInput::make('contact_person')->label('Personne de contact')->maxLength(255),
                TextInput::make('email')->label('Email')->email()->required()
                    ->unique(Supplier::class, 'email', ignoreRecord: true),
                TextInput::make('phone')->label('Téléphone')->tel()->maxLength(30),
                TextInput::make('mobile')->label('Mobile')->tel()->maxLength(30),
            ])->columns(2),

            Section::make('Adresse')->schema([
                Textarea::make('address')->label('Adresse')->rows(2)->columnSpanFull(),
                TextInput::make('city')->label('Ville')->maxLength(100),
                TextInput::make('postal_code')->label('Code postal')->maxLength(20),
                TextInput::make('country')->label('Pays')->default('Togo')->maxLength(100),
            ])->columns(2),

            Section::make('Informations légales')->schema([
                TextInput::make('siret')->label('SIRET / IFU')->maxLength(20),
                TextInput::make('tva_intracom')->label('TVA Intracom')->maxLength(20),
                TextInput::make('rating')->label('Note (0-5)')->numeric()->minValue(0)->maxValue(5)->step(0.1),
                Textarea::make('notes')->label('Notes internes')->rows(3),
            ])->columns(2),

            Toggle::make('is_active')->label('Actif')->default(true),
        ]);
    }
}
PHP,

            'Tables/SuppliersTable.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Suppliers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class SuppliersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Raison sociale')->searchable()->sortable(),
                TextColumn::make('contact_person')->label('Contact')->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('phone')->label('Téléphone'),
                TextColumn::make('city')->label('Ville'),
                TextColumn::make('rating')->label('Note')->sortable(),
                IconColumn::make('is_active')->label('Actif')->boolean(),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Actif'),
                TrashedFilter::make(),
            ])
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([
                DeleteBulkAction::make(),
                ForceDeleteBulkAction::make(),
                RestoreBulkAction::make(),
            ])]);
    }
}
PHP,

            'Pages/ListSuppliers.php'  => $this->stubList('Suppliers', 'SupplierResource', 'ListSuppliers'),
            'Pages/CreateSupplier.php' => $this->stubCreate('Suppliers', 'SupplierResource', 'CreateSupplier'),
            'Pages/EditSupplier.php'   => $this->stubEdit('Suppliers', 'SupplierResource', 'EditSupplier'),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  10. StockMovement
    // ─────────────────────────────────────────────────────────────

    private function generateStockMovementResource(): void
    {
        $this->scaffold('StockMovements', [

            'StockMovementResource.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\StockMovements;

use App\Filament\Resources\StockMovements\Pages;
use App\Filament\Resources\StockMovements\Schemas\StockMovementForm;
use App\Filament\Resources\StockMovements\Tables\StockMovementsTable;
use App\Models\StockMovement;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class StockMovementResource extends Resource
{
    protected static ?string $model = StockMovement::class;
    protected static  \BackedEnum|string|null $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static \UnitEnum|string|null $navigationGroup = 'Stock';
    protected static ?int $navigationSort = 31;
    protected static ?string $modelLabel = 'Mouvement de stock';
    protected static ?string $pluralModelLabel = 'Mouvements de stock';

    public static function form(Schema $schema): Schema
    {
        return StockMovementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockMovementsTable::configure($table);
    }

    public static function canEdit($record): bool
    {
        return false; // Les mouvements sont immuables
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListStockMovements::route('/'),
            'create' => Pages\CreateStockMovement::route('/create'),
            'view'   => Pages\ViewStockMovement::route('/{record}'),
        ];
    }
}
PHP,

            'Schemas/StockMovementForm.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('product_id')
                ->label('Produit')->relationship('product', 'name')->searchable()->preload()->required(),
            Select::make('warehouse_id')
                ->label('Entrepôt')->relationship('warehouse', 'name')->searchable()->preload()->required(),
            Select::make('movement_type')->label('Type de mouvement')
                ->options([
                    'purchase'           => 'Achat',
                    'sale'               => 'Vente',
                    'adjustment'         => 'Ajustement',
                    'transfer_in'        => 'Transfert entrant',
                    'transfer_out'       => 'Transfert sortant',
                    'return'             => 'Retour client',
                    'return_to_supplier' => 'Retour fournisseur',
                    'damage'             => 'Dommage / Perte',
                    'inventory'          => 'Inventaire',
                ])->required(),
            TextInput::make('quantity')->label('Quantité')->numeric()->required(),
            TextInput::make('unit_price')->label('Prix unitaire')->numeric()->prefix('FCFA')->default(0),
            TextInput::make('reference_number')->label('Référence')->maxLength(255),
            DateTimePicker::make('occurred_at')->label('Date du mouvement')->default(now()),
            Textarea::make('notes')->label('Notes')->rows(3)->columnSpanFull(),
        ]);
    }
}
PHP,

            'Tables/StockMovementsTable.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\StockMovements\Tables;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('occurred_at')->label('Date')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('product.name')->label('Produit')->searchable()->sortable(),
                TextColumn::make('warehouse.name')->label('Entrepôt')->sortable(),
                BadgeColumn::make('movement_type')->label('Type')
                    ->colors([
                        'success' => fn ($state) => in_array($state, ['purchase', 'return', 'transfer_in', 'inventory']),
                        'danger'  => fn ($state) => in_array($state, ['sale', 'transfer_out', 'damage']),
                        'warning' => 'adjustment',
                    ]),
                TextColumn::make('quantity')->label('Quantité')->sortable(),
                TextColumn::make('quantity_before')->label('Avant'),
                TextColumn::make('quantity_after')->label('Après'),
                TextColumn::make('reference_number')->label('Référence')->searchable(),
                TextColumn::make('createdBy.name')->label('Par'),
            ])
            ->filters([
                SelectFilter::make('warehouse_id')
                    ->label('Entrepôt')->relationship('warehouse', 'name'),
                SelectFilter::make('movement_type')->label('Type')
                    ->options([
                        'purchase'           => 'Achat',
                        'sale'               => 'Vente',
                        'adjustment'         => 'Ajustement',
                        'transfer_in'        => 'Transfert entrant',
                        'transfer_out'       => 'Transfert sortant',
                        'return'             => 'Retour client',
                        'return_to_supplier' => 'Retour fournisseur',
                        'damage'             => 'Dommage',
                        'inventory'          => 'Inventaire',
                    ]),
                Filter::make('occurred_at')
                    ->form([
                        DatePicker::make('from')->label('Du'),
                        DatePicker::make('to')->label('Au'),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['from'], fn ($q) => $q->whereDate('occurred_at', '>=', $data['from']))
                        ->when($data['to'],   fn ($q) => $q->whereDate('occurred_at', '<=', $data['to']))
                    ),
            ])
            ->defaultSort('occurred_at', 'desc')
            ->actions([ViewAction::make()])
            ->bulkActions([]);
    }
}
PHP,

            'Pages/ListStockMovements.php'  => $this->stubList('StockMovements', 'StockMovementResource', 'ListStockMovements'),
            'Pages/CreateStockMovement.php' => $this->stubCreate('StockMovements', 'StockMovementResource', 'CreateStockMovement'),
            'Pages/ViewStockMovement.php'   => $this->stubView('StockMovements', 'StockMovementResource', 'ViewStockMovement'),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  11. SupplierOrder
    // ─────────────────────────────────────────────────────────────

    private function generateSupplierOrderResource(): void
    {
        $this->scaffold('SupplierOrders', [

            'SupplierOrderResource.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\SupplierOrders;

use App\Filament\Resources\SupplierOrders\Pages;
use App\Filament\Resources\SupplierOrders\Schemas\SupplierOrderForm;
use App\Filament\Resources\SupplierOrders\Tables\SupplierOrdersTable;
use App\Models\SupplierOrder;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SupplierOrderResource extends Resource
{
    protected static ?string $model = SupplierOrder::class;
    protected static  \BackedEnum|string|null $navigationIcon = 'heroicon-o-shopping-cart';
    protected static \UnitEnum|string|null $navigationGroup = 'Achats';
    protected static ?int $navigationSort = 41;
    protected static ?string $modelLabel = 'Commande fournisseur';
    protected static ?string $pluralModelLabel = 'Commandes fournisseurs';

    public static function form(Schema $schema): Schema
    {
        return SupplierOrderForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SupplierOrdersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSupplierOrders::route('/'),
            'create' => Pages\CreateSupplierOrder::route('/create'),
            'edit'   => Pages\EditSupplierOrder::route('/{record}/edit'),
        ];
    }
}
PHP,

            'Schemas/SupplierOrderForm.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\SupplierOrders\Schemas;

use App\Models\SupplierOrder;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SupplierOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Commande')->schema([
                TextInput::make('order_number')
                    ->label('N° de commande')
                    ->required()
                    ->unique(SupplierOrder::class, 'order_number', ignoreRecord: true)
                    ->default(fn () => 'CMD-' . strtoupper(uniqid())),
                Select::make('supplier_id')
                    ->label('Fournisseur')->relationship('supplier', 'name')->searchable()->preload()->required(),
                Select::make('warehouse_id')
                    ->label('Entrepôt destinataire')->relationship('warehouse', 'name')->searchable()->preload()->required(),
                Select::make('status')->label('Statut')
                    ->options([
                        'draft'     => 'Brouillon',
                        'sent'      => 'Envoyée',
                        'confirmed' => 'Confirmée',
                        'partial'   => 'Partiellement reçue',
                        'received'  => 'Reçue',
                        'cancelled' => 'Annulée',
                    ])->default('draft')->required(),
            ])->columns(2),

            Section::make('Dates')->schema([
                DatePicker::make('order_date')->label('Date de commande')->default(now())->required(),
                DatePicker::make('expected_date')->label('Livraison prévue'),
                DatePicker::make('received_date')->label('Date de réception'),
            ])->columns(3),

            Section::make('Montants')->schema([
                TextInput::make('subtotal')->label('Sous-total')->numeric()->prefix('FCFA')->default(0),
                TextInput::make('shipping_cost')->label('Frais de port')->numeric()->prefix('FCFA')->default(0),
                TextInput::make('tax_amount')->label('TVA')->numeric()->prefix('FCFA')->default(0),
                TextInput::make('total_amount')->label('Total TTC')->numeric()->prefix('FCFA')->default(0),
            ])->columns(2),

            Section::make('Notes')->schema([
                Textarea::make('notes')->label('Notes publiques')->rows(2),
                Textarea::make('internal_notes')->label('Notes internes')->rows(2),
            ])->columns(2),
        ]);
    }
}
PHP,

            'Tables/SupplierOrdersTable.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\SupplierOrders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SupplierOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')->label('N° Commande')->searchable()->sortable(),
                TextColumn::make('supplier.name')->label('Fournisseur')->searchable()->sortable(),
                TextColumn::make('warehouse.name')->label('Entrepôt')->sortable(),
                TextColumn::make('order_date')->label('Date')->date('d/m/Y')->sortable(),
                TextColumn::make('expected_date')->label('Livraison prévue')->date('d/m/Y'),
                BadgeColumn::make('status')->label('Statut')
                    ->colors([
                        'gray'    => 'draft',
                        'info'    => 'sent',
                        'warning' => fn ($state) => in_array($state, ['confirmed', 'partial']),
                        'success' => 'received',
                        'danger'  => 'cancelled',
                    ]),
                TextColumn::make('total_amount')->label('Total')->money('XOF')->sortable(),
            ])
            ->filters([
                SelectFilter::make('supplier_id')->label('Fournisseur')->relationship('supplier', 'name'),
                SelectFilter::make('status')->label('Statut')
                    ->options([
                        'draft'     => 'Brouillon',
                        'sent'      => 'Envoyée',
                        'confirmed' => 'Confirmée',
                        'partial'   => 'Partielle',
                        'received'  => 'Reçue',
                        'cancelled' => 'Annulée',
                    ]),
            ])
            ->defaultSort('order_date', 'desc')
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
PHP,

            'Pages/ListSupplierOrders.php'  => $this->stubList('SupplierOrders', 'SupplierOrderResource', 'ListSupplierOrders'),
            'Pages/CreateSupplierOrder.php' => $this->stubCreate('SupplierOrders', 'SupplierOrderResource', 'CreateSupplierOrder'),
            'Pages/EditSupplierOrder.php'   => $this->stubEdit('SupplierOrders', 'SupplierOrderResource', 'EditSupplierOrder'),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  12. Alert
    // ─────────────────────────────────────────────────────────────

    private function generateAlertResource(): void
    {
        $this->scaffold('Alerts', [

            'AlertResource.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Alerts;

use App\Filament\Resources\Alerts\Pages;
use App\Filament\Resources\Alerts\Schemas\AlertForm;
use App\Filament\Resources\Alerts\Tables\AlertsTable;
use App\Models\Alert;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AlertResource extends Resource
{
    protected static ?string $model = Alert::class;
    protected static  \BackedEnum|string|null $navigationIcon = 'heroicon-o-bell-alert';
    protected static \UnitEnum|string|null $navigationGroup = 'Stock';
    protected static ?int $navigationSort = 32;
    protected static ?string $modelLabel = 'Alerte';
    protected static ?string $pluralModelLabel = 'Alertes';

    public static function form(Schema $schema): Schema
    {
        return AlertForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AlertsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListAlerts::route('/'),
            'create' => Pages\CreateAlert::route('/create'),
            'edit'   => Pages\EditAlert::route('/{record}/edit'),
        ];
    }
}
PHP,

            'Schemas/AlertForm.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Alerts\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AlertForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('product_id')
                ->label('Produit')->relationship('product', 'name')->searchable()->preload()->required(),
            Select::make('warehouse_id')
                ->label('Entrepôt')->relationship('warehouse', 'name')->searchable()->preload(),
            Select::make('alert_type')->label("Type d'alerte")
                ->options([
                    'low_stock'      => 'Stock faible',
                    'out_of_stock'   => 'Rupture de stock',
                    'overstock'      => 'Surstock',
                    'expiring_soon'  => 'Expiration proche',
                    'expired'        => 'Expiré',
                    'price_change'   => 'Changement de prix',
                    'supplier_delay' => 'Délai fournisseur',
                ])->required(),
            TextInput::make('title')->label('Titre')->required()->maxLength(255)->columnSpanFull(),
            Textarea::make('message')->label('Message')->rows(3)->required()->columnSpanFull(),
            TextInput::make('current_quantity')->label('Quantité actuelle')->numeric(),
            TextInput::make('threshold_quantity')->label('Seuil')->numeric(),
            Toggle::make('is_read')->label('Lu')->default(false),
            Toggle::make('is_resolved')->label('Résolu')->default(false),
        ]);
    }
}
PHP,

            'Tables/AlertsTable.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\Alerts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AlertsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Date')->dateTime('d/m/Y H:i')->sortable(),
                TextColumn::make('product.name')->label('Produit')->searchable()->sortable(),
                BadgeColumn::make('alert_type')->label('Type')
                    ->colors([
                        'warning' => fn ($state) => in_array($state, ['low_stock', 'expiring_soon']),
                        'danger'  => fn ($state) => in_array($state, ['out_of_stock', 'expired']),
                        'info'    => fn ($state) => in_array($state, ['overstock', 'price_change', 'supplier_delay']),
                    ]),
                TextColumn::make('title')->label('Titre')->searchable()->limit(50),
                TextColumn::make('current_quantity')->label('Qté actuelle'),
                TextColumn::make('threshold_quantity')->label('Seuil'),
                IconColumn::make('is_read')->label('Lu')->boolean(),
                IconColumn::make('is_resolved')->label('Résolu')->boolean(),
            ])
            ->filters([
                SelectFilter::make('alert_type')->label("Type d'alerte")
                    ->options([
                        'low_stock'      => 'Stock faible',
                        'out_of_stock'   => 'Rupture',
                        'overstock'      => 'Surstock',
                        'expiring_soon'  => 'Expiration proche',
                        'expired'        => 'Expiré',
                        'price_change'   => 'Prix',
                        'supplier_delay' => 'Délai',
                    ]),
                TernaryFilter::make('is_read')->label('Lu'),
                TernaryFilter::make('is_resolved')->label('Résolu'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([EditAction::make(), DeleteAction::make()])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
PHP,

            'Pages/ListAlerts.php'  => $this->stubList('Alerts', 'AlertResource', 'ListAlerts'),
            'Pages/CreateAlert.php' => $this->stubCreate('Alerts', 'AlertResource', 'CreateAlert'),
            'Pages/EditAlert.php'   => $this->stubEdit('Alerts', 'AlertResource', 'EditAlert'),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  13. ActivityLog
    // ─────────────────────────────────────────────────────────────

    private function generateActivityLogResource(): void
    {
        $this->scaffold('ActivityLogs', [

            'ActivityLogResource.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages;
use App\Filament\Resources\ActivityLogs\Schemas\ActivityLogForm;
use App\Filament\Resources\ActivityLogs\Tables\ActivityLogsTable;
use App\Models\ActivityLog;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;
    protected static  \BackedEnum|string|null $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static \UnitEnum|string|null $navigationGroup = 'Système';
    protected static ?int $navigationSort = 99;
    protected static ?string $modelLabel = "Journal d'activité";
    protected static ?string $pluralModelLabel = "Journaux d'activité";

    public static function form(Schema $schema): Schema
    {
        return ActivityLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ActivityLogsTable::configure($table);
    }

    public static function canCreate(): bool        { return false; }
    public static function canEdit($record): bool   { return false; }
    public static function canDelete($record): bool { return false; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            'view'  => Pages\ViewActivityLog::route('/{record}'),
        ];
    }
}
PHP,

            'Schemas/ActivityLogForm.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\ActivityLogs\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ActivityLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('action')->label('Action')->disabled(),
            TextInput::make('entity_type')->label('Entité')->disabled(),
            TextInput::make('entity_id')->label('ID Entité')->disabled(),
            TextInput::make('ip_address')->label('Adresse IP')->disabled(),
            Textarea::make('description')->label('Description')->rows(2)->disabled()->columnSpanFull(),
            KeyValue::make('old_values')->label('Anciennes valeurs')->disabled()->columnSpanFull(),
            KeyValue::make('new_values')->label('Nouvelles valeurs')->disabled()->columnSpanFull(),
        ]);
    }
}
PHP,

            'Tables/ActivityLogsTable.php' => <<<'PHP'
<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use Filament\Forms\Components\DatePicker;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')->label('Date')->dateTime('d/m/Y H:i:s')->sortable(),
                TextColumn::make('user.name')->label('Utilisateur')->searchable()->sortable(),
                BadgeColumn::make('action')->label('Action')
                    ->colors([
                        'success' => 'create',
                        'warning' => 'update',
                        'danger'  => 'delete',
                        'info'    => 'view',
                    ]),
                TextColumn::make('entity_type')->label('Entité')->searchable(),
                TextColumn::make('entity_id')->label('ID')->sortable(),
                TextColumn::make('description')->label('Description')->limit(60)->searchable(),
                TextColumn::make('ip_address')->label('IP'),
            ])
            ->filters([
                SelectFilter::make('action')->label('Action')
                    ->options([
                        'create' => 'Création',
                        'update' => 'Modification',
                        'delete' => 'Suppression',
                        'view'   => 'Consultation',
                    ]),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('Du'),
                        DatePicker::make('to')->label('Au'),
                    ])
                    ->query(fn ($query, array $data) => $query
                        ->when($data['from'], fn ($q) => $q->whereDate('created_at', '>=', $data['from']))
                        ->when($data['to'],   fn ($q) => $q->whereDate('created_at', '<=', $data['to']))
                    ),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([ViewAction::make()])
            ->bulkActions([]);
    }
}
PHP,

            'Pages/ListActivityLogs.php' => $this->stubList('ActivityLogs', 'ActivityLogResource', 'ListActivityLogs'),
            'Pages/ViewActivityLog.php'  => $this->stubView('ActivityLogs', 'ActivityLogResource', 'ViewActivityLog'),
        ]);
    }
}