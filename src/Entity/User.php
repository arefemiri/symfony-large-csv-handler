<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email', 'username')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('employee')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups('employee')]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups('employee')]
    private ?string $employeeId = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('employee')]
    private ?string $namePrefix = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups('employee')]
    private ?string $firstName = null;

    #[ORM\Column(length: 3, nullable: true)]
    #[Groups('employee')]
    private ?string $middleInitial = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups('employee')]
    private ?string $lastName = null;

    #[ORM\Column(length: 3)]
    #[Assert\NotBlank]
    #[Assert\Choice(["F", "M"])]
    #[Groups('employee')]
    private ?string $gender = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups('employee')]
    private ?string $email = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
//    #[Assert\Date]
    #[Groups('employee')]
    private ?\DateTimeInterface $birthdayDate = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    #[Assert\NotBlank]
//    #[Assert\Time]
    #[Groups('employee')]
    private ?\DateTimeInterface $birthdayTime = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups('employee')]
    private ?float $age = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    #[Groups('employee')]
    private ?\DateTimeInterface $joinedAt = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups('employee')]
    private ?float $companyAge = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups('employee')]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255, nullable: true)]
//    #[Assert\NotBlank]
    #[Groups('employee')]
    private ?string $placeName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups('employee')]
    private ?string $zip = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups('employee')]
    private ?string $region = null;

    #[ORM\Column(length: 255)]
//    #[Assert\Country]
    #[Groups('employee')]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    #[Groups('employee')]
    private ?string $city = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmployeeId(): ?string
    {
        return $this->employeeId;
    }

    public function setEmployeeId(string $employeeId): self
    {
        $this->employeeId = $employeeId;

        return $this;
    }

    public function getNamePrefix(): ?string
    {
        return $this->namePrefix;
    }

    public function setNamePrefix(?string $namePrefix): self
    {
        $this->namePrefix = $namePrefix;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getMiddleInitial(): ?string
    {
        return $this->middleInitial;
    }

    public function setMiddleInitial(?string $middleInitial): self
    {
        $this->middleInitial = $middleInitial;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getBirthdayDate(): ?\DateTimeInterface
    {
        return $this->birthdayDate;
    }

    public function setBirthdayDate(\DateTimeInterface $birthdayDate): self
    {
        $this->birthdayDate = $birthdayDate;

        return $this;
    }

    public function getBirthdayTime(): ?\DateTimeInterface
    {
        return $this->birthdayTime;
    }

    public function setBirthdayTime(\DateTimeInterface $birthdayTime): self
    {
        $this->birthdayTime = $birthdayTime;

        return $this;
    }

    public function getAge(): ?float
    {
        return $this->age;
    }

    public function setAge(float $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getJoinedAt(): ?\DateTimeInterface
    {
        return $this->joinedAt;
    }

    public function setJoinedAt(\DateTimeInterface $joinedAt): self
    {
        $this->joinedAt = $joinedAt;

        return $this;
    }

    public function getCompanyAge(): ?float
    {
        return $this->companyAge;
    }

    public function setCompanyAge(float $companyAge): self
    {
        $this->companyAge = $companyAge;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getPlaceName(): ?string
    {
        return $this->placeName;
    }

    public function setPlaceName(?string $placeName): self
    {
        $this->placeName = $placeName;

        return $this;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(string $zip): self
    {
        $this->zip = $zip;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

}
