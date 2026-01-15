function validasi() {
    let username = document.querySelector('input[name="username"]').value;
    let password = document.querySelector('input[name="password"]').value;
    let msg = document.getElementById('msg');

    if (username === '' || password === '') {
        msg.style.display = 'block';
        msg.innerText = 'Username dan password wajib diisi';
        return;
    }
    msg.style.display = 'none';
}

