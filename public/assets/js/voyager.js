tinymce.init({
    menubar: false,
    selector:'textarea.richTextBox',
    skin_url: $('meta[name="assets-path"]').attr('content')+'?path=js/skins/voyager',
    min_height: 250,
    resize: 'vertical',
    plugins: 'link, image, code, table, textcolor, lists, responsivefilemanager',
    extended_valid_elements : 'input[id|name|value|type|class|style|required|placeholder|autocomplete|onclick]',
    file_browser_callback: function(field_name, url, type, win) {
            if(type =='image'){
              $('#upload_file').trigger('click');
            }
        },
    toolbar: 'styleselect bold italic underline | blockquote | forecolor backcolor | alignleft aligncenter alignright | bullist numlist outdent indent | link image table | code',
    convert_urls: false,
    image_caption: true,
    image_title: true,
	image_advtab: true,
	external_filemanager_path: "/NJsAJWIKSndefu/",
	filemanager_title: "Responsive Filemanager",
	external_plugins: {
		"responsivefilemanager": "/NJsAJWIKSndefu/plugin.min.js",
		"filemanager": "/NJsAJWIKSndefu/plugin.min.js"
	},
    init_instance_callback: function (editor) {
        if (typeof tinymce_init_callback !== "undefined") {
            tinymce_init_callback(editor);
        }
    },
    setup: function (editor) {
        if (typeof tinymce_setup_callback !== "undefined") {
            tinymce_setup_callback(editor);
        }
    }
  });


  $(':input').removeAttr('placeholder');


