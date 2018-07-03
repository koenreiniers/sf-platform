<?php
namespace CrmBundle\Contact;

trait ContactTrait
{
    public function getFullName()
    {
        $parts = [
            $this->namePrefix,
            $this->firstName,
            $this->middleName,
            $this->lastName,
            $this->nameSuffix,
        ];
        $fullName = trim(implode(' ', $parts));
        return $fullName;
    }
}