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

namespace local_aws;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../sdk/aws-autoloader.php');

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use local_aws\local\aws_helper;
use local_aws\local\guzzle_helper;

/**
 * Test the guzzle_helper class that includes middleware for proxy settings.
 *
 * @package    local_aws
 * @author     Andrew Madden <andrewmadden@catalyst-au.net>
 * @copyright  2023 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \local_aws\local\guzzle_helper
 */
class guzzle_helper_test extends \advanced_testcase {

    /**
     * This method runs before every test.
     */
    public function setUp(): void {
        $this->resetAfterTest();
    }

    /**
     * Test that the Middleware is added to the HandlerStack for the Guzzle client.
     */
    public function test_configure_client_proxy_adds_middleware() {
        // Create client with mock response.
        $mockhandler = new MockHandler([
            new Response(200, [], 'Mock response'),
        ]);
        $stack = HandlerStack::create($mockhandler);
        $client = guzzle_helper::configure_client_proxy(new Client(['handler' => $stack]));
        // Check proxy middleware added to handler stack.
        $handler = $client->getConfig('handler');
        if (method_exists($this, 'assertStringContainsString')) {
            $this->assertStringContainsString("Name: 'proxy'", (string) $handler);
        } else {
            // Use earlier alternate method for older PHPUnit versions.
            $this->assertContains("Name: 'proxy'", (string) $handler);
        }
    }

    /**
     * Test proxy is used for a request.
     */
    public function test_configure_client_proxy_with_proxied_request() {
        global $CFG;
        // Set default proxy settings.
        $CFG->proxyhost = 'proxyhost';
        $CFG->proxyport = 3128;
        $CFG->proxytype = 'HTTP';
        $CFG->proxybypass = '';

        // Create client with mock response.
        $mockhandler = new MockHandler([
            new Response(200, [], 'Mock response'),
        ]);
        $stack = HandlerStack::create($mockhandler);
        $client = guzzle_helper::configure_client_proxy(new Client(['handler' => $stack]));

        // Check what Middleware is called.
        $client->request('get', 'http://example.com');
        $lastrequestoptions = $mockhandler->getLastOptions();
        $this->assertArrayHasKey('proxy', $lastrequestoptions);
        $this->assertEquals(aws_helper::get_proxy_string(), $lastrequestoptions['proxy']);
    }

    /**
     * Test no proxy used if request is in proxy bypass list.
     */
    public function test_configure_client_proxy_with_bypassed_proxied_request() {
        global $CFG;
        // Set default proxy settings.
        $CFG->proxyhost = 'proxyhost';
        $CFG->proxyport = 3128;
        $CFG->proxytype = 'HTTP';
        $CFG->proxybypass = 'example.com';

        // Create client with mock response.
        $mockhandler = new MockHandler([
            new Response(200, [], 'Mock response'),
        ]);
        $stack = HandlerStack::create($mockhandler);
        $client = guzzle_helper::configure_client_proxy(new Client(['handler' => $stack]));

        // Check what Middleware is called.
        $client->request('get', 'http://example.com');
        $lastrequestoptions = $mockhandler->getLastOptions();
        $this->assertArrayNotHasKey('proxy', $lastrequestoptions);
    }

    /**
     * Test no proxy used if none is configured.
     */
    public function test_configure_client_proxy_with_no_proxy_configured() {
        global $CFG;
        // Set default proxy settings.
        $CFG->proxyhost = '';
        $CFG->proxyport = '';
        $CFG->proxytype = '';
        $CFG->proxybypass = '';

        // Create client with mock response.
        $mockhandler = new MockHandler([
            new Response(200, [], 'Mock response'),
        ]);
        $stack = HandlerStack::create($mockhandler);
        $client = guzzle_helper::configure_client_proxy(new Client(['handler' => $stack]));

        // Check what Middleware is called.
        $client->request('get', 'http://example.com');
        $lastrequestoptions = $mockhandler->getLastOptions();
        $this->assertArrayNotHasKey('proxy', $lastrequestoptions);
    }
}
