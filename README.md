# HR Dummy Website

Website dummy untuk kebutuhan HR menggunakan Vue.js di frontend, PHP sebagai backend API, dan SQLite sebagai database lokal.

## Fitur

- Dashboard ringkas untuk jumlah karyawan dan status cuti
- Tambah dan ubah status karyawan
- Ajukan cuti karyawan
- Approve atau reject cuti
- Database otomatis dibuat saat pertama kali dibuka

## Cara Menjalankan

1. Pastikan PHP sudah terpasang di komputer Anda.
2. Buka folder project ini di terminal:

```powershell
cd "C:\Users\Fido Wahyu\Documents\Dummy test ai pln persero"
```

3. Jalankan server bawaan PHP:

```powershell
php -S localhost:8000 -t .
```

4. Buka browser ke:

```text
http://localhost:8000
```

5. Jika data belum muncul atau database belum terbentuk, refresh browser sekali lagi.

## Jika PHP Belum Ada

Kalau perintah `php` belum dikenali di terminal Windows, Anda bisa:

1. Install PHP manual.
2. Atau install XAMPP/WAMP lalu gunakan PHP bawaan dari paket tersebut.
3. Setelah terpasang, ulangi langkah di atas dari folder project ini.

## Database

- Engine: SQLite
- File database: `data/hr.sqlite`
- Data contoh akan dibuat otomatis saat aplikasi pertama kali diakses

## Catatan

- Semua data tersimpan lokal di komputer Anda.
- Tombol hapus karyawan akan ikut menghapus data cuti yang terkait.
- Dashboard menggunakan Vue.js CDN, jadi tidak perlu `npm install`.
