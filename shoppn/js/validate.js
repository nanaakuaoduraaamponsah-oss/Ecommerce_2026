document.addEventListener('DOMContentLoaded', function () {
const signupForm = document.getElementById('signup-form');
if (signupForm) {
    signupForm.addEventListener('submit', function (evt) {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const pwdPattern = /^(?=.*[A-Za-z])(?=.*\d).{8,}$/.test(password);


    const emailField   = signupForm.querySelector('[name="email_addr"]');
    const contactField = document.getElementById('signup-contact');
    const passField    = document.getElementById('signup-pass');

    const problems = [];
    if (!emailPattern.test(emailField.value)) problems.push('Enter a valid email address.');
    if (!phonePattern.test(contactField.value)) problems.push('Enter a valid contact number.');
    if (!pwdPattern.test(passField.value)) problems.push('Password needs 8+ chars and a number.');

    if (problems.length) {
        evt.preventDefault();
        alert(problems.join('\n'));
        return;
    }

    const btn = document.getElementById('signup-btn');
    btn.disabled = true;
    btn.textContent = 'Creating account...';
    });
}

const loginForm = document.getElementById('login-form');
if (loginForm) {
    loginForm.addEventListener('submit', function (evt) {
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const emailField = loginForm.querySelector('[name="email_addr"]');
    if (!emailPattern.test(emailField.value)) {
        evt.preventDefault();
        alert('Enter a valid email address.');
    }
    });
}
});
document.addEventListener('DOMContentLoaded', function () {
    const productForm = document.getElementById('product-form');
    if (!productForm) return;

    productForm.addEventListener('submit', function (evt) {
    const problems = [];
    const title = productForm.querySelector('[name="product_title"]').value.trim();
    const price = parseFloat(productForm.querySelector('[name="product_price"]').value);
    const fileInput = productForm.querySelector('[name="product_image"]');
    const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if (title.length < 2) problems.push('Enter a product title.');
    if (isNaN(price) || price <= 0) problems.push('Enter a price greater than 0.');

    if (fileInput.files.length) {
        const file = fileInput.files[0];
        if (!allowedTypes.includes(file.type)) problems.push('Image must be JPG, PNG, GIF or WEBP.');
        if (file.size > 2 * 1024 * 1024) problems.push('Image must be 2MB or smaller.');
    }

    if (problems.length) {
        evt.preventDefault();
        alert(problems.join('\n'));
    }
    });
});