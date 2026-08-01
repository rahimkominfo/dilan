Bertindaklah sebagai Expert UI/UX Designer dan Frontend Developer.

Pada halaman admin/user_info saat ini tampilannya berfungsi dengan baik, namun terasa kaku dan kurang menarik. Saya ingin Anda membuatnya jauh lebih modern, elegan, clean, dan membuat pengguna sangat nyaman.

Fokus Perbaikan UI/UX yang saya inginkan:

Tabel & Card Container:

Berikan efek bayangan (soft shadow) yang lebih elegan pada card pembungkus tabel.

Buat Search bar lebih modern dengan focus ring yang lebih smooth dan ikon search yang presisi.

Buat tombol "Tambah Informasi" (+) menjadi lebih menonjol (mungkin menggunakan gradient tipis dari warna indigo ke blue atau efek glow tipis).

Desain ulang bagian header tabel (<thead>) agar terlihat lebih clean (mungkin background abu-abu sangat terang dengan teks yang sedikit lebih tebal).

Empty State (Keadaan Kosong): Saat ini baris "Belum ada informasi yang tersedia" sangat membosankan. Ubah empty state ini menjadi area tengah yang estetik: tambahkan ilustrasi SVG/Icon FontAwesome berukuran besar dengan warna redup (text-slate-300), diikuti teks tebal "Belum Ada Artikel", dan teks kecil "Mulai buat artikel pertama Anda dengan menekan tombol tambah".

Pagination & Footer: Percantik bagian footer tabel. Buat tombol "Sebelumnya" dan "Selanjutnya" menggunakan desain pill modern yang merespon ketika di-hover.

Micro-interactions: Tambahkan transisi (transition-all duration-300 ease-in-out) pada setiap tombol, input, dan baris tabel (saat di-hover baris tabel sedikit berubah warna).

Gunakan kelas Tailwind murni, jangan menggunakan CSS kustom kecuali benar-benar diperlukan untuk animasi khusus.

halaman yang ingin diperbaiki adalah halaman admin/user_info