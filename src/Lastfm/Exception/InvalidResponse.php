<?php

namespace Lastfm\Exception;

use Lastfm\Exception;

/**
 * Exception to be thrown when an API response is not valid (e.g cannot be
 * unserialized)
 *
 * @package Last.fm
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class InvalidResponse extends Exception
{
}
