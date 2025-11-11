<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wilayah;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        $provinces = Wilayah::getProvinces();
        return response()->json($provinces);
    }

    /**
     * Get cities/regencies by province code
     */
    public function getCities($provinceCode)
    {
        $cities = Wilayah::getCitiesByProvince($provinceCode);
        return response()->json($cities);
    }

    /**
     * Get districts by city code
     */
    public function getDistricts($cityCode)
    {
        $districts = Wilayah::getDistrictsByCity($cityCode);
        return response()->json($districts);
    }

    /**
     * Get villages by district code
     */
    public function getVillages($districtCode)
    {
        $villages = Wilayah::getVillagesByDistrict($districtCode);
        return response()->json($villages);
    }
}
