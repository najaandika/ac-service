# AC Service - Aplikasi Manajemen Jasa Service AC

Aplikasi web untuk manajemen jasa service AC profesional. Dibangun dengan Laravel 12, Tailwind CSS, dan Alpine.js.

## 🚀 Fitur

### Admin Panel
- **Dashboard** - Statistik order, pendapatan, grafik trend, dan overview bisnis
- **Order Management** - Kelola order masuk, proses, dan selesai
- **WhatsApp Integration** - Kirim notifikasi status order via WhatsApp
- **Reminder H-1** - Tombol reminder sehari sebelum jadwal service
- **Estimasi Kedatangan** - Tracking keberangkatan teknisi
- **Layanan** - CRUD layanan dengan harga per kapasitas AC
- **Teknisi** - Kelola data teknisi dan toggle status aktif
- **Promo** - Kelola kode promo dan diskon
- **Portfolio/Gallery** - Upload foto before-after hasil kerja
- **Pelanggan** - Lihat riwayat pelanggan dan order
- **Laporan** - Export laporan Excel dan PDF dengan summary
- **Invoice PDF** - Generate invoice untuk setiap order
- **Settings** - Pengaturan bisnis, kontak, dan media sosial

### Public Website
- **Landing Page** - Homepage dengan info layanan & social proof
- **Detail Layanan** - Info lengkap per layanan dengan harga
- **Order Online** - Form order dengan promo code
- **Lacak Order** - Customer bisa lacak status order real-time
- **Testimoni** - Halaman review dari pelanggan
- **Gallery** - Before-after portfolio hasil kerja
- **FAQ** - Pertanyaan umum dengan accordion
- **Rating System** - Customer bisa beri rating setelah service
- **Floating WhatsApp** - Tombol chat cepat di pojok kanan bawah

### SEO Optimization
- **Meta Tags** - Title dan description untuk semua halaman publik
- **Schema Markup** - LocalBusiness, FAQPage, AggregateRating
- **Sitemap** - Auto-generated sitemap.xml
- **Robots.txt** - SEO-friendly robots configuration

## ♿ Accessibility (WCAG Compliance)
Aplikasi ini telah dioptimalkan untuk aksesibilitas:
- **Semantic HTML**: Penggunaan elemen form yang tepat (`label`, `fieldset`, `legend`)
- **Screen Reader Support**: Atribut `aria-label` dan `.sr-only` text
- **Keyboard Navigation**: Semua elemen interaktif dapat diakses via keyboard
- **Form Best Practices**: Atribut `autocomplete` dan label yang jelas

## 🛠️ Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Blade, Tailwind CSS 4, Alpine.js
- **Database**: MySQL
- **Build Tool**: Vite
- **PDF**: Dompdf
- **Icons**: Lucide Icons

## 📦 Instalasi

```bash
# Clone repository
git clone https://github.com/najaandika/ac-service.git
cd ac-service

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate --seed

# Create storage link
php artisan storage:link

# Run development server
npm run dev
php artisan serve
```

## 🔐 Login Admin

```
Email: admin@acservice.com
Password: password
```

## 📁 Struktur Project

```
app/
├── Http/Controllers/
│   ├── Admin/          # Controller admin panel
│   └── ...             # Controller public
├── Models/             # Eloquent models
├── Helpers/            # Helper classes (FormatHelper)
├── Services/           # Service classes (WhatsAppService)
├── Exports/            # Export classes (OrdersExport)
└── View/Components/    # Blade components

resources/
├── views/
│   ├── admin/          # View admin panel
│   ├── layouts/        # Layout templates
│   ├── components/     # Blade components
│   ├── invoices/       # Invoice PDF template
│   └── ...             # Public views
├── css/
│   └── modules/        # CSS modules
└── js/
    ├── modules/        # JS modules
    ├── pages/          # Page-specific JS
    └── utils/          # JS utilities
```

## 🌐 Public Routes

| Route | Description |
|-------|-------------|
| `/` | Homepage |
| `/layanan/{slug}` | Service detail |
| `/order` | Order form |
| `/track` | Track order |
| `/testimoni` | Testimonials page |
| `/gallery` | Portfolio gallery |
| `/faq` | FAQ page |
| `/sitemap.xml` | Sitemap |

## 📝 License

MIT License