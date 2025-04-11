document.addEventListener('DOMContentLoaded', () => {
    const $ = selector => document.querySelector(selector);
    const $$ = selector => Array.from(document.querySelectorAll(selector));

    const dashboardForm = $("#dashboard_form");
    const deleteBtn = $("#deleteBtn");
    const editBtn = $("#edit");
    const cancelEditBtn = $("#cancelEdit");
    const finishEditBtn = $("#finishEdit");
    const submitBtns = $$(`#dashboard_form button[type='submit']`);
    const inputElements = $$("#dashboard_form input, #dashboard_form textarea");
    const passInput = $("input[type='password']");
    const toggleBtn = $("#togglePasswordView");
    const lineThrough = $("#pass-line")?.classList;
    const username = $("input[name='username']");
    const email = $("input[name='email']");

    let uValue, eValue;

    const toggleInputs = () => inputElements.forEach(el => el.readOnly = !el.readOnly);

    const toggleBtnVisibility = (editing) => {
        submitBtns.forEach(btn => btn.classList.toggle("hidden", editing && btn !== finishEditBtn));
        [editBtn, deleteBtn].forEach(el => el?.classList.toggle("hidden", editing));
        finishEditBtn.classList.toggle("hidden", !editing);
        cancelEditBtn.classList.toggle("hidden", !editing);
    };

    const handleUpdate = () => {
        [uValue, eValue] = [username.value, email.value];
        toggleInputs();
        toggleBtnVisibility(true);
        inputElements[0]?.focus();
    };

    const cancelEdit = () => {
        [username.value, email.value] = [uValue, eValue];
        toggleInputs();
        toggleBtnVisibility(false);
    };

    const togglePasswordVisibility = () => {
        if (!passInput) return;
        const isHidden = passInput.type === 'password';
        passInput.type = isHidden ? 'text' : 'password';
        lineThrough?.toggle("hidden", !isHidden);
    };

    const confirmDelete = () => {
        if (!confirm("This process is irreversable. Are you sure about this?")) return;
        const input = Object.assign(document.createElement('input'), {
            type: 'hidden',
            name: 'delete',
            value: '1'
        });
        dashboardForm.appendChild(input);
        dashboardForm.submit();
    };

    if (dashboardForm) {
        editBtn?.addEventListener('click', handleUpdate);
        cancelEditBtn?.addEventListener('click', cancelEdit);
        deleteBtn?.addEventListener('click', confirmDelete);
    }

    toggleBtn?.addEventListener('click', togglePasswordVisibility);
});
