(function ($) {

    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": true,
      "progressBar": false,
      "positionClass": "toast-top-right",
      "preventDuplicates": true,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }


    var bytesToSize = function (bytes) {
        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (bytes == 0) return '0 Byte';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    };

    var table_get = function () {
        return $('#megaoptim-compress-infotable');
    };

    var table_inert_row = function (file, response) {
        var col1 = '';
        var col2 = '-';
        var col3 = '-';
        var col4 = '-';
        var col5 = '-';
        var col6 = '-';
        var $table = table_get();
        if (response.success) {
            var has_webp = null != response.data.result.webp && response.data.result.webp.hasOwnProperty('url');
            col1 = response.data.result.file_name;
            col2 = response.data.result.success ? (has_webp ? 'Done (including WebP)' : 'Done') : 'Already Optimized';
            col3 = bytesToSize(response.data.result.original_size);
            col4 = (has_webp ? '<strong>Normal:</strong> ' : '') + bytesToSize(response.data.result.optimized_size);
            col5 = (has_webp ? '<strong>Normal:</strong> ' : '') + parseFloat(response.data.result.saved_percent).toFixed(2) + '%';
            col6 = '<a target="_blank" href="' + response.data.result.url + '"><i class="fa fa-save"></i> Save</a>';
            if (has_webp) {
                col4 += ', <strong>WebP:</strong> ' + bytesToSize(response.data.result.webp.optimized_size);
                col5 += ', <strong>WebP:</strong> ' + parseFloat(response.data.result.webp.saved_percent).toFixed(2) + '%';
                col6 = '<a target="_blank" href="' + response.data.result.url + '"><i class="fa fa-save"></i> Normal</a> <br/> <a target="_blank" href="' + response.data.result.webp.url + '"><i class="fa fa-save"></i> WebP</a>';
            }
        } else {
            col1 = file.name;
            col2 = 'Error: ' + response.data.message;
        }

        if($table.is(':hidden')) {
            $table.show();
        }
        var row = '<tr><td>' + col1 + '</td><td>' + col2 + '</td><td>' + col3 + '</td><td>' + col4 + '</td><td>' + col5 + '</td><td>' + col6 + '</td></tr>';
        $table.find('tbody').append(row);
    };


    var ajax_url = MGOCompress.ajax_url + '?action=megaoptim_compress&_ajax_nonce=' + MGOCompress.nonce;

    var max_files = 5;

    Dropzone.options.megaoptimCompress = {
        autoProcessQueue: false,
        maxFiles: max_files,
        parallelUploads: 5,
        timeout: 0,
        url: ajax_url,
        init: function () {

            var myDropzone = this;

            // Update selector to match your button
            $(document).on('click', '#runOptimizer', function (e) {

                if($(this).is('disabled')) {
                    alert('Please wait for the current queue to finish.');
                    return;
                }


                e.preventDefault();
                if (myDropzone.files.length > 0) {
                    myDropzone.processQueue();
                    $(this).prop('disabled', true);
                } else {
                    alert('No files selected.');
                }
            });

            // Attach data
            this.on('sending', function (file, xhr, formData) {
                var data = [
                    {name: 'compression', value: $('#compression').val()},
                    {name: 'keep_exif', value: $('#keep_exif').val()},
                    {name: 'webp', value: $('#webp').val()},
                    {name: 'max_width', value: $('#max_width').val()}
                ];
                $.each(data, function (key, el) {
                    formData.append(el.name, el.value);
                });
            });

            this.on("maxfilesexceeded", function (file) {
                myDropzone.removeFile(file);
                toastr["warning"]("Max files limit", "You can only send up to 5 images at once.");
            });


            this.on("success", function (file, response) {
                if(!response.success) {
                    myDropzone.removeFile(file);
                    toastr['error']("Error optimizing file", response.data.message);
                    return;
                }
                table_inert_row(file, response);
                toastr['success']("Success!", response.data.message);
            });

            this.on("complete", function(){
                $('#runOptimizer').prop('disabled', false);
            });

        }
    };


})(jQuery);