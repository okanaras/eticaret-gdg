document.addEventListener('DOMContentLoaded', () => {
    // * axios setup
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    axios.defaults.headers.common['Content-Type'] = 'application/json';

    let btnSubmit = document.querySelector("#btnSubmit");
    let gdgForm = document.querySelector("#gdgForm");
    let addVariant = document.querySelector("#addVariant");
    let variants = document.querySelector("#variants");
    let typeID = document.querySelector("#type_id");

    let productVariantTab = document.querySelector("#productVariantTab"); // control

    let varianCount = 0;
    let varianSizeStockInfo = [];
    const sizeDivKey = "sizeDiv";
    const requiredFields = {
        name: { type: "input", },
        price: { type: "input", data_type: "price", },
        type_id: { type: "select", },
        brand_id: { type: "select", },
        category_id: { type: "select", },
    };
    const sizes = {
        1: ["XS", "S", "M", "L", "XL", "XXL", "3XL", "4XL", "5XL"],
        2: Array.from({ length: 31 }, (_, i) => i + 20),
        3: ["standart"]
    };

    /** BTNSUBMIT **/
    btnSubmit.addEventListener('click', () => {
        let { isValid, message } = validateForm();

        if (isValid) {
            gdgForm.submit();
        } else {
            toastr.error(message || 'Lutfen gerekli alanlari doldurunuz.', 'Uyari!');
        }
    });

    /**Varyant ekle butonuna basildigindaki olaylar... **/
    addVariant.addEventListener("click", () => {
        let row = createDiv('row variant', `row-${varianCount}`);
        let row2 = createDiv('row');

        // variant delete div olustrduk ve icerisine a butonunu ekledim
        let varianDeleteDiv = createDiv('col-md-12 mb-1');
        let variantDeleteAElement = createElement('a', 'btn-delete-variant btn btn-danger col-md-3', { 'href': 'javascript:void(0)', 'data-variant-id': varianCount });
        variantDeleteAElement.textContent = 'Variant Kaldir';
        varianDeleteDiv.appendChild(variantDeleteAElement);
        row2.appendChild(varianDeleteDiv);
        row.appendChild(row2);

        // variant form elemanlarini arr icinde obje seklinde tuttuk
        let fields = [
            { id: 'name', label: 'Urun Adi', className: 'variant-product-name', colClass: 'col-md-4 mb-4' },
            { id: 'variant_name', label: 'Urun Varyant Adi', className: 'variant-name', colClass: 'col-md-4 mb-4' },
            { id: 'slug', label: 'Slug', className: 'product-slug', colClass: 'col-md-4 mb-4' },
            { id: 'additional_price', label: 'Fiyat', className: 'additional-price-input', colClass: 'col-md-6 mb-4', dataAttr: { 'data-variant-id': varianCount } },
            { id: 'final_price', label: 'Son Fiyat', className: 'readonly', colClass: 'col-md-6 mb-4', readonly: true, value: document.querySelector("#price").value },
            { id: 'extra_description', label: 'Ekstra Aciklama', className: '', colClass: 'col-md-12 mb-4' },
            { id: 'publish_date', label: 'Yayimlanma Tarihi', className: '', colClass: 'col-md-12 mb-4', date: true },
            { id: 'p_status', label: 'Aktif mi?', className: '', colClass: 'col-md-6 mb-4', checkbox: true },
        ];

        fields.forEach(field => {
            let colDiv = createDiv(field.colClass);
            colDiv.appendChild(createLabel('form-label', `${field.id}-${varianCount}`));
            let input;
            if (field.checkbox) {
                input = createInput('form-check-input me-2', `${field.id}-${varianCount}`, '', `variant[${varianCount}][${field.id}]`, 'checkbox');
                colDiv.appendChild(input);
            } else if (field.date) {
                input = createInput('form-control-input me-2', `${field.id}-${varianCount}`, field.label, `variant[${varianCount}][${field.id}]`);
                let span = createElement('span', 'input-group-text input-group-addon', { 'data-toggle': '' });
                span.innerHTML = '<i data-feather="calendar"></i>';
                let dateDiv = createDiv('input-group flatpickr flatpickr-date');
                dateDiv.appendChild(input);
                dateDiv.appendChild(span);
                colDiv.appendChild(dateDiv);
            } else {
                input = createInput('form-control', `${field.id}-${varianCount}`, field.label, `variant[${varianCount}][${field.id}]`, field.type || 'text', field.value || '');
                if (field.dataAttr) Object.entries(field.dataAttr).forEach(([key, value]) => input.setAttribute(key, value));
                if (field.readonly) { input.readonly = true; input.classList.add('readonly'); }
                colDiv.appendChild(input);
            }
            row.appendChild(colDiv);
        });
    });

    /** CREATE ELEMENTS **/
    const createElement = (tag, className = '', attrs = {}) => {
        let el = document.createElement(tag);
        el.className = className;

        Object.entries(attrs).forEach(([key, value]) => el.setAttribute(key, value));
        return el;
    };

    const createInput = (className, id, placeholder, name, type = 'text', value = '') => createElement('input', className, { id, placeholder, name, type, value });

    const createDiv = (className, id = '') => createElement('div', className, { id });

    const createLabel = (className, forAttr, textContent = '') => {
        let label = createElement('label', className, { for: forAttr });
        label.textContent = textContent;
        // innetHTML kontrol
        return label
    };

    const createSelect = (className, id, name, options = []) => {
        let select = createElement('select', className, { id, name });
        options.forEach(opt => {
            let option = createElement('optian', '', { value: opt === 'Beden Secebilirsiniz' ? '-1' : opt })
            option.textContent = opt;
            select.appendChild(option);
        });
        return select;
    };
});
