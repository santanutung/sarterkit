<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('name_of_applicant');
            $table->date('date_of_birth');
            $table->string('place_of_birth');
            $table->string('email_address');
            $table->string('phone_number');
            $table->string('marital_status');
            $table->text('address');
            $table->string('passport_number');
            $table->date('date_of_issue_of_passport');
            $table->date('expiry_date_of_passport');
            $table->string('place_of_issue');
            $table->string('purpose_for_travel');
            $table->string('type_of_entry');
            $table->date('travel_date');
            $table->integer('length_of_stay');
            $table->string('visiting_country');
            $table->string('present_occupation');
            $table->text('employer_address');
            $table->string('highest_education_details');
      
            $table->text('name_of_institution');
            $table->text('address_of_institution');
            $table->string('total_amount')->default(0);
            $table->string('status')->default('booked');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
