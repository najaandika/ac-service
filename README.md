# AC Service - Aplikasi Manajemen Jasa Service AC

Aplikasi web untuk manajemen jasa service AC profesional. Dibangun dengan Laravel 12, Tailwind CSS, dan Alpine.js.

## 🚀 Fitur

### Admin Panel
- **Dashboard** - Statistik order, pendapatan, dan overview bisnis
- **Order Service** - Kelola order masuk, proses, dan selesai
- **Layanan** - CRUD layanan dengan harga per kapasitas AC
- **Teknisi** - Kelola data teknisi dan toggle status aktif
- **Pelanggan** - Lihat riwayat pelanggan dan order
- **Laporan** - Laporan pendapatan dan performa
- **Settings** - Pengaturan bisnis, kontak, dan media sosial

### Public Website
- **Landing Page** - Homepage dengan info layanan & social proof
- **Detail Layanan** - Info lengkap per layanan dengan harga
- **Order Online** - Form order untuk customer
- **Lacak Order** - Customer bisa lacak status order
- **Floating WhatsApp** - Tombol chat cepat di pojok kanan bawah
- **Social Proof** - Statistik order selesai & rating pelanggan

## ♿ Accessibility (WCAG Compliance)
Aplikasi ini telah dioptimalkan untuk aksesibilitas, memastikan pengalaman yang inklusif bagi semua pengguna:
- **Semantic HTML**: Penggunaan elemen form yang tepat (`label`, `fieldset`, `legend`).
- **Screen Reader Support**: Atribut `aria-label` dan `.sr-only` text untuk elemen visual/ikon.
- **Keyboard Navigation**: Semua input dan elemen interaktif dapat diakses via keyboard.
- **Form Best Practices**: Atribut `autocomplete` untuk membantu pengisian form otomatis dan label yang jelas.

## 🛠️ Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Blade, Tailwind CSS 4, Alpine.js
- **Database**: MySQL
- **Build Tool**: Vite

## 📦 Instalasi

```bash
# Clone repository
git clone https://github.com/username/ac-service.git
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
├── Helpers/            # Helper classes
└── View/Composers/     # View composers

resources/
├── views/
│   ├── admin/          # View admin panel
│   ├── layouts/        # Layout templates
│   ├── components/     # Blade components
│   └── ...             # Public views
├── css/
│   └── modules/        # CSS modules (theme, base, components)
└── js/
    └── utils/          # JS utilities
```

## 📝 License

MIT License


Icon	Nama	Cocok untuk Layanan
🌀	Wind (Angin)	Cuci AC, Servis AC
❄️	Snowflake (Salju)	Isi Freon, AC Dingin
🔧	Wrench (Obeng)	Perbaikan AC, Servis
🌡️	Thermometer	Cek Suhu, Diagnosa
⚙️	Settings (Gear)	Maintenance, Tune-up
⚡	Zap (Listrik)	Problem Listrik, Kompressor
💧	Droplets (Tetes)	AC Bocor, Pembersihan
🌀	Fan (Kipas)	Kipas AC, Instalasi