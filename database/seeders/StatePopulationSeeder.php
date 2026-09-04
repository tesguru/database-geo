<?php

namespace Database\Seeders;

use App\Services\ClickHouseService;

class StatePopulationSeeder
{
    public static function run(ClickHouseService $clickhouse): string
    {
        $states = [
            ['Alabama', 'Alabama', 'United States', 5024279],
            ['Alaska', 'Alaska', 'United States', 733391],
            ['Arizona', 'Arizona', 'United States', 7151502],
            ['Arkansas', 'Arkansas', 'United States', 3011524],
            ['California', 'California', 'United States', 39538223],
            ['Colorado', 'Colorado', 'United States', 5773714],
            ['Connecticut', 'Connecticut', 'United States', 3605944],
            ['Delaware', 'Delaware', 'United States', 989948],
            ['Florida', 'Florida', 'United States', 21538187],
            ['Georgia', 'Georgia', 'United States', 10711908],
            ['Hawaii', 'Hawaii', 'United States', 1455271],
            ['Idaho', 'Idaho', 'United States', 1839106],
            ['Illinois', 'Illinois', 'United States', 12812508],
            ['Indiana', 'Indiana', 'United States', 6785528],
            ['Iowa', 'Iowa', 'United States', 3190369],
            ['Kansas', 'Kansas', 'United States', 2937880],
            ['Kentucky', 'Kentucky', 'United States', 4505836],
            ['Louisiana', 'Louisiana', 'United States', 4657757],
            ['Maine', 'Maine', 'United States', 1362359],
            ['Maryland', 'Maryland', 'United States', 6177224],
            ['Massachusetts', 'Massachusetts', 'United States', 7029917],
            ['Michigan', 'Michigan', 'United States', 10077331],
            ['Minnesota', 'Minnesota', 'United States', 5706494],
            ['Mississippi', 'Mississippi', 'United States', 2961279],
            ['Missouri', 'Missouri', 'United States', 6154913],
            ['Montana', 'Montana', 'United States', 1084225],
            ['Nebraska', 'Nebraska', 'United States', 1961504],
            ['Nevada', 'Nevada', 'United States', 3104614],
            ['New Hampshire', 'New Hampshire', 'United States', 1377529],
            ['New Jersey', 'New Jersey', 'United States', 9288994],
            ['New Mexico', 'New Mexico', 'United States', 2117522],
            ['New York', 'New York', 'United States', 20201249],
            ['North Carolina', 'North Carolina', 'United States', 10439388],
            ['North Dakota', 'North Dakota', 'United States', 779094],
            ['Ohio', 'Ohio', 'United States', 11799448],
            ['Oklahoma', 'Oklahoma', 'United States', 3959353],
            ['Oregon', 'Oregon', 'United States', 4237256],
            ['Pennsylvania', 'Pennsylvania', 'United States', 13002700],
            ['Rhode Island', 'Rhode Island', 'United States', 1097379],
            ['South Carolina', 'South Carolina', 'United States', 5118425],
            ['South Dakota', 'South Dakota', 'United States', 886667],
            ['Tennessee', 'Tennessee', 'United States', 6910840],
            ['Texas', 'Texas', 'United States', 29145505],
            ['Utah', 'Utah', 'United States', 3271616],
            ['Vermont', 'Vermont', 'United States', 643077],
            ['Virginia', 'Virginia', 'United States', 8631393],
            ['Washington', 'Washington', 'United States', 7614893],
            ['West Virginia', 'West Virginia', 'United States', 1793716],
            ['Wisconsin', 'Wisconsin', 'United States', 5893718],
            ['Wyoming', 'Wyoming', 'United States', 576851],
            ['Ontario', 'Ontario', 'Canada', 14223942],
            ['Quebec', 'Quebec', 'Canada', 8501833],
            ['British Columbia', 'British Columbia', 'Canada', 5000879],
            ['Alberta', 'Alberta', 'Canada', 4262635],
            ['Manitoba', 'Manitoba', 'Canada', 1342153],
            ['Saskatchewan', 'Saskatchewan', 'Canada', 1132505],
            ['Nova Scotia', 'Nova Scotia', 'Canada', 969383],
            ['Victoria', 'Victoria', 'Australia', 6543291],
            ['New South Wales', 'New South Wales', 'Australia', 8072163],
            ['Queensland', 'Queensland', 'Australia', 5156138],
            ['South Australia', 'South Australia', 'Australia', 1770063],
            ['Western Australia', 'Western Australia', 'Australia', 2685064],
            ['Dubai', 'Dubai', 'United Arab Emirates', 3400000],
        ];

        $existing = $clickhouse->query("SELECT DISTINCT state FROM city_population WHERE state != ''");
        $existingStates = [];
        if ($existing) {
            foreach ($existing->rows() as $row) {
                $existingStates[strtolower(preg_replace('/[^a-z]/', '', $row['state']))] = true;
            }
        }

        $maxId = $clickhouse->query("SELECT max(id) as max_id FROM city_population")->fetchOne('max_id') ?? 0;
        $id = (int) $maxId;
        $batch = [];
        $inserted = 0;
        $skipped = 0;

        foreach ($states as $state) {
            $norm = strtolower(preg_replace('/[^a-z]/', '', $state[0]));
            if (isset($existingStates[$norm])) {
                $skipped++;
                continue;
            }
            $id++;
            $batch[] = [$id, $state[0], $state[1], $state[2], $state[3]];
            $inserted++;
        }

        if (!empty($batch)) {
            $clickhouse->insert('city_population', $batch, ['id', 'city', 'state', 'country', 'population']);
        }

        return "State populations seeded: {$inserted} added, {$skipped} already existed.";
    }
}
