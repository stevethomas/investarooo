<?php

namespace App\Http\Controllers;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Sheets;
use Illuminate\Http\Request;
use Google\Service\Sheets\ValueRange;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $spreadsheetId = config('investaroo.sheet_id');

        $client = new Client();
        $client->useApplicationDefaultCredentials();
        $client->addScope(Drive::DRIVE);
        $service = new Sheets($client);

        // put the data
        $range = 'Sheet1!A1:B3';
        $valueRange = new ValueRange();
        $valueRange->setRange($range);
        $valueRange->setValues([
            ['Ticker', 'Last price'],
            ['MOT', '=GOOGLEFINANCE(CONCAT("ASX:", A2))'],
            ['MXT', '=GOOGLEFINANCE(CONCAT("ASX:", A3))'],
        ]);

        $response = $service->spreadsheets_values->update(
            $spreadsheetId,
            $range,
            $valueRange,
            ['valueInputOption' => 'USER_ENTERED']
        );

        // Fetch the data
        $response = $service->spreadsheets_values->get($spreadsheetId, $range);
        $values = $response->getValues();

        ray($values);

        return inertia('Dashboard');
    }
}
