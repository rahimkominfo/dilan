#!/data/data/com.termux/files/usr/bin/bash

# Script push.sh - Otomasi Git DILAN
# Format Commit: YYMMDD - [Tipe]: Deskripsi

# Pastikan ada pesan commit
if [ -z "$1" ] || [ -z "$2" ]; then
    echo "Penggunaan: ./push.sh [Tipe] \"Pesan Deskripsi\""
    echo "Contoh: ./push.sh Added \"Menambahkan modul switch kategori dan integrasi API\""
    echo "Tipe: Added, Fixed, Changed, Security, Refactor"
    exit 1
fi

TIPE=$1
PESAN=$2
TANGGAL=$(date +%y%m%d)
BRANCH=$(git rev-parse --abbrev-ref HEAD)

# Format Pesan Commit
COMMIT_MSG="$TANGGAL - [$TIPE]: $PESAN"

echo "🚀 Memulai proses push ke branch: $BRANCH..."
echo "📝 Pesan Commit: $COMMIT_MSG"

# Git Actions
git add .
git commit -m "$COMMIT_MSG"

if [ $? -eq 0 ]; then
    echo "✅ Commit berhasil."
    echo "📤 Melakukan push ke origin $BRANCH..."
    git push origin "$BRANCH"
    
    if [ $? -eq 0 ]; then
        echo "🎉 Push Selesai! Kode Anda sudah aman di GitHub."
    else
        echo "❌ Gagal melakukan push. Periksa koneksi atau SSH Key Anda."
    fi
else
    echo "⚠️ Gagal melakukan commit. Mungkin tidak ada perubahan yang di-stage?"
fi
