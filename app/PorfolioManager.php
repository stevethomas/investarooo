<?php

namespace App;

use Google\Client;
use App\Models\Holding;
use Google\Service\Drive;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

class PorfolioManager
{
    protected static ?Sheets $service = null;

    public static function refresh(): void
    {
        $i = 1;
        $holdings = Holding::query()
            ->get();

        // put the data
        $valueRange = new ValueRange;
        $valueRange->setRange(static::range());
        $valueRange->setValues([
            ['Ticker', 'Last price'],
            ...$holdings->map(function (Holding $holding) use (&$i) {
                $i++;
                return [$holding->ticker, sprintf('=GOOGLEFINANCE(CONCAT("ASX:", A%s))', $i)];
            })
        ]);

        static::getService()->spreadsheets_values->update(
            static::spreadsheetId(),
            static::range(),
            $valueRange,
            ['valueInputOption' => 'USER_ENTERED']
        );
    }

    public static function all(): array
    {
        return cache()->remember('portfolio', 120, fn () => static::getService()
            ->spreadsheets_values
            ->get(static::spreadsheetId(), static::range())
            ->getValues());
    }

    protected static function getService(): Sheets
    {
        if (static::$service) {
            return static::$service;
        }

        $client = new Client;
        $client->useApplicationDefaultCredentials();
        $client->addScope(Drive::DRIVE);

        return new Sheets($client);
    }

    protected static function spreadsheetId(): string
    {
        return config('investaroo.sheet_id');
    }

    protected static function range(): string
    {
        return 'Sheet1!A1:B' . Holding::count() + 1;
    }
}
