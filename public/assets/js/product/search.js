document.addEventListener('DOMContentLoaded', () => {
    let currentPage = 1;
    let currentSortColumn = 'id';
    let currentSortDirection = 'asc';

    let inputs = document.querySelectorAll('#filter-form input, #filter-form select');

    inputs.forEach(input => {
        // input.addEventListener('change', inputChange);
        input.addEventListener('input', inputChange);
    });

    function inputChange(event) {
        let filterForm = document.querySelector('#filter-form');
        let inputs = document.querySelectorAll('#filter-form input, #filter-form select');

        let formData = {};
        inputs.forEach(input => {
            formData[input.getAttribute('name')] = input.value;
        });

        let element = event.target;
        // let elementType = element.tagName.toLowerCase();
        // let elementVal = element.val;

        let queryString = new URLSearchParams(formData).toString();

        let newRoute = `${searchRoute}/?${queryString}`;

        fetch(newRoute)
            .then(response => response.json())
            .then(data => {
                initializeTable(data.data);
            });
    };

    function initializeTable(data) {
        let listBody = document.querySelector('#list-body');
        listBody.innerHTML = '';
        data.forEach((product) => {
            let finalEditRoute = editRoute.replace(':main_id_val', product.id)
            const tr = document.createElement("tr");
            tr.innerHTML = `
            <td>${product.id}</td>
            <td>${product.name}</td>
            <td>${product.price}</td>
            <td>${product.category.name}</td>
            <td>${product.brand.name}</td>
            <td>${product.type.name}</td>
            <td>${product.status ?
                    `<a href="javascript:void(0)" class="btn btn-inverse-success btn-change-status" data-id="${product.id}" data-name="${product.name}">Aktif</a>` :
                    `<a href="javascript:void(0)" class="btn btn-inverse-danger btn-change-status" data-id="${product.id}" data-name="${product.name}">Pasif</a>`
                }</td>
            <td>
                <a href="${finalEditRoute}">
                    <i data-feather="edit" class="text-warning"></i>
                </a>
                <a href="javascript:void(0)">
                    <i data-feather="trash" class="text-danger btn-delete-product" data-id="${product.id}" data-name="${product.name}"></i>
                </a>
            </td>
            `;
            listBody.append(tr);
            feather.replace();
        });
    }
});
