import axios from 'axios';

async function callFunction(functionName, ...parameters) {
    const targetFunction = window[functionName];

    if (typeof targetFunction === 'function') {
        // Jika fungsi asinkron, tunggu hasilnya
        if (targetFunction.constructor.name === 'AsyncFunction') {
            return await targetFunction(...parameters);
        } else {
            // Fungsi biasa
            return targetFunction(...parameters);
        }
    } else {
        throw new Error(`${functionName} is not a valid function`);
    }
}

async function selectTicket(selectedSchedule) {
    try {
        const token = localStorage.getItem('jwt_token');
        const result = await axios.post('/schedule/select-ticket', {
            schedule_id: selectedSchedule
        }, {
            headers: {
                'Authorization': `Bearer ${token}` // Menambahkan token ke header
            }
        });

        // Jika request berhasil, Anda bisa menambahkan logika tambahan di sini, misalnya redirect atau update UI
        // Contoh: window.location.href = '/next-page';
        window.location.href = '/booking/detail';
    } catch (error) {
        // Tangani error dengan menampilkan toast
        showToast('error', 'Terjadi kesalahan saat memproses checkout.', 5000);

        // Log error ke console untuk debugging
        console.error('Error details:', error);

        // Jika error berasal dari response error (misalnya 401 atau 500)
        if (error.response) {
            console.error('Response error:', error.response.data);
        }
        // Jika error berasal dari jaringan (misalnya koneksi internet terputus)
        else if (error.request) {
            console.error('Request error:', error.request);
        }
    }
}

window.callFunction = callFunction;
window.selectTicket = selectTicket;