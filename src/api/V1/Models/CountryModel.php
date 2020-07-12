<?php


namespace App\api\V1\Models;


use phpDocumentor\Reflection\Types\String_;



/**
 * Class CountryModel
 *
 * Model fo countries tale.
 * Tax value we use it, but not save in database.
 * We use tax value as a property, but we do not save it in database.
 *
 * @package App\api\V1\Models
 */
class CountryModel extends Model
{
    protected $tableName = 'countries';

    private $country_code;
    private $tax ;



    /**
     * @return mixed
     */
    public function getCountryCode()
    {
        return $this->country_code;
    }


    /**
     * @param String $country_code
     */
    public function setCountryCode(String $country_code)
    {
        $this->country_code = $country_code;
    }

    /**
     * @return mixed
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * @param mixed $tax
     */
    public function setTax($tax)
    {
        $this->tax = $tax;
    }


    public function save()
    {
        // TODO: Implement save() method.
    }


    /**
     * @param String $countryCode
     * @return int
     */
    public function findIdByCountryCode(String $countryCode) :int {
        $tt = $this->getConnection()->prepare("SELECT id FROM {$this->tableName} WHERE country_code = :cc" );
        $tt->execute([':cc' => $countryCode]);
        return (int)$tt->fetchColumn();
    }

    /**
     * @param int $id
     * @return CountryModel
     */
    public function get(int $id):CountryModel{

        $rty = $this->find($id);

        $rr = new CountryModel();
        $rr->setCountryCode($rty['country_code']);
        $rr->setTax($rty['tax']);
        $rr->setId($id);
        return $rr;

    }


    /**
     * @return string
     */
    public function getTablename()
    {
        return $this->tableName;
    }
}