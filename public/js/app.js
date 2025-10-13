document.addEventListener('DOMContentLoaded', () => {
    let headerMenu = document.querySelector('.header__nav');
    const modal = document.querySelector('.modal');
    document.addEventListener('click', ({ target }) => {
        // burger
        if (target.classList.contains('burger')) {
            target.classList.toggle('_opened');
            headerMenu.classList.toggle('active');
        }
        if(target.classList.contains('modal')) {
          modal.classList.remove('active');
        }
        if(target.closest('.js-modal-req')) {
          modal.classList.add('active');
        }
    });

    const info = document.querySelector('.product__desc');

    if (info) {
      const tabs = info.querySelectorAll('.product__info-link');
      const blocks = info.querySelectorAll('.product__content-block');
      tabs.forEach((tab, index) => {
        tab.addEventListener('click', () => {
          // Убираем active со всех табов и контента
          tabs.forEach(t => t.classList.remove('active'));
          blocks.forEach(b => b.classList.remove('active'));

          // Добавляем active к выбранным
          tab.classList.add('active');
          blocks[index].classList.add('active');
        });
      });
    };

    const catalogHeader = document.querySelector('.menu');

    if(catalogHeader) {
      const categoryLinks = document.querySelectorAll(".menu__list-item");
      const items = document.querySelectorAll(".menu__item");
      const blockItems = document.querySelector('.menu__block');
      const blockBack = document.querySelector('.menu__back');
      const headerBtn = document.querySelector('.header__catalog');
      categoryLinks.forEach(link => {
        link.addEventListener("click", () => {
          // убираем актив у всех
          categoryLinks.forEach(el => el.classList.remove("active"));
          link.classList.add("active");

          const categoryId = link.dataset.id;
          blockItems.classList.add('active');

          if (link.classList.contains("reset")) {
          // показать все
          items.forEach(item => {
            item.style.display = "grid";
          });
          } else {
          // скрыть все и показать только выбранные
          items.forEach(item => {
            if (item.dataset.id === categoryId) {
              item.style.display = "grid";
            } else {
              item.style.display = "none";
            }
          });
          }
        });
      });
      blockBack.addEventListener('click', () => {
        blockItems.classList.remove('active');
        categoryLinks.forEach(item => item.classList.remove('active'));
      });
      headerBtn.addEventListener('click', () => {
        catalogHeader.classList.toggle('active');
      });
      catalogHeader.addEventListener('click', ({target}) => {
        if(target.classList.contains('menu')) {
          catalogHeader.classList.remove('active');
        }
      });
    }

    //Корзина магазина
    // product storage
    //Корзина магазина
    function requestCart() {

        const cartDOMElement = document.querySelector('.js-cart')
        const cartItemsCounterDOMElement = document.querySelectorAll('.js-cart-total-count-items')
        const cartTotalPriceDOMElement = document.querySelectorAll('.js-cart-total-summa')
            // const cartTotalSummaDOMElement = document.querySelector('.js-cart-total-price')
            // const totalSumma = document.querySelector('.js-all-summa')
        const basketForm = document.querySelector('.busket-form');

        const cart = JSON.parse(localStorage.getItem('ordaKlimatCart')) || {};


        const clearBusket = () => {
            let busketTable = document.querySelector('.basket__wrapper');
            let busketForm = document.querySelector('.basket__form');
            let empty = document.querySelector('.empty');
            if (Object.keys(cart).length == 0) {
                busketTable.classList.add('disabled');
                busketForm.classList.add('disabled');
                empty.classList.add('active');
            }
        }
        const busketpage = document.querySelector('.basket')
        if (busketpage) {
            clearBusket();
        }
        //отображаем добавленный товар в корзине
        const renderCartItem = ({ id, articul, name, totalprice, price, src, quantity, href, montage, montageBox }) => {
          const cartItemDOMElement = document.createElement('tr');
          if (articul === null) {
              articul = '';
          }
          totalprice = totalprice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
          price = price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
          let montageFlag = montage == '1' ? 'checked' : '';
          const cartItemTemplate = `
            <tr>
              <td>
                <div class="basket__image">
                  <img src="${src}" alt="">
                </div>
              </td>
              <td>
                <div class="basket__desc">
                  <div class="basket__name">${name}</div>
                  <div class="basket__art">Арт: ${articul}</div>
                </div>
              </td>
              <td>
                <div class="basket__price">${price} Т</div>
              </td>
              <td>
                <div class="basket__counter">
                  <button type="button" class="js-minus">
                    <svg width="6" height="2" viewBox="0 0 6 2" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M0 1.33333V0H6V1.33333H0Z" fill="#FF6600"/>
                    </svg>
                  </button>
                  <span class="js-cart-item-quantity">${quantity}</span>
                  <button type="button" class="js-plus">
                    <svg width="7" height="8" viewBox="0 0 7 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M2.80023 7.25336V4.72003H0.240234V3.22669H2.80023V0.693359H4.32023V3.22669H6.88024V4.72003H4.32023V7.25336H2.80023Z" fill="#FF6600"/>
                    </svg>
                  </button>
                </div>
              </td>
              <td>
                <div class="basket__montage">
                  <input type="checkbox" class="js-montage" ${montageFlag} name="montage">
                </div>
              </td>
              <td>
                <div class="basket__totalprice js-cart-item-totalprice"><span>${totalprice}</span> Т</div>
              </td>
            </tr>
          `;
          cartItemDOMElement.innerHTML = cartItemTemplate;
          cartItemDOMElement.setAttribute('data-id', id);
          cartItemDOMElement.classList.add('busket__item');
          cartDOMElement.appendChild(cartItemDOMElement);
          totalBusket();
          updateCart();
        }

        //сохраняем товар в LocalStorage
        const saveCart = () => {
            localStorage.setItem('ordaKlimatCart', JSON.stringify(cart));
        }


        // подсчитываение колличества и суммы товара
        const totalBusket = () => {
            let totalcount = 0;
            const ids = Object.keys(cart);
            for (let i = 0; i < ids.length; i++) {
                const id = ids[i]
                totalcount += +(cart[id].quantity);
            }

            let totalAll = 0;
            const price = document.querySelectorAll('.js-cart-item-totalprice span');
            const allTotalPrice = document.querySelector('.js-all-total-price');
            const delivPoint = document.querySelector('.js-deliv-point');
            for (let i = 0; i < price.length; i++) {
                totalAll = totalAll + parseInt(price[i].innerHTML.replaceAll(' ', ''));
            }

            // cartTotalPriceDOMElement.textContent = totalAll + ' тг';
            // cartTotalSummaDOMElement.textContent = total + ' тг';
            cartItemsCounterDOMElement.forEach(elem => {
                    elem.textContent = totalcount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                })
                // Итоговая сумма всех товаров
            cartTotalPriceDOMElement.forEach(elem => {
                elem.textContent = totalAll.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + ' Т';
                // elem.textContent = totalAll.toString() + ' тг';
                // console.log(totalAll);
            })

            // console.log(ids.length);
            if (ids.length == 0) {
                cartTotalPriceDOMElement.forEach(elem => {
                        elem.textContent = totalAll + ' тг'
                        // console.log(totalAll)
                    })
                    // cartTotalSummaDOMElement.textContent = 0;
                $('.js-cart-total-summa').attr('data-summ', 0);
            }
            if(delivPoint.checked) {
              allTotalPrice.textContent = (totalAll + +(delivPoint.getAttribute('data-price'))).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + ' Т';
              $('.js-cart-total-summa').attr('data-summ', (totalAll + +(delivPoint.getAttribute('data-price'))).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + ' Т');
            } else {
              allTotalPrice.textContent = totalAll.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + ' Т';
              $('.js-cart-total-summa').attr('data-summ', totalAll.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ") + ' Т');
            }
            updateCart();
            // checkSelectDeliv();
        }

        function totalBusketHeader() {
            let busket = document.querySelector('.header__basket span')
            let totalcount = 0;
            const ids = Object.keys(cart);
            for (let i = 0; i < ids.length; i++) {
                const id = ids[i]
                totalcount += +(cart[id].quantity);
            }
            // console.log(totalcount)
            busket.innerHTML = totalcount;
            if (totalcount > 1) {
                busket.classList.add('active')
            } else {
                busket.classList.remove('active')
            }
        }
        totalBusketHeader();

        // Проверка выбранного селекта для доставки товара
        let radiosDelivery = document.querySelectorAll('.js-radio-delivery');
        let templateHtmlDelivery = document.querySelector('.deliv');
        if (radiosDelivery.length) {
            radiosDelivery.forEach(item => item.addEventListener('input', setDeliveryMark));
        }

        function setDeliveryMark() {
          const priceDeliv = document.querySelector('.js-deliv-point');
          if(this.value == 0) {
            templateHtmlDelivery.innerHTML = this.nextElementSibling.textContent;
          } else {
            templateHtmlDelivery.innerHTML = priceDeliv.getAttribute('data-price') + ' Т';
          }
          totalBusket();
          requestTable();
        }

        // Проверка лица клиента
        let radiosFace = document.querySelectorAll('.js-radio-face');
        if(radiosFace.length) {
            let individButton = document.querySelector('.busket-form__submit.default');
            let entityButton = document.querySelector('.busket-form__submit.entity');
            let entityFields = document.querySelectorAll('.busket-form__col.entity');
            let csrfToken = document.querySelector('meta[name="csrfToken"]').getAttribute('content');
            let requiredFields = document.querySelectorAll('.required-field');
            radiosFace.forEach(item => item.addEventListener('input', setFaceButton));
            const setEntityFields = (array, styleDisplay, boolAttribute) => {
                array.forEach(item => {
                    item.style.display = `${styleDisplay}`;
                    item.querySelector('input').disabled = boolAttribute;
                });
            }
            function setFaceButton() {
                if(this.getAttribute('id') === 'entity' && this.checked) {
                    individButton.style.display = 'none';
                    entityButton.style.display = 'block';
                    setEntityFields(entityFields, 'block', false);
                } else {
                    individButton.style.display = 'block';
                    entityButton.style.display = 'none';
                    setEntityFields(entityFields, 'none', true);
                }
            }

            let getUrl = 'http://realklimat.113.kz/pdf';

            const postData = async(url, bodyJson) => {
                showPreloader();
                const response = await fetch(url, {
                    method: 'POST',
                    body: JSON.stringify(bodyJson),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': csrfToken,
                    },
                });
                return response.text();
            }
            let pdfBlock = document.querySelector('.pdf-block');
            const convertHtmlToPdf = (html, block) => {
                block.style.display = 'block';
                block.innerHTML = html;
                let opt = {
                    margin: 4,
                    filename: 'myfile.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: {
                        windowWidht: 730,
                        height: block.offsetHeight,
                        scale: 1.5,
                        scrollY: 0,
                        scrollX: 0,
                    },
                    jsPDF: {
                        orientation: 'portrait',
                        format: [200, 271],
                    }
                };
                return html2pdf().from(html).set(opt).save();
            }
            const validRequiredFields = (requiredFields) => {
                return Array.from(requiredFields).every(field => field.value != '');
            }
            entityButton.addEventListener('click', () => {
                if(validRequiredFields(requiredFields)) {
                    postData(getUrl, cart).then(async (data) => {
                        await convertHtmlToPdf(data, pdfBlock);
                        pdfBlock.style.display = 'none';
                        clearLocalStorage();
                        basketForm.submit();
                    });
                } else {
                    alert('Заполните нужные поля!');
                }
            });

        }

        //Удаление из корзины
        const deleteCartItem = (id) => {
            const cartItemDOMElement = cartDOMElement.querySelector(`[data-id="${id}"]`);
            // const tableElement = tableDOMElement.querySelector(`[data-product-articul="${articul}"]`);
            cartDOMElement.removeChild(cartItemDOMElement);
            // tableDOMElement.removeChild(tableElement);
            delete cart[id];
            updateCart();
            totalBusket();
        }
            //Обновление количества товара и итоговой суммы
        const updateQuantityTotalPrice = (id, quantity) => {
            const cartItemDOMElement = cartDOMElement.querySelector(`[data-id="${id}"`);
            const cartItemQuantityDOMElement = cartItemDOMElement.querySelector('.js-cart-item-quantity');
            const cartItemPriceDOMElement = cartItemDOMElement.querySelector('.js-cart-item-totalprice span');
            // const ids = Object.keys(cart);

            let cartSelectValue = parseInt(cart[id].lining != undefined ? Object.values(cart[id].lining) : 0);
            let cartInputValue = parseInt(cart[id].meters != undefined ? +(cart[id].meters.meters * cart[id].meters.priceMeters) : 0);
            // console.log(cartInputValue);
            // console.log(cartSelectValue, cartInputValue);

            cart[id].quantity = quantity;
            cartItemQuantityDOMElement.textContent = quantity;
            cart[id].totalprice = cart[id].quantity * cart[id].price + cartSelectValue + cartInputValue;
            cartItemPriceDOMElement.textContent = cart[id].totalprice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
            // console.log(cart[id].totalprice)
            // tableQuantity.textContent = quantity;
            // cart[id].totalprice = cart[id].quantity * cart[id].price + cartSelectValue;
            // tableTotal.textContent = cart[articul].totalprice;
            updateCart();
        }
            //Увеличение количества товара и итоговой суммы
        const increaseQuantity = (id) => {
            const newQuantity = +(cart[id].quantity) + 1;
            updateQuantityTotalPrice(id, newQuantity);
        }
            //Уменьшение количества товара и итоговой суммы
        const decreaseQuantity = (id) => {
            const newQuantity = +(cart[id].quantity) - 1;
            if (newQuantity >= 1) {
                updateQuantityTotalPrice(id, newQuantity);
            }
        }

        //Добавление в корзину
        const addCartItem = (data) => {
            // console.log(data)
            const { id } = data;
            cart[id] = data;
            updateCart();
            if (cartDOMElement) {
                renderCartItem(data);
            }
        }

        //Обновляем данные в LocalStorage
        const updateCart = () => {
            saveCart();
        }

        //Получаем данные для объекта
        const getProductData = (productDOMElement) => {
            const button = document.querySelector('.buy__product')
            const id = productDOMElement.getAttribute('data-id')
            const name = productDOMElement.getAttribute('data-product-name');
            // const desc = productDOMElement.getAttribute('data-product-desc');
            const articul = productDOMElement.getAttribute('data-product-articul');
            // const size = productDOMElement.getAttribute('data-product-size');
            // const color = productDOMElement.getAttribute('data-product-color');
            // const oldPrice = productDOMElement.getAttribute('data-product-old-price');
            const price = productDOMElement.getAttribute('data-product-price');
            const src = productDOMElement.getAttribute('data-product-src');
            const quantity = productDOMElement.getAttribute('data-product-quantity');
            let href = productDOMElement.getAttribute('data-product-href');
            const montage = productDOMElement.getAttribute('data-montage');
            const montageBox = JSON.stringify(productDOMElement?.querySelector('.busket__item-selects')?.innerHTML);
            if (button) {
                href = window.location.href;
            }
            // const quantity = 1;
            const totalprice = quantity * +(price);
            return { id, name, articul, price, totalprice, src, quantity, href, montage, montageBox /*oldPrice*/ };
        }

        const renderCart = () => {
            const ids = Object.keys(cart);
            // console.log(ids);
            ids.forEach((id) => renderCartItem(cart[id]));
        };

        const disabledButton = () => {
            // console.log(cart)
            const test = document.querySelectorAll('.js-product')
            const buttonCounter = document.querySelector('.product__info-counter');
            for (let i = 0; i < test.length; i++) {
                const attr = (test[i].getAttribute('data-id'))
                const parent = test[i].querySelector('.js-buy')
                    // console.log(parent)
                    // console.log(cart.hasOwnProperty(attr))
                if (cart.hasOwnProperty(attr)) {
                    parent.classList.add('disable')
                        // parent.innerHTML = 'Товар в корзине';
                    parent.disabled = true;
                    if (buttonCounter) {
                        buttonCounter.style.pointerEvents = 'none';
                        buttonCounter.style.opacity = '0.5';
                    }
                }

            }

        }
        disabledButton();

        //Инициализация
        const cartInit = () => {
            if (cartDOMElement) {
                renderCart();
            }

            document.querySelector('body').addEventListener('click', (e) => {
                const target = e.target;
                //В корзину
                if (target.classList.contains('js-buy')) {
                    e.preventDefault();
                    const productDOMElement = target.closest('.js-product');
                    const data = getProductData(productDOMElement);
                    addCartItem(data);
                    disabledButton();
                    totalBusketHeader();
                    // showPopup();
                }

                //Удалить из корзины
                if (target.classList.contains('remove')) {
                    e.preventDefault();
                    const cartItemDOMElement = target.closest('.busket__item');
                    const productId = cartItemDOMElement.getAttribute('data-id');
                    deleteCartItem(productId);
                    clearBusket();
                    requestTable();
                    totalBusketHeader();
                }

                //Увеличить
                if (target.classList.contains('js-plus')) {
                    e.preventDefault();
                    const cartItemDOMElement = target.closest('.busket__item');
                    const productId = cartItemDOMElement.getAttribute('data-id');
                    increaseQuantity(productId);
                    totalBusket();
                    requestTable();
                    totalBusketHeader();
                }

                //Уменьшить
                if (target.classList.contains('js-minus')) {
                    e.preventDefault();
                    const cartItemDOMElement = target.closest('.busket__item');
                    const productId = cartItemDOMElement.getAttribute('data-id');
                    decreaseQuantity(productId);
                    totalBusket();
                    requestTable();
                    totalBusketHeader();
                }

                if (target.classList.contains('decrease')) {
                    let targetProduct = target.closest('.js-product');
                    let newProductQuantity = +(targetProduct.getAttribute('data-product-quantity')) - 1;
                    if (newProductQuantity < 1) {
                        newProductQuantity = 1;
                    }
                    targetProduct.setAttribute('data-product-quantity', newProductQuantity);
                    let targetCountTemplate = targetProduct.querySelector('.product__counter span');
                    // let targetPriceTemplate = targetProduct.querySelector('.product__info-price span');
                    targetCountTemplate.textContent = newProductQuantity;
                    // targetPriceTemplate.textContent = (newProductQuantity * targetProduct.getAttribute('data-product-price')).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                }

                if (target.classList.contains('increase')) {
                    let targetProduct = target.closest('.js-product');
                    let newProductQuantity = +(targetProduct.getAttribute('data-product-quantity')) + 1;
                    targetProduct.setAttribute('data-product-quantity', newProductQuantity);
                    let targetCountTemplate = targetProduct.querySelector('.product__counter span');
                    // let targetPriceTemplate = targetProduct.querySelector('.product__info-price span');
                    targetCountTemplate.textContent = newProductQuantity;
                    // targetPriceTemplate.textContent = (newProductQuantity * targetProduct.getAttribute('data-product-price')).toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                }

            });
            const resetCartItemMontage = () => {
                for(item in cart) {
                    delete cart[item].lining;
                    delete cart[item].meters;
                    updateQuantityTotalPrice(cart[item].id, cart[item].quantity);
                }
                totalBusket();
            }
            if(busketpage) {
                resetCartItemMontage();
            }
            document.querySelector('body').addEventListener('input', e => {
              // show montage selects
              const target = e.target;
              if (target.classList.contains('js-montage')) {
                    let parentItem = target.closest('.busket__item');
                    let id = parentItem.getAttribute('data-id');
                    if (target.checked) {
                        cart[id].montage = '1';
                        updateCart();
                        requestTable();
                    } else {
                        cart[id].montage = '0';
                        updateCart();
                        requestTable();
                    }
              }
            });
        }
        cartInit();
    }
    requestCart();
    const textarea = document.querySelector('.textarea-table');
    const textareaJSON = document.querySelector('.textarea-table-json');

    function requestTable() {
        const cart = JSON.parse(localStorage.getItem('ordaKlimatCart')) || {};
        let totalSumma = document.querySelector('.js-cart-total-summa').getAttribute('data-summ');
        const ids = Object.keys(cart);
        let textareaPopupOrder = document.querySelector('#textarea-order-popup');
        if (textareaPopupOrder) {
            textareaPopupOrder.innerHTML = localStorage.getItem('ordaKlimatCart');
        }

        let tableItem = '';
        let tableTotalPrice = '';
        let tableTemplate = '';
        const renderTable = () => {
            let counter = 0;
            for (let i in ids) {
                const keys = ids[i];
                const id = cart[keys].id;
                const img = cart[keys].src;
                const name = cart[keys].name;
                const articul = cart[keys].articul;
                const quantity = cart[keys].quantity;
                const price = cart[keys].price;
                const montage = cart[keys].montage == '1' ? 'Да' : 'Нет';
                // const oldPrice = cart[keys].oldPrice > 0 ? cart[keys].oldPrice + ' тг' : '';
                // <td style="text-decoration: line-through;">${oldPrice}</td>
                // <th>Старая цена</th>
                const totalprice = cart[keys].totalprice;
                tableItem += `
                        <tr class="order-row" style="page-break-after: always;">
                        <td>${id}</td>
                        <td><${img} src="../img"></td>
                        <td>${name}</td>
                        <td>${articul}</td>
                        <td>${montage}</td>
                        <td>${quantity}</td>
                        <td>${price} тг</td>
                        <td>${totalprice} тг</td>
                        </tr>
                `;
                tableTotalPrice = `
                    <tr>
                        <td colspan="8">Итоговая сумма всех товаров - ${totalSumma} тенге </td>
                    </tr>
                `;
                counter++;
                if (counter % 9 == 0) {
                    tableTemplate += `
                    <table border="1" cellspacing="0" cellpadding="10">
                        <thead>
                            <tr>
                            <th>ID Товара</th>
                            <th>Изображение</th>
                            <th>Название</th>
                            <th>Артикул</th>
                            <th>Монтаж</th>
                            <th>Количество</th>
                            <th>Цена за шт.</th>
                            <th>Итоговая сумма</th>
                            </tr>
                        </thead>
                        <tbody>${tableItem}</tbody>
                    </table>
                    `;
                    tableItem = '';
                }
            }
            tableTemplate += `
                <table border="1" cellspacing="0" cellpadding="10">
                    <thead>
                        <tr>
                        <th>ID Товара</th>
                        <th>Изображение</th>
                        <th>Название</th>
                        <th>Артикул</th>
                        <th>Монтаж</th>
                        <th>Количество</th>
                        <th>Цена за шт.</th>
                        <th>Итоговая сумма</th>
                        </tr>
                    </thead>
                    <tbody>${tableItem} ${tableTotalPrice}</tbody>
                </table>
            `;
            // console.log(tableTemplate.length);
            textarea.innerHTML = tableTemplate;
        }
        renderTable();
        let filterCart = cart;
        for(let key in filterCart) {
            delete filterCart[key].montageBox;
        }
        // console.log(filterCart);
        // textareaJSON.innerHTML = JSON.stringify(cart);
    }

    if (textarea) {
        requestTable();
    }
});

window.onload = () => {
  // $.fn.setCursorPosition = function(pos) {
  //     if ($(this).get(0).setSelectionRange) {
  //         $(this).get(0).setSelectionRange(pos, pos)
  //     } else if ($(this).get(0).createTextRange) {
  //         var range = $(this).get(0).createTextRange()
  //         range.collapse(true)
  //         range.moveEnd('character', pos)
  //         range.moveStart('character', pos)
  //         range.select()
  //     }
  // }
  // $('input[type="tel"]').on('click', function() {
  //     $(this).setCursorPosition(3)
  // }).mask('+7 (999) 999 99 99')
  // $('.way').waypoint({
  //     handler: function() {
  //         $(this.element).addClass("way--active");
  //     },
  //     offset: '88%'
  // });

  const heroImagesSwiper = new Swiper('.hero__images', {
      slidesPerView: 1,
      effect: 'fade',
      fadeEffect: { crossFade: true },
      // loop: true,
      allowTouchMove: false,
      // speed: 2000,
      // autoplay: {
      //   delay: 5000,
      // },
  });

  const heroInfoSwiper = new Swiper('.hero__text', {
    slidesPerView: 1,
    effect: 'fade',
    fadeEffect: { crossFade: true },
    autoHeight: true,
    autoplay: { delay: 3000, disableOnInteraction: false },
    allowTouchMove: false,
    thumbs: {
      swiper: heroImagesSwiper, // ← вот эта твоя строка
    },
    pagination: { el: ".hero__pagination" },
    navigation: {
      prevEl: '.hero__arrow.prev',
      nextEl: '.hero__arrow.next'
    },
  });

  const partnerSlider = new Swiper('.partner__slider', {
    slidesPerView: 4,
    spaceBetween: 30,
    loop: true,
    navigation: {
        prelEl: '.partner__slider-arr.prev',
        nextEl: '.partner__slider-arr.next',
    },
    breakpoints: {
        0: {
            slidesPerView: 2,
            spaceBetween: 20,
        },
        768: {
            slidesPerView: 4,
            spaceBetween: 30,
        }
    }
  });

  const customImagesSwiper = new Swiper('.custom__images', {
    slidesPerView: 1,
    effect: 'fade',
    fadeEffect: { crossFade: true },
    // loop: true,
    allowTouchMove: false,
    // speed: 2000,
    // autoplay: {
    //   delay: 5000,
    // },
  });

  const customInfoSwiper = new Swiper('.custom__text', {
    slidesPerView: 1,
    effect: 'fade',
    fadeEffect: { crossFade: true },
    autoHeight: true,
    autoplay: { delay: 3000, disableOnInteraction: false },
    allowTouchMove: false,
    thumbs: {
      swiper: customImagesSwiper, // ← вот эта твоя строка
    },
    pagination: { el: ".custom__pagination" },
  });

  const productSmallSlider = new Swiper('.product__thumbs',{
    slidesPerView: 4,
    spaceBetween: 20,
  });
  const productBigSlider = new Swiper('.product__image', {
    slidesPerView: 1,
    effect: 'fade',
    fadeEffect: { crossFade: true },
    thumbs: {
        swiper: productSmallSlider,
    },
  });

  const recomendSlider = new Swiper('.recomend__slider', {
    slidesPerView: 4,
    spaceBetween: 30,
    navigation: {
        prevEl: '.recomend__arrow.prev',
        nextEl: '.recomend__arrow.next',
    },
    breakpoints: {
      0: {
        slidesPerView: 1,
        spaceBetween: 20,
      },
      480: {
        slidesPerView: 2,
        spaceBetween: 30,
      },
      768: {
        slidesPerView: 3,
        spaceBetween: 30,
      },
      1240: {
        slidesPerView: 4,
        spaceBetween: 30,
      }
    }
  });

  const docSlider = new Swiper('.doc__slider', {
    slidesPerView: 4,
    spaceBetween: 30,
    loop: true,
    navigation: {
        prevEl: '.doc__arrow.prev',
        nextEl: '.doc__arrow.next',
    },
    breakpoints: {
        0: {
            slidesPerView: 2,
            spaceBetween: 20,
        },
        768: {
            slidesPerView: 4,
            spaceBetween: 30,
        }
    }
  });

  const clientSlider = new Swiper('.client__slider', {
      spaceBetween: 30,
      centeredSlides: true,
      speed: 6000,
      autoplay: {
        delay: 1,
      },
      loop: true,
      slidesPerView:'auto',
      allowTouchMove: false,
      disableOnInteraction: true
    });
};

// loader func
function submitForm() {
    // $('#form_loader').show();
    const busket = document.querySelector('.basket')
    if (busket) {
        clearLocalStorage();
    }
}

function clearLocalStorage() {
    localStorage.removeItem('ordaKlimatCart');
}
//Alert form
let alertt = document.querySelector(".alert--fixed");
let alertClose = document.querySelectorAll(".alert--close")
for (let item of alertClose) {
    item.addEventListener('click', function(event) {
        alertt.classList.remove("alert--active")
        alertt.classList.remove("alert--warning")
        alertt.classList.remove("alert--error")
    })
}

// --- lightweight success + close modal (без изменения HTML формы) ---
(function () {
    const modal = document.querySelector('.modal.js-modal');
    if (!modal) return;

    const form = modal.querySelector('form');
    if (!form) return;

    const submitBtn = form.querySelector('button[type="submit"]');
    const block = form.querySelector('.modal__block') || form;
    // чтобы перекрытие легло поверх блока формы
    if (getComputedStyle(block).position === 'static') {
        block.style.position = 'relative';
    }

    // создаём крошечный оверлей с галочкой (✓) — без тяжёлых анимаций
    const toast = document.createElement('div');
    toast.className = 'mini-check mini-check--hidden';
    toast.innerHTML = '<span class="mini-check__icon">✓</span><span class="mini-check__text">Отправлено</span>';
    block.appendChild(toast);

    function showCheckAndClose() {
        toast.classList.remove('mini-check--hidden');
        toast.classList.add('mini-check--show');

        setTimeout(() => {
            // скрыть оверлей
            toast.classList.add('mini-check--hidden');
            toast.classList.remove('mini-check--show');

            // закрыть модалку (подстрой под свою логику открытия/закрытия)
            modal.classList.remove('active');   // если у тебя показ через класс .active
            modal.style.display = 'none';       // жёсткое скрытие на всякий случай
            document.documentElement.classList.remove('no-scroll'); // если блокируешь скролл
        }, 1200);
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const action = form.getAttribute('action') || window.location.href;
        const data = new FormData(form);
        const csrf = data.get('_token') || '';

        if (submitBtn) submitBtn.disabled = true;

        try {
            const res = await fetch(action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json'
                },
                body: data
            });

            if (!res.ok) throw new Error('Server error');

            form.reset();
            showCheckAndClose();
        } catch (err) {
            console.error(err);
            alert('Не удалось отправить. Попробуйте ещё раз.');
        } finally {
            if (submitBtn) submitBtn.disabled = false;
        }
    });
})();
