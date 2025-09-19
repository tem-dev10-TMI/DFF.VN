document.addEventListener('DOMContentLoaded', function () {
    // Password visibility toggle
    const passwordInputs = document.querySelectorAll('.password-input');
    const toggleButtons = document.querySelectorAll('.password-toggle');

    toggleButtons.forEach((button, index) => {
        button.addEventListener('click', function () {
            const type = passwordInputs[index].getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInputs[index].setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });

    // Lấy các form: ưu tiên .auth-form, nếu không có thì fallback #login #register
    let formsArr = Array.from(document.querySelectorAll('.auth-form'));
    if (formsArr.length === 0) {
        ['login', 'register'].forEach(id => {
            const f = document.getElementById(id);
            if (f) formsArr.push(f);
        });
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^\d{10}$/; // thay đổi nếu cần
    const strongPasswordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

    formsArr.forEach(form => {
        // submit handler
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            clearFormErrors(form);

            // helper: lấy value nếu tồn tại
            const getVal = (name) => {
                const el = form.querySelector(`[name="${name}"]`);
                return el ? el.value.trim() : '';
            };

            const values = {
                name: getVal('name'),
                username: getVal('username'),
                email: getVal('email'),
                phone: getVal('phone'),
                password: getVal('password'),
                password_confirm: getVal('password_confirm')
            };

            // phát hiện đây là form register hay login
            const isRegister = (
                form.id === 'register'
                || !!form.querySelector('[name="password_confirm"]')
                || form.dataset.auth === 'register'
            );

            const errors = {};

            // --- Kiểm tra chung ---
            if (!values.username) {
                errors.username = 'Vui lòng nhập tên đăng nhập';
            }

            if (!values.password) {
                errors.password = 'Vui lòng nhập mật khẩu';
            }

            // --- Kiểm tra riêng cho register ---
            if (isRegister) {
                if (!values.name) {
                    errors.name = 'Vui lòng nhập họ và tên';
                }

                if (!values.email) {
                    errors.email = 'Vui lòng nhập email';
                } else if (!emailRegex.test(values.email)) {
                    errors.email = 'Email không hợp lệ';
                }

                if (!values.phone) {
                    errors.phone = 'Vui lòng nhập số điện thoại';
                } else if (!phoneRegex.test(values.phone)) {
                    errors.phone = 'Số điện thoại phải có 10 chữ số';
                }

                // username rule cho register
                if (values.username && (values.username.length < 3 || values.username.length > 20)) {
                    errors.username = 'Tên đăng nhập phải từ 3 đến 20 ký tự';
                } else if (values.username && !/^[a-z0-9_]+$/.test(values.username)) {
                    errors.username = 'Tên đăng nhập chỉ được chứa chữ thường, số và dấu gạch dưới';
                }

                // password mạnh cho register
                if (values.password && !strongPasswordRegex.test(values.password)) {
                    errors.password = 'Mật khẩu phải ≥8 ký tự, có chữ hoa, chữ thường, số và ký tự đặc biệt';
                }

                // confirm password
                if (!values.password_confirm) {
                    errors.password_confirm = 'Vui lòng xác nhận mật khẩu';
                } else if (values.password_confirm !== values.password) {
                    errors.password_confirm = 'Mật khẩu xác nhận không khớp';
                }
            } else {
                // login: chỉ cần username và password không rỗng (nếu bạn muốn quy tắc khác cho login thì thay ở đây)
            }

            // Nếu có lỗi -> hiển thị
            if (Object.keys(errors).length > 0) {
                showErrors(form, errors);
                // focus field lỗi đầu tiên
                const firstName = Object.keys(errors)[0];
                const firstInput = form.querySelector(`[name="${firstName}"]`);
                if (firstInput) firstInput.focus();
                return;
            }

            // Nếu hợp lệ -> submit (an toàn nếu form có element name="submit")
            if (typeof form.submit === 'function') {
                form.submit();
            } else {
                HTMLFormElement.prototype.submit.call(form);
            }
        });

        // Xoá lỗi khi user nhập lại vào input (real-time)
        form.addEventListener('input', function (e) {
            const input = e.target;
            if (!input || !input.name) return;
            // remove inline error message
            const inline = form.querySelector(`.error-for-${input.name}`);
            if (inline) inline.remove();
            input.classList.remove('is-invalid');
            // Nếu không còn alert-danger tổng thể thì xoá nó
            const alert = getErrorContainer(form)?.querySelector('.alert-danger');
            if (alert && form.querySelectorAll('.error-for-').length === 0) {
                alert.remove();
            }
        });
    });

    // --- Helpers ---
    function clearFormErrors(form) {
        // remove global alert
        const oldAlert = getErrorContainer(form)?.querySelector('.alert-danger') || form.querySelector('.alert-danger');
        if (oldAlert) oldAlert.remove();
        // remove inline errors and classes
        form.querySelectorAll('[data-inline-error="1"]').forEach(n => n.remove());
        form.querySelectorAll('.is-invalid').forEach(n => n.classList.remove('is-invalid'));
    }

    function getErrorContainer(form) {
        // ưu tiên .popup-area-msg trong modal nếu có
        const modal = form.closest('.modal');
        if (modal) {
            const box = modal.querySelector('.popup-area-msg');
            if (box) return box;
        }
        // fallback: tìm trong chính form
        return form.querySelector('.popup-area-msg') || null;
    }

    function showErrors(form, errors) {
        for (let key in errors) {
            const input = form.querySelector(`[name="${key}"]`);
            if (input) {
                input.classList.add('is-invalid');

                // tạo thẻ nhỏ hiển thị lỗi sát input
                const small = document.createElement('small');
                small.className = `text-danger error-for-${key}`;
                small.dataset.inlineError = '1';
                small.textContent = errors[key];

                // tìm container col-12 bao quanh input-group
                const col = input.closest('.col-12');
                if (col) {
                    // xoá lỗi cũ nếu có
                    const old = col.querySelector(`.error-for-${key}`);
                    if (old) old.remove();
                    col.appendChild(small); // ✅ lỗi nằm ngay dưới input-group
                } else {
                    input.insertAdjacentElement('afterend', small);
                }
            }
        }
    }
});
const menuBtn = document.querySelector('.menu-toggle');
const leftSidebar = document.querySelector('.left-sidebar');

menuBtn.addEventListener('click', () => {
    leftSidebar.classList.toggle('open');
});
