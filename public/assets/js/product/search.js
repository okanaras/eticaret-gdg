document.addEventListener('DOMContentLoaded', () => {
    let currentPage = 1;
    let defaultOrderDirection = document.querySelector('#order_direction').value;
    let oldSortColumn = 'products_main.id';
    let currentSortDirection = 'asc';

    let inputs = document.querySelectorAll('#filter-form input, #filter-form select');

    inputs.forEach(input => {
        // input.addEventListener('change', inputChange);
        input.addEventListener('input', inputChange);
    });

    function inputChange() {
        let inputs = document.querySelectorAll('#filter-form input, #filter-form select');

        let formData = {};
        inputs.forEach(input => {
            formData[input.getAttribute('name')] = input.value;
        });

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
            <td>${product.cname}</td>
            <td>${product.bname}</td>
            <td>${product.typename}</td>
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

    document.querySelector('.table').addEventListener('click', (event) => {
        let element = event.target;

        if (element.classList.contains('order-by')) {
            let dataOrder = element.getAttribute('data-order');
            let orderByElement = document.querySelector('#order_by');
            let orderDirectionElement = document.querySelector('#order_direction');

            orderByElement.value = dataOrder;
            removeIElements();

            defaultOrderDirection = orderDirectionElement.value;

            if (oldSortColumn === dataOrder && defaultOrderDirection === 'asc') {
                defaultOrderDirection = 'desc';
                let iElement = document.createElement('i');
                iElement.setAttribute('data-feather', 'arrow-up-circle');
                iElement.classList.add('size-14');
                element.appendChild(iElement);
            } else {
                defaultOrderDirection = 'asc';
                let iElement = document.createElement('i');
                iElement.setAttribute('data-feather', 'arrow-down-circle');
                iElement.classList.add('size-14');
                element.appendChild(iElement);
            }



            // if (defaultOrderDirection === '' || defaultOrderDirection === null ||
            //     defaultOrderDirection === undefined) {
            //     defaultOrderDirection = 'desc';

            //     let iElement = document.createElement('i');
            //     iElement.setAttribute('data-feather', 'arrow-up-circle');
            //     iElement.classList.add('size-14');
            //     element.appendChild(iElement);

            // } else if (defaultOrderDirection === 'asc') {
            //     defaultOrderDirection = 'desc';
            //     let iElement = document.createElement('i');
            //     iElement.setAttribute('data-feather', 'arrow-up-circle');
            //     iElement.classList.add('size-14');
            //     element.appendChild(iElement);
            // } else {
            //     defaultOrderDirection = 'asc';
            //     let iElement = document.createElement('i');
            //     iElement.setAttribute('data-feather', 'arrow-down-circle');
            //     iElement.classList.add('size-14');
            //     element.appendChild(iElement);
            // }

            orderDirectionElement.value = defaultOrderDirection;
            feather.replace();
            inputChange();
            oldSortColumn = dataOrder;
        }

        function removeIElements() {
            let findIElement = document.querySelectorAll('th svg');
            findIElement.forEach(i => i.remove());
        }

    });
});
