<!DOCTYPE html>
<html>
<head>
    <title>Peringatan Keterlambatan Perbaikan</title>
</head>
<body style="font-family: 'Plus Jakarta Sans', Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f8fafc; padding: 20px;">
    
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 10px; border-top: 5px solid #DC2626; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        
        <h2 style="color: #DC2626; margin-top: 0;">🚨 PERINGATAN: Perbaikan Melewati Batas Waktu</h2>
        
        <p>Halo <strong>{{ $details['name'] }}</strong>,</p>
        
        <p>Email ini adalah pengingat otomatis bahwa tugas perbaikan masalah air yang ditugaskan kepada Anda telah melewati batas waktu yang ditentukan. Saat ini kita sudah berada di tanggal <strong>26 April 2026</strong>, dan warga masih menunggu penanganan.</p>
        
        <div style="background-color: #FEE2E2; border-left: 4px solid #DC2626; padding: 15px; margin: 20px 0; border-radius: 4px;">
            <p style="margin: 0 0 10px 0;"><strong>Detail Laporan:</strong></p>
            <ul style="margin: 0; padding-left: 20px;">
                <li><strong>Lokasi:</strong> {{ $details['location'] }}, Surabaya, Jawa Timur</li>
                <li><strong>Keluhan:</strong> {{ $details['problem'] }}</li>
                <li><strong>Dilaporkan Oleh:</strong> {{ $details['reporter_count'] }} warga (Cluster Alert)</li>
                <li><strong>Batas Waktu:</strong> {{ $details['due_date'] }}</li>
            </ul>
        </div>

        <p>Mohon segera menuju ke lokasi untuk melakukan perbaikan demi mencegah krisis air bersih dan gangguan kesehatan pada warga sekitar.</p>
        
        <p>Silakan perbarui status di dashboard AirLayak setelah penanganan selesai.</p>
        
        <br>
        <p style="margin-bottom: 0;">Terima kasih,<br><strong>Sistem Pemantauan AirLayak Surabaya</strong></p>
    </div>

</body>
</html>