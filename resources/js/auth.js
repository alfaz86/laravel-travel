import axios from 'axios';

async function checkLoginStatus() {
    try {
        const response = await axios.post('/api/auth/me', {}, {
            headers: {
                Authorization: `Bearer ${localStorage.getItem('jwt_token')}`
            }
        });
        return response.status === 200;
    } catch (error) {
        return false;
    }
}

window.checkLoginStatus = checkLoginStatus;