<?php
namespace Raw\Component\Bol\Plaza\Model;

class AddressDetails
{
    /**
     * @var string
     */
    protected $salutationCode;

    /**
     * @var string
     */
    protected $firstname;

    /**
     * @var string
     */
    protected $surname;

    /**
     * @var string
     */
    protected $streetname;

    /**
     * @var string
     */
    protected $housenumber;

    /**
     * @var string
     */
    protected $housenumberExtended;

    /**
     * @var string
     */
    protected $zipCode;

    /**
     * @var string
     */
    protected $countryCode;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $deliveryPhoneNumber;

    /**
     * @var string
     */
    protected $vatNumber;

    /**
     * @var string
     */
    protected $addressSupplement;

    /**
     * @var string
     */
    protected $extraAddressInformation;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $company;

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return AddressDetails
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param string $company
     * @return AddressDetails
     */
    public function setCompany($company)
    {
        $this->company = $company;
        return $this;
    }

    

    /**
     * @return string
     */
    public function getSalutationCode()
    {
        return $this->salutationCode;
    }

    /**
     * @param string $salutationCode
     * @return AddressDetails
     */
    public function setSalutationCode($salutationCode)
    {
        $this->salutationCode = $salutationCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return AddressDetails
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return AddressDetails
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreetname()
    {
        return $this->streetname;
    }

    /**
     * @param string $streetname
     * @return AddressDetails
     */
    public function setStreetname($streetname)
    {
        $this->streetname = $streetname;
        return $this;
    }

    /**
     * @return string
     */
    public function getHousenumber()
    {
        return $this->housenumber;
    }

    /**
     * @param string $housenumber
     * @return AddressDetails
     */
    public function setHousenumber($housenumber)
    {
        $this->housenumber = $housenumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getHousenumberExtended()
    {
        return $this->housenumberExtended;
    }

    /**
     * @param string $housenumberExtended
     * @return AddressDetails
     */
    public function setHousenumberExtended($housenumberExtended)
    {
        $this->housenumberExtended = $housenumberExtended;
        return $this;
    }



    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     * @return AddressDetails
     */
    public function setZipCode($zipCode)
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     * @return AddressDetails
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return AddressDetails
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryPhoneNumber()
    {
        return $this->deliveryPhoneNumber;
    }

    /**
     * @param string $deliveryPhoneNumber
     * @return AddressDetails
     */
    public function setDeliveryPhoneNumber($deliveryPhoneNumber)
    {
        $this->deliveryPhoneNumber = $deliveryPhoneNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getVatNumber()
    {
        return $this->vatNumber;
    }

    /**
     * @param string $vatNumber
     * @return AddressDetails
     */
    public function setVatNumber($vatNumber)
    {
        $this->vatNumber = $vatNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddressSupplement()
    {
        return $this->addressSupplement;
    }

    /**
     * @param string $addressSupplement
     * @return AddressDetails
     */
    public function setAddressSupplement($addressSupplement)
    {
        $this->addressSupplement = $addressSupplement;
        return $this;
    }

    /**
     * @return string
     */
    public function getExtraAddressInformation()
    {
        return $this->extraAddressInformation;
    }

    /**
     * @param string $extraAddressInformation
     * @return AddressDetails
     */
    public function setExtraAddressInformation($extraAddressInformation)
    {
        $this->extraAddressInformation = $extraAddressInformation;
        return $this;
    }
    
    
}