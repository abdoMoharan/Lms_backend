<?php
namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App; // استيراد الـ App facade
use Symfony\Component\HttpFoundation\Response;
// استيراد Carbon لتحديد لغة التواريخ

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
                                        // Get the locale from the second segment of the URL (e.g., 'en' or 'ar')
        $locale = $request->segment(2); // Assuming the URL format is /api/{locale}/...

        // Check if the locale is supported, if not, fallback to default
        if (in_array($locale, ['en', 'ar'])) {
            // Set the application locale
            App::setLocale($locale);

            // Optionally, set Carbon locale if you're working with date localization
            Carbon::setLocale($locale);
        } else {
            // Fallback to a default locale (you can choose the default locale from config)
            App::setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
