<?php

namespace App\Models\Database;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $table = 'configurations';

    protected $fillable = [    
        'name_dashboard',
        'title_dashboard',
        'logo_dashboard',
        'logo_dashboard_2',
        'favicon_dashboard',

        'name_page',
        'title_page',
        'logo_hitam_page',
        'logo_putih_page',
        'logo_footer_page',
        'favicon_page',
        'company_profile_pdf',
        'off_canvas_page',
        'footer_page',
        'text_footer_page',

        'title_login',
        'name_login',
        'logo_login',
        'favicon_login',
        'image_login',

        'meta_keywords',
        'meta_descriptions',
        'cta',
        'banner_cta',
        'image_cta',
        'image_cta_2',
        'description_cta',
        'title_link_cta',
        'link_cta',
        'name_link_cta',
        
        'banner',

        'meta_title',
        'meta_author',
        'meta_subject',

        'base_url',
        'site_url',
        'canonical_url',

        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'og_url',
        'og_site_name',

        'twitter_card',
        'twitter_site',
        'twitter_creator',
        'twitter_title',
        'twitter_description',
        'twitter_image',

        'geo_placename',
        'geo_region',
        'geo_latitude',
        'geo_longitude',

        'language',
        'robots',
        'distribution',
        'city',
        'country',
        'revisit_after',
        'copyright',
        'designer',
        'generator'
    ];
}
