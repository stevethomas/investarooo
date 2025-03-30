<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Google\Service\Sheets\ValueRange;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    // Specify the spreadsheet ID and range
    $spreadsheetId = ''; // Replace with your spreadsheet ID
//    $range = 'Sheet1!A1:C10'; // Specify the range you want to read

    $client = new Google\Client();
    $client->useApplicationDefaultCredentials();
    $client->addScope(Google\Service\Drive::DRIVE);
//    $client->setSubject($user_to_impersonate);
    $service = new \Google\Service\Sheets($client);

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

    // Display the data
    if (empty($values)) {
        ray("No data found.");
    } else {
        foreach ($values as $row) {
            ray(implode(', ', $row));
        }
    }

    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
