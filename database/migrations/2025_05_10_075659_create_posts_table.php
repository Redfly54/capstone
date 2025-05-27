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
        Schema::create('posts', function (Blueprint $table) {
             $table->id();
            $table->string('pet_name');
            
            // relasi ke pet_categories
            $table->unsignedBigInteger('pet_category_id');
            $table->foreign('pet_category_id')
                    ->references('id')->on('pet_categories')
                  ->onDelete('cascade');
            
            // relasi ke breeds
            $table->unsignedBigInteger('breed_id');
            $table->foreign('breed_id')
                  ->references('id')->on('breeds')
                  ->onDelete('cascade');
            
            // warna; jika butuh lookup table warna, bisa ubah menjadi unsignedBigInteger + fk
            $table->string('color_count');
            
            // relasi ke ages
            $table->unsignedBigInteger('age_id');
            $table->foreign('age_id')
                  ->references('id')->on('ages')
                  ->onDelete('cascade');
            
            $table->decimal('weight', 8, 2);    // berat dalam kg, 2 desimal
            
            $table->enum('gender', ['Jantan', 'Betina']);
            
            $table->text('about_pet');         // deskripsi
            
            $table->json('pictures');          // array nama file gambar
            
            // relasi ke users
            $table->string('user_id');
            $table->foreign('user_id')
                  ->references('user_id')->on('users')
                  ->onDelete('cascade');
            
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('kota');
            $table->string('provinsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
