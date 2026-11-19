<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); $table->string('name')->unique(); $table->string('description')->default(''); $table->timestamps();
        });
        Schema::create('collections', function (Blueprint $table) {
            $table->id(); $table->string('name')->unique(); $table->string('description')->default(''); $table->timestamps();
        });
        foreach (['Laptop & Komputer','Periferal','Komponen PC','Aksesoris','Jaringan'] as $name) DB::table('categories')->insert(['name'=>$name,'description'=>'','created_at'=>now(),'updated_at'=>now()]);
        foreach ([['WFH','Kerja nyaman dari rumah'],['Gaming','Performa sesuai anggaran'],['Mahasiswa','Ringan, praktis, hemat']] as [$name,$description]) DB::table('collections')->insert(compact('name','description') + ['created_at'=>now(),'updated_at'=>now()]);
    }
    public function down(): void { Schema::dropIfExists('collections'); Schema::dropIfExists('categories'); }
};