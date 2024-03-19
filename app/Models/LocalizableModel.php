<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

abstract class LocalizableModel extends Model {

    /**
     * Localized attributes.
     *
     * @var array
     */
    protected $localizable = [
        'name'
    ];

    /**
     * Magic method for checking if an attribute is set.
     *
     * @param string $attribute
     * @return bool
     */
    public function __isset($attribute)
    {
        if (in_array($attribute, $this->localizable)) {
            $localeSpecificAttribute = $attribute.'_'.App::currentLocale();
            return isset($this->{$localeSpecificAttribute});
        }
        return parent::__isset($attribute);
    }

    /**
     * Magic method for retrieving a missing attribute.
     *
     * @param string $attribute
     * @return mixed
     */
    public function __get($attribute)
    {
        if (in_array($attribute, $this->localizable)) {
            $localeSpecificAttribute = $attribute.'_'.App::currentLocale();
            return $this->{$localeSpecificAttribute};
        }
        return parent::__get($attribute);
    }
}
