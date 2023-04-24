// Age Range

const ageMinInput = document.getElementById("age_min");
const ageMaxInput = document.getElementById("age_max");
const ageRangeInput = document.getElementById("age_range");

function updateAgeRange() {
    console.log('updateAgeRange ')
    let ageMin = ageMinInput.value = Math.max(parseInt(ageMinInput.value) || 1, 1);
    let ageMax = ageMaxInput.value = Math.max(parseInt(ageMaxInput.value) || 2, 2);

    if (ageMin >= ageMax) {
        ageMaxInput.value = ageMin + 1;
        ageRangeInput.value = `${ageMin},${ageMin + 1}`;

    } else {
        ageRangeInput.value = `${ageMin},${ageMax}`;
    }

}

ageMinInput.addEventListener("input", updateAgeRange);
ageMaxInput.addEventListener("input", updateAgeRange);


// Checkbox

const ageInput = document.getElementById('age');
const ageSimpleDiv = document.getElementById('age_simple');
const ageRangeDiv = document.getElementById('age_range_div');
const disabledCheckbox = document.getElementById('disabled');

function toggleDisabled() {

    if (disabledCheckbox.checked) {
        ageInput.setAttribute('disabled', true);
        ageInput.value = ``;
        ageSimpleDiv.classList.add('d-none');
        ageMinInput.removeAttribute('disabled');
        ageMaxInput.removeAttribute('disabled');
        ageRangeDiv.classList.remove('d-none');
        ageRangeInput.removeAttribute("disabled");


    } else {

        ageInput.removeAttribute('disabled');
        ageSimpleDiv.classList.remove('d-none');
        ageMinInput.setAttribute('disabled', '');
        ageMaxInput.setAttribute('disabled', '');
        ageRangeDiv.classList.add('d-none');
        ageRangeInput.setAttribute("disabled", true);
        ageRangeInput.value = ``;
    }
}


