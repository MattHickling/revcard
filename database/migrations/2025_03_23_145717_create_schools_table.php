<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('URN');
            $table->string('LA_code'); 
            $table->string('LA_name'); 
            $table->string('EstablishmentNumber');
            $table->string('EstablishmentName');
            $table->string('TypeOfEstablishment_name');
            $table->string('EstablishmentStatus_name');
            $table->string('ReasonEstablishmentOpened_name');
            $table->date('OpenDate');
            $table->string('PhaseOfEducation_name');
            $table->integer('StatutoryLowAge');
            $table->integer('StatutoryHighAge');
            $table->string('Boarders_name');
            $table->string('OfficialSixthForm_name');
            $table->string('Gender_name');
            $table->string('ReligiousCharacter_name');
            $table->string('AdmissionsPolicy_name');
            $table->string('UKPRN');
            $table->string('Street');
            $table->string('Locality');
            $table->string('Address3')->nullable();
            $table->string('Town');
            $table->string('County_name');
            $table->string('Postcode');
            $table->string('SchoolWebsite')->nullable();
            $table->string('TelephoneNum');
            $table->string('HeadTitle_name');
            $table->string('HeadFirstName');
            $table->string('HeadLastName');
            $table->string('HeadPreferredJobTitle');
            $table->string('GOR_name');
            $table->string('ParliamentaryConstituency_code');
            $table->string('ParliamentaryConstituency_name');
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
