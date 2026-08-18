async function apiFetch(url, options = {}) {
    let accessToken = localStorage.getItem('access_token');
    let refreshToken = localStorage.getItem('refresh_token');

    options.headers = {
        'Accept': 'application/json',
        ...(options.headers || {}),
        'X-CSRF-TOKEN': window.CSRF_TOKEN,
        'Authorization': `Bearer ${accessToken}`
    };

    let res = await fetch(url, options);
    if (res.status === 401) {
        const errorData = await res.clone().json().catch(() => ({}));
        console.log("API 401:", errorData);

        if (errorData.code === 'TOKEN_EXPIRED') {
            console.log("Access Token หมดอายุ กำลัง refresh token...");
        }

        const refreshRes = await fetch(`${window.BASE_URL}jwt/refresh`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": window.CSRF_TOKEN
            },
            body: JSON.stringify({
                refresh_token: refreshToken
            })
        });

        if (!refreshRes.ok) {
            const refreshError = await refreshRes.json().catch(() => ({}));
            console.log("Refresh failed:", refreshError);
            Swal.fire({
                icon: 'warning',
                title: 'Token หมดอายุ',
                text: 'กรุณาเข้าสู่ระบบใหม่'
            }).then(() => {
                localStorage.removeItem('access_token');
                localStorage.removeItem('refresh_token');
                window.location.href = `${window.BASE_URL}login`;
            });
            
            $(document).on("click", "#btnYes", function() {
                localStorage.removeItem('access_token');
                localStorage.removeItem('refresh_token');
                window.location.href = `${window.BASE_URL}login`;
            });
           
            // หยุด function
            return new Promise(() => {});
            //throw new Error('Refresh token expired');
        }

        const refreshData = await refreshRes.json();
        console.log("ได้ Access Token ใหม่แล้ว");
        localStorage.setItem('access_token', refreshData.access_token);
        options.headers['Authorization'] = `Bearer ${refreshData.access_token}`;
        res = await fetch(url, options);
    }

    return res;
}