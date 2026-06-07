<div>
    <!-- An unexamined life is not worth living. - Socrates -->
</div>

<!-- Quill.js (Free, no API key required) -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<style>
    /* Quill Editor Custom Styles */
    #content-editor-wrapper {
        background: #fff;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        overflow: hidden;
        transition: border-color .18s, box-shadow .18s;
    }

    #content-editor-wrapper:focus-within {
        border-color: #059669;
        box-shadow: 0 0 0 3px rgba(5, 150, 105, .08);
    }

    /* Override for question pages */
    .qs-card-body #content-editor-wrapper:focus-within {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .08);
    }

    .ql-toolbar.ql-snow {
        border: none;
        border-bottom: 1.5px solid #f1f5f9;
        background: #fafbfc;
        font-family: 'Inter', sans-serif;
    }

    .ql-container.ql-snow {
        border: none;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
    }

    .ql-editor {
        min-height: 280px;
        max-height: 500px;
        overflow-y: auto;
        padding: 14px 18px;
        line-height: 1.7;
    }

    .ql-editor.ql-blank::before {
        color: #94a3b8;
        font-style: normal;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const textarea = document.getElementById('content-editor');
        if (!textarea) return;

        // Create wrapper and editor div
        const wrapper = document.createElement('div');
        wrapper.id = 'content-editor-wrapper';
        const editorDiv = document.createElement('div');
        editorDiv.id = 'quill-editor-div';

        textarea.style.display = 'none';
        textarea.parentNode.insertBefore(wrapper, textarea);
        wrapper.appendChild(editorDiv);

        // Initialize Quill
        const quill = new Quill('#quill-editor-div', {
            theme: 'snow',
            placeholder: 'Tulis konten di sini...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });

        // Load existing content
        const existingContent = textarea.value.trim();
        if (existingContent) {
            // If it's HTML content
            quill.clipboard.dangerouslyPasteHTML(existingContent);
        }

        // Sync content before form submit
        const form = textarea.closest('form');
        if (form) {
            form.addEventListener('submit', function () {
                textarea.value = quill.root.innerHTML;
            });
        }

        // Expose globally for validation use
        window._quillInstance = quill;
    });
</script>