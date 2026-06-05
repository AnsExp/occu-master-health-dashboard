const roleSelect = document.getElementById('role');
const doctorFields = document.getElementsByClassName('doctor-fields');
const phoneField = document.getElementById('phone');
const lastnameField = document.getElementById('last_name');
const idCardField = document.getElementById('id_card');
const specialtyField = document.getElementById('specialty');

roleSelect.addEventListener('change', () => {
    Array.from(doctorFields).forEach(field => {
        roleSelect.value == doctorRoleId ? field.classList.remove('hidden') : field.classList.add('hidden');
    });
    phoneField.required = roleSelect.value == doctorRoleId;
    lastnameField.required = roleSelect.value == doctorRoleId;
    idCardField.required = roleSelect.value == doctorRoleId;
    specialtyField.required = roleSelect.value == doctorRoleId;
});
