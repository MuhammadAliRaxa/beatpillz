(function($) {
    "use strict";

    let itemTagsInput = $('#item-tags');
    if (itemTagsInput.length) {
        itemTagsInput.tagsinput({
            cancelConfirmKeysOnEmpty: false,
            maxTags: parseInt(itemConfig.max_tags),
        });
    }

    let mainFileSource = $('#mainFileSource');
    mainFileSource.on('change', function() {
        let mainFileSource1 = $('.main-file-source-1'),
            mainFileSource2 = $('.main-file-source-2');
        if ($(this).val() == 0) {
            mainFileSource2.prop('disabled', true);
            mainFileSource2.addClass('d-none');
            mainFileSource1.prop('disabled', false);
            mainFileSource1.removeClass('d-none');
        } else {
            mainFileSource1.prop('disabled', true);
            mainFileSource1.addClass('d-none');
            mainFileSource2.prop('disabled', false);
            mainFileSource2.removeClass('d-none');
        }
    });

    let supportOption = $('.support-option');
    supportOption.on('click', function() {
        let supportInstructions = $('.support-instructions');
        if ($(this).val() == 1) {
            supportInstructions.removeClass('d-none');
        } else {
            supportInstructions.addClass('d-none');
        }
    });

    let freeItemOption = $('.free-item-option');
    freeItemOption.on('click', function() {
        let purchasingOption = $('.purchasing-option');
        if ($(this).val() == 1) {
            purchasingOption.removeClass('d-none');
        } else {
            purchasingOption.addClass('d-none');
        }
    });

    let uploadFilesBox = $('#upload-files-box');

    if (uploadFilesBox.length) {

        let previewNode = document.querySelector("#upload-previews");
        previewNode.id = "";
        let previewTemplate = previewNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        let maxFiles = parseInt(uploadConfig.max_files),
            imageTypes = ["image/jpeg", "image/jpg", "image/png"];

        Dropzone.autoDiscover = false;
        var dropzone = new Dropzone("#dropzone-wrapper", {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: uploadConfig.upload_url,
            method: "POST",
            paramName: 'file',
            filesizeBase: 1024,
            parallelUploads: maxFiles,
            maxFiles: maxFiles,
            maxFilesize: parseInt(uploadConfig.max_file_size),
            acceptedFiles: uploadConfig.allowed_types,
            autoProcessQueue: true,
            timeout: 0,
            chunking: true,
            forceChunking: true,
            chunkSize: 52428800,
            retryChunks: true,
            clickable: "[data-dz-click]",
            previewsContainer: "#dropzone",
            previewTemplate: previewTemplate,
        });

        function fileDrag() {
            let dropzoneBox = $(".dropzone-box");
            if (dropzone.files.length > 0) {
                dropzoneBox.addClass('active');
            } else {
                dropzoneBox.removeClass('active');
            }
        }

        function onAddFile(file) {

            if (dropzone.files.length > maxFiles) {
                this.removeFile(file);
                toastr.error(uploadConfig.translates.errors.max_files_exceeded);
                return;
            }

            if (this.files.length) {
                var _i, _len;
                for (_i = 0, _len = this.files.length; _i < _len - 1; _i++) {
                    if (this.files[_i].name === file.name) {
                        this.removeFile(file);
                        toastr.error(uploadConfig.translates.errors.file_duplicate);
                        return;
                    }
                }
            }

            if (file.size == 0) {
                this.removeFile(file);
                toastr.error(uploadConfig.translates.errors.file_empty);
                return;
            }

            if (file.size > uploadConfig.max_file_size) {
                this.removeFile(file);
                toastr.error(uploadConfig.translates.errors.max_file_size_exceeded);
                return;
            }

            fileDrag();

            let preview = $(file.previewElement),
                previewFileSize = preview.find('.dz-size');

            previewFileSize.html('(' + formatBytes(file.size) + ')');

            let previewFileExt = preview.find("[data-dz-extension]");
            if (imageTypes.includes(file.type)) {
                previewFileExt.remove();
            } else {
                let fileExtension = file.name.split('.').pop(),
                    previewFileThumbnail = preview.find("[data-dz-thumbnail]")
                previewFileThumbnail.remove();
                if (fileExtension != "") {
                    previewFileExt.attr('data-type', fileExtension.substring(0, 4));
                } else {
                    previewFileExt.attr('data-type', '?');
                }
            }

            preview.find('[data-dz-name]').text(sliceString(file.upload.filename));
        }

        function onRemovedfile() {
            fileDrag();
        }

        function onUploadProgress(file, progress) {
            let preview = $(file.previewElement);
            preview.find(".dz-upload-percentage").html(progress.toFixed(0) + "%");
        }

        function onFileError(file, message = null) {
            this.removeFile(file);
            toastr.error(message);
        }

        function onUploadComplete(file) {
            if (file.status == "success") {
                let preview = $(file.previewElement),
                    response = JSON.parse(file.xhr.response);
                if (response.type == 'success') {
                    this.removeFile(file);
                    let uploadedFiles = $('.uploaded-files'),
                        thumbnail = '<span class="vi vi-file" data-type="' + response.extension + '"></span>';
                    if (imageTypes.includes(response.mime_type)) {
                        thumbnail = '<img src="' + response.link + '" alt="' + response.name + '" />';
                    }
                    uploadedFiles.append('<div class="uploaded-file uploaded-file-' + response.id + '">' +
                        '<div class="uploaded-file-icon">' + thumbnail + '</div>' +
                        '<div class="uploaded-file-info">' +
                        '<h6 class="uploaded-file-name">' +
                        '<span class="success-mark"><i class="far fa-check-circle"></i></span>' + sliceString(response.name) +
                        '<small class="ms-1">(' + response.size + ')</small></h6>' +
                        '<p class="uploaded-file-time">' + response.time + '</p>' +
                        '</div>' +
                        '<button type="button" class="uploaded-file-remove" data-id="' + response.id + '" data-delete-link="' + response.delete_link + '">' +
                        '<i class="fa fa-trash-alt"></i>' +
                        '</button>' +
                        '</div>');
                    loadUploadedFiles();
                    maxFiles--;
                    removeUploadedFiles();
                } else {
                    preview.removeClass('dz-success');
                    preview.addClass('dz-error');
                    this.removeFile(file);
                    toastr.error(response.message);
                }
            }
        }

        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return "0 " + uploadConfig.translates.format_bytes[0];
            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = uploadConfig.translates.format_bytes;
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        function sliceString(string) {
            if (string.length > 40) {
                return string.slice(0, 20) + ".." + string.slice(string.length - 4);
            }
            return string;
        }

        dropzone.on("addedfile", onAddFile);
        dropzone.on("removedfile", onRemovedfile);
        dropzone.on('uploadprogress', onUploadProgress);
        dropzone.on('error', onFileError);
        dropzone.on('complete', onUploadComplete);

        function removeUploadedFiles() {
            let uploadedFileRemove = $('.uploaded-file-remove');
            uploadedFileRemove.on('click', function(e) {
                e.preventDefault();
                let uploadedFileId = $(this).data('id'),
                    uploadedFileDeleteLink = $(this).data('delete-link'),
                    uploadedFileRemoveBtn = $('button[data-id="' + uploadedFileId + '"]');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: uploadedFileDeleteLink,
                    type: "DELETE",
                    dataType: "JSON",
                    beforeSend: function() {
                        uploadedFileRemoveBtn.prop('disabled', true);
                        uploadedFileRemoveBtn.empty();
                        uploadedFileRemoveBtn.append('<div class="spinner-border spinner-border-sm me-2"></div>');
                    },
                    success: function(response) {
                        uploadedFileRemoveBtn.prop('false', true);
                        uploadedFileRemoveBtn.empty();
                        uploadedFileRemoveBtn.append('<i class="fa fa-trash-alt"></i>');
                        if ($.isEmptyObject(response.error)) {
                            $('.uploaded-file-' + uploadedFileId).remove();
                            loadUploadedFiles();
                            maxFiles++;
                            toastr.success(response.success);
                        } else {
                            toastr.error(response.error);
                        }
                    },
                    error: function(request, status, error) {
                        uploadedFileRemoveBtn.prop('false', true);
                        uploadedFileRemoveBtn.empty();
                        uploadedFileRemoveBtn.append('<i class="fa fa-trash-alt"></i>');
                        toastr.error(error);
                    }
                });
            });
        }

        removeUploadedFiles();

        function loadUploadedFiles() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: itemConfig.load_files_route,
                type: "POST",
                dataType: "JSON",
                success: function(response) {
                    let itemFilesSelect = $('select.item-files-select');
                    itemFilesSelect.selectpicker('destroy');
                    if (itemFilesSelect.length > 0) {
                        itemFilesSelect.empty();
                        $.each(response, function(index, option) {
                            itemFilesSelect.append('<option value="' + index + '">' + option + '</option>');
                        });
                    } else {
                        itemFilesSelect.empty();
                    }
                    itemFilesSelect.selectpicker();
                    itemFilesSelect.addClass('selectpicker');
                },
                error: function(request, status, error) {
                    toastr.error(error);
                }
            });
        }

    }

    let regularLicensePrice = $('#regular-license-price'),
        regularLicensePurchasePrice = $('#regular-license-purchase-price'),
        extendedLicensePrice = $('#extended-license-price'),
        extendedLicensePurchasePrice = $('#extended-license-purchase-price'),
        maxDiscountPercentage = parseInt(itemConfig.max_discount_percentage);

    function updateRegularLicensePurchasePrice(discountPercentage = 0) {
        if (regularLicensePrice.length) {
            let inputVal = regularLicensePrice.val(),
                regularBuyerFee = parseFloat(itemConfig.buyer_fee.regular);
            let price = (inputVal !== null && inputVal.trim() !== '' && !isNaN(inputVal)) ? parseFloat(inputVal) + regularBuyerFee : 0;

            if (discountPercentage > 0) {
                let discountAmount = (itemConfig.prices.regular * discountPercentage) / 100,
                    regularPrice = itemConfig.prices.regular - discountAmount;
                price = Math.ceil(regularPrice + regularBuyerFee);
                regularLicensePurchasePrice.val(price.toFixed(2));
            } else {
                regularLicensePurchasePrice.val(price.toFixed(2));
            }
        }
    }

    let regularLicensePercentage = $('#regular-license-percentage');
    regularLicensePercentage.on('input', function() {
        let percentageValue = regularLicensePercentage.val(),
            percentage = (percentageValue !== null && percentageValue.trim() !== '' && !isNaN(percentageValue)) ? parseFloat(percentageValue) : 0;

        if (percentage > maxDiscountPercentage) {
            regularLicensePercentage.val(maxDiscountPercentage);
            alert(itemConfig.translates.max_discount_percentage_error);
        } else {
            updateRegularLicensePurchasePrice(percentage);
        }
    });

    function updateExtendedLicensePurchasePrice(discountPercentage = 0) {
        if (extendedLicensePrice.length) {
            let inputVal = extendedLicensePrice.val(),
                extendedBuyerFee = parseFloat(itemConfig.buyer_fee.extended);
            let price = (inputVal !== null && inputVal.trim() !== '' && !isNaN(inputVal)) ? parseFloat(inputVal) + extendedBuyerFee : 0;

            if (discountPercentage > 0) {
                let discountAmount = (itemConfig.prices.extended * discountPercentage) / 100,
                    extendedPrice = itemConfig.prices.extended - discountAmount;
                price = Math.ceil(extendedPrice + extendedBuyerFee);
                extendedLicensePurchasePrice.val(price.toFixed(2));
            } else {
                extendedLicensePurchasePrice.val(price.toFixed(2));
            }
        }
    }

    let extendedLicensePercentage = $('#extended-license-percentage');
    extendedLicensePercentage.on('input', function() {
        let percentageValue = extendedLicensePercentage.val(),
            percentage = (percentageValue !== null && percentageValue.trim() !== '' && !isNaN(percentageValue)) ? parseFloat(percentageValue) : 0;

        if (percentage > maxDiscountPercentage) {
            extendedLicensePercentage.val(maxDiscountPercentage);
            alert(itemConfig.translates.max_discount_percentage_error);
        } else {
            updateExtendedLicensePurchasePrice(percentage);
        }
    });

    updateRegularLicensePurchasePrice();
    updateExtendedLicensePurchasePrice();

    regularLicensePrice.on('input', function() {
        updateRegularLicensePurchasePrice();
    });

    extendedLicensePrice.on('input', function() {
        updateExtendedLicensePurchasePrice();
    });

})(jQuery);