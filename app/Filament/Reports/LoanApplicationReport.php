<?php

namespace App\Filament\Reports;

use EightyNine\Reports\Components\Body;
use EightyNine\Reports\Components\Body\Table;
use EightyNine\Reports\Components\Body\TableGroup;
use EightyNine\Reports\Components\Footer;
use EightyNine\Reports\Components\Header;
use EightyNine\Reports\Components\Header\Layout\HeaderColumn;
use EightyNine\Reports\Components\Header\Layout\HeaderRow;
use EightyNine\Reports\Components\Text;
use EightyNine\Reports\Components\Image;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use EightyNine\Reports\Components\Body\TextColumn;

class LoanApplicationReport extends \EightyNine\Reports\Report
{
    public ?string $heading = "Loan application Report";

    public ?string $subHeading = "A list of loan applications";

    // public ?string $icon = "heroicon-o-user";

    public ?string $group = "Collectionsa";

    public function header(Header $header): Header
    {
        return $header
            ->schema([
                HeaderRow::make()
                    ->schema([
                        Image::make(asset("/img/logo.png"))
                            ->widthBase(),
                        HeaderColumn::make()
                            ->schema([
                                Text::make("Loan Application Report")
                                    ->title()
                                    ->primary(),
                                Text::make("A list of loan applications for the month of " . date("F Y"))
                                    ->subTitle(),
                                Text::make("Date: " . date("d/m/Y"))
                                    ->fontXs()
                            ])
                            ->alignRight()
                    ])
            ]);
    }


    public function body(Body $body): Body
    {
        return $body
            ->schema([
                Table::make()
                    ->data(fn (?array $filters) => $this->getData($filters))
                    ->columns([
                        TextColumn::make("sales"),
                        TextColumn::make("loss")
                        ->money("TZS"),
                        TextColumn::make("profit")
                        ->money("TZS"),
                        TextColumn::make("date")->dateTime("d/m/Y"),
                        TextColumn::make("name"),
                    ])
                    ->useKeysAsHeadings()
                    ->useFirstColumnAsHeadings()
            ]);
    }

    public function footer(Footer $footer): Footer
    {
        return $footer
            ->schema([
                Text::make("This is the footer")
                    ->fontXs()
            ]);
    }

    public function filterForm(Form $form): Form
    {
        return $form
            ->schema([
                // DateTimePicker::make("from")
                //     ->default(today()->startOfMonth()),
                // DateTimePicker::make("to")
                //     ->default(today()->endOfMonth()),
                TextInput::make("min")
                    ->label("Minimum")
                    ->numeric()
                    ->default(10),
                TextInput::make("max")
                    ->label("Maximum")
                    ->numeric()
                    ->default(100),
            ]);
    }

    private function getData(?array $filters)
    {
        return collect([
            [
                "name" => "John Doe",
                "sales" => "100",
                "profit" => "100000",
                "loss" => "50000",
                "date" => \Carbon\Carbon::now()->subDays(rand(1, 30))->toDateString(),
            ],
            [
                "name" => "Jane Doe",
                "sales" => "200",
                "profit" => "200000",
                "loss" => "100000",
                "date" => \Carbon\Carbon::now()->subDays(rand(1, 30))->toDateString(),
            ],
            [
                "name" => "John Doe",
                "sales" => "100",
                "profit" => "100000",
                "loss" => "50000",
                "date" => \Carbon\Carbon::now()->subDays(rand(1, 30))->toDateString(),
            ],
            [
                "name" => "Jane Doe",
                "sales" => "200",
                "profit" => "200000",
                "loss" => "100000",
                "date" => \Carbon\Carbon::now()->subDays(rand(1, 30))->toDateString(),
            ],
            [
                "name" => "John Doe",
                "sales" => "100",
                "profit" => "100000",
                "loss" => "50000",
                "date" => \Carbon\Carbon::now()->subDays(rand(1, 30))->toDateString(),
            ],
            [
                "name" => "Jane Doe",
                "sales" => "200",
                "profit" => "200000",
                "loss" => "100000",
                "date" => \Carbon\Carbon::now()->subDays(rand(1, 30))->toDateString(),
            ],
            [
                "name" => "John Doe",
                "sales" => "100",
                "profit" => "100000",
                "loss" => "50000",
                "date" => \Carbon\Carbon::now()->subDays(rand(1, 30))->toDateString(),
            ],
            [
                "name" => "Jane Doe",
                "sales" => "200",
                "profit" => "200000",
                "loss" => "100000",
                "date" => \Carbon\Carbon::now()->subDays(rand(1, 30))->toDateString(),
            ],
            [
                "name" => "John Doe",
                "sales" => "100",
                "profit" => "100000",
                "loss" => "50000",
                "date" => \Carbon\Carbon::now()->subDays(rand(1, 30))->toDateString(),
            ],
            [
                "name" => "Jane Doe",
                "sales" => "200",
                "profit" => "200000",
                "loss" => "100000",
                "date" => \Carbon\Carbon::now()->subDays(rand(1, 30))->toDateString(),
            ],
            [
                "name" => "John Doe",
                "sales" => "100",
                "profit" => "100000",
                "loss" => "50000",
                "date" => \Carbon\Carbon::now()->subDays(rand(1, 30))->toDateString(),
            ],
            [
                "name" => "Jane Doe",
                "sales" => "200",
                "profit" => "200000",
                "loss" => "100000",
                "date" => \Carbon\Carbon::now()->subDays(rand(1, 30))->toDateString(),
            ],
        ])->filter(function ($item) use ($filters) {
            if (isset($filters["min"]) && isset($filters["max"])) {
                return $item["sales"] >= $filters["min"] && $item["sales"] <= $filters["max"];
            }
            return true;
        });
    }
}
