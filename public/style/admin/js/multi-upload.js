;(function() {
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('fileElem');
    const gallery   = document.getElementById('gallery');

    // Helper: preview a single file
    function previewFile(file) {
        const div = document.createElement('div');
        div.classList.add('preview');

        // remove button
        const btn = document.createElement('button');
        btn.textContent = '×';
        btn.classList.add('remove');
        btn.addEventListener('click', () => {
            removeFile(file, div);
        });
        div.appendChild(btn);

        // if image, show thumbnail; otherwise, show name
        if (file.type.startsWith('image/')) {
            const img = document.createElement('img');
            const reader = new FileReader();
            reader.onload = e => img.src = e.target.result;
            reader.readAsDataURL(file);
            div.appendChild(img);
        } else {
            const span = document.createElement('span');
            span.textContent = file.name;
            span.style.padding = '0.5rem';
            div.appendChild(span);
        }

        gallery.appendChild(div);
    }

    // Keep track of files via a DataTransfer
    const dt = new DataTransfer();

    function handleFiles(files) {
        Array.from(files).forEach(file => {
            dt.items.add(file);
            previewFile(file);
        });
        fileInput.files = dt.files; // assign to input
    }

    function removeFile(file, previewDiv) {
        // remove from DataTransfer
        for (let i = 0; i < dt.items.length; i++) {
            if (dt.items[i].getAsFile() === file) {
                dt.items.remove(i);
                break;
            }
        }
        fileInput.files = dt.files;
        gallery.removeChild(previewDiv);
    }

    // highlight on drag
    ;['dragenter','dragover'].forEach(evt =>
        dropArea.addEventListener(evt, e => {
            e.preventDefault();
            dropArea.classList.add('hover');
        })
    );
    ;['dragleave','drop'].forEach(evt =>
        dropArea.addEventListener(evt, e => {
            e.preventDefault();
            dropArea.classList.remove('hover');
        })
    );

    // handle drop
    dropArea.addEventListener('drop', e => {
        handleFiles(e.dataTransfer.files);
    });

    // click to open file picker
    dropArea.addEventListener('click', () => fileInput.click());

    // also handle selection via picker
    fileInput.addEventListener('change', () => {
        handleFiles(fileInput.files);
    });
})();
