<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display the FAQ page.
     */
    public function __invoke()
    {
        $faqs = [
            [
                'question' => 'Berapa biaya cuci AC?',
                'answer' => 'Biaya cuci AC mulai dari Rp 50.000 untuk AC 1/2 PK hingga 1 PK. Harga dapat berbeda tergantung kapasitas AC dan kondisi unit. Anda bisa melihat daftar harga lengkap di halaman layanan kami.',
            ],
            [
                'question' => 'Area mana saja yang dilayani?',
                'answer' => 'Kami melayani area Jabodetabek dan sekitarnya. Untuk memastikan area Anda tercover, silakan hubungi kami via WhatsApp atau lihat daftar area layanan di website.',
            ],
            [
                'question' => 'Berapa lama proses cuci AC?',
                'answer' => 'Proses cuci AC standar membutuhkan waktu sekitar 30-60 menit per unit, tergantung kondisi AC dan tingkat kekotoran. Untuk service yang lebih kompleks seperti bongkar pasang, waktu pengerjaan bisa lebih lama.',
            ],
            [
                'question' => 'Apakah ada garansi layanan?',
                'answer' => 'Ya, kami memberikan garansi 7 hari untuk setiap layanan. Jika ada masalah setelah service, kami akan datang kembali tanpa biaya tambahan.',
            ],
            [
                'question' => 'Bagaimana cara melakukan booking?',
                'answer' => 'Anda bisa booking melalui website kami dengan mengisi form order, atau langsung hubungi kami via WhatsApp. Pilih tanggal dan waktu yang diinginkan, dan teknisi kami akan datang sesuai jadwal.',
            ],
            [
                'question' => 'Apakah bisa request waktu tertentu?',
                'answer' => 'Ya, saat booking Anda bisa memilih slot waktu yang tersedia seperti pagi (08:00-11:00), siang (11:00-14:00), atau sore (14:00-17:00). Kami akan menyesuaikan dengan jadwal yang Anda pilih.',
            ],
            [
                'question' => 'Pembayaran dilakukan kapan dan bagaimana?',
                'answer' => 'Pembayaran dilakukan secara cash langsung kepada teknisi setelah pekerjaan selesai. Kami tidak meminta DP atau pembayaran di muka.',
            ],
            [
                'question' => 'Merk AC apa saja yang bisa dilayani?',
                'answer' => 'Kami melayani semua merk AC seperti Daikin, LG, Samsung, Panasonic, Sharp, Polytron, Aqua, Gree, Midea, dan merk lainnya. Baik AC split maupun AC standing.',
            ],
            [
                'question' => 'Apakah tersedia layanan isi freon dan perbaikan?',
                'answer' => 'Ya, selain cuci AC kami juga menyediakan layanan isi freon, perbaikan AC (tidak dingin, bocor, berisik), bongkar pasang, dan pemasangan unit baru. Lihat daftar layanan lengkap kami.',
            ],
            [
                'question' => 'Bagaimana jika saya tidak puas dengan layanan?',
                'answer' => 'Kepuasan pelanggan adalah prioritas kami. Jika Anda tidak puas, silakan hubungi kami dalam 7 hari dan kami akan melakukan perbaikan tanpa biaya tambahan. Kami juga menerima kritik dan saran untuk peningkatan layanan.',
            ],
        ];

        // Generate FAQ Schema for SEO
        $faqSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn($faq) => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'],
                ],
            ], $faqs),
        ];

        return view('faq', compact('faqs', 'faqSchema'));
    }
}
