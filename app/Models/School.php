<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = 'schools'; 

    protected $fillable = [
        'URN', 'LA_code', 'LA_name', 'EstablishmentNumber', 'EstablishmentName', 'TypeOfEstablishment_name', 
        'EstablishmentStatus_name', 'ReasonEstablishmentOpened_name', 'OpenDate', 'PhaseOfEducation_name', 
        'StatutoryLowAge', 'StatutoryHighAge', 'Boarders_name', 'OfficialSixthForm_name', 'Gender_name', 
        'ReligiousCharacter_name', 'AdmissionsPolicy_name', 'UKPRN', 'Street', 'Locality', 'Address3', 'Town', 
        'County_name', 'Postcode', 'SchoolWebsite', 'TelephoneNum', 'HeadTitle_name', 'HeadFirstName', 
        'HeadLastName', 'HeadPreferredJobTitle', 'GOR_name', 'ParliamentaryConstituency_code', 
        'ParliamentaryConstituency_name'
    ];

    public function users()
    {
        return $this->hasMany(User::class); 
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}
