const roleSelect = document.getElementById('role');
const specialityField = document.getElementById('speciality');

roleSelect.addEventListener('change', () => {
    roleSelect.value == doctorRoleId ? document.getElementById('speciality-field').classList.remove('hidden') : document.getElementById('speciality-field').classList.add('hidden');
    specialityField.required = roleSelect.value == doctorRoleId;
});
