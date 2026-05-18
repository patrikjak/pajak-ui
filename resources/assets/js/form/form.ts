import { PajakCheckbox } from './checkbox';
import { PajakDropzone, PajakAvatar, PajakImageGrid } from './file-upload';
import { PajakFile } from './file';
import { PajakRepeater } from './repeater';
import { PajakSelect } from './select';
import { PajakSlider } from './slider';
import { PajakToggle } from './toggle';
import { initFormSubmitLoaders, PajakForm } from './form-submit';
import { PajakToast } from '../toast/toast';

export { PajakCheckbox } from './checkbox';
export { PajakDropzone, PajakAvatar, PajakImageGrid } from './file-upload';
export { PajakFile } from './file';
export { PajakRepeater } from './repeater';
export { PajakSelect } from './select';
export { PajakSlider } from './slider';
export { PajakToggle } from './toggle';
export { initFormSubmitLoaders, PajakForm } from './form-submit';
export { PajakToast } from '../toast/toast';
export type { ToastOptions } from '../toast/toast';

function initAll(root: ParentNode = document): void {
    PajakSelect.initAll(root);
    PajakToggle.initAll(root);
    PajakCheckbox.initAll(root);
    PajakFile.initAll(root);
    PajakRepeater.initAll(root);
    PajakSlider.initAll(root);
    PajakDropzone.initAll(root);
    PajakAvatar.initAll(root);
    PajakImageGrid.initAll(root);
    initFormSubmitLoaders(root);
}

declare global {
    interface Window {
        Pajak?: Record<string, unknown>;
    }
}

window.Pajak = { ...window.Pajak, PajakSelect, PajakToggle, PajakCheckbox, PajakFile, PajakRepeater, PajakSlider, PajakDropzone, PajakAvatar, PajakImageGrid, PajakForm, PajakToast, initFormSubmitLoaders, initAll };

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => initAll());
} else {
    initAll();
}
