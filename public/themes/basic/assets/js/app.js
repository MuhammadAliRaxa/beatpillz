(function($) {
    "use strict";

    window.addEventListener('alert', event => {
        toastr[event.detail.type](event.detail.message);
    });

    window.addEventListener('show-modal', event => {
        let modal = document.getElementById(event.detail.id);
        if (modal) {
            let modalInstance = bootstrap.Modal.getInstance(modal);
            if (!modalInstance) {
                modalInstance = new bootstrap.Modal(modal);
            }
            modalInstance.show();
        }
    });

    window.addEventListener('close-modal', event => {
        let modal = document.getElementById(event.detail.id);
        if (modal) {
            let modalInstance = bootstrap.Modal.getInstance(modal);
            if (!modalInstance) {
                modalInstance = new bootstrap.Modal(modal);
            }
            modalInstance.hide();
        }
    });

    document.querySelectorAll("[data-year]").forEach(function(el) {
        el.textContent = new Date().getFullYear();
    });

    if ($("[data-aos]").length > 0) {
        function aosFunc() {
            AOS.init({
                once: true,
            });
        }

        window.addEventListener("load", aosFunc);
        window.addEventListener("scroll", aosFunc);
    }

    var dropdown = document.querySelectorAll("[data-dropdown]");
    if (dropdown) {
        dropdown.forEach(function(el) {
            let dropdownMenu = el.querySelector(".drop-down-menu");

            function dropdownOP() {
                if (
                    el.getBoundingClientRect().top + dropdownMenu.offsetHeight >
                    window.innerHeight - 60 &&
                    el.getAttribute("data-dropdown-position") !== "top"
                ) {
                    dropdownMenu.style.top = "auto";
                    dropdownMenu.style.bottom = "40px";
                } else {
                    dropdownMenu.style.top = "40px";
                    dropdownMenu.style.bottom = "auto";
                }
            }
            window.addEventListener("click", function(e) {
                if (el.contains(e.target)) {
                    el.classList.toggle("active");
                    setTimeout(function() {
                        el.classList.toggle("animated");
                    }, 0);
                } else {
                    el.classList.remove("active");
                    el.classList.remove("animated");
                }
                dropdownOP();
            });
            window.addEventListener("resize", dropdownOP);
            window.addEventListener("scroll", dropdownOP);
        });
    }

    var toggle = document.querySelectorAll('[data-toggle]');
    if (toggle) {
        toggle.forEach(function(el, id) {
            el.querySelector(".toggle-title").addEventListener("click", () => {
                for (var i = 0; i < toggle.length; i++) {
                    if (i !== id) {
                        toggle[i].classList.remove("active");
                        toggle[i].classList.remove("animated");
                    }
                }
                if (el.classList.contains("active")) {
                    el.classList.remove("active");
                    el.classList.remove("animated");
                } else {
                    el.classList.add("active");
                    setTimeout(function() {
                        el.classList.add("animated");
                    }, 0);
                }
            });
        });
    }


    function setCookie(cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        let expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        let name = cname + "=";
        let decodedCookie = decodeURIComponent(document.cookie);
        let ca = decodedCookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    const announcement = document.querySelector(".announcement"),
        announcementClose = document.querySelector(".announcement-close");
    if (announcement) {
        announcementClose.addEventListener("click", () => {
            announcement.remove();
            setCookie("announce_close", true, 1);
        });

        if (getCookie("announce_close") === "true") {
            announcement.remove();
        }
    }

    const navbar = document.querySelectorAll(".nav-bar");
    if (navbar) {
        navbar.forEach((el) => {
            let navbarMenu = el.querySelector(".nav-bar-menu"),
                navbarMenuBtn = el.querySelector(".nav-bar-menu-btn"),
                navbarMenuClose = el.querySelector(".nav-bar-menu-close"),
                navbarMenuOverlay = navbarMenu.querySelector(".overlay");

            if (navbarMenu && navbarMenuBtn) {
                navbarMenuBtn.onclick = () => {
                    navbarMenu.classList.add("show");
                    document.body.classList.add("overflow-hidden");
                };
                navbarMenuClose.onclick = navbarMenuOverlay.onclick = () => {
                    navbarMenu.classList.remove("show");
                    document.body.classList.remove("overflow-hidden");
                };
            }
        });
    }

    const categoriesSwiper = document.querySelector(".categories-swiper");
    if (categoriesSwiper) {
        new Swiper(categoriesSwiper.querySelector(".categoriesSwiper"), {
            autoplay: {
                delay: 5000,
            },
            slidesPerView: 1,
            breakpoints: {
                400: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                992: {
                    slidesPerView: 4,
                },
                1200: {
                    slidesPerView: 5,
                },
                1440: {
                    slidesPerView: 6,
                },
            },
            spaceBetween: 16,
            navigation: {
                nextEl: "#categoriesSwiperNext",
                prevEl: "#categoriesSwiperPrev",
            },
        });
    }

    const itemSwiper = document.querySelector(".item-swiper");
    if (itemSwiper) {
        new Swiper(itemSwiper.querySelector(".itemSwiper"), {
            slidesPerView: 2,
            breakpoints: {
                400: {
                    slidesPerView: 3,
                },
                768: {
                    slidesPerView: 4,
                },
                992: {
                    slidesPerView: 5,
                },
                1440: {
                    slidesPerView: 6,
                },
            },
            spaceBetween: 12,
            navigation: {
                nextEl: "#itemSwiperNext",
                prevEl: "#itemSwiperPrev",
            },
        });
    }

    const testimonialsSwiper = document.querySelector(".testimonials-swiper");
    if (testimonialsSwiper) {
        new Swiper(testimonialsSwiper.querySelector(".testimonialsSwiper"), {
            autoplay: {
                delay: 5000,
            },
            autoHeight: true,
            slidesPerView: 1,
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1440: {
                    slidesPerView: 3,
                },
            },
            spaceBetween: 16,
            navigation: {
                nextEl: "#testimonialsSwiperNext",
                prevEl: "#testimonialsSwiperPrev",
            },
        });
    }

    const ratings = document.querySelectorAll(".ratings-selective");
    if (ratings) {
        ratings.forEach((el) => {
            const rating = el.querySelectorAll(".rating");
            rating.forEach((ratingEl, id) => {
                ratingEl.addEventListener("click", () => {
                    ratingEl.querySelector("input[type=radio]").checked = true;
                    rating.forEach((ratingActive, activeId) => {
                        ratingActive.classList.remove("rating-active");
                        if (id >= activeId) {
                            ratingActive.classList.add("rating-active");
                        }
                    });
                });
            });
        });
    }

    const items = document.querySelector(".items");
    if (items) {
        const itemElements = items.querySelectorAll(".item");
        itemElements.forEach((el) => {
            el.classList.contains("item-inline") ? itemsList.setAttribute("disabled", "") : itemsGrid.setAttribute("disabled", "");
        });

        itemsList.addEventListener("click", () => {
            itemsList.setAttribute("disabled", "");
            itemsGrid.removeAttribute("disabled", "");
            itemElements.forEach((el) => {
                el.classList.add("item-inline");
                el.parentNode.classList.add("w-100");
                setCookie("item_view", "list", 100);
            });
        });

        itemsGrid.addEventListener("click", () => {
            itemsGrid.setAttribute("disabled", "");
            itemsList.removeAttribute("disabled", "");
            itemElements.forEach((el) => {
                el.classList.remove("item-inline");
                el.parentNode.classList.remove("w-100");
                setCookie("item_view", "grid", 100);
            });
        });

        if (getCookie("item_view") == "list") {
            itemsList.setAttribute("disabled", "");
            itemsGrid.removeAttribute("disabled", "");
            itemElements.forEach((el) => {
                el.classList.add("item-inline");
                el.parentNode.classList.add("w-100");
                setCookie("item_view", "list", 100);
            });
        } else if (getCookie("item_view") == "grid") {
            itemsGrid.setAttribute("disabled", "");
            itemsList.removeAttribute("disabled", "");
            itemElements.forEach((el) => {
                el.classList.remove("item-inline");
                el.parentNode.classList.remove("w-100");
                setCookie("item_view", "grid", 100);
            });
        }
    }

    const dashboard = document.querySelector(".dashboard"),
        dashboardToggleBtn = document.querySelectorAll(".dashboard-toggle-btn");
    if (dashboard) {
        dashboardToggleBtn.forEach((el) => {
            el.addEventListener("click", () => {
                dashboard.classList.toggle("toggle");
            });
        });
        dashboard.querySelector(".dashboard-sidebar .overlay").addEventListener("click", () => {
            dashboard.classList.remove("toggle");
        });
    }

    const previewNav = document.querySelector(".preview-nav"),
        previewBody = document.querySelector(".preview-body"),
        previewBtn = document.querySelector(".preview-btn"),
        previewDesktopBtn = document.querySelector(".preview-desktop"),
        previewTabletBtn = document.querySelector(".preview-tablet"),
        previewMobileBtn = document.querySelector(".preview-mobile");

    if (previewNav) {
        previewBtn.addEventListener("click", () => {
            previewNav.classList.toggle("toggle");
            previewBody.classList.toggle("toggle");
        });
        previewDesktopBtn.addEventListener("click", () => {
            previewBody.classList.remove("tablet");
            previewBody.classList.remove("mobile");
        });
        previewTabletBtn.addEventListener("click", () => {
            previewBody.classList.remove("mobile");
            previewBody.classList.add("tablet");
        });
        previewMobileBtn.addEventListener("click", () => {
            previewBody.classList.remove("tablet");
            previewBody.classList.add("mobile");
        });
    }

    let clipboardBtn = document.querySelectorAll(".btn-copy");
    if (clipboardBtn) {
        clipboardBtn.forEach((el) => {
            let clipboard = new ClipboardJS(el);
            clipboard.on("success", () => {
                toastr.success(config.translates.copied);
            });
        });
    }

    let inputNumeric = document.querySelectorAll('.input-numeric');
    if (inputNumeric) {
        inputNumeric.forEach((el) => {
            el.oninput = () => {
                el.value = el.value.replace(/[^0-9]/g, '');
            };
        });
    }

    let cookies = document.querySelector('.cookies');
    if (cookies) {
        window.addEventListener('load', () => {
            setTimeout(() => {
                cookies.classList.add('show');
            }, 1000);
        });
    }

    let acceptCookie = $('#acceptCookie'),
        cookieNotice = $('.cookies');
    acceptCookie.on('click', function(e) {
        e.preventDefault();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: config.url + '/cookie/accept',
            type: 'POST',
        });
        cookieNotice.remove();
    });


    let imageInput = $('.image-input');
    imageInput.on('change', function() {
        const ImageExtension = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
        if ($.inArray($(this).val().split('.').pop().toLowerCase(), ImageExtension) != -1) {
            var dataId = $(this).data('id');
            let imagePreview = $('#image-preview-' + dataId);
            var file = true,
                readLogoURL;
            if (file) {
                readLogoURL = function(input_file) {
                    if (input_file.files && input_file.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.attr('src', e.target.result);
                        }
                        reader.readAsDataURL(input_file.files[0]);
                    }
                }
            }
            readLogoURL(this);
        }
    });

    let inputPrice = $('.input-price');
    if (inputPrice.length) {
        inputPrice.priceFormat({
            prefix: '',
            thousandsSeparator: '',
            clearOnEmpty: true
        });
    }

    let selectpicker = $('.selectpicker');
    if (selectpicker.length) {
        selectpicker.selectpicker({
            noneSelectedText: config.translates.noneSelectedText,
            noneResultsText: config.translates.noneResultsText,
            countSelectedText: config.translates.countSelectedText
        });
    }

    let actionConfirm = $('.action-confirm');
    if (actionConfirm.length) {
        actionConfirm.on('click', function(e) {
            if (!confirm(config.translates.actionConfirm)) {
                e.preventDefault();
            }
        });
    }

    let ckeditor = document.querySelector('.ckeditor');
    if (ckeditor) {
        function UploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                return new UploadAdapter(loader);
            };
        }
        ClassicEditor.create(ckeditor, {
            language: config.lang,
            extraPlugins: [UploadAdapterPlugin],
            mediaEmbed: {
                previewsInData: true
            },
        }).catch(error => {
            alert(error);
        });
    }

    let sortableList = $('.sortable-list');
    if (sortableList.length) {
        sortableList.sortable({
            handle: '.sortable-list-handle',
            placeholder: 'sortable-list-placeholder',
            axis: "y",
            update: function() {
                const sortableTableIds = sortableList.sortable('toArray', {
                    attribute: 'data-id'
                });
                updateSortedItems(sortableTableIds.join(','));
            }
        });

        function updateSortedItems(ids) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: sortableRoute,
                type: "POST",
                data: {
                    ids: ids
                },
                dataType: "JSON",
                success: function(response) {
                    if (!$.isEmptyObject(response.error)) {
                        toastr.error(response.error);
                    }
                },
                error: function(request, status, error) {
                    toastr.error(error);
                }
            });
        }
    }


    let kycDocument = $('#kycDocument');

    kycDocument.on('change', function() {
        let kycDocumentVal = $(this).val();

        let nationalId = $('#nationalId'),
            nationalIDNumber = $('#nationalIDNumber'),
            passport = $('#passport'),
            passportNumber = $('#passportNumber');
        if (kycDocumentVal == "national_id") {
            passport.addClass('d-none');
            passportNumber.addClass('d-none');
            nationalId.removeClass('d-none');
            nationalIDNumber.removeClass('d-none');
        } else if (kycDocumentVal == "passport") {
            nationalId.addClass('d-none');
            nationalIDNumber.addClass('d-none');
            passport.removeClass('d-none');
            passportNumber.removeClass('d-none');
        }
    });


    function updateCartCounter() {
        let cartBtn = $('.cart-btn'),
            cartCounter = $('.cart-counter');
        if (cartCounter.length) {
            cartCounter.each(function() {
                var currentCount = parseInt($(this).text().trim());
                if (!isNaN(currentCount)) {
                    $(this).text(currentCount >= 99 ? '+99' : currentCount + 1);
                } else {
                    $(this).text(1);
                }
            });
        } else {
            cartBtn.append('<div class="cart-counter">1</div>');
        }
    }

    let addToCartForm = $('.add-to-cart-form');
    addToCartForm.on('submit', function(e) {
        e.preventDefault();

        let form = $(this),
            action = form.data('action'),
            formData = form.serializeArray();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: action,
            type: "POST",
            data: formData,
            dataType: 'json',
            beforeSend: function() {
                form.find('button').prop('disabled', true);
            },
            success: function(response) {
                if ($.isEmptyObject(response.error)) {
                    form.find('button').prop('disabled', false);
                    toastr.success(response.success);
                    updateCartCounter();
                } else {
                    form.find('button').prop('disabled', false);
                    toastr.error(response.error);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                form.find('button').prop('disabled', false);
                toastr.error(errorThrown);
            }
        })
    });

    let buyNowForm = $('.buy-now-form'),
        licenseType = $('.license-type'),
        itemSupport = $('.item-support');
    licenseType.on('click', function() {

        let licenseTypeValue = $(this).val();
        buyNowForm.find('input[name=license_type]').val(licenseTypeValue);

        let regularSupport = $('.regular-support'),
            extendedSupport = $('.extended-support');
        if (licenseTypeValue == 1) {
            extendedSupport.addClass('d-none');
            regularSupport.removeClass('d-none');
        } else {
            regularSupport.addClass('d-none');
            extendedSupport.removeClass('d-none');
        }
    });

    itemSupport.on('click', function() {
        buyNowForm.find('input[name=support]').val($(this).val());
    });

    let searchParam = $('#searchFiltersSidebar .search-param');
    if (window.innerWidth <= 1200) {
        searchParam = $('#searchFiltersMenu .search-param');
    }

    $.each(searchParam, function(index, element) {
        let url = new URL($(location).attr("href")),
            params = new URLSearchParams(url.search);

        params.forEach((value, key) => {
            if ($(element).attr('name') == key && $(element).val() == value) {
                $(element).prop('checked', true);
            }
        });
    });

    let searchByPrice = $('#searchByPrice');
    searchByPrice.on('click', function(e) {
        e.preventDefault();

        let url = new URL($(location).attr('href')),
            priceForm = $('#priceForm'),
            priceTo = $('#priceTo');


        if (priceForm.val() != '') {
            url = removeParameterFromUrl(url, priceForm.attr('name'));
            url = addParameterToUrl(url, priceForm.attr('name'), priceForm.val());
        } else {
            url = removeParameterFromUrl(url, priceForm.attr('name'));
        }

        if (priceTo.val() != '') {
            url = removeParameterFromUrl(url, priceTo.attr('name'));
            url = addParameterToUrl(url, priceTo.attr('name'), priceTo.val());
        } else {
            url = removeParameterFromUrl(url, priceTo.attr('name'));
        }

        window.location.href = url;
    });

    $(document).on('click', '.search-param', function() {
        let url = new URL($(location).attr('href')),
            param = $(this).attr('name'),
            value = $(this).val(),
            multiple = $(this).data('multiple') ? $(this).data('multiple') : null;

        url = removeParameterFromUrl(url);

        if (!multiple) {
            let paramExists = $(`[name='${param}']`).not(this);

            $.each(paramExists, function(index, element) {
                let params = new URLSearchParams(url.search),
                    param = $(element).attr('name'),
                    value = $(element).val();

                params.forEach((val, key) => {
                    if (param == key && value == val) {
                        url = removeParameterFromUrl(url, param, value);
                    }
                });

                $(element).prop('checked', false);
            });
        }

        if ($(this).is(':checked')) {
            $(this).prop('checked', true);
            url = addParameterToUrl(url, param, value);
        } else {
            $(this).prop('checked', false);
            url = removeParameterFromUrl(url, param, value, multiple);
        }

        window.location.href = url;
    });

    function addParameterToUrl(url, param = null, value = null) {
        var url = new URL(url);
        const params = new URLSearchParams([
            ...Array.from(url.searchParams.entries()),
            ...Object.entries({
                [param]: value
            })
        ]);

        return new URL(`${url.origin}${url.pathname}?${params}`);
    }

    function removeParameterFromUrl(url, param = null, param_value = null, multiple = false) {
        var url = new URL(url),
            params = new URLSearchParams(url.search);

        if (multiple) {
            const multipleParams = params.getAll(param).filter(param => param != param_value);
            params.delete(param);
            for (const value of multipleParams) {
                params.append(param, value);
            }
        } else {
            params.delete(param);
        }

        return new URL(`${url.origin}${url.pathname}?${params}`);
    }


    let periodSelect = $('#period-select');
    periodSelect.on('change', function() {
        location.href = $(this).val();
    });


    let i = 1,
        attachments = $('.attachments'),
        addAttachment = $('#addAttachment');

    addAttachment.on('click', function(e) {
        e.preventDefault();
        if (i < ticketsConfig.max_file) {
            i++;
            attachments.append('<div class="attachment-box-' + i + ' mt-3">' +
                '<div class="input-group">' +
                '<input type="file" name="attachments[]" class="form-control form-control-md">' +
                '<button class="btn btn-danger attachment-remove" data-id="' + i + '" type="button">' +
                '<i class="fa-regular fa-trash-can"></i>' +
                '</button>' +
                '</div>' +
                '</div>');
        } else {
            toastr.error(ticketsConfig.max_files_error)
        }
    });

    $(document).on('click', '.attachment-remove', function() {
        let id = $(this).data("id");
        i--;
        $('.attachment-box-' + id).remove();
    });

    function hexToRgb(hex) {
        let r = parseInt(hex.slice(1, 3), 16),
            g = parseInt(hex.slice(3, 5), 16),
            b = parseInt(hex.slice(5, 7), 16);

        return { r, g, b };
    }

    function rgbToHex(r, g, b) {
        const toHex = (val) => val.toString(16).padStart(2, '0').toUpperCase();
        return `#${toHex(r)}${toHex(g)}${toHex(b)}`;
    }

    function applyOpacityToHex(hexColor, opacity) {
        const { r, g, b } = hexToRgb(hexColor);
        const backgroundRgb = { r: 255, g: 255, b: 255 };
        const newR = Math.round((1 - opacity) * backgroundRgb.r + opacity * r);
        const newG = Math.round((1 - opacity) * backgroundRgb.g + opacity * g);
        const newB = Math.round((1 - opacity) * backgroundRgb.b + opacity * b);
        return rgbToHex(newR, newG, newB);
    }

    const itemVideo = document.querySelectorAll(".item-video");
    if (itemVideo) {
        itemVideo.forEach((el) => {
            const video = el.querySelector("video"),
                volumeBtn = el.querySelector(".item-video-volume"),
                fullBtn = el.querySelector(".item-video-full"),
                videoProgress = el.querySelector(".item-video-progress");

            if (video.muted) {
                el.classList.add("muted");
            } else {
                el.classList.remove("muted");
            }

            el.addEventListener("mouseenter", () => {
                video.play();
            });

            el.addEventListener("mouseleave", () => {
                video.pause();
                setTimeout(() => {
                    video.currentTime = 0;
                }, 0);
                video.load();
            });

            video.addEventListener("timeupdate", () => {
                videoProgress.style.setProperty("width", `${Math.ceil(video.currentTime / video.duration * 100)}%`)
            });

            volumeBtn.addEventListener("click", (e) => {
                e.preventDefault();
                e.stopPropagation();
                itemVideo.forEach((videoVolume) => {
                    if (videoVolume.querySelector("video").muted) {
                        videoVolume.querySelector("video").muted = false;
                        videoVolume.classList.remove("muted");

                    } else {
                        videoVolume.querySelector("video").muted = true;
                        videoVolume.classList.add("muted");
                    }
                });
            });

            function openFullscreen(e) {
                e.preventDefault();
                e.stopPropagation();
                if (video.requestFullscreen) {
                    video.requestFullscreen();
                } else if (video.webkitRequestFullscreen) {
                    video.webkitRequestFullscreen();
                } else if (video.msRequestFullscreen) {
                    video.msRequestFullscreen();
                }
            }
            fullBtn.addEventListener("click", openFullscreen);
        });
    }

    const plyr = document.querySelectorAll(".video-plyr");
    if (plyr.length > 0) {
        plyr.forEach((el) => {
            new Plyr(el);
        });
    }

    const audioPlayer = document.querySelectorAll(".item-audio-wave");
    audioPlayer.forEach((el) => {
        let waveForm = el.querySelector(".waveform"),
            playButton = el.querySelector(".play-button"),
            pauseButton = el.querySelector(".pause-button"),
            totalDuration = el.querySelector(".total-duration"),
            currentTime = el.querySelector(".current-time"),

            waveColor = applyOpacityToHex(config.colors.primary_color, 0.4);

        const initializeWavesurfer = () => {
            return WaveSurfer.create({
                container: waveForm,
                responsive: true,
                waveColor: waveColor,
                progressColor: config.colors.primary_color,
                cursorColor: "transparent",
                height: waveForm.getAttribute("data-waveheight"),
                hideScrollbar: true,
                barWidth: 2,
                barMinHeight: 1,
                barHeight: 1,
                barGap: 2,
                barRadius: 3
            })
        };

        const play = () => {
            document.querySelectorAll(".pause-button").forEach((el) => {
                el.click();
            });
            wavesurfer.play();
            playButton.classList.add("d-none");
            pauseButton.classList.remove("d-none");
        };

        const pause = () => {
            wavesurfer.pause();
            pauseButton.classList.add("d-none");
            playButton.classList.remove("d-none");
        };

        const formatTimeCode = seconds => {
            return new Date(seconds * 1000).toISOString().slice(14, 19);
        };

        const wavesurfer = initializeWavesurfer();
        wavesurfer.load(waveForm.getAttribute("data-url"));

        playButton.addEventListener("click", play);
        pauseButton.addEventListener("click", pause);

        wavesurfer.on("ready", () => {
            if (totalDuration) {
                totalDuration.textContent = formatTimeCode(wavesurfer.getDuration());
            }
        });

        wavesurfer.on("audioprocess", () => {
            if (currentTime) {
                const time = wavesurfer.getCurrentTime();
                currentTime.innerHTML = formatTimeCode(time);
            }
        });

        wavesurfer.on("finish", () => {
            pauseButton.classList.add("d-none");
            playButton.classList.remove("d-none");
        });
    });

})(jQuery);