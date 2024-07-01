document.addEventListener('DOMContentLoaded', () => {
    let defaultOrderDirection = document.querySelector('#order_direction').value;
    let oldSortColumn = 'products_main.id';

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
        formData.page = currentPage;

        let queryString = new URLSearchParams(formData).toString();
        let newRoute = `${searchRoute}/?${queryString}`;

        fetch(newRoute)
            .then(response => response.json())
            .then(data => {
                initializeTable(data.data);
                updatePagination(data);
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

    function updatePagination(data) {
        let paginationElement = document.querySelector('.d-sm-none .pagination');
        let desktopPaginationElement = document.querySelector('.d-none.d-sm-flex .pagination');

        if (data) {
            currentPage = data.current_page;
        }

        if (data.data.length) {
            desktopPagination(data, desktopPaginationElement);
        } else {
            desktopPaginationElement.innerHTML = '';
        }

        console.log('data: ', data);
    }

    function desktopPagination(data, element) {
        element.innerHTML = '';

        if (data.data.length && data.prev_page_url) {
            let prevLi = document.createElement('li');
            prevLi.classList.add('page-item');
            prevLi.innerHTML = `<a class="page-link" href="javascript:void(0)" data-page="${data.current_page - 1}" rel="prev" aria-label="« Geri">‹</a>`;
            element.appendChild(prevLi);
        } else {
            let prevLi = document.createElement('li');
            prevLi.classList.add('page-item', 'disabled');
            prevLi.innerHTML = `<span class="page-link" aria-hidden="true">‹</span>`;
            element.appendChild(prevLi);
        }

        let maxPagesToShow = 5;
        let startPage = Math.max(1, data.current_page - Math.floor(maxPagesToShow / 2));
        let endPage = Math.min(data.last_page, startPage + maxPagesToShow - 1);

        if (startPage > 1) {
            let firstPageLi = document.createElement('li');
            firstPageLi.classList.add('page-item');
            firstPageLi.innerHTML = `<a class="page-link" href="javascript:void(0)" data-page="1">1</a>`;
            element.appendChild(firstPageLi);

            if (startPage > 2) {
                let dotsLi = document.createElement('li');
                dotsLi.classList.add('page-item', 'disabled');
                dotsLi.innerHTML = `<span class="page-link">...</span>`;
                element.appendChild(dotsLi);
            }
        }

        if (data.data.length) {
            for (let i = startPage; i <= endPage; i++) {
                let pageLi = document.createElement('li');
                pageLi.classList.add('page-item');
                if (data.current_page === i) {
                    pageLi.classList.add('active');
                    pageLi.innerHTML = `<span class="page-link">${i}</span>`;
                } else {
                    pageLi.innerHTML = `<a class="page-link" href="javascript:void(0)" data-page="${i}">${i}</a>`;
                }
                element.appendChild(pageLi);
            }
        }

        if (endPage < data.last_page) {
            if (endPage < (data.last_page - 1)) {
                let dotsLi = document.createElement('li');
                dotsLi.classList.add('page-item', 'disabled');
                dotsLi.innerHTML = `<span class="page-link">...</span>`;
                element.appendChild(dotsLi);
            }

            let lastPageLi = document.createElement('li');
            lastPageLi.classList.add('page-item');
            lastPageLi.innerHTML = `<a class="page-link" href="javascript:void(0)" data-page="${data.last_page}">${data.last_page}</a>`;
            element.appendChild(lastPageLi);
        }

        if (data.data.length && data.next_page_url) {
            let nextLi = document.createElement('li');
            nextLi.classList.add('page-item');
            nextLi.innerHTML = `<a class="page-link" href="javascript:void(0)" data-page="${data.current_page + 1}" rel="next" aria-label="İleri »">›</a>`;
            element.appendChild(nextLi);
        } else {
            let nextLi = document.createElement('li');
            nextLi.classList.add('page-item', 'disabled');
            nextLi.innerHTML = `<span class="page-link" aria-hidden="true">›</span>`;
            element.appendChild(nextLi);
        }

        element.querySelectorAll('a.page-link').forEach(link => {
            link.addEventListener('click', function () {
                currentPage = parseInt(this.getAttribute('data-page'));
                inputChange();
            });
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

    inputChange();
});
