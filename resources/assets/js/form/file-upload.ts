import { type ComponentInstance, createRegistry } from '../registry';

// ─── Shared helpers ────────────────────────────────────────────────────────────

function formatBytes(n: number): string {
    if (n < 1024) {
        return sprintf('%d B', String(n));
    }

    if (n < 1_048_576) {
        return sprintf('%.1f KB', (n / 1024).toFixed(1));
    }

    return sprintf('%.1f MB', (n / 1_048_576).toFixed(1));
}

function extOf(name: string): string {
    const i = name.lastIndexOf('.');
    return i >= 0 ? name.slice(i + 1).toLowerCase() : '';
}

function fileTypeClass(name: string): string {
    const ext = extOf(name);

    if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'avif'].includes(ext)) {
        return 'img';
    }

    if (ext === 'pdf') {
        return 'pdf';
    }

    if (['xls', 'xlsx', 'csv'].includes(ext)) {
        return 'xls';
    }

    if (['doc', 'docx', 'txt', 'rtf', 'odt'].includes(ext)) {
        return 'doc';
    }

    return '';
}

function isImageFile(file: File): boolean {
    return file.type.startsWith('image/');
}

function readAsDataUrl(file: File): Promise<string> {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = () => resolve(reader.result as string);
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}

function buildHiddenFileInput(name: string, file: File): HTMLInputElement {
    const input = document.createElement('input');
    input.type = 'file';
    input.name = name;
    input.setAttribute('aria-hidden', 'true');
    const dt = new DataTransfer();
    dt.items.add(file);
    input.files = dt.files;
    return input;
}

function sprintf(template: string, ...args: string[]): string {
    let i = 0;
    return template.replace(/%[sd.0-9]*[sdf]/g, () => args[i++] ?? '');
}

function uid(): string {
    return Math.random().toString(36).slice(2, 10);
}

// ─── Dropzone ──────────────────────────────────────────────────────────────────

interface DropzoneEntry {
    type: 'new' | 'existing';
    isImage: boolean;
    rowEl: HTMLElement;
    inputEl: HTMLInputElement | null;
}

const dropzoneRegistry = createRegistry<ComponentInstance>();

function buildDropzoneRow(
    file: File,
    key: string,
    onRemove: (key: string) => void,
): HTMLElement {
    const cls = fileTypeClass(file.name);
    const ext = extOf(file.name).toUpperCase().slice(0, 4) || 'FILE';

    const row = document.createElement('div');
    row.className = 'pajak-dropzone__item pajak-dropzone__item--new';
    row.dataset.key = key;

    const iconEl = document.createElement('span');
    iconEl.className = sprintf('pajak-dropzone__item-icon%s', cls ? sprintf(' pajak-dropzone__item-icon--%s', cls) : '');
    iconEl.setAttribute('aria-hidden', 'true');

    const info = document.createElement('span');
    info.className = 'pajak-dropzone__item-info';

    const nameEl = document.createElement('span');
    nameEl.className = 'pajak-dropzone__item-name';
    nameEl.textContent = file.name;

    const sizeEl = document.createElement('span');
    sizeEl.className = 'pajak-dropzone__item-size';
    sizeEl.textContent = formatBytes(file.size);

    info.appendChild(nameEl);
    info.appendChild(sizeEl);

    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'pajak-dropzone__item-remove';
    removeBtn.setAttribute('aria-label', 'Remove file');
    removeBtn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
    removeBtn.addEventListener('click', () => onRemove(key));

    row.appendChild(iconEl);
    row.appendChild(info);
    row.appendChild(removeBtn);

    if (isImageFile(file)) {
        const img = document.createElement('img');
        img.className = 'pajak-dropzone__item-thumb';
        img.alt = '';
        readAsDataUrl(file).then((src) => { img.src = src; });
        iconEl.appendChild(img);
    } else {
        iconEl.textContent = ext;
    }

    return row;
}

function upgradeDropzone(wrap: HTMLElement): ComponentInstance {
    const existing = dropzoneRegistry.get(wrap);

    if (existing) {
        return existing;
    }

    const multiple = wrap.hasAttribute('data-multiple');
    const name = sprintf('%s[]', wrap.dataset.name ?? '');

    const zone = wrap.querySelector<HTMLElement>('.pajak-dropzone__zone');
    const realInput = wrap.querySelector<HTMLInputElement>('.pajak-dropzone__input');
    const listEl = wrap.querySelector<HTMLElement>('[data-pajak-dropzone-list]');
    const inputsContainer = wrap.querySelector<HTMLElement>('[data-pajak-dropzone-inputs]');
    const deletesContainer = wrap.querySelector<HTMLElement>('[data-pajak-dropzone-deletes]');

    if (!zone || !realInput || !listEl || !inputsContainer || !deletesContainer) {
        const noop: ComponentInstance = { destroy(): void {} };
        dropzoneRegistry.set(wrap, noop);
        return noop;
    }

    const deletes: HTMLElement = deletesContainer;
    const list: HTMLElement = listEl;
    const inputs: HTMLElement = inputsContainer;

    const entries = new Map<string, DropzoneEntry>();

    function syncGridMode(): void {
        const all = Array.from(entries.values());
        const allImages = all.length > 0 && all.every((e) => e.isImage);
        wrap.toggleAttribute('data-grid', allImages);
    }

    // Register server-rendered existing items
    list.querySelectorAll<HTMLElement>('[data-existing]').forEach((el) => {
        const id = el.dataset.fileId ?? '';
        const inputEl = el.querySelector<HTMLInputElement>('[data-existing-input]');
        const isImage = el.hasAttribute('data-image');
        entries.set(id, { type: 'existing', isImage, rowEl: el, inputEl });

        const btn = el.querySelector<HTMLButtonElement>('.pajak-dropzone__item-remove');

        if (btn) {
            btn.addEventListener('click', () => removeExistingFile(id));
        }
    });
    syncGridMode();

    function addFile(file: File): void {
        if (!multiple) {
            entries.forEach((entry, key) => {
                if (entry.type === 'new') {
                    entry.rowEl.remove();
                    entry.inputEl?.remove();
                    entries.delete(key);
                }
            });
        }

        const key = uid();
        const rowEl = buildDropzoneRow(file, key, removeNewFile);
        list.appendChild(rowEl);

        const inputEl = buildHiddenFileInput(name, file);
        inputs.appendChild(inputEl);

        entries.set(key, { type: 'new', isImage: isImageFile(file), rowEl, inputEl });
        syncGridMode();

        wrap.dispatchEvent(new CustomEvent('pajak:dropzone:add', {
            bubbles: true,
            detail: { file },
        }));
    }

    function removeNewFile(key: string): void {
        const entry = entries.get(key);

        if (!entry) {
            return;
        }

        entry.rowEl.remove();
        entry.inputEl?.remove();
        entries.delete(key);
        syncGridMode();
        wrap.dispatchEvent(new CustomEvent('pajak:dropzone:remove', {
            bubbles: true,
            detail: { id: key, type: 'new' },
        }));
    }

    function removeExistingFile(id: string): void {
        const entry = entries.get(id);

        if (!entry) {
            return;
        }

        entry.inputEl?.remove();

        const deleteInput = document.createElement('input');
        deleteInput.type = 'hidden';
        deleteInput.name = sprintf('%s_delete[]', wrap.dataset.name ?? '');
        deleteInput.value = id;
        deletes.appendChild(deleteInput);

        entry.rowEl.classList.add('is-removing');
        setTimeout(() => entry.rowEl.remove(), 150);
        entries.delete(id);
        syncGridMode();

        wrap.dispatchEvent(new CustomEvent('pajak:dropzone:remove', {
            bubbles: true,
            detail: { id, type: 'existing' },
        }));
    }

    const onZoneClick = (): void => {
        realInput.click();
    };
    const onZoneKeydown = (e: KeyboardEvent): void => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            realInput.click();
        }
    };
    const onInputChange = (): void => {
        if (!realInput.files) {
            return;
        }

        Array.from(realInput.files).forEach(addFile);
        realInput.value = '';
    };
    const onDragEnter = (e: DragEvent): void => {
        e.preventDefault();
        wrap.classList.add('pajak-dropzone--dragover');
    };
    const onDragOver = (e: DragEvent): void => {
        e.preventDefault();
        wrap.classList.add('pajak-dropzone--dragover');
    };
    const onDragLeave = (): void => {
        wrap.classList.remove('pajak-dropzone--dragover');
    };
    const onDrop = (e: DragEvent): void => {
        e.preventDefault();
        wrap.classList.remove('pajak-dropzone--dragover');

        if (!e.dataTransfer) {
            return;
        }

        Array.from(e.dataTransfer.files).forEach(addFile);
    };

    zone.addEventListener('click', onZoneClick);
    zone.addEventListener('keydown', onZoneKeydown);
    realInput.addEventListener('change', onInputChange);
    zone.addEventListener('dragenter', onDragEnter);
    zone.addEventListener('dragover', onDragOver);
    zone.addEventListener('dragleave', onDragLeave);
    zone.addEventListener('drop', onDrop);

    const instance: ComponentInstance = {
        destroy(): void {
            zone.removeEventListener('click', onZoneClick);
            zone.removeEventListener('keydown', onZoneKeydown);
            realInput.removeEventListener('change', onInputChange);
            zone.removeEventListener('dragenter', onDragEnter);
            zone.removeEventListener('dragover', onDragOver);
            zone.removeEventListener('dragleave', onDragLeave);
            zone.removeEventListener('drop', onDrop);
            dropzoneRegistry.delete(wrap);
        },
    };

    dropzoneRegistry.set(wrap, instance);
    return instance;
}

// ─── Avatar ────────────────────────────────────────────────────────────────────

interface AvatarInstance extends ComponentInstance {
    readonly hasImage: boolean;
}

const avatarRegistry = createRegistry<AvatarInstance>();

function upgradeAvatar(wrap: HTMLElement): AvatarInstance {
    const existing = avatarRegistry.get(wrap);

    if (existing) {
        return existing;
    }

    const fieldName = wrap.dataset.name ?? '';

    const input = wrap.querySelector<HTMLInputElement>('[data-avatar-input]');
    const circle = wrap.querySelector<HTMLElement>('.pajak-avatar-upload__circle');
    const uploadBtn = wrap.querySelector<HTMLButtonElement>('[data-avatar-upload-btn]');
    const removeBtn = wrap.querySelector<HTMLButtonElement>('[data-avatar-remove-btn]');
    const deletesContainer = wrap.querySelector<HTMLElement>('[data-avatar-deletes]');

    if (!input || !circle || !deletesContainer) {
        const noop: AvatarInstance = {
            get hasImage(): boolean { return false; },
            destroy(): void {},
        };
        avatarRegistry.set(wrap, noop);
        return noop;
    }

    const deletes: HTMLElement = deletesContainer;
    const avatarCircle: HTMLElement = circle;
    const avatarInput: HTMLInputElement = input;

    let imgEl = wrap.querySelector<HTMLImageElement>('[data-avatar-img]');
    let initialsEl = wrap.querySelector<HTMLElement>('[data-avatar-initials]');
    let existingInput = wrap.querySelector<HTMLInputElement>('[data-avatar-existing-input]');
    const hadExistingImage = existingInput !== null;
    let deleteInjected = false;

    function showPreview(file: File): void {
        readAsDataUrl(file).then((src) => {
            if (imgEl) {
                imgEl.src = src;
            } else {
                const newImg = document.createElement('img');
                newImg.className = 'pajak-avatar-upload__image';
                newImg.src = src;
                newImg.alt = '';
                newImg.setAttribute('data-avatar-img', '');
                avatarCircle.insertBefore(newImg, avatarCircle.firstChild);
                imgEl = newImg;
            }

            if (initialsEl) {
                initialsEl.style.display = 'none';
            }

            if (existingInput) {
                existingInput.remove();
                existingInput = null;
            }

            if (hadExistingImage && !deleteInjected) {
                const del = document.createElement('input');
                del.type = 'hidden';
                del.name = sprintf('%s_delete', fieldName);
                del.value = '1';
                deletes.appendChild(del);
                deleteInjected = true;
            }

            if (removeBtn) {
                removeBtn.hidden = false;
            }

            wrap.classList.add('pajak-avatar-upload--has-image');
        });
    }

    function clearImage(): void {
        avatarInput.value = '';

        if (imgEl) {
            if (hadExistingImage) {
                // Restore to original src — it is still in the DOM with data-avatar-img
            } else {
                imgEl.remove();
                imgEl = null;
            }
        }

        if (initialsEl) {
            initialsEl.style.removeProperty('display');
        }

        if (deleteInjected) {
            const del = deletes.querySelector('input[type="hidden"]');
            del?.remove();
            deleteInjected = false;
        }

        if (hadExistingImage) {
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = sprintf('%s_existing', fieldName);
            inp.value = '1';
            inp.setAttribute('data-avatar-existing-input', '');
            wrap.appendChild(inp);
            existingInput = inp;
        } else {
            wrap.classList.remove('pajak-avatar-upload--has-image');
        }

        if (removeBtn && !hadExistingImage) {
            removeBtn.hidden = true;
        }

        wrap.dispatchEvent(new CustomEvent('pajak:avatar:change', {
            bubbles: true,
            detail: { file: null },
        }));
    }

    const onCircleClick = (e: MouseEvent): void => {
        if (e.target === avatarInput) {
            return;
        }

        avatarInput.click();
    };
    const onUploadClick = (): void => {
        avatarInput.click();
    };
    const onInputChange = (): void => {
        const file = avatarInput.files?.[0];

        if (!file) {
            return;
        }

        showPreview(file);
        wrap.dispatchEvent(new CustomEvent('pajak:avatar:change', {
            bubbles: true,
            detail: { file },
        }));
    };
    const onRemoveClick = (): void => {
        clearImage();
    };

    avatarCircle.addEventListener('click', onCircleClick);
    uploadBtn?.addEventListener('click', onUploadClick);
    avatarInput.addEventListener('change', onInputChange);
    removeBtn?.addEventListener('click', onRemoveClick);

    const instance: AvatarInstance = {
        get hasImage(): boolean {
            return wrap.classList.contains('pajak-avatar-upload--has-image');
        },
        destroy(): void {
            avatarCircle.removeEventListener('click', onCircleClick);
            uploadBtn?.removeEventListener('click', onUploadClick);
            avatarInput.removeEventListener('change', onInputChange);
            removeBtn?.removeEventListener('click', onRemoveClick);
            avatarRegistry.delete(wrap);
        },
    };

    avatarRegistry.set(wrap, instance);
    return instance;
}

// ─── Image Grid ────────────────────────────────────────────────────────────────

const imageGridRegistry = createRegistry<ComponentInstance>();

function upgradeImageGrid(wrap: HTMLElement): ComponentInstance {
    const existing = imageGridRegistry.get(wrap);

    if (existing) {
        return existing;
    }

    const fieldName = wrap.dataset.name ?? '';

    const gridEl = wrap.querySelector<HTMLElement>('[data-pajak-grid-items]');
    const addTile = wrap.querySelector<HTMLElement>('[data-pajak-grid-add]');
    const fileInput = wrap.querySelector<HTMLInputElement>('[data-pajak-grid-input]');
    const deletesContainer = wrap.querySelector<HTMLElement>('[data-pajak-grid-deletes]');

    if (!gridEl || !fileInput || !deletesContainer) {
        const noop: ComponentInstance = { destroy(): void {} };
        imageGridRegistry.set(wrap, noop);
        return noop;
    }

    const grid: HTMLElement = gridEl;
    const deletes: HTMLElement = deletesContainer;
    const gridInput: HTMLInputElement = fileInput;

    const newFiles = new Map<string, { file: File; tileEl: HTMLElement }>();

    function rebuildFileInput(): void {
        const dt = new DataTransfer();
        newFiles.forEach(({ file }) => dt.items.add(file));
        gridInput.files = dt.files;
    }

    function buildImageTile(key: string, file: File): HTMLElement {
        const tile = document.createElement('div');
        tile.className = 'pajak-image-grid__tile pajak-image-grid__tile--new';
        tile.dataset.key = key;

        const overlay = document.createElement('div');
        overlay.className = 'pajak-image-grid__overlay';

        const img = document.createElement('img');
        img.className = 'pajak-image-grid__thumb';
        img.alt = file.name;
        readAsDataUrl(file).then((src) => { img.src = src; });

        const delBtn = document.createElement('button');
        delBtn.type = 'button';
        delBtn.className = 'pajak-image-grid__delete';
        delBtn.setAttribute('aria-label', 'Remove image');
        delBtn.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';
        delBtn.addEventListener('click', () => removeNewImage(key));

        tile.appendChild(img);
        tile.appendChild(overlay);
        tile.appendChild(delBtn);

        return tile;
    }

    function addImages(files: FileList | File[]): void {
        Array.from(files).forEach((file) => {
            const key = uid();
            const tileEl = buildImageTile(key, file);

            if (addTile) {
                grid.insertBefore(tileEl, addTile);
            } else {
                grid.appendChild(tileEl);
            }

            newFiles.set(key, { file, tileEl });
        });
        rebuildFileInput();

        wrap.dispatchEvent(new CustomEvent('pajak:imagegrid:add', {
            bubbles: true,
            detail: { files: Array.from(files) },
        }));
    }

    function removeNewImage(key: string): void {
        const entry = newFiles.get(key);

        if (!entry) {
            return;
        }

        entry.tileEl.remove();
        newFiles.delete(key);
        rebuildFileInput();

        wrap.dispatchEvent(new CustomEvent('pajak:imagegrid:remove', {
            bubbles: true,
            detail: { id: key, type: 'new' },
        }));
    }

    function removeExistingImage(id: string): void {
        const tile = grid.querySelector<HTMLElement>(sprintf('[data-file-id="%s"]', id));

        if (!tile) {
            return;
        }

        const existingInputEl = tile.querySelector<HTMLInputElement>('[data-existing-input]');
        existingInputEl?.remove();
        tile.remove();

        const del = document.createElement('input');
        del.type = 'hidden';
        del.name = sprintf('%s_delete[]', fieldName);
        del.value = id;
        deletes.appendChild(del);

        wrap.dispatchEvent(new CustomEvent('pajak:imagegrid:remove', {
            bubbles: true,
            detail: { id, type: 'existing' },
        }));
    }

    // Wire pre-rendered existing delete buttons
    grid.querySelectorAll<HTMLElement>('[data-existing]').forEach((tile) => {
        const id = tile.dataset.fileId ?? '';
        const btn = tile.querySelector<HTMLButtonElement>('.pajak-image-grid__delete');

        if (btn) {
            btn.addEventListener('click', () => removeExistingImage(id));
        }
    });

    const onAddClick = (): void => {
        gridInput.click();
    };
    const onAddKeydown = (e: KeyboardEvent): void => {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            gridInput.click();
        }
    };
    const onFileChange = (): void => {
        if (!gridInput.files || gridInput.files.length === 0) {
            return;
        }

        const picked = Array.from(gridInput.files);
        gridInput.value = '';
        addImages(picked);
    };

    addTile?.addEventListener('click', onAddClick);
    addTile?.addEventListener('keydown', onAddKeydown);
    gridInput.addEventListener('change', onFileChange);

    const instance: ComponentInstance = {
        destroy(): void {
            addTile?.removeEventListener('click', onAddClick);
            addTile?.removeEventListener('keydown', onAddKeydown);
            gridInput.removeEventListener('change', onFileChange);
            imageGridRegistry.delete(wrap);
        },
    };

    imageGridRegistry.set(wrap, instance);
    return instance;
}

// ─── Public API ────────────────────────────────────────────────────────────────

export const PajakDropzone = {
    init(el: HTMLElement): ComponentInstance {
        return upgradeDropzone(el);
    },
    initAll(root: ParentNode = document): ComponentInstance[] {
        return Array.from(root.querySelectorAll<HTMLElement>('[data-pajak-dropzone]')).map(upgradeDropzone);
    },
};

export const PajakAvatar = {
    init(el: HTMLElement): AvatarInstance {
        return upgradeAvatar(el);
    },
    initAll(root: ParentNode = document): AvatarInstance[] {
        return Array.from(root.querySelectorAll<HTMLElement>('[data-pajak-avatar]')).map(upgradeAvatar);
    },
};

export const PajakImageGrid = {
    init(el: HTMLElement): ComponentInstance {
        return upgradeImageGrid(el);
    },
    initAll(root: ParentNode = document): ComponentInstance[] {
        return Array.from(root.querySelectorAll<HTMLElement>('[data-pajak-image-grid]')).map(upgradeImageGrid);
    },
};
