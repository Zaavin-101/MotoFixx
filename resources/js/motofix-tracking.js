import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const config = window.motofixReverb || {};
if (window.motofixTracking && config.key && config.host) {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: config.key,
        wsHost: config.host,
        wsPort: Number(config.port || 8080),
        wssPort: Number(config.port || 8080),
        forceTLS: (config.scheme || 'http') === 'https',
        enabledTransports: ['ws', 'wss'],
        authEndpoint: '/broadcasting/auth',
        auth: { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' } },
    });

    const statusEl = document.getElementById('live-status');
    const noteEl = document.getElementById('live-note');
    const connectionEl = document.getElementById('connection-status');
    const historyEl = document.getElementById('status-history');
    const labels = { antrian:'Antrian', pengecekan:'Pengecekan', pengerjaan:'Pengerjaan', test_drive:'Test Drive', selesai:'Selesai' };

    window.Echo.private(`users.${window.motofixTracking.userId}`)
        .listen('.booking.status.updated', (event) => {
            if (Number(event.booking_id) !== Number(window.motofixTracking.bookingId)) return;
            statusEl.textContent = labels[event.status] || event.status;
            noteEl.textContent = event.keterangan || 'Status baru diterima.';
            const li = document.createElement('li');
            li.className = 'flex gap-3 rounded-xl border border-white/10 p-4';
            const dot = document.createElement('span'); dot.className = 'mt-1 h-2.5 w-2.5 shrink-0 rounded-full bg-brand';
            const content = document.createElement('div');
            const title = document.createElement('p'); title.className = 'font-semibold'; title.textContent = labels[event.status] || event.status;
            const note = document.createElement('p'); note.className = 'mt-1 text-sm text-zinc-400'; note.textContent = event.keterangan || 'Status diperbarui.';
            const time = document.createElement('p'); time.className = 'mt-1 text-xs text-zinc-500'; time.textContent = new Date(event.updated_at || Date.now()).toLocaleString('id-ID');
            content.append(title, note, time); li.append(dot, content); historyEl.prepend(li);
        });

    window.Echo.connector.pusher.connection.bind('connected', () => connectionEl.textContent = 'terhubung realtime');
    window.Echo.connector.pusher.connection.bind('disconnected', () => connectionEl.textContent = 'terputus, mencoba menghubungkan kembali…');
} else {
    const connectionEl = document.getElementById('connection-status');
    if (connectionEl) connectionEl.textContent = 'belum dikonfigurasi — ikuti README Reverb';
}
