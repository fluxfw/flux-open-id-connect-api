<?php

namespace FluxOpenIdConnectApi\Adapter\Api;

use JsonSerializable;

class UserInfosDto implements JsonSerializable
{

    private readonly ?string $email;
    private readonly ?string $name;
    private readonly ?string $nickname;
    private readonly ?string $picture;
    private readonly ?string $profile;
    private readonly ?string $sub;


    public static function new(?string $sub, ?string $name, ?string $nickname, ?string $profile, ?string $picture, ?string $email) : static
    {
        $dto = new static();

        $dto->sub = $sub;
        $dto->name = $name;
        $dto->nickname = $nickname;
        $dto->profile = $profile;
        $dto->picture = $picture;
        $dto->email = $email;

        return $dto;
    }


    public function getEmail() : ?string
    {
        return $this->email;
    }


    public function getName() : ?string
    {
        return $this->name;
    }


    public function getNickname() : ?string
    {
        return $this->nickname;
    }


    public function getPicture() : ?string
    {
        return $this->picture;
    }


    public function getProfile() : ?string
    {
        return $this->profile;
    }


    public function getSub() : ?string
    {
        return $this->sub;
    }


    public function jsonSerialize() : array
    {
        return get_object_vars($this);
    }
}
