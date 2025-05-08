<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->string('accounting_code')->nullable();
            $table->string('company_name')->nullable();
            $table->string('title')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('nickname')->nullable();
            $table->string('category')->nullable();
            $table->enum('type', ['customer', 'supplier', 'shareholder', 'employee']);

            // بخش عمومی
            $table->decimal('credit_limit', 20, 2)->default(0);
            $table->string('price_list')->nullable();
            $table->string('tax_type')->nullable();
            $table->string('national_code', 10)->unique()->nullable();
            $table->string('economic_code')->nullable();
            $table->string('registration_number')->nullable();
            $table->string('branch_code')->nullable();
            $table->text('description')->nullable();

            // بخش آدرس
            $table->text('address')->nullable();
            $table->string('country')->default('ایران');
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code', 10)->nullable();

            // بخش تماس
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('fax')->nullable();
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('phone3')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            // تاریخ‌ها
            $table->date('birth_date')->nullable();
            $table->date('marriage_date')->nullable();
            $table->date('join_date');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('persons');
    }
};
