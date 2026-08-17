(function () {
    'use strict';

    const docConfig = {
        pan:      { placeholder: 'ABCDE1234F',           hint: 'Format: ABCDE1234F (5 letters · 4 digits · 1 letter)' },
        aadhaar:  { placeholder: 'XXXX XXXX XXXX',       hint: 'Format: 12-digit Aadhaar number' },
        passport: { placeholder: 'A1234567',              hint: 'Format: Letter followed by 7 digits' },
        other:    { placeholder: 'Enter document number', hint: 'Enter the number printed on your document' },
    };

    const pageData = JSON.parse(document.getElementById('kycUploadData').textContent);
    let selectedDocType = pageData.selectedDocType;
    let hasFile = false;

    function onDocTypeChange(type) {
        selectedDocType = type;
        const input  = document.getElementById('documentNumber');
        const hintEl = document.getElementById('docFormatHint');
        const config = docConfig[type];
        input.placeholder = config.placeholder;
        input.disabled = false;
        input.focus();
        hintEl.textContent = config.hint;
        checkReady();
    }

    function handleFileSelect(input) {
        if (input.files && input.files.length > 0) setFileSelected(input.files[0].name);
    }
    function handleDragOver(e) {
        e.preventDefault();
        document.getElementById('dropZone').classList.add('drag-over');
    }
    function handleDragLeave(e) {
        document.getElementById('dropZone').classList.remove('drag-over');
    }
    function handleDrop(e) {
        e.preventDefault();
        handleDragLeave(e);
        const files = e.dataTransfer.files;
        if (files && files.length > 0) {
            const dt = new DataTransfer();
            dt.items.add(files[0]);
            document.getElementById('document_file').files = dt.files;
            setFileSelected(files[0].name);
        }
    }
    function resetDropZone() {
        const zone = document.getElementById('dropZone');
        zone.classList.remove('file-selected', 'drag-over');
        document.getElementById('uploadIconWrap').innerHTML = `
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:36px;height:36px;color:var(--text3);">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V4m0 0L8 8m4-4l4 4"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2"/>
        </svg>`;
        document.getElementById('uploadPrimary').textContent = 'Drag & drop or click to upload';
        document.getElementById('uploadSub').style.display = '';
        document.getElementById('fileName').textContent = '';
        document.getElementById('filePreview').classList.remove('visible');
        hasFile = false;
    }
    function setFileSelected(name) {
        resetDropZone();
        hasFile = true;
        const zone = document.getElementById('dropZone');
        zone.classList.add('file-selected');
        document.getElementById('uploadIconWrap').innerHTML = `
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:36px;height:36px;color:var(--green);">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>`;
        document.getElementById('uploadPrimary').textContent = 'File selected — click to change';
        document.getElementById('uploadSub').style.display = 'none';
        document.getElementById('fileName').textContent = name;
        document.getElementById('filePreview').classList.add('visible');
        checkReady();
    }
    function checkReady() {
        const numVal = document.getElementById('documentNumber').value.trim();
        const btn = document.getElementById('submitBtn');
        btn.disabled = !(selectedDocType && hasFile && numVal.length > 2);
    }

    document.getElementById('documentNumber').addEventListener('input', checkReady);

    document.getElementById('kycForm').addEventListener('submit', function (e) {
        const btn = document.getElementById('submitBtn');
        const label = document.getElementById('submitLabel');
        label.textContent = 'Submitting…';
        btn.disabled = true;
        btn.style.opacity = '0.7';
        showToast();
        e.preventDefault();
        setTimeout(() => this.submit(), 600);
    });

    function showToast() {
        const t = document.getElementById('successToast');
        t.style.transform = 'translateX(-50%) translateY(0)';
        t.style.opacity = '1';
    }

    document.addEventListener('change', function (e) {
        const fileInput = e.target.closest('[data-action="dz-file"]');
        if (fileInput) {
            handleFileSelect(fileInput);
            return;
        }
        const radio = e.target.closest('[data-action="doc-type"]');
        if (radio) {
            onDocTypeChange(radio.dataset.type);
            return;
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('[data-action="dz-zone"]')) {
            document.getElementById('document_file').click();
        }
    });

    document.addEventListener('dragover', function (e) {
        if (e.target.closest('[data-action="dz-zone"]')) handleDragOver(e);
    });
    document.addEventListener('dragleave', function (e) {
        if (e.target.closest('[data-action="dz-zone"]')) handleDragLeave(e);
    });
    document.addEventListener('drop', function (e) {
        if (e.target.closest('[data-action="dz-zone"]')) handleDrop(e);
    });

    if (selectedDocType) onDocTypeChange(selectedDocType);
})();