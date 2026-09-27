(function($) {

    "use strict";

    document.querySelectorAll('[data-year]').forEach((el) => {
        el.textContent = " " + new Date().getFullYear();
    });

    let dropdown = document.querySelectorAll('[data-dropdown]'),
        dropdownV2 = document.querySelectorAll('[data-dropdown-v2]'),
        itemHeight = 38;

    if (dropdown !== null) {
        dropdown.forEach((el) => {
            let dropdownFunc = () => {
                let sideBarLinksMenu = el.querySelector('.vironeer-sidebar-link-menu');
                if (el.classList.contains('active')) {
                    sideBarLinksMenu.style.height = sideBarLinksMenu.children.length * itemHeight + 'px';
                } else {
                    sideBarLinksMenu.style.height = 0;
                }
            };

            el.querySelector('.vironeer-sidebar-link-title').onclick = () => {
                el.classList.toggle('active');
                dropdownFunc();
            };
            window.addEventListener('load', dropdownFunc);
        });
    }

    if (dropdownV2 !== null) {
        dropdownV2.forEach(function(el) {
            window.addEventListener('click', function(e) {
                if (el.contains(e.target)) {
                    el.classList.toggle('active');
                    setTimeout(function() {
                        el.classList.toggle('animated');
                    }, 0);
                } else {
                    el.classList.remove('active');
                    el.classList.remove('animated');
                }
            });
        });
    }

    let counterCards = document.querySelectorAll('.counter-card');
    let dashCountersOP = () => {
        counterCards.forEach((el) => {
            let itemWidth = 350,
                clientWidthX = el.clientWidth;
            if (clientWidthX > itemWidth) {
                el.classList.remove('active');
            } else {
                el.classList.add('active');
            }
        });
    };

    window.addEventListener('load', dashCountersOP);
    window.addEventListener('resize', dashCountersOP);

    let sideBar = document.querySelector('.vironeer-sidebar'),
        pageContent = document.querySelector('.vironeer-page-content'),
        sideBarIcon = document.querySelector('.vironeer-sibebar-icon');
    if (sideBar !== null) {
        sideBarIcon.onclick = () => {
            sideBar.classList.toggle('active');
            pageContent.classList.toggle('active');
            setTimeout(dashCountersOP, 150);

        };
        sideBar.querySelector('.overlay').onclick = () => {
            sideBar.classList.remove('active');
            pageContent.classList.remove('active');
        };
        window.addEventListener('resize', () => {
            if (window.innerWidth < 1200) {
                sideBar.classList.remove('active');
                pageContent.classList.remove('active');
            }
        });
    }

    let sidebarLinkCounter = document.querySelectorAll(".vironeer-sidebar-link-title .counter");
    if (sidebarLinkCounter) {
        sidebarLinkCounter.forEach((el) => {
            if (el.innerHTML == 0) {
                el.classList.add("disabled");
            }
        });
    }

    let navbarLinkCounter = document.querySelectorAll(".vironeer-notifications-title .counter");
    if (navbarLinkCounter) {
        navbarLinkCounter.forEach((el) => {
            if (el.innerHTML == 0) {
                el.classList.add("disabled");
            } else {
                el.classList.add("flashit");
            }
        });
    }

    let dataTable = $('.datatable'),
        DataTable2 = $('.datatable2');
    if (dataTable.length || DataTable2.length) {
        let dataTableConfig = {
            "language": {
                emptyTable: config.translates.emptyTable,
                searchPlaceholder: config.translates.searchPlaceholder,
                search: "",
                zeroRecords: config.translates.zeroRecords,
                sLengthMenu: config.translates.sLengthMenu,
                info: config.translates.info,
                infoEmpty: config.translates.infoEmpty,
                infoFiltered: config.translates.infoFiltered,
                paginate: {
                    first: config.translates.paginate.first,
                    previous: config.translates.paginate.previous,
                    next: config.translates.paginate.next,
                    last: config.translates.paginate.last,
                },
            },
            "dom": '<"top"f><"table-wrapper"rt><"bottom"ilp><"clear">',
            drawCallback: function() {
                document.querySelector('.dataTables_wrapper .pagination').classList.add('pagination-sm');
                $('.dataTables_filter input').attr('type', 'text');
            }
        }

        if (dataTable.length) {
            dataTable.DataTable($.extend({}, dataTableConfig, {
                pageLength: 50,
                order: [
                    [0, "desc"]
                ],
            }));
        }

        if (DataTable2.length) {
            DataTable2.DataTable($.extend({}, dataTableConfig, {
                pageLength: 50,
            }));
        }
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
            }
        }).catch(error => {
            alert(error);
        });
    }


    let selectFileBtn = $('#selectFileBtn'),
        selectedFileInput = $("#selectedFileInput"),
        filePreviewBox = $('.file-preview-box'),
        filePreviewImg = $('#filePreview');

    selectFileBtn.on('click', function() {
        selectedFileInput.trigger('click');
    });

    selectedFileInput.on('change', function() {
        var file = true,
            readLogoURL;
        if (file) {
            readLogoURL = function(input_file) {
                if (input_file.files && input_file.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        filePreviewBox.removeClass('d-none');
                        filePreviewImg.attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input_file.files[0]);
                }
            }
        }
        readLogoURL(this);
    });

    let createSlug = $("#create_slug"),
        showSlug = $('#show_slug');

    createSlug.on('input', function() {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            url: GET_SLUG_URL,
            data: {
                content: $(this).val(),
            },
            success: function(data) {
                showSlug.val(data.slug);
            }
        });
    });


    let actionConfirm = $('.action-confirm');
    if (actionConfirm.length) {
        actionConfirm.on('click', function(e) {
            if (!confirm(config.translates.actionConfirm)) {
                e.preventDefault();
            }
        });
    }

    let attachImageButton = $('.attach-image-button');
    attachImageButton.on('click', function() {
        var dataId = $(this).data('id');
        let targetedImageInput = $('#attach-image-targeted-input-' + dataId),
            targetedImagePreview = $('#attach-image-preview-' + dataId);
        targetedImageInput.trigger('click');
        targetedImageInput.on('change', function() {
            const ImageExtension = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
            if ($.inArray($(this).val().split('.').pop().toLowerCase(), ImageExtension) != -1) {
                var file = true,
                    readLogoURL;
                if (file) {
                    readLogoURL = function(input_file) {
                        if (input_file.files && input_file.files[0]) {
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                targetedImagePreview.attr('src', e.target.result);
                            }
                            reader.readAsDataURL(input_file.files[0]);
                        }
                    }
                }
                readLogoURL(this);
            }
        });
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

    let selectpicker = $('.selectpicker');
    if (selectpicker.length) {
        selectpicker.selectpicker({
            noneSelectedText: config.translates.noneSelectedText,
            noneResultsText: config.translates.noneResultsText,
            countSelectedText: config.translates.countSelectedText
        });
    }


    let removeSpaces = $(".remove-spaces");
    removeSpaces.on('input', function() {
        $(this).val($(this).val().replace(/\s/g, ""));
    });

    let htmlEditor = document.getElementById("html-editor");
    if (htmlEditor) {
        var editor = CodeMirror.fromTextArea(htmlEditor, {
            lineNumbers: true,
            mode: "htmlmixed",
            theme: "monokai",
            keyMap: "sublime",
            autoCloseBrackets: true,
            matchBrackets: true,
            showCursorWhenSelecting: true,
        });
        editor.setSize(null, 400);
    }

    var cssEditor = document.getElementById("css-editor");
    if (cssEditor) {
        var editor = CodeMirror.fromTextArea(cssEditor, {
            lineNumbers: true,
            mode: "text/css",
            theme: "monokai",
            keyMap: "sublime",
            autoCloseBrackets: true,
            matchBrackets: true,
            showCursorWhenSelecting: true,
        });
        editor.setSize(null, 700);
    }

    function updateSortedItems(ids) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: sortingRoute,
            type: "POST",
            data: { ids: ids },
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
    }

    let sortableTableTbody = $('.sortable-table-tbody');
    if (sortableTableTbody.length) {
        sortableTableTbody.sortable({
            handle: '.sortable-table-handle',
            placeholder: 'sortable-table-placeholder',
            axis: "y",
            update: function() {
                const sortableTableIds = sortableTableTbody.sortable('toArray', {
                    attribute: 'data-id'
                });
                updateSortedItems(sortableTableIds.join(','));
            }
        });
    }

    let nestable = $('.nestable');
    if (nestable.length) {
        nestable.nestable({ maxDepth: nestableMaxDepth });
        nestable.on('change', function() {
            const ids = JSON.stringify(nestable.nestable('serialize'));
            updateSortedItems(ids);
        });
    }

    let commentView = $('.vironeer-view-comment'),
        viewCommentModal = $('#viewComment'),
        deleteCommentForm = $('#deleteCommentForm'),
        publishCommentForm = $('#publishCommentForm'),
        publishCommentBtn = $('.publish-comment-btn'),
        commentInput = $('#comment');
    commentView.on('click', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: config.admin_url + '/blog/comments/' + id + '/view',
            type: 'GET',
            dataType: 'JSON',
            success: function(response) {
                if ($.isEmptyObject(response.error)) {
                    commentInput.val(response.comment);
                    deleteCommentForm.attr('action', response.delete_link);
                    if (response.status === 1) {
                        publishCommentBtn.addClass('disabled');
                    } else {
                        publishCommentBtn.removeClass('disabled');
                        publishCommentForm.attr('action', response.publish_link);
                    }
                    viewCommentModal.modal('show');
                } else {
                    toastr.error(response.error);
                }
            },
        });
    });

    let inputNumeric = document.querySelectorAll('.input-numeric');
    if (inputNumeric) {
        inputNumeric.forEach((el) => {
            el.oninput = () => {
                el.value = el.value.replace(/[^0-9]/g, '');
            };
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

    function generatePassword(length) {
        var charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        var password = "";
        for (var i = 0; i < length; i++) {
            var randomIndex = Math.floor(Math.random() * charset.length);
            password += charset.charAt(randomIndex);
        }
        return password;
    }


    let randomPasswordBtn = $('#randomPasswordBtn'),
        randomPasswordInput = $('#randomPasswordInput');
    randomPasswordBtn.on('click', function(e) {
        e.preventDefault();
        randomPasswordInput.val(generatePassword(16));
    });

    randomPasswordInput.val(generatePassword(16));


    let colorpicker = $('.colorpicker');
    if (colorpicker.length) {
        Coloris({ el: '.coloris', rtl: config.direction == "rtl" ? true : false, });
        Coloris.setInstance('.coloris', {
            theme: 'pill',
            themeMode: 'light',
            formatToggle: true,
            closeButton: true,
            clearButton: true,
            swatches: ['#067bc2', '#84bcda', '#80e377', '#ecc30b', '#f37748', '#d56062']
        });
    }

    let addonStatus = $('.addon-status');
    addonStatus.on('change', function() {
        let updateLink = $(this).data('update-link'),
            status = $(this).is(':checked') ? 1 : 0;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: updateLink,
            type: "POST",
            data: { status: status },
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
    });


    let categoryOptions = $('.category-options'),
        addCategoryOption = $('#addCategoryOption');

    addCategoryOption.on('click', function(e) {
        e.preventDefault();
        categoryOptionsCount++;
        categoryOptions.append('<div class="category-option-' + categoryOptionsCount + ' mt-3">' +
            '<div class="input-group">' +
            '<input type="text" name="options[]" class="form-control form-control-lg" required>' +
            '<button class="btn btn-danger px-3 category-option-remove" data-id="' + categoryOptionsCount + '" type="button">' +
            '<i class="fa-regular fa-trash-can"></i>' +
            '</button>' +
            '</div>' +
            '</div>');
    });

    $(document).on('click', '.category-option-remove', function() {
        let id = $(this).data("id");
        categoryOptionsCount--;
        $('.category-option-' + id).remove();
    });

    let badgeType = $('#badgeType');
    badgeType.on('change', function() {

        let badgeTypeValue = badgeType.val(),
            countries = $('#countries'),
            authorLevels = $('#authorLevels'),
            membershipYears = $('#membershipYears');

        if (badgeTypeValue == "countries") {
            $('#membershipYears input').prop('disabled', true);
            membershipYears.addClass('d-none');
            $('#authorLevels select').prop('disabled', true);
            authorLevels.addClass('d-none');
            $('#countries select').prop('disabled', false);
            countries.removeClass('d-none');
        } else if (badgeTypeValue == "author_levels") {
            $('#membershipYears input').prop('disabled', true);
            membershipYears.addClass('d-none');
            $('#countries select').prop('disabled', true);
            countries.addClass('d-none');
            $('#authorLevels select').prop('disabled', false);
            authorLevels.removeClass('d-none')
        } else if (badgeTypeValue == "membership_years") {
            $('#authorLevels select').prop('disabled', true);
            authorLevels.addClass('d-none');
            $('#countries select').prop('disabled', true);
            countries.addClass('d-none');
            $('#membershipYears input').prop('disabled', false);
            membershipYears.removeClass('d-none');
        }
    });

    let inputPrice = $('.input-price');
    if (inputPrice.length) {
        inputPrice.priceFormat({
            prefix: '',
            thousandsSeparator: '',
            clearOnEmpty: false
        });
    }

    let attachments_i = 1,
        attachments = $('.attachments'),
        addAttachment = $('#addAttachment');

    addAttachment.on('click', function(e) {
        e.preventDefault();
        attachments_i++;
        attachments.append('<div class="attachment-box-' + attachments_i + ' mt-3">' +
            '<div class="input-group">' +
            '<input type="file" name="attachments[]" class="form-control form-control-md">' +
            '<button class="btn btn-danger attachment-remove" data-id="' + attachments_i + '" type="button">' +
            '<i class="fa-regular fa-trash-can"></i>' +
            '</button>' +
            '</div>' +
            '</div>');
    });

    $(document).on('click', '.attachment-remove', function() {
        let id = $(this).data("id");
        attachments_i--;
        $('.attachment-box-' + id).remove();
    });

    let periodSelect = $('#period-select');
    periodSelect.on('change', function() {
        location.href = $(this).val();
    });

    let tagsInput = $('.tags-input');
    if (tagsInput.length) {
        tagsInput.tagsinput({
            cancelConfirmKeysOnEmpty: false
        });
    }

    let customFeatures = $('.custom-features'),
        addCustomFeature = $('#addCustomFeature');

    addCustomFeature.on('click', function(e) {
        e.preventDefault();
        customFeatures_i++;
        customFeatures.append('<div class="col-12 custom-feature-box-' + customFeatures_i + '">' +
            '<div class="input-group">' +
            '<input type="text" name="custom_features[]" class="form-control form-control-md" required>' +
            '<button class="btn btn-danger custom-feature-remove" type="button" data-id="' +
            customFeatures_i + '">' +
            '<i class="fa-regular fa-trash-can"></i></button>' +
            '</div>' +
            '</div>');
    });

    $(document).on('click', '.custom-feature-remove', function() {
        let id = $(this).data("id");
        customFeatures_i--;
        $('.custom-feature-box-' + id).remove();
    });

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
            currentTime = el.querySelector(".current-time");

        const initializeWavesurfer = () => {
            return WaveSurfer.create({
                container: waveForm,
                responsive: true,
                waveColor: '#d2ebd3',
                progressColor: '#4caf50',
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