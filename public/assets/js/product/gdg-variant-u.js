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

    /** Varyant ekle butonuna basildigindaki olaylar... **/
    addVariant.addEventListener("click", () => {
        let row = createDiv('row variant', `row-${varianCount}`);
        let row2 = createDiv('row');

        // variant delete div olustrduk ve icerisine a butonunu ekledim
        let varianDeleteDiv = createDiv('col-md-12 mb-1');
        let variantDeleteAElement = createElement('a', 'btn-delete-variant btn btn-danger col-md-3', { 'href': 'javascript:void(0)', 'data-variant-id': varianCount });
        variantDeleteAElement.textContent = 'Variant Kaldir';
        let hr = createElement('hr', 'my-2');
        varianDeleteDiv.appendChild(variantDeleteAElement);
        varianDeleteDiv.appendChild(hr);

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

        let urunAddSizeDiv = createDiv("row");
        let urunAddSizeSpan = createElement('span', "ms-2");
        urunAddSizeSpan.textContent = 'Beden Ekle';
        let urunAddSizeIElement = createElement('i', "add-size", { 'data-feather': 'plus-circle' });
        let urunAddSizeAElement = createElement('a', "btn-add-size col-md-12", { 'href': 'javascript:void', 'data-variant-id': varianCount });
        urunAddSizeAElement.appendChild(urunAddSizeIElement);
        urunAddSizeAElement.appendChild(urunAddSizeSpan);



        let urunAddSizeIElementImage = createElement('i', 'add-size', { 'data-feather': 'image' });
        let imageDataInputElement = createInput("form-control", `data-input-${varianCount}`, '', `image[${varianCount}][]`, 'hidden');
        let imageDataPreviewElement = createDiv("col-md-12", `data-preview-${varianCount}`);

        let urunAddSizeAElementImage = createElement('a', "btn btn-info btn-add-image mb-4", { 'href': 'javascript:void', "data-variant-id": varianCount, "data-input": `data-input-${varianCount}`, "data-preview": `data-preview-${varianCount}` });
        urunAddSizeAElementImage.textContent = "Gorsel Ekle ";
        let urunAddSizeAElementDiv = createDiv("col-md-12");

        urunAddSizeAElementImage.appendChild(urunAddSizeIElementImage);
        urunAddSizeAElementDiv.appendChild(urunAddSizeAElementImage);

        urunAddSizeDiv.appendChild(urunAddSizeAElementDiv);
        urunAddSizeDiv.appendChild(imageDataInputElement);
        urunAddSizeDiv.appendChild(imageDataPreviewElement);
        urunAddSizeDiv.appendChild(urunAddSizeAElement);


        let urunAddSizeGeneralDiv = createDiv("col-md-12 p-0 mb-3", sizeDivKey + varianCount);

        row2.appendChild(varianDeleteDiv);
        row.appendChild(row2);
        row.appendChild(urunAddSizeDiv);
        row.appendChild(urunAddSizeGeneralDiv);

        let hr2 = createElement('hr', 'my-5');
        row.appendChild(hr);

        variants.insertAdjacentElement("afterbegin", row);
        varianCount++;
        feather.replace();
        flatpickr(".flatpickr-date", {
            wrap: true,
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });

    });

    /** Urun bilgileri tab'inda urun turu degistiginde varyant ekleme tabindaki size ve stock alanlarini temizleme **/
    typeID.addEventListener("change", () => {
        document.querySelector(`[id^=${sizeDivKey}]`).forEach(div => div.innerHTML = "");

        // // NOT : her div'in id'si 'SizeKey+VariantCount' (SizeKey0, SizeKey1, SizeKey2...) oldugundan bulma islemi basarili gerceklesmekte
        // for (let i = 0; i <= varianCount; i++) {
        //     let findDiv = document.querySelector("#" + sizeDivKey + i);

        //     if (findDiv) {
        //         findDiv.innerHTML = "";
        //     }
        // }
    });

    /** document body click actions **/
    document.body.addEventListener("click", (event) => {
        let element = event.target;

        if (element.classList.contains("btn-delete-variant")) {
            let variantID = element.getAttribute("data-variant-id");
            let findDeleteVariantElement = document.querySelector(`#row-${variantID}`);

            if (findDeleteVariantElement) {
                findDeleteVariantElement.remove();
                updateVariantIndexes();
            }
        }

        if (element.classList.contains("btn-size-stock-delete")) {
            let dataSizeStockID = element.getAttribute("data-size-stock-id");
            let findSizeStockDiv = document.querySelector(`#sizeStockDeleteGeneral-${dataSizeStockID}`);

            if (findSizeStockDiv) {
                findSizeStockDiv.remove();
                updateSizeStockIndexes(dataSizeStockID);
            }
        }

        if (element.classList.contains("btn-add-size")) {
            btnAddSizeAction(element);
        }
        if (element.parentElement.classList.contains("btn-add-size")) {
            btnAddSizeAction(element.parentElement);
        }

        if (element.classList.contains("btn-add-image")) {
            openFileManager(element);
        }

        if (element.classList.contains("delete-variant-image")) {
            deleteVarianImage(element);
        }


    });

    /** Update Variant Indexes **/
    function updateVariantIndexes() {
        let allVariants = document.querySelectorAll(".row.variant");
        allVariants = [...allVariants].reverse();

        const attributesToUpdate = [
            { selector: '[data-variant-id]', attr: '[data-variant-id]', prefix: '' },
            { selector: '[for^="name-"]', attr: 'for', prefix: 'name-' },
            { selector: '[id^="name-"]', attr: 'id', prefix: 'name-', name: true },
            { selector: '[for^="variant_name-"]', attr: 'for', prefix: 'variant_name-' },
            { selector: '[id^="variant_name-"]', attr: 'id', prefix: 'variant_name-', name: true },
            { selector: '[for^="slug-"]', attr: 'for', prefix: 'slug-' },
            { selector: '[id^="slug-"]', attr: 'id', prefix: 'slug-', name: true },
            { selector: '[for^="additional_price-"]', attr: 'for', prefix: 'additional_price-' },
            { selector: '[id^="additional_price-"]', attr: 'id', prefix: 'additional_price-', name: true },
            { selector: '[for^="final_price-"]', attr: 'for', prefix: 'final_price-' },
            { selector: '[id^="final_price-"]', attr: 'id', prefix: 'final_price-', name: true },
            { selector: '[for^="extra_description-"]', attr: 'for', prefix: 'extra_description-' },
            { selector: '[id^="extra_description-"]', attr: 'id', prefix: 'extra_description-', name: true },
            { selector: '[for^="publish_date-"]', attr: 'for', prefix: 'publish_date-' },
            { selector: '[id^="publish_date-"]', attr: 'id', prefix: 'publish_date-', name: true },
            { selector: '[for^="p_status-"]', attr: 'for', prefix: 'p_status-' },
            { selector: '[id^="p_status-"]', attr: 'id', prefix: 'p_status-', name: true },
            { selector: '[for^="size-"]', attr: 'for', prefix: 'size-' },
            { selector: '[id^="size-"]', attr: 'id', prefix: 'size-', name: true },
            { selector: '[id^="sizeDiv"]', attr: 'id', prefix: 'sizeDiv' },
            { selector: '[id^="sizeStockDeleteGeneral-"]', attr: 'id', prefix: 'sizeStockDeleteGeneral-', special: true },
            { selector: '[for^="stock-"]', attr: 'for', prefix: 'stock-' },
            { selector: '[id^="stock-"]', attr: 'id', prefix: 'stock-', name: true },
            { selector: '[for^="radio-"]', attr: 'for', prefix: 'radio-', special: true },
            { selector: '[id^="radio-"]', attr: 'id', prefix: 'radio-', name: true, special: true },
            { selector: '[id^="data-input-"]', attr: 'id', prefix: 'data-input-', name: 'image' },
        ];

        allVariants.forEach((variant, index) => {
            variant.id = `row-${index}`;
            attributesToUpdate.forEach(({ selector, attr, prefix, name, special }) => {
                variant.querySelectorAll(selector).forEach(element => {
                    if (special && attr === 'id' && selector === '[id^="sizeStockDeleteGeneral-"]') {
                        let [_, oldVariantID, stockID] = element.getAttribute(attr).split('-');
                        element.id = `${prefix}${index}-${stockID}`;
                        element.classList.replace(`size-stock-${oldVariantID}`, `size-stock-${index}`);
                        element.querySelectorAll('[for^="size-"]').forEach(e => e.setAttribute('for', `size-${index}-${stockID}`));
                        element.querySelectorAll('[id^="size-"]').forEach(e => {
                            e.id = `size-${index}-${stockID}`;
                            e.setAttribute('name', `variant[${index}][size][${stockID}]`);
                        });
                    } else if (special && selector === '[for^="radio-"]' && attr === 'for') {
                        let [_, __, imageID] = element.getAttribute(attr).split('-');
                        element.setAttribute(attr, `${prefix}${index}-${imageID}`);
                    } else if (special && selector === '[id^="radio-"]') {
                        let [_, __, imageID] = element.getAttribute(attr).split('-');
                        element.id = `${prefix}${index}-${imageID}`
                        element.setAttribute('name', `variant[${index}][radio]`);
                    } else {
                        element.setAttribute(attr, `${prefix}${index}`);

                        if (name) {
                            element.setAttribute('name', `${name === true ? `variant[${index}][${prefix.slice(0, -1)}]` : `${name}[${index}]`}`);
                        }
                    }
                });
            });

        });

        varianCount--;
    }

    /** document body focusout actions **/
    document.body.addEventListener('focusout', (event) => {
        let element = event.target;

        if (element.classList.contains('product-slug')) {
            let slug = generateSlug(element.value);
            validateSlug(element, slug);

        }

        if (element.classList.contains('variant-product-name') || element.classList.contains('variant-name')) {
            let variantID = element.id.split('-')[1];
            let slugInput = document.querySelector(`#slug-${variantID}`);

            let slug = generateSlug(slugInput.value);
            validateSlug(element, slug);
        }
    });
    /** Slug Validate **/
    function validateSlug(element, slug) {
        let response = checkSlug(slug);

        element.classList.remove('is-invalid');
        if (response != null) {
            element.classList.add('is-invalid');
        }
    }


    //END

    /** document body input actions, funcs START **/
    document.body.addEventListener("input", (event) => {
        let element = event.target;

        checkRequiredFieldForProductVariantTab();

        if (element.classList.contains("additional-price-input")) {
            calculateFinalPrice(element);
        }

        if (element.id === 'name') {
            changeNameForSlug(element);
        }

        if (element.classList.contains("variant-product-name")) {
            changeVariantProductNameForSlug(element);
        }

        if (element.classList.contains("variant-name")) {
            changeVariantNameForSlug(element);
        }
    });
    /** Son Fiyat Hesaplama Fonk **/
    function checkRequiredFieldForProductVariantTab() {
        let requiredFieldStatus = true;
        for (const [key, properties] of Object.entries(requiredFields)) {
            // objedeki ilk property ler htmldeki id lere esit oldugu icin asagida onlari cektik
            let keyElement = document.querySelector("#" + key);
            let keyElementValue = keyElement.value;

            if (properties.type === "input") {
                if (keyElementValue.length < 2) {
                    requiredFieldStatus = false;
                } else if (
                    properties.hasOwnProperty("data_type") &&
                    properties.data_type === "price" &&
                    (isNaN(keyElementValue) || keyElementValue < 0)
                ) {
                    requiredFieldStatus = false;
                }
            } else if (
                properties.type === "select" &&
                keyElementValue === "-1"
            ) {
                requiredFieldStatus = false;
            }
        }

        if (requiredFieldStatus) {
            productVariantTab.removeAttribute("disabled");
        } else {
            productVariantTab.setAttribute("disabled", "");
        }
    }
    function calculateFinalPrice(element) {
        let variantID = element.getAttribute("data-variant-id");
        let findFinalPriceElement = document.querySelector(`#final_price-${variantID}`);
        let priceValue = document.querySelector("#price").value;
        let finalPrice = Number(priceValue) - Number(element.value);
        findFinalPriceElement.value = finalPrice;
    }
    /** Name Inputu Degistigi Zaman Slug Fonk **/
    function changeNameForSlug(element) {
        let slugInputs = document.querySelectorAll('.product-slug');
        slugInputs.forEach((slugInput) => {
            let variantID = slugInput.id.split('-')[1];
            let findVariantProductName = document.querySelector(`#name-${variantID}`).value;
            let findVariantName = document.querySelector(`#variant_name-${variantID}`).value;
            let slug = `${element.value}-${findVariantName}`;

            if (findVariantProductName.trim() !== "") {
                slug = `${findVariantProductName}-${findVariantName}`;
            }
            slugInput.value = generateSlug(slug);
        });
    }
    /** Variant Product Name Inputu Degistigi Zaman Slug Fonk **/
    function changeVariantProductNameForSlug(element) {
        let variantID = element.id.split("-")[1];
        let findVariantName = document.querySelector(`#variant_name-${variantID}`).value;
        let slugInput = document.querySelector(`#slug-${variantID}`);
        let nameInput = document.querySelector("#name").value.trim();
        let slug = `${nameInput}-${findVariantName}`;

        if (element.value.trim() !== "") {
            slug = `${element.value.trim()}-${findVariantName}`;
        }

        slugInput.value = generateSlug(slug);
    }
    /** Variant Name Inputu Degistigi Zaman Slug Fonk **/
    function changeVariantNameForSlug(element) {
        let variantID = element.id.split("-")[1];
        let findVariantProductName = document.querySelector(`#name-${variantID}`).value;
        let slugInput = document.querySelector(`#slug-${variantID}`);
        let nameInput = document.querySelector("#name").value;
        let variantName = element.value.trim();
        let slug = `${nameInput}-${variantName}`;


        if (findVariantProductName.trim() !== "") {
            slug = `${findVariantProductName}-${variantName}`;
        }

        slugInput.value = generateSlug(slug);
    }
    /** document body input actions, funcs END **/

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
