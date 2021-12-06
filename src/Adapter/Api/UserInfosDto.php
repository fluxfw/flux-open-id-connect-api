<?php

namespace FluxOpenIdConnectApi\Adapter\Api;

use JsonSerializable;

class UserInfosDto implements JsonSerializable
{

    public readonly ?string $email;
    public readonly ?string $name;
    public readonly ?string $nickname;
    public readonly ?string $picture;
    public readonly ?string $profile;
    public readonly ?string $sub;


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


    public function jsonSerialize() : array
    {
        return get_object_vars($this);
    }
}
