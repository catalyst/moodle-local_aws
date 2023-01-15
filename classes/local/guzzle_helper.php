<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_aws\local;

use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;

/**
 * Guzzle helper class. Provides useful middleware such as preparing proxies.
 *
 * @package    local_aws
 * @author     Andrew Madden <andrewmadden@catalyst-au.net>
 * @copyright  2022 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class guzzle_helper {

    /**
     * Add additional configuration to the Guzzle client. For example, adding additional Middleware.
     *
     * @param Client $client
     * @return Client
     */
    public static function configure_client_proxy(Client $client): Client {
        $client->getConfig('handler')->push(self::add_proxy_when_required(), 'proxy');
        return $client;
    }

    /**
     * Middleware that checks if proxy options should be added to request.
     *
     * @return callable
     */
    protected static function add_proxy_when_required(): callable {
        return function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                global $CFG;
                if (!empty($CFG->proxyhost) && !is_proxybypass((string) $request->getUri())) {
                    $options['proxy'] = aws_helper::get_proxy_string();
                }
                return $handler($request, $options);
            };
        };
    }
}
