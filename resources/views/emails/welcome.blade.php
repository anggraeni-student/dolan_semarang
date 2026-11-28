<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat datang di Dolan Semarang</title>
</head>
<body style="margin:0;padding:0;background:#F6F7F9;font-family:'Segoe UI',Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F6F7F9;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:560px;background:#FFFFFF;border-radius:18px;overflow:hidden;border:1px solid #E9EBEF;">
                    {{-- Header --}}
                    <tr>
                        <td style="background:#E23A4E;padding:26px 32px;">
                            <span style="font-size:19px;font-weight:800;color:#FFFFFF;letter-spacing:.3px;">Dolan Semarang</span>
                            <div style="font-size:12.5px;color:#FFD9DE;margin-top:3px;">Review tempat sejujur teman sendiri</div>
                        </td>
                    </tr>

                    {{-- Isi --}}
                    <tr>
                        <td style="padding:30px 32px 8px;">
                            <h1 style="margin:0 0 12px;font-size:21px;color:#221D27;">Halo, {{ $user->name }}! 👋</h1>
                            <p style="margin:0 0 14px;font-size:14.5px;line-height:1.7;color:#4A4450;">
                                Selamat datang di <b>Dolan Semarang</b>! Akunmu dengan username
                                <b>@{{ $user->username }}</b> sudah aktif.
                            </p>
                            <p style="margin:0 0 14px;font-size:14.5px;line-height:1.7;color:#4A4450;">
                                Mulai perjalananmu: tulis review jujur, tanya-tanya di komunitas,
                                atau tambahkan tempat favoritmu yang belum terdaftar.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:8px 32px 26px;">
                            <a href="{{ route('login') }}" style="display:inline-block;background:#E23A4E;color:#FFFFFF;font-weight:700;font-size:14px;text-decoration:none;padding:12px 26px;border-radius:10px;">Masuk ke Akunmu</a>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:18px 32px;border-top:1px solid #E9EBEF;background:#FAFBFC;">
                            <p style="margin:0;font-size:12px;line-height:1.6;color:#8A8391;">
                                Email ini dikirim otomatis karena ada pendaftaran baru dengan alamat ini.
                                Kalau bukan kamu yang mendaftar, abaikan email ini.
                            </p>
                        </td>
                    </tr>
                </table>
                <p style="margin:14px 0 0;font-size:11.5px;color:#9AA0A8;">© 2026 Dolan Semarang — Semarang Raya</p>
            </td>
        </tr>
    </table>
</body>
</html>
