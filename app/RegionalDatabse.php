<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegionalDatabse extends Model {

    protected $table = 'regional_database';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['short_name', 'long_name', 'flag','country','language','status', 'created_at', 'updated_at'];

    public static function get_search_arr(){
            $searcherArr = [
            [
                "value"=>"ae",
                "key"=>"google.ae"
            ],
            [
                "value"=>"au",
                "key"=>"google.com.au"
            ],
            [
                "value"=>"az",
                "key"=>"google.az"
            ],
            [
                "value"=>"be",
                "key"=>"google.be"
            ],
            [
                "value"=>"br",
                "key"=>"google.com.br"
            ],
            [
                "value"=>"ca",
                "key"=>"google.ca"
            ],
            [
                "value"=>"ch",
                "key"=>"google.ch"
            ],
            [
                "value"=>"cl",
                "key"=>"google.cl"
            ],
            [
                "value"=>"cy",
                "key"=>"google.com.cy"
            ],
            [
                "value"=>"de",
                "key"=>"google.de"
            ],
            [
                "value"=>"de",
                "key"=>"google.dk"
            ],
            [
                "value"=>"ee",
                "key"=>"google.ee"
            ],
            [
                "value"=>"eg",
                "key"=>"google.com.eg"
            ],
            [
                "value"=>"es",
                "key"=>"google.es"
            ],
            [
                "value"=>"fr",
                "key"=>"google.fr"
            ],
            [
                "value"=>"gr",
                "key"=>"google.gr"
            ],
            [
                "value"=>"hk",
                "key"=>"google.com.hk"
            ],
            [
                "value"=>"ie",
                "key"=>"google.ie"
            ],
            [
                "value"=>"il",
                "key"=>"google.co.il"
            ],
            [
                "value"=>"id",
                "key"=>"google.co.id"
            ],
            [
                "value"=>"in",
                "key"=>"google.co.in"
            ],
            [
                "value"=>"it",
                "key"=>"google.it"
            ],
            [
                "value"=>"jm",
                "key"=>"google.com.jm"
            ],
            [
                "value"=>"ke",
                "key"=>"google.co.ke"
            ],
            [
                "value"=>"sa",
                "key"=>"google.co.sa"
            ],
            [
                "value"=>"ma",
                "key"=>"google.co.ma"
            ],
            [
                "value"=>"mu",
                "key"=>"google.mu"
            ],
            [
                "value"=>"my",
                "key"=>"google.com.my"
            ],
            [
                "value"=>"ng",
                "key"=>"google.ng"
            ],
            [
                "value"=>"nl",
                "key"=>"google.nl"
            ],
            [
                "value"=>"no",
                "key"=>"google.no"
            ],
            [
                "value"=>"nz",
                "key"=>"google.co.nz"
            ],
            [
                "value"=>"pk",
                "key"=>"google.com.pk"
            ],
            [
                "value"=>"pl",
                "key"=>"google.pl"
            ],
            [
                "value"=>"ph",
                "key"=>"google.com.ph"
            ],
            [
                "value"=>"ru",
                "key"=>"google.ru"
            ],
            [
                "value"=>"se",
                "key"=>"google.se"
            ],
            [
                "value"=>"sg",
                "key"=>"google.com.sg"
            ],
            [
                "value"=>"th",
                "key"=>"google.co.th"
            ],
            [
                "value"=>"tr",
                "key"=>"google.com.tr"
            ],
            [
                "value"=>"us",
                "key"=>"google.com"
            ],
            [
                "value"=>"uk",
                "key"=>"google.co.uk"
            ],
            [
                "value"=>"za",
                "key"=>"google.co.za"
            ]
        ];
        return $searcherArr;
    }

}
