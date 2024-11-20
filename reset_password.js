document.getElementById('resetPasswordForm').addEventListener('submit', async function (e) {
    e.preventDefault();

    const username = document.getElementById('username').value.trim();
    const newPassword = document.getElementById('newPassword').value.trim();

    if (!username || !newPassword) {
        alert('กรุณากรอกข้อมูลให้ครบถ้วน');
        return;
    }

    try {
        const response = await fetch('/crud/reset_password.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({ username, newPassword }),
        });

        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new Error('Invalid response format (not JSON)');
        }

        const data = await response.json();

        if (data.status === 'success') {
            alert('ตั้งรหัสผ่านใหม่สำเร็จแล้ว');
            window.location.href = '/Login/';
        } else {
            alert(data.message || 'เกิดข้อผิดพลาด');
        }
    } catch (error) {
        alert('เกิดข้อผิดพลาด: ' + error.message);
        console.error('Error:', error);
    }
});
