// Harga per malam untuk setiap hotel dalam USD
const hotelPrices = {
    amanjiwo: 900,
    mulia: 300,
    mandapa: 800,
    lomboklodge: 400
};

// Mengambil elemen form
const hotelSelect = document.getElementById('hotelname');
const priceField = document.getElementById('price');
const checkinField = document.getElementById('checkin');
const checkoutField = document.getElementById('checkout');
const totalField = document.getElementById('total');

// Update harga per malam saat hotel berubah
hotelSelect.addEventListener('change', function() {
    const selectedHotel = hotelSelect.value;
    const pricePerNight = hotelPrices[selectedHotel];
    priceField.value = pricePerNight ? pricePerNight : 0;
    calculateTotal();
});

// Menghitung total biaya berdasarkan tanggal check-in dan check-out
function calculateTotal() {
    const checkinDate = new Date(checkinField.value);
    const checkoutDate = new Date(checkoutField.value);
    const nights = Math.ceil((checkoutDate - checkinDate) / (1000 * 60 * 60 * 24));
    const pricePerNight = parseInt(priceField.value);
    
    if (!isNaN(nights) && nights > 0 && !isNaN(pricePerNight)) {
        totalField.value = pricePerNight * nights;
    } else {
        totalField.value = 0;
    }
}

// Update total biaya saat tanggal check-in atau check-out berubah
checkinField.addEventListener('change', calculateTotal);
checkoutField.addEventListener('change', calculateTotal);

// Menangani pengiriman formulir pemesanan
document.getElementById('transaksiForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Mencegah submit form secara default

    // Mengambil data dari form
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const phone = document.getElementById('phone').value;
    const destination = document.getElementById('destination').value;
    const hotelname = document.getElementById('hotelname').value;
    const checkin = document.getElementById('checkin').value;
    const checkout = document.getElementById('checkout').value;
    const total = document.getElementById('total').value;

    // Membuat objek data pesanan
    const bookingData = {
        name: name,
        email: email,
        phone: phone,
        destination: destination,
        hotelname: hotelname,
        checkin: checkin,
        checkout: checkout,
        total: total
    };

    // Menampilkan data pemesanan ke konsol atau bisa dikirim ke server
    console.log('Booking Data:', bookingData);

    // Menampilkan pesan konfirmasi kepada pengguna
    alert(`Terima kasih ${name}, pemesanan Anda telah diterima!\nDestinasi: ${destination}\nHotel: ${hotelname}\nCheck-In: ${checkin}\nCheck-Out: ${checkout}\nTotal Biaya: $${total}`);

    // Membuat dan mengunduh receipt
    createAndDownloadReceipt(bookingData);

    // Reset form setelah submit
    event.target.reset();
});

// Fungsi untuk membuat dan mengunduh receipt
function createAndDownloadReceipt(data) {
    // Membuat konten receipt dalam bentuk teks
    const receiptContent = `
        Terima kasih atas pemesanan Anda!

        Nama: ${data.name}
        Email: ${data.email}
        Nomor Telepon: ${data.phone}
        Destinasi: ${data.destination}
        Hotel: ${data.hotelname}
        Check-In: ${data.checkin}
        Check-Out: ${data.checkout}
        Total Biaya: $${data.total}

        Kami berharap Anda memiliki pengalaman yang menyenangkan!
    `;

    // Membuat Blob dari konten teks
    const blob = new Blob([receiptContent], { type: 'text/plain' });

    // Membuat link unduh sementara
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = `Receipt_${data.name.replace(/\s+/g, '_')}.txt`; // Nama file dihasilkan dari nama pengguna
    document.body.appendChild(link);

    // Klik link untuk memulai unduhan
    link.click();

    // Menghapus link setelah selesai
    document.body.removeChild(link);
}
