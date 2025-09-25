<?php
// Copy this file to configure your API key.
// 1) Get an API key from Google AI Studio
// 2) Replace 'YOUR_GEMINI_API_KEY' below
// 3) Optionally change model to 'gemini-1.5-flash' for lower cost

// define('GEMINI_API_KEY', getenv('GEMINI_API_KEY') ?: 'AIzaSyCj9MdlsfCpz5xlxJay9GIS3tNkwAsPKyE');
// define('GEMINI_MODEL', getenv('GEMINI_MODEL') ?: 'gemini-1.5-pro');
// New: prefer env vars or placeholder; set your real key via env
define('GEMINI_API_KEY', getenv('GEMINI_API_KEY') ?: 'AIzaSyCj9MdlsfCpz5xlxJay9GIS3tNkwAsPKyE');
define('GEMINI_MODEL', getenv('GEMINI_MODEL') ?: 'gemini-1.5-pro');
// Optional tokens for external APIs
// define('CRYPTOPANIC_TOKEN', getenv('CRYPTOPANIC_TOKEN') ?: 'a7ea2ca7-36a3-472b-9485-8f9be0025198');
define('CRYPTOPANIC_TOKEN', getenv('CRYPTOPANIC_TOKEN') ?: '');
define('CMC_API_KEY', getenv('CMC_API_KEY') ?: 'a7ea2ca7-36a3-472b-9485-8f9be0025198');
