<?php

namespace Lastfm\Service;

use Lastfm\Service;
use Lastfm\Transport;

/**
 * User service class
 *
 * @package Last.fm
 * @author  Antoine Hérault <antoine.herault@gmail.com>
 */
class User extends Service
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->addMethod('getArtistTracks');
        $this->addMethod('getBannedTracks');
        $this->addMethod('getEvents');
        $this->addMethod('getFriends');
        $this->addMethod('getInfo');
        $this->addMethod('getLovedTracks');
        $this->addMethod('getNeighbours');
        $this->addMethod('getNewReleases');
        $this->addMethod('getPastEvents');
        $this->addMethod('getPersonalTags');
        $this->addMethod('getPlaylists');
        $this->addMethod('getRecentStations', array(
            'requires_authentication'   => true
        ));
        $this->addMethod('getRecentTracks');
        $this->addMethod('getRecommendedArtists', array(
            'requires_authentication'   => true
        ));
        $this->addMethod('getRecommendedEvents', array(
            'requires_authentication'   => true
        ));
        $this->addMethod('getShouts');
        $this->addMethod('getTopAlbums');
        $this->addMethod('getTopArtists');
        $this->addMethod('getTopTags');
        $this->addMethod('getTopTracks');
        $this->addMethod('getWeeklyAlbumChart');
        $this->addMethod('getWeeklyArtistChart');
        $this->addMethod('getWeeklyChartList');
        $this->addMethod('getWeeklyTrackChart');
        $this->addMethod('shout', array(
            'http_method'               => Transport::HTTP_METHOD_POST,
            'requires_authentication'   => true
        ));
    }
}
