import axios from 'axios';

async function login(email, password) {
    try {
        const response = await axios.post('/api/auth/login', { email, password });
        const apiResponse = response.data;
        if (apiResponse && apiResponse.data.token) {
            const token = apiResponse.data.token;

            // Simpan JWT
            localStorage.setItem('jwt_token', token);

            // Simpan payload pengguna
            saveAuthUserLogin(token);

            return response.data;
        }
    } catch (error) {
        console.error('Login Error:', error.response?.data || error.message);
        return null;
    }
}


async function register(name, email, phone, password, password_confirmation) {
    try {
        const response = await axios.post('/api/auth/register', {
            name,
            email,
            phone,
            password,
            password_confirmation
        });
        const apiResponse = response.data;
        if (apiResponse && apiResponse.data.token) {
            const token = apiResponse.data.token;

            // Simpan JWT
            localStorage.setItem('jwt_token', token);

            // Simpan payload pengguna
            saveAuthUserLogin(token);

            return response.data;
        }
    } catch (error) {
        if (error.response && error.response.data) {
            return error.response.data;
        }
        console.error('Register Error:', error.message);
        return null;
    }
}

async function logout() {
    try {
        await axios.post('/api/auth/logout', {}, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('jwt_token')}`
            }
        });
        localStorage.removeItem('jwt_token'); // Hapus JWT
        localStorage.removeItem('auth_user'); // Hapus payload pengguna
        return true;
    } catch (error) {
        return false;
    }
}

function saveAuthUserLogin(token) {
    try {
        // Decode payload dari JWT
        const payload = JSON.parse(atob(token.split('.')[1])); // Bagian kedua adalah payload

        // Hanya ambil data `name`, `email`, dan `role`
        const { name, email, phone, role } = payload;

        // Simpan di localStorage
        localStorage.setItem(
            'auth_user',
            JSON.stringify({ name, email, phone, role })
        );
    } catch (error) {
        console.error('Error decoding JWT payload:', error);
    }
}


function getAuthUserLogin() {
    try {
        const user = localStorage.getItem('auth_user');
        return user ? JSON.parse(user) : null;
    } catch (error) {
        console.error('Error parsing auth_user:', error);
        return null;
    }
}

function updateAuthUserLogin() {
    const token = localStorage.getItem('jwt_token');

    // cek apakah token ada
    if (!token) {
        return null;
    }
    // cek apakah token sudah expired
    const payload = JSON.parse(atob(token.split('.')[1]));
    const exp = payload.exp;
    const now = Math.floor(Date.now() / 1000);
    if (exp < now) {
        localStorage.removeItem('jwt_token');
        localStorage.removeItem('auth_user');
        return null;
    }
    // update data user
    saveAuthUserLogin(token);

    return getAuthUserLogin();
}

function checkLoginStatus() {
    return updateAuthUserLogin() ? true : false;
}

// check if route is login or register
const isAuthPage = window.location.pathname.includes('login') || window.location.pathname.includes('register');
if (isAuthPage) {
    // check user is already logged in
    const check = checkLoginStatus();
    if (check) {
        window.location.href = '/'; // redirect to home page
    }
}

window.checkLoginStatus = checkLoginStatus;
window.authLogin = login;
window.authRegister = register;
window.authLogout = logout;
window.getAuthUser = getAuthUserLogin;
window.isAuthPage = isAuthPage;
updateAuthUserLogin();