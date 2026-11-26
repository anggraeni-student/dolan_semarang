@extends('layouts.app')
@section('title', $article->title)

@section('content')
    <div class="crumb">
        <a href="{{ route('home') }}">Home</a> /
        <a href="{{ route('articles.index') }}">Panduan</a> /
        {{ $article->tag }}
    </div>

    <div style="max-width:760px">
        <span class="tag" style="background:var(--pink-soft);letter-spacing:.6px">{{ $article->tag }}</span>
        <h1 style="margin:10px 0 12px">{{ $article->title }}</h1>

        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:18px">
            <a href="{{ route('profiles.show', $article->author->username) }}" class="avatar" style="width:40px;height:40px" title="Lihat profil {{ $article->author->name }}">
                {{ $article->author->avatar_initials }}
            </a>
            <span style="font-size:13.5px;color:var(--ink-mid)">
                oleh <a href="{{ route('profiles.show', $article->author->username) }}" style="font-weight:700;color:var(--ink)">{{ $article->author->name }}</a>
                <span class="badge-lime" style="margin-left:6px"><span class="material-icons" style="font-size:12px">verified</span>Kontributor Terverifikasi</span>
                · {{ $article->read_minutes }} menit baca
            </span>
        </div>

        <img src="{{ asset($article->cover_image) }}" alt="{{ $article->title }}" style="width:100%;max-height:380px;object-fit:cover;border-radius:18px;margin-bottom:24px">

        {{-- BODY ARTIKEL --}}
        <div style="font-size:15.5px;line-height:1.85;color:var(--ink)">
            <p>{{ $article->excerpt }} Setiap perjalanan selalu dimulai dari pertanyaan yang sama: mulai dari mana, kapan berangkat, dan berapa sih sebenarnya biaya yang wajar? Panduan ini saya rangkum dari beberapa trip yang saya lakukan langsung — bukan hasil salin-tempel dari internet — supaya kamu bisa langsung pakai tanpa harus belajar dari kesalahan yang sama seperti saya dulu.</p>

            <p>Kunci dari itinerary yang nyaman itu sederhana: jangan serakah. Godaan memang besar — Indonesia punya terlalu banyak tempat menarik dalam satu koridor, dan rasanya sayang kalau dilewatkan. Tapi pengalaman mengajarkan bahwa dua destinasi yang dieksplor dengan tenang jauh lebih berkesan daripada lima destinasi yang hanya sempat difoto dari kendaraan. Sisakan waktu untuk hal-hal tak terduga: hujan sore, warung yang kebetulan enak, atau obrolan panjang dengan warga setempat yang justru jadi momen paling diingat.</p>

            <p>Soal persiapan, mulailah dari fisik dan perlengkapan, bukan dari daftar foto Instagram. Jaket tebal, sepatu dengan grip yang layak, dan lampu kepala adalah tiga barang yang paling sering disesali orang karena dianggap remeh. Cek juga prakiraan cuaca H-3 dan siapkan rencana cadangan — rute alternatif atau destinasi pengganti yang tetap asyik walau langit sedang tidak bersahabat. Tiket dan penginapan sebaiknya dipesan jauh hari di musim ramai, sebab harga bisa naik dua kali lipat dan kamar kehabisan justru di tanggal-tanggal yang kamu incar.</p>

            <p>Dari sisi biaya, selalu pisahkan anggaran menjadi tiga pos: transportasi, menginap, dan "uang senang" untuk tiket masuk, makan, serta oleh-oleh. Catat pengeluaran harian di aplikasi catatan sederhana saja — kebiasaan kecil ini terbukti menyelamatkan banyak dompet di hari ketiga perjalanan. Kalau berangkat berrombongan, open trip bisa menekan biaya hingga 30–40 persen; tapi kalau kamu tipe traveler yang betah mengatur sendiri ritme perjalanan, private trip dengan mobil sewa justru lebih leluasa dan totalnya tidak jauh berbeda.</p>

            <p>Satu lagi yang sering dilupakan: jadilah tamu yang baik. Bawa pulang sampahmu, ikuti aturan lokal seperti adat dan jam kunjung, dan belilah oleh-oleh langsung dari penjual sekitar destinasi — uang yang kamu keluarkan akan berputar kembali untuk komunitas yang menjaga tempat indah itu tetap indah. Traveling yang baik bukan hanya soal seberapa banyak tempat yang kita datangi, tapi seberapa banyak cerita dan hubungan yang kita bawa pulang.</p>

            <p>Semoga panduan ini membantumu menyusun rencana yang realistis dan menyenangkan. Kalau kamu sudah pernah mencoba rute ini atau punya tips lain yang belum saya sebut, bagikan pengalamannya di kolom komunitas Dolan Semarang — review dan ceritamu bisa jadi acuan berharga untuk pengunjung berikutnya. Selamat dolan!</p>
        </div>

        <div class="card-meta" style="margin-top:22px;padding-top:16px;border-top:1px solid var(--line);font-size:13.5px;color:var(--ink-mid)">
            <span style="color:var(--star);font-weight:700">★</span> disukai <b style="color:var(--ink)">{{ number_format($article->likes) }}</b>
        </div>
    </div>
@endsection
