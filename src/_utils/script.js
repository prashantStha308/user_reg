document.addEventListener('DOMContentLoaded', () => {
    const editBtn = document.getElementById("edit");
    const cancleEditBtn = document.getElementById("cancleEdit");
    const finishEditBtn = document.getElementById("finishEdit");
    const submitBtns = Array.from(document.querySelectorAll("#dashboard_form button[type='submit']"));
    const inputElements = Array.from(document.querySelectorAll("#dashboard_form input, #dashboard_form textarea"));

    const username = document.querySelector("input[name='username']");
    const email = document.querySelector("input[name='email']");
    const description = document.querySelector("textarea[name='description']");

    let uValue , eValue , dValue;

    const toggleInputs = () => {
        inputElements.forEach(item => {
            item.readOnly = !item.readOnly;
        });
    }

    function handleUpdate() {
        uValue = username.value;
        eValue = email.value;
        dValue = description.value;
        toggleInputs();
        // Hide all submit buttons except Finish Edit
        submitBtns.forEach(btn => {
            if (btn !== finishEditBtn) {
                btn.classList.add("hidden");
            }
        });

        // Toggle visibility between Edit and Finish Edit buttons
        editBtn.classList.add("hidden");
        finishEditBtn.classList.remove("hidden");
        cancleEditBtn.classList.remove("hidden");

        if (inputElements.length > 0 && !inputElements[0].readOnly) {
            inputElements[0].focus();
        }
    }

    function cancleEdit(){
        username.value = uValue;
        email.value = eValue;
        description.value = dValue;
        toggleInputs();
        // Hide all submit buttons except Finish Edit
        submitBtns.forEach(btn => {
            if (btn !== finishEditBtn) {
                btn.classList.remove("hidden");
            }
        });

        // Toggle visibility between Edit and Finish Edit buttons
        editBtn.classList.remove("hidden");
        finishEditBtn.classList.add("hidden");
        cancleEditBtn.classList.add("hidden");
    }

    editBtn.addEventListener('click', handleUpdate);
    cancleEditBtn.addEventListener('click',cancleEdit);
});
