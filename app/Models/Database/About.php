<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;

    protected $table = 'abouts';

    protected $fillable = [
        'label_title',
        'label_subTitle',
        'label_description',
        'title',
        'span',
        'subTitle',
        'description',
        'company_values',
        'certifications',
        'image',

        // Visi & Misi
        'image_visi_misi',
        'title_visi_misi',
        'subTitle_visi_misi',
        'span_visi_misi',
        'description_visi_misi',
        'title_visi',
        'title_misi',
        'visi',
        'misi',

        // Kepemilikan saham
        'ks_image',
        'ks_title',
        'ks_subTitle',
        'ks_span',
        'ks_description',

        // Struktur Organisasi
        'so_image',
        'so_title',
        'so_subTitle',
        'so_span',
        'so_description',

        // Sejarah
        'sejarah_title',
        'sejarah_image',
        'sejarah_description',

        // Budaya Perusahaan
        'budaya_title',
        'budaya_image',
        'budaya_description',

        // Stat / Counter Perusahaan
        'counter_title',
        'counter_description',
    ];
}
