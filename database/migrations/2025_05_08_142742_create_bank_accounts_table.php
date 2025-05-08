<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('persons')->onDelete('cascade');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('card_number', 16)->nullable();
            $table->string('iban', 26)->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('person_id');
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');

        });
    }

    public function down()
    {
        Schema::dropIfExists('bank_accounts');
    }
};
