$(document).on("submit", '#saveForm', function (e) {
    e.preventDefault();
    let button = $(this).find("button");
    let icon = button.find("i");
    icon.removeClass("hidden");
    button.attr("disabled", true);
    document.getElementById("saveForm").submit();
})
$(document).ready(function () {
    // Listen for change event on file input
    $('.uploadFile').change(function () {
        let image_tag = $(this).parent().find('.file_choose_icon').find("img");
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                image_tag.attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
});
$(function () {
    $(".basic-datatable").DataTable({
        responsive: true,
    });
});
var editorOptions = {
    theme: 'snow' // Choose the theme for the editor (snow or bubble)
};

// Initialize Quill on textareas with class 'editor'
class MyUploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }

    upload() {
        return this.loader.file
            .then(file => new Promise((resolve, reject) => {
                this._initRequest();
                this._initListeners(resolve, reject, file);
                this._sendRequest(file);
            }));
    }

    _initRequest() {
        const xhr = this.xhr = new XMLHttpRequest();
        xhr.open('POST', '/upload-editor-image', true);  // Replace with your API endpoint
        xhr.responseType = 'json';

        // Set the CSRF token header
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
    }

    _initListeners(resolve, reject, file) {
        const xhr = this.xhr;
        const loader = this.loader;
        const genericErrorText = `Couldn't upload file: ${file.name}.`;

        xhr.addEventListener('error', () => reject(genericErrorText));
        xhr.addEventListener('abort', () => reject());
        xhr.addEventListener('load', () => {
            const response = xhr.response;

            if (!response || response.error) {
                return reject(response && response.error ? response.error.message : genericErrorText);
            }

            resolve({
                default: response.url
            });
        });

        if (xhr.upload) {
            xhr.upload.addEventListener('progress', evt => {
                if (evt.lengthComputable) {
                    loader.uploadTotal = evt.total;
                    loader.uploaded = evt.loaded;
                }
            });
        }
    }

    _sendRequest(file) {
        const data = new FormData();
        data.append('upload', file);

        this.xhr.send(data);
    }

    abort() {
        if (this.xhr) {
            this.xhr.abort();
        }
    }
}

function MyCustomUploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
        return new MyUploadAdapter(loader);
    };
}

document.addEventListener('DOMContentLoaded', () => {
    var editors = document.querySelectorAll('.editor');
    editors.forEach(function (editorEl) {
        ClassicEditor
            .create(editorEl, {
                extraPlugins: [MyCustomUploadAdapterPlugin],
                toolbar: {
                    items: [
                        'heading',
                        '|',
                        'bold',
                        'italic',
                        'link',
                        'bulletedList',
                        'numberedList',
                        'blockQuote',
                        '|',
                        'insertTable',
                        'imageUpload'  // Add the imageUpload button
                    ]
                },
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                        { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                        { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                        { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                        { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                    ]
                }
            })
            .catch(error => {
                console.error(error);
            });
    });
});


$(function () {

    $(".rateYo").rateYo({

        onChange: function (rating, rateYoInstance) {

            $(this).next().val(rating);
        }
    });
});
$('.rateYo-show').rateYo({
    rating: 5.0,
    fullStar: true,
    readOnly: true,
    normalFill: '#ddd',
    ratedFill: '#f6a623',
    starWidth: '14px',
    spacing: '2px'
});
$(document).on("click", '#sidebarnav > li > a.has-arrow', function (e) {
    e.preventDefault();
    let current = $(this);
    current.toggleClass("active")
    current.next().toggleClass('in')
})