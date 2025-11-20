<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    protected $table = 'wilayah';
    protected $primaryKey = 'kode';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = ['kode', 'nama'];

    /**
     * Get all provinces (kode length = 2)
     */
    public static function getProvinces()
    {
        return self::whereRaw('LENGTH(kode) = 2')
            ->orderBy('nama', 'asc')
            ->get();
    }

    /**
     * Get cities/regencies by province code
     * Format: XX.YY (e.g., 11.01)
     */
    public static function getCitiesByProvince($provinceCode)
    {
        return self::whereRaw('LENGTH(kode) = 5')
            ->where('kode', 'like', $provinceCode . '.%')
            ->orderBy('nama', 'asc')
            ->get();
    }

    /**
     * Get districts by city code
     * Format: XX.YY.ZZ (e.g., 11.01.01)
     */
    public static function getDistrictsByCity($cityCode)
    {
        return self::whereRaw('LENGTH(kode) = 8')
            ->where('kode', 'like', $cityCode . '.%')
            ->orderBy('nama', 'asc')
            ->get();
    }

    /**
     * Get villages by district code
     * Format: XX.YY.ZZ.AAAA (e.g., 11.01.01.2001)
     */
    public static function getVillagesByDistrict($districtCode)
    {
        return self::whereRaw('LENGTH(kode) = 13')
            ->where('kode', 'like', $districtCode . '.%')
            ->orderBy('nama', 'asc')
            ->get();
    }

    /**
     * Get wilayah name by code
     */
    public static function getNameByCode($code)
    {
        $wilayah = self::find($code);
        return $wilayah ? $wilayah->nama : null;
    }
}
