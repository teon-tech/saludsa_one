<?php

namespace App\Processes;


use App\Http\Resources\CityResource;
use App\Http\Resources\ZoneResource;
use App\Traits\UtilGeographic;
use App\Validators\CoverageValidator;
use Illuminate\Http\Request;

class LocatorProcess
{
    use UtilGeographic;

    private $coverageValidator;

    public function __construct(CoverageValidator $coverageValidator)
    {
        $this->coverageValidator = $coverageValidator;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function coverage(Request $request)
    {
        //validator
        $this->coverageValidator->validate($request);

        //verified has coverage
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        $zone = $this->getZoneByCoordinates($latitude, $longitude);
        ZoneResource::withoutWrapping();
        CityResource::withoutWrapping();
        return [
            'has_coverage' => $zone != null,
            'zone' => $zone ? new ZoneResource($zone) : null,
            'city' => $zone ? new CityResource($zone->city) : null,
        ];
    }

}