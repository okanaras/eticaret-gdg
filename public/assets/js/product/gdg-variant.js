document.addEventListener("DOMContentLoaded", () => {
    let addVariant = document.querySelector("#addVariant");
    let variants = document.querySelector("#variants");
    let typeID = document.querySelector("#type_id");
    let productVariantTab = document.querySelector("#productVariantTab");

    let varianCount = 0;
    let varianSizeStockInfo = [];
    const sizeDivKey = "sizeDiv";
    const requiredFields = {
        name: {
            type: "input",
        },
        price: {
            type: "input",
            data_type: "price",
        },
        type_id: {
            type: "select",
        },
        brand_id: {
            type: "select",
        },
        category_id: {
            type: "select",
        },
    };

    let dressSize = ["XS", "S", "M", "L", "XL", "XXL", "3XL", "4XL", "5XL"];
    let shoesSize = shoesNumberGenerate();
    let standartSize = ["standart"];

    let sizes = {
        1: dressSize,
        2: shoesSize,
        3: standartSize,
    };

    // *Varyant ekle butonuna basildigindaki olaylar...
    addVariant.addEventListener("click", () => {
        let row = createDiv("row variant", "row-" + varianCount);
        let row2 = createDiv("row");

        let variantDeleteDiv = createDiv("col-md-12 mb-1");
        let variantDeleteAElement = createAElement(
            null,
            "btn-delete-variant btn btn-danger col-md-3",
            "javascript:void(0)",
            ["data-variant-id", varianCount],
            "Variant Kaldir"
        );

        let urunAdiID = "name-" + varianCount;
        let urunAdiNameAttr = "variant[" + varianCount + "][name]";
        let urunAdiDiv = createDiv("col-md-4 mb-4");
        let urunAdiLabel = createLabel("form-label", urunAdiID, "Urun Adi");
        let urunAdiInput = createInput(
            "form-control",
            urunAdiID,
            "off",
            "Urun Adi",
            urunAdiNameAttr
        );

        urunAdiDiv.appendChild(urunAdiLabel);
        urunAdiDiv.appendChild(urunAdiInput);

        let urunVariantNameID = "variant_name-" + varianCount;
        let urunVariantNameAttr = "variant[" + varianCount + "][variant_name]";
        let urunVariantNameDiv = createDiv("col-md-4 mb-4");
        let urunVariantNameLabel = createLabel(
            "form-label",
            urunVariantNameID,
            "Urun Varyant Adi"
        );
        let urunVariantNameInput = createInput(
            "form-control",
            urunAdiID,
            "off",
            "Urun Varyant Adi",
            urunVariantNameAttr
        );

        urunVariantNameDiv.appendChild(urunVariantNameLabel);
        urunVariantNameDiv.appendChild(urunVariantNameInput);

        let urunSlugID = "slug-" + varianCount;
        let urunSlugNameAttr = "variant[" + varianCount + "][slug]";
        let urunSlugDiv = createDiv("col-md-4 mb-4");
        let urunSlugLabel = createLabel("form-label", urunSlugID, "Slug");
        let urunSlugInput = createInput(
            "form-control",
            urunSlugID,
            "off",
            "Slug",
            urunSlugNameAttr
        );

        urunSlugDiv.appendChild(urunSlugLabel);
        urunSlugDiv.appendChild(urunSlugInput);

        let urunAdditionalPriceID = "additional_price-" + varianCount;
        let urunAdditionalPriceNameAttr =
            "variant[" + varianCount + "][additional_price]";
        let urunAdditionalPriceDiv = createDiv("col-md-6 mb-4");
        let urunAdditionalPriceLabel = createLabel(
            "form-label",
            urunAdditionalPriceID,
            "Fiyat"
        );
        let urunAdditionalPriceInput = createInput(
            "form-control",
            urunAdditionalPriceID,
            "off",
            "Fiyat",
            urunAdditionalPriceNameAttr
        );

        urunAdditionalPriceDiv.appendChild(urunAdditionalPriceLabel);
        urunAdditionalPriceDiv.appendChild(urunAdditionalPriceInput);

        let urunFinalPriceID = "final_price-" + varianCount;
        let urunFinalPriceNameAttr =
            "variant[" + varianCount + "][final_price]";
        let urunFinalPriceDiv = createDiv("col-md-6 mb-4");
        let urunFinalPriceLabel = createLabel(
            "form-label",
            urunFinalPriceID,
            "Son Fiyat"
        );
        let urunFinalPriceInput = createInput(
            "form-control",
            urunFinalPriceID,
            "off",
            "Son Fiyat",
            urunFinalPriceNameAttr
        );

        urunFinalPriceDiv.appendChild(urunFinalPriceLabel);
        urunFinalPriceDiv.appendChild(urunFinalPriceInput);

        let urunExtraDescriptionID = "extra_description-" + varianCount;
        let urunExtraDescriptionNameAttr =
            "variant[" + varianCount + "][extra_description]";
        let urunExtraDescriptionDiv = createDiv("col-md-12 mb-4");
        let urunExtraDescriptionLabel = createLabel(
            "form-label",
            urunExtraDescriptionID,
            "Ekstra Aciklama"
        );
        let urunExtraDescriptionInput = createInput(
            "form-control",
            urunExtraDescriptionID,
            "off",
            "Ekstra Aciklama",
            urunExtraDescriptionNameAttr
        );

        urunExtraDescriptionDiv.appendChild(urunExtraDescriptionLabel);
        urunExtraDescriptionDiv.appendChild(urunExtraDescriptionInput);

        let urunPublishDateID = "publish_date-" + varianCount;
        let urunPublishDateNameAttr =
            "variant[" + varianCount + "][publish_date]";
        let urunPublishDateDiv = createDiv("col-md-12 mb-4");
        let urunPublishDateDiv2 = createDiv(
            "input-group flatpickr flatpickr-date"
        );
        let urunPublishDateLabel = createLabel(
            "form-label",
            urunPublishDateID,
            "Yayimlanma Tarihi"
        );
        let urunPublishDateInput = createInput(
            "form-control",
            urunAdiID,
            "off",
            "Yayimlanma Tarihi",
            urunPublishDateNameAttr,
            ["data-input", ""]
        );
        let urunPublishDateSpan = createSpan(
            "input-group-text input-group-addon",
            "",
            ["data-toggle", ""]
        );
        let urunPublishDateIElemant = createIElement("", [
            "data-feather",
            "calendar",
        ]);

        urunPublishDateDiv.appendChild(urunPublishDateLabel);
        urunPublishDateSpan.appendChild(urunPublishDateIElemant);
        urunPublishDateDiv2.appendChild(urunPublishDateInput);
        urunPublishDateDiv2.appendChild(urunPublishDateSpan);
        urunPublishDateDiv.appendChild(urunPublishDateDiv2);

        let urunPStatusID = "p_status-" + varianCount;
        let urunPStatusNameAttr = "variant[" + varianCount + "][p_status]";
        let urunPStatusDiv = createDiv("col-md-6 mb-4");
        let urunPStatusLabel = createLabel(
            "form-check-label",
            urunPStatusID,
            "Aktif mi?"
        );
        let urunPStatusInput = createInput(
            "form-check-input me-2",
            urunPStatusID,
            "",
            "",
            urunPStatusNameAttr,
            null,
            (type = "checkbox")
        );

        urunPStatusDiv.appendChild(urunPStatusInput);
        urunPStatusDiv.appendChild(urunPStatusLabel);

        let urunAddSizeDiv = createDiv("");
        let urunAddSizeSpan = createSpan("ms-2", "Beden Ekle", null);
        let urunAddSizeIElement = createIElement("add-size", [
            "data-feather",
            "plus-circle",
        ]);
        let urunAddSizeAElement = createAElement(
            null,
            "btn-add-size",
            "javascript:void",
            ["data-variant-id", varianCount]
        );

        urunAddSizeAElement.appendChild(urunAddSizeIElement);
        urunAddSizeAElement.appendChild(urunAddSizeSpan);
        urunAddSizeDiv.appendChild(urunAddSizeAElement);

        let urunAddSizeGeneralDiv = createDiv(
            "col-md-12 d-flex flex-wrap p-0 mb-3",
            sizeDivKey + varianCount
        );

        let hr2 = document.createElement("hr");
        hr2.className = "my-2";

        variantDeleteDiv.appendChild(variantDeleteAElement);
        variantDeleteDiv.appendChild(hr2);

        row2.appendChild(variantDeleteDiv);

        row.appendChild(row2);

        row.appendChild(urunAdiDiv);
        row.appendChild(urunVariantNameDiv);
        row.appendChild(urunSlugDiv);
        row.appendChild(urunAdditionalPriceDiv);
        row.appendChild(urunFinalPriceDiv);
        row.appendChild(urunExtraDescriptionDiv);
        row.appendChild(urunPublishDateDiv);
        row.appendChild(urunPStatusDiv);
        row.appendChild(urunAddSizeDiv);
        row.appendChild(urunAddSizeGeneralDiv);

        let hr = document.createElement("hr");
        hr.className = "my-5";
        row.appendChild(hr);

        // variants.insertAdjacentElement("beforebegin", row2);
        // variants.insertAdjacentElement("beforebegin", hr2);
        variants.insertAdjacentElement("afterbegin", row);

        varianCount++;

        feather.replace();

        flatpickr(".flatpickr-date", {
            wrap: true,
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
    });

    // *Urun bilgileri tab'inda utun turu degistiginde varyant ekleme tabindaki size ve stock alanlarini temizleme
    typeID.addEventListener("change", () => {
        // NOT : her div'in id'si 'SizeKey+VariantCount' (SizeKey0, SizeKey1, SizeKey2...) oldugundan bulma islemi basarili gerceklesmekte
        for (let i = 0; i <= varianCount; i++) {
            let findDiv = document.querySelector("#" + sizeDivKey + i);

            if (findDiv) {
                findDiv.innerHTML = "";
            }
        }
    });

    // *Size Stock ekletme
    document.body.addEventListener("click", (event) => {
        let element = event.target;

        if (element.classList.contains("btn-delete-variant")) {
            let variantID = element.getAttribute("data-variant-id");
            let findDeleteVariantElement = document.querySelector(
                "#row-" + variantID
            );

            if (findDeleteVariantElement) {
                findDeleteVariantElement.remove();
                updateVariantIndexes();
            }
        }

        if (element.classList.contains("btn-add-size")) {
            btnAddSizeAction(element);
        }
        if (element.parentElement.classList.contains("btn-add-size")) {
            btnAddSizeAction(element.parentElement);
        }
    });

    document.body.addEventListener("input", (event) => {
        let element = event.target;
        let requiredFieldStatus = true;
        let elementID = element.id;

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
    });

    // * UpdateVariantIndexes
    function updateVariantIndexes() {
        let allVariants = document.querySelectorAll(".row.variant");
        allVariants = [...allVariants].reverse();
        console.log(allVariants);

        allVariants.forEach((variant, index) => {
            variant.id = "row-" + index;

            variant.querySelectorAll("[data-variant-id]").forEach((element) => {
                element.setAttribute("data-variant-id", index);
            });

            variant.querySelectorAll('[for^="name-"]').forEach((element) => {
                element.setAttribute("for", "name-" + index);
            });

            variant.querySelectorAll('[id^="name-"]').forEach((element) => {
                element.id = "name-" + index;
                element.setAttribute("name", "variant[" + index + "][name]");
            });

            variant
                .querySelectorAll('[for^="variant_name-"]')
                .forEach((element) => {
                    element.setAttribute("for", "variant_name-" + index);
                });
            variant
                .querySelectorAll('[id^="variant_name-"]')
                .forEach((element) => {
                    element.id = "variant_name-" + index;
                    element.setAttribute(
                        "name",
                        "variant[" + index + "][variant_name]"
                    );
                });

            variant.querySelectorAll('[for^="slug-"]').forEach((element) => {
                element.setAttribute("for", "slug-" + index);
            });
            variant.querySelectorAll('[id^="slug-"]').forEach((element) => {
                element.id = "slug-" + index;
                element.setAttribute("name", "variant[" + index + "][slug]");
            });

            variant
                .querySelectorAll('[for^="additional_price-"]')
                .forEach((element) => {
                    element.setAttribute("for", "additional_price-" + index);
                });
            variant
                .querySelectorAll('[id^="additional_price-"]')
                .forEach((element) => {
                    element.id = "additional_price-" + index;
                    element.setAttribute(
                        "name",
                        "variant[" + index + "][additional_price]"
                    );
                });

            variant
                .querySelectorAll('[for^="final_price-"]')
                .forEach((element) => {
                    element.setAttribute("for", "final_price-" + index);
                });
            variant
                .querySelectorAll('[id^="final_price-"]')
                .forEach((element) => {
                    element.id = "final_price-" + index;
                    element.setAttribute(
                        "name",
                        "variant[" + index + "][final_price]"
                    );
                });

            variant
                .querySelectorAll('[for^="extra_description-"]')
                .forEach((element) => {
                    element.setAttribute("for", "extra_description-" + index);
                });
            variant
                .querySelectorAll('[id^="extra_description-"]')
                .forEach((element) => {
                    element.id = "extra_description-" + index;
                    element.setAttribute(
                        "name",
                        "variant[" + index + "][extra_description]"
                    );
                });

            variant
                .querySelectorAll('[for^="publish_date-"]')
                .forEach((element) => {
                    element.setAttribute("for", "publish_date-" + index);
                });
            variant
                .querySelectorAll('[id^="publish_date-"]')
                .forEach((element) => {
                    element.id = "publish_date-" + index;
                    element.setAttribute(
                        "name",
                        "variant[" + index + "][publish_date]"
                    );
                });

            variant
                .querySelectorAll('[for^="p_status-"]')
                .forEach((element) => {
                    element.setAttribute("for", "p_status-" + index);
                });
            variant.querySelectorAll('[id^="p_status-"]').forEach((element) => {
                element.id = "p_status-" + index;
                element.setAttribute(
                    "name",
                    "variant[" + index + "][p_status]"
                );
            });

            variant.querySelectorAll('[for^="size-"]').forEach((element) => {
                element.setAttribute("for", "size-" + index);
            });
            variant.querySelectorAll('[id^="size-"]').forEach((element) => {
                element.id = "size-" + index;
                element.setAttribute("name", "variant[" + index + "][size]");
            });

            variant.querySelectorAll('[for^="stock-"]').forEach((element) => {
                element.setAttribute("for", "stock-" + index);
            });
            variant.querySelectorAll('[id^="stock-"]').forEach((element) => {
                element.id = "stock-" + index;
                element.setAttribute("name", "variant[" + index + "][stock]");
            });
        });
    }

    // *Size Stock Actions
    function btnAddSizeAction(element) {
        let dataVariantID = element.getAttribute("data-variant-id");
        let sizeStock = 0;
        if (varianSizeStockInfo.hasOwnProperty(dataVariantID)) {
            sizeStock = varianSizeStockInfo[dataVariantID]["size_stock"];
        }

        let productTypeID = typeID.value;
        let productSize = sizes[productTypeID];

        let options = ["Beden Secebilirsiniz"];
        options = options.concat(productSize);

        let divID = sizeDivKey + dataVariantID;
        let findDiv = document.querySelector("#" + divID);

        let urunSizeID = "size-" + dataVariantID + "-" + sizeStock;
        let urunSizeNameAttr =
            "variant[" + dataVariantID + "][size][" + sizeStock + "]";
        let urunSizeDiv = createDiv("col-md-5 mb-2 px-3");
        let urunSizeLabel = createLabel("form-label", urunSizeID, "Beden");
        let urunSizeSelect = createSelect(
            "form-control",
            urunSizeID,
            urunSizeNameAttr,
            null,
            options
        );

        urunSizeDiv.appendChild(urunSizeLabel);
        urunSizeDiv.appendChild(urunSizeSelect);

        let urunStockID = "stock-" + dataVariantID + "-" + sizeStock;
        let urunStockNameAttr =
            "variant[" + dataVariantID + "][stock][" + sizeStock + "]";
        let urunStockDiv = createDiv("col-md-5 mb-2 px-3");
        let urunStockLabel = createLabel(
            "form-label",
            urunStockID,
            "Stock Sayisi"
        );
        let urunStockInput = createInput(
            "form-control",
            urunStockID,
            "off",
            "Stock Sayisi",
            urunStockNameAttr
        );

        urunStockDiv.appendChild(urunStockLabel);
        urunStockDiv.appendChild(urunStockInput);

        let urunSizeStockDeleteDiv = createDiv("col-md-2 mb-2 px-3");
        let aElementID = "sizeStockDelete-" + dataVariantID + "-" + sizeStock;
        let urunSizeStockDeleteAElement = createAElement(
            aElementID,
            "btn btn-danger w-100 btn-size-stock-delete",
            "javascript:void",
            ["data-size-stock-id", dataVariantID + "-" + sizeStock],
            "Beden Sil"
        );
        let urunSizeStockDeleteAElementLabel = createLabel(
            "form-label",
            "",
            "",
            null
        );

        urunSizeStockDeleteDiv.appendChild(urunSizeStockDeleteAElementLabel);
        urunSizeStockDeleteDiv.appendChild(urunSizeStockDeleteAElement);

        findDiv.appendChild(urunSizeDiv);
        findDiv.appendChild(urunStockDiv);
        findDiv.appendChild(urunSizeStockDeleteDiv);

        if (varianSizeStockInfo.hasOwnProperty(dataVariantID)) {
            varianSizeStockInfo[dataVariantID]["size_stock"] = Number(
                varianSizeStockInfo[dataVariantID]["size_stock"] + 1
            );
        } else {
            varianSizeStockInfo[dataVariantID] = { size_stock: 1 };

            // varianSizeStockInfo.push({
            //     dataVariantID: { size_stock: 1 },
            // });
        }

        console.log(varianSizeStockInfo);
    }

    // *Shoes Size icin otomatik olarak numara olusturma fonksiyonu
    function shoesNumberGenerate() {
        let numbers = [];
        for (let i = 20; i < 51; i++) {
            numbers.push(i);
        }

        return numbers;
    }

    // *element olusturma fonksiyonlari start
    function createDiv(className, id = null) {
        let div = document.createElement("div");
        div.className = className;

        if (id != null) {
            div.id = id;
        }

        return div;
    }

    function createLabel(
        className,
        forAttr,
        textContent = null,
        innerHTML = null
    ) {
        let label = document.createElement("label");
        label.className = className;
        label.textContent = textContent;
        label.innerHTML = innerHTML;
        label.setAttribute("for", forAttr);

        return label;
    }

    function createSpan(className, textContent = null, setAttribute = null) {
        let span = document.createElement("span");
        span.className = className;
        if (textContent != null) {
            span.textContent = textContent;
        }
        if (setAttribute != null) {
            span.setAttribute(setAttribute[0], setAttribute[1]);
        }

        return span;
    }

    function createIElement(className, setAttribute = null) {
        let iElement = document.createElement("i");
        iElement.className = className;
        if (setAttribute != null) {
            iElement.setAttribute(setAttribute[0], setAttribute[1]);
        }

        return iElement;
    }

    function createInput(
        className,
        id,
        autocomplete,
        placeholder,
        nameAttr,
        setAttribute = null,
        type = "text"
    ) {
        let input = document.createElement("input");
        input.type = type;
        input.className = className;
        input.id = id;
        input.autocomplete = autocomplete;
        input.placeholder = placeholder;
        input.setAttribute("name", nameAttr);

        if (setAttribute != null) {
            input.setAttribute(setAttribute[0], setAttribute[1]);
        }

        return input;
    }

    function createSelect(
        className = null,
        id = null,
        nameAttr = null,
        setAttribute = null,
        options = null
    ) {
        let select = document.createElement("select");
        select.className = className;

        if (id != null) {
            select.id = id;
        }
        select.setAttribute("name", nameAttr);

        if (setAttribute != null) {
            select.setAttribute(setAttribute[0], setAttribute[1]);
        }

        if (options != null && Array.isArray(options)) {
            options.forEach((item, index, array) => {
                if (select.options.length < 1) {
                    select.options[select.options.length] = new Option(
                        item,
                        "-1"
                    );
                } else {
                    select.options[select.options.length] = new Option(item);
                }
            });
        }

        return select;
    }

    function createAElement(
        id = null,
        className = null,
        href = null,
        setAttribute = null,
        textContent = null
    ) {
        let aElement = document.createElement("a");
        aElement.textContent = textContent;

        if (className != null) {
            aElement.className = className;
        }

        if (id != null) {
            aElement.id = id;
        }

        if (setAttribute != null) {
            aElement.setAttribute(setAttribute[0], setAttribute[1]);
        }
        aElement.textContent = textContent;

        return aElement;
    }
    // !element olusturma fonksiyonlari end
});
