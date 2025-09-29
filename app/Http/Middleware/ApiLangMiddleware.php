<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiLangMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get language from various sources
        $lang = $this->getLanguageFromRequest($request);
        
        // Set the application locale
        app()->setLocale($lang);
        
        return $next($request);
    }

    /**
     * Get language from request headers or body
     *
     * @param Request $request
     * @return string
     */
    private function getLanguageFromRequest(Request $request): string
    {
        // Priority order: Header -> Body -> Default
        
        // 1. Check Accept-Language header
        $acceptLanguage = $request->header('Accept-Language');
        if ($acceptLanguage) {
            $lang = $this->extractLanguageFromHeader($acceptLanguage);
            if ($lang) {
                return $lang;
            }
        }
        
        // 2. Check custom X-Language header
        $xLanguage = $request->header('X-Language');
        if ($xLanguage && $this->isValidLanguage($xLanguage)) {
            return $xLanguage;
        }
        
        // 3. Check request body
        $bodyLang = $request->input('lang');
        if ($bodyLang && $this->isValidLanguage($bodyLang)) {
            return $bodyLang;
        }
        
        // 4. Check query parameter
        $queryLang = $request->query('lang');
        if ($queryLang && $this->isValidLanguage($queryLang)) {
            return $queryLang;
        }
        
        // 5. Default to Arabic
        return 'ar';
    }

    /**
     * Extract language from Accept-Language header
     *
     * @param string $acceptLanguage
     * @return string|null
     */
    private function extractLanguageFromHeader(string $acceptLanguage): ?string
    {
        // Parse Accept-Language header (e.g., "en-US,en;q=0.9,ar;q=0.8")
        $languages = explode(',', $acceptLanguage);
        
        foreach ($languages as $language) {
            // Remove quality values (e.g., ";q=0.9")
            $lang = trim(explode(';', $language)[0]);
            
            // Extract language code (e.g., "en" from "en-US")
            $langCode = explode('-', $lang)[0];
            
            if ($this->isValidLanguage($langCode)) {
                return $langCode;
            }
        }
        
        return null;
    }

    /**
     * Check if language is valid
     *
     * @param string $lang
     * @return bool
     */
    private function isValidLanguage(string $lang): bool
    {
        $supportedLanguages = ['ar', 'en', 'fr', 'es'];
        return in_array(strtolower($lang), $supportedLanguages);
    }
}
