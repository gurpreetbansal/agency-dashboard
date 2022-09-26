<script type="text/javascript">
var BASE_URL = $('.base_url').val();
$(document).ready(function(){  

$("#whiteLableLogo").fileinput({
        theme:'fa',
        maxFileSize: 1500,
        showClose: false,
        showCaption: false,
        showBrowse: true,
        browseOnZoneClick: true,
        removeLabel: '',
        removeIcon: '<i class="fa fa-trash"></i>',
        removeTitle: 'Cancel or reset changes',
        elErrorContainer: '#logo-avatar-errors-2',
        msgErrorClass: 'alert alert-block alert-danger',
        layoutTemplates: {
            main2: '{preview} {remove} {browse}'
        },
        allowedFileExtensions: ["jpg", "png", "gif"],
        uploadUrl: BASE_URL + "/ajax_upload_agency_logo", // server upload action
        uploadExtraData: function() {
                return {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    request_id: $('.campaignId').val()
                };
        },
        uploadAsync: true,
        minFileCount: 1,
        maxFileCount: 1,
        overwriteInitial: false,
        initialPreviewAsData: true,            
        initialPreview: ["<?php if(!empty($logo)){
            echo @$logo['return_path'];
        }?>"],
        append: true,
        initialPreviewFileType: 'image', // image is the default and can be overridden in config below
        initialPreviewConfig: [{
            caption: "<?php if(!empty($logo)){
            echo @$logo['file_name'];
        }?>",
            size: 576237,
            width: "120px",
            url: BASE_URL + "/ajax_remove_agency_logo",
            extra: {
                request_id: $('.campaignId').val(),
                 _token: $('meta[name="csrf-token"]').attr('content'),
            }
        }], 
});

$("#managerImage").fileinput({
        theme:'fa',
        maxFileSize: 1500,
        showClose: false,
        showCaption: false,
        showBrowse: true,
        browseOnZoneClick: true,
        removeLabel: '',
        removeIcon: '<i class="fa fa-trash"></i>',
        removeTitle: 'Cancel or reset changes',
        elErrorContainer: '#logo-avatar-errors-2',
        msgErrorClass: 'alert alert-block alert-danger',
        layoutTemplates: {
            main2: '{preview} {remove} {browse}'
        },
        allowedFileExtensions: ["jpg", "png", "gif"],
        uploadUrl: BASE_URL + "/ajax_upload_manager_image", // server upload action
        uploadExtraData: function() {
                return {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    request_id: $('.campaignId').val()
                };
        },
        uploadAsync: true,
        minFileCount: 1,
        maxFileCount: 1,
        overwriteInitial: false,
        initialPreviewAsData: true,            
        initialPreview: ["<?php if(!empty($managerImage)){
            echo @$managerImage['return_path'];
        }?>"],
        append: true,
        initialPreviewFileType: 'image', // image is the default and can be overridden in config below
        initialPreviewConfig: [{
            caption: "<?php if(!empty($managerImage)){
            echo @$managerImage['file_name'];
        }?>",
            size: 576237,
            width: "120px",
            url: BASE_URL + "/ajax_remove_manager_image",
            extra: {
                request_id: $('.campaignId').val(),
                 _token: $('meta[name="csrf-token"]').attr('content'),
            }
        }], 
});


}); 

</script>