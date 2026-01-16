# https://www.robotstxt.org/robotstxt.html
User-agent: *
Allow: /
Disallow: /admin/
Disallow: /login
Disallow: /api/
Disallow: /storage/

# Sitemap
Sitemap: {{ url('/sitemap.xml') }}
