<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Jenssegers\Agent\Agent;
use GuzzleHttp\Client;
use App\Models\Visitor;

class LogVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is a guest (not authenticated)
        if (auth()->guest()) {
            $client = new Client(); // Initialize the Guzzle client
            $response = $client->request('GET', 'https://api.ipify.org'); // Send a GET request to ipify
            $ipAddress = $response->getBody(); // Get the response body, which contains the IP address
            $geoip = geoip($ipAddress);
            $current_location = array(
                'iso_code'		=>	$geoip->iso_code,
                'country'	    =>	$geoip->country,
                'city'	        =>	$geoip->city,
                'state'	        =>	$geoip->state,
                'state_name'	=>	$geoip->state_name,
                'postal_code'	=>	$geoip->postal_code,
                'lat'		    =>	$geoip->lat,
                'lon'		    =>	$geoip->lon,
                'timezone'		=>	$geoip->timezone,
                'continent'		=>	$geoip->continent,
                'currency'		=>	$geoip->currency,
                'default'		=>	$geoip->default,
            );
            $current_location = json_encode($current_location);

            $agent = new Agent();

            if ($agent->isMobile()) {
                $deviceType = 'Mobile';
            } else if ($agent->isTablet()) {
                $deviceType = 'Tablet';
            } else if ($agent->isDesktop()) {
                $deviceType = 'Computer';
            }

            // Check for an existing visitor with the same session ID and page view for today
            $visitor = Visitor::where('session_id', $request->session()->getId())
            ->first();

            if ($visitor) {
                // Visitor exists, update visit time
                $visitor->device = $deviceType;
                $visitor->browser = $agent->browser();
                $visitor->os = $agent->platform();
                $visitor->ip_address = $ipAddress;
                $visitor->current_location = $current_location;
                $visitor->page_view = $request->path();
                $visitor->visit_time = now();
                $visitor->save();
            } else {
                $visitor = new Visitor();
                $visitor->session_id = $request->session()->getId();
                $visitor->device = $deviceType;
                $visitor->browser = $agent->browser();
                $visitor->os = $agent->platform();
                $visitor->ip_address = $ipAddress;
                $visitor->current_location = $current_location;
                $visitor->page_view = $request->path();
                $visitor->visit_time = now();
                $visitor->save();
            }
        }

        return $next($request);
    }
}
