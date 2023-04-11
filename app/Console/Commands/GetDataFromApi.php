<?php

namespace App\Console\Commands;

use App\Models\Forecast;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetDataFromApi extends Command
{
    protected $signature = 'get-data-from-api';
    protected $description = 'Gets data from external api';

    const URI = "api.openweathermap.org/data/2.5/forecast/daily?lat=51.099998&lon=17.033331&appid=[HERE_IS_API_KEY]&units=metric&lang=pl";

    public function handle(): void
    {
        $this->sayHello();

        $response = Http::get(self::URI);

        // fail fast
        if ($response->failed()) {
            $this->error("Request failed");
            return;
        }

        $json = $response->body();
        $jsonObject = json_decode($json);

        foreach ($jsonObject->list as $item) {


            Forecast::updateOrCreate(
                [
                    'hash' => md5($item->dt . $jsonObject->city->name),
                ],
                [
                    'dt' => $item->dt,
                    'city_name' => $jsonObject->city->name,
                    'temp_day' => $item->temp->day,
                    'temp_night' => $item->temp->night,
                    'description' => $item->weather[0]->description,
                    'pressure' => $item->pressure,
                    'wind_speed' => $item->speed,
                    'wind_direction' => $item->deg,
                    // 'rain' => isset($item->rain) ? $item->rain : null, // ternary operator
                    'precipitation' => $item->rain ?? $item->snow ?? null,
                ]
            );
        }

        $this->info("Command finished");
    }

    public function sayHello()
    {
        $this->info("Command started");
    }
}
