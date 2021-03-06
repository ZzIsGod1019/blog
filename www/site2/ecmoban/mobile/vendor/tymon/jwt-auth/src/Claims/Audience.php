<?php

/*
 * This file is part of jwt-auth.
 *
 * (c) Sean Tymon <tymon148@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tymon\JWTAuth\Claims;

class Audience extends Claim
{
    /**
     * The claim name.
     *
     * @var string
     */
    protected $name = 'aud';
}
